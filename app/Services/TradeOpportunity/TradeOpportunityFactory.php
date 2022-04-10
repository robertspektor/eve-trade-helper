<?php

declare(strict_types=1);

namespace App\Services\TradeOpportunity;

use App\Models\MarketOrder;
use App\Models\TradeOpportunity;

class TradeOpportunityFactory
{
    public function fromMarketOrders(MarketOrder $buyOrder, MarketOrder $sellOrder): ?TradeOpportunity
    {
        // calculate tax.
        $tax = $this->getTax($sellOrder->price);

        // calculate margin.
        $margin = $this->calculateMargin(
            $buyOrder->price,
            $sellOrder->price,
            $tax
        );

        if ($margin < 10) {
            return null;
        }

        $trade = new TradeOpportunity();
        $trade->type_id = $buyOrder->type_id;
        $trade->start_hub = $buyOrder->location_id;
        $trade->end_hub = $sellOrder->location_id;
        $trade->start_hub_price = $buyOrder->price;
        $trade->end_hub_price = $sellOrder->price;
        $trade->taxes = $tax;
        $trade->hub2hub_margin = $margin;

        return $trade;
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
