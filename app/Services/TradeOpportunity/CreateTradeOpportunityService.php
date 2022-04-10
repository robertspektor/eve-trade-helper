<?php

declare(strict_types=1);

namespace App\Services\TradeOpportunity;

use App\Models\MarketOrder;
use App\Models\Region;
use App\Models\TradeOpportunity;
use App\Services\Universe\GetNameService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Location;

class CreateTradeOpportunityService
{
    private int $countTrades = 0;

    public function __construct(private TradeOpportunityFactory $factory)
    {
    }

    public function run(): void
    {
        DB::beginTransaction();
        TradeOpportunity::query()->delete();

        Log::info('start analyse trade opportunities');

        $hubs = collect(config('eve.trade_hubs'));
        $matrix = $hubs->crossJoin($hubs)->filter(fn ($item) => $item[0] !== $item[1]);

        $matrix->each(function ($item) {

            Log::info(sprintf('-> trade route: %s to %s', $item[0], $item[1]));
            $this->analyseLocationToLocation($item[0], $item[1]);
        });

        DB::commit();
    }

    private function analyseLocationToLocation(int $startLocationId, int $endLocationId): void
    {
        $startHubTypes = MarketOrder::getTypesByLocationId($startLocationId);
        $startHubTypes->each(function ($startHubType) use ($endLocationId, $startLocationId) {

            $endHubType = MarketOrder::getTypeByLocationId($startHubType->type_id, $endLocationId);

            if (empty($endHubType)) {
                return;
            }

            $this->factory->fromMarketOrders(
                $startHubType,
                $endHubType
            )?->save();

            $this->countTrades++;

        });

        Log::info(
            sprintf('add %d trades for trade route: %s to %s',
                $this->countTrades,
                $startLocationId,
                $endLocationId
            )
        );

    }


}
