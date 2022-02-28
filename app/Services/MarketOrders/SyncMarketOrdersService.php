<?php

declare(strict_types=1);

namespace App\Services\MarketOrders;

use App\Jobs\SyncMarketOrders;
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
        SyncMarketOrders::dispatch($this->region, $page);
    }

    private function saveMarketOrdersByRegion(Collection $orders): void
    {
        DB::transaction(function () use ($orders) {
            $orders->each(fn ($order) => $this->marketOrderMapper->map($order, $this->region)->save());
        });
    }

    private function map(array $order, Region $region): MarketOrder
    {
        $marketOrder = MarketOrder::query()->find((int) $order['id']) ?? new MarketOrder();
        $marketOrder->id = $order['id'];

        $order['region_id'] = $region->id;
        $order['issued'] = new Carbon($order['issued']);
        $order['last_seen'] = Carbon::now();

        return new MarketOrder($order);
    }

    private function getPages(): int
    {
        $response = $this->getDataForPage(1);

        return (int) $response->header('x-pages');
    }

    private function getDataForPage(int $page): Response
    {
        $url = Str::replace('{regionId}', $this->region->id, config('app.eve_esi_url') . $this->url);

        return Http::get($url, [
            'order_type' => 'all',
            'page' => $page,
        ]);
    }
}
