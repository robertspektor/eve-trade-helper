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

    public function run(): void
    {
        DB::beginTransaction();
        TradeOpportunity::query()->delete();

        Log::info('start analyse trade opportunities');

        $startHubsIds = collect(config('eve.trade_hubs'));
        $startHubsIds->each(function ($startLocationId) {

            $endHubsIds = collect(config('eve.trade_hubs'));
            $endHubsIds->each(function ($endLocationId) use ($startLocationId) {

                if ($startLocationId == $endLocationId) {
                    return;
                }

                Log::info(sprintf('-> trade route: %s to %s', $startLocationId, $endLocationId));

                $this->analyseLocationToLocation($startLocationId, $endLocationId);
            });
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

            // calculate tax.
            $tax = $this->getTax($endHubType->best_sell_price);

            // calculate margin.
            $margin = $this->calculateMargin(
                $startHubType->best_sell_price,
                $endHubType->best_sell_price,
                $tax
            );

            if ($margin < 10) {
                return;
            }

            $trade = new TradeOpportunity();
            $trade->type_id = $startHubType->type_id;
            $trade->start_hub = $startLocationId;
            $trade->end_hub = $endLocationId;
            $trade->start_hub_price = $startHubType->best_sell_price;
            $trade->end_hub_price = $endHubType->best_sell_price;
            $trade->taxes = $tax;
            $trade->hub2hub_margin = $margin;
            $trade->save();

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

    private function getTax(float $sellPrice): float
    {
        return round($sellPrice * (config('eve.broker_fee') + config('eve.sales_tax')) / 100, 2);
    }

    private function calculateMargin(float $buyPrice, float $sellPrice, float $tax): float
    {
        $sellPrice = $sellPrice - $tax;
        return round(($sellPrice - $buyPrice) / $sellPrice  * 100,2);
    }
}
