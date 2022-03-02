<?php

declare(strict_types=1);

namespace App\Services\MarketOrders;

use App\Jobs\SyncLocations;
use App\Jobs\SyncMarketOrders;
use App\Models\Location;
use App\Models\MarketOrder;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncMarketOrdersService
{
    private Region $region;

    protected string $url = '/markets/{regionId}/orders';

    public function __construct(private MarketOrderMapper $marketOrderMapper)
    {
    }

    public function setRegion(Region $region) {
        $this->region = $region;
    }

    public function run(): void
    {
        $regions = Region::getRegionsForMarketOrderSync();
        $regions->each(function (Region $region) {
            $this->region = $region;

            foreach (range(1, $this->getPages()) as $page) {
                $this->dispatchJob($page);
            }
        });
    }

    public function cleanup(): void
    {
        MarketOrder::query()->where('last_seen', '<', Carbon::now()->subDays(2))->delete();
    }

    public function sync(Region $region, int $page): void
    {
        $this->region = $region;

        $response = $this->getDataForPage($page);

        $this->saveMarketOrdersByRegion($response->collect());

        Log::debug('sync market orders for region', [
            'page' => $page,
            'region' => $region->id,
        ]);
    }

    private function dispatchJob(int $page): void
    {
        SyncMarketOrders::dispatch($this->region, $page)->onQueue('order_sync');
    }

    private function saveMarketOrdersByRegion(Collection $orders): void
    {
        DB::transaction(function () use ($orders) {
            $orders->each(fn ($order) => $this->marketOrderMapper->map($order, $this->region)->save());
        });

        $this->dispatchJobsForMarketLocation();
    }

    private function getPages(): int
    {
        $response = $this->getDataForPage(1);

        return (int) $response->header('x-pages');
    }

    private function getDataForPage(int $page): Response
    {
        $url = Str::replace('{regionId}', $this->region->id, config('eve.esi_url') . $this->url);

        return Http::get($url, [
            'order_type' => 'all',
            'page' => $page,
        ]);
    }

    public function dispatchJobsForMarketLocation(): void
    {
        $uniqueLocations = MarketOrder::query()->selectRaw('location_id')->where('region_id', $this->region->id)->groupBy('location_id')->get();
        $uniqueLocations = $uniqueLocations->filter(function ($location) {

            // filter npc stations.
            if (strlen((string) $location->location_id) !== 8) {
                return false;
            }

            // filter already known stations.
            $existingLocation = Location::query()->find($location->location_id);
            if (!empty($existingLocation)) {
                return false;
            }

            return true;
        });

        $locationIds = $uniqueLocations->pluck('location_id');
        if (!empty($locationIds->count())) {
            SyncLocations::dispatch($locationIds)->onQueue('location_sync');
        }
    }
}
