<?php

declare(strict_types=1);

namespace App\Services\TradeOpportunity;

use App\Models\MarketOrder;
use App\Models\Region;
use App\Services\Universe\GetNameService;
use phpDocumentor\Reflection\Location;

class CreateTradeOpportunityService
{
    public function run(): void
    {
        $startHubsIds = collect(config('eve.trade_hubs'));
        $startHubsIds->each(function ($startLocationId) {

            $endHubsIds = collect(config('eve.trade_hubs'));
            $endHubsIds->each(function ($endLocationId) use ($startLocationId) {

                if ($startLocationId == $endLocationId) {
                    return;
                }

                $startHubTypes = MarketOrder::getTypesByLocationId($startLocationId);
                $startHubTypes->each(function ($startHubType) use ($endLocationId) {

                    $endHubType = MarketOrder::getTypeByLocationId($startHubType->type_id, $endLocationId);

                    dd([
                        'startHubType.best_sell_price' => $startHubType->best_sell_price,
                        'endHubType.best_sell_price' => $endHubType->best_sell_price
                    ]);
                });

            });



            $marketOrdersOnStartHub = MarketOrder::getTypesByLocationId($startHub);

            dd($marketOrdersOnStartHub->toArray());
        });

        $sourceRegions = Region::getRegionsForMarketOrderSync();
        $sourceRegions->each(function (Region $sourceRegion) {

            $targetRegion = Region::getRegionsForMarketOrderSync();
            $targetRegion->each(function (Region $targetRegion) use ($sourceRegion) {

                if ($sourceRegion->id == $targetRegion->id) {
                    return;
                }

                $this->analyseRegionToRegion($sourceRegion, $targetRegion);
            });
        });
    }

    private function analyseLocationToLocation(Location $sourceLocation, Location $targetLocation): void
    {

    }

    private function analyseRegionToRegion(Region $sourceRegion, Region $targetRegion): void
    {
        $typesSourceRegion = MarketOrder::getBestSellPricesByRegionId($sourceRegion->id);
        $typesSourceRegion->each(function ($typeSourceRegion) use ($targetRegion) {

            $typeTargetRegion = MarketOrder::getBestSellPriceByTypeForRegion(
                (int) $typeSourceRegion['type_id'],
                $targetRegion->id
            );

            $sourcePrice = $typeSourceRegion->best_sell_price;
            $targetPrice = $typeTargetRegion->best_sell_price;

            $margin = round(($targetPrice - $sourcePrice) / $targetPrice * 100,2);

            if ($margin > 20) {
                dump(GetNameService::getNames([$typeSourceRegion['type_id']])->first());
                dump($sourcePrice);
                dump($targetPrice);
                dd($margin);
            }
        });
        dd($typesSourceRegion->toArray());

        $marketOrdersSourceRegion = MarketOrder::query()->where('region_id', $sourceRegion->id);
        $marketOrdersSourceRegion->each(function (MarketOrder $marketOrderSourceRegion) {


        });
    }
}
