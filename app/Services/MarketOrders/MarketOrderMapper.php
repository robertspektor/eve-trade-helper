<?php

declare(strict_types=1);

namespace App\Services\MarketOrders;

use App\Models\MarketOrder;
use App\Models\Region;
use Carbon\Carbon;

class MarketOrderMapper
{
    /**
     * @param array<string> $order
     * @param Region $region
     * @return MarketOrder
     */
    public function map(array $order, Region $region): MarketOrder
    {
        $marketOrder = MarketOrder::query()->find((int) $order['order_id']) ?? new MarketOrder();
        $marketOrder->id = $order['order_id'];
        $marketOrder->region_id = $region->id;
        $marketOrder->duration = $order['duration'];
        $marketOrder->is_buy_order = $order['is_buy_order'];
        $marketOrder->issued = new Carbon($order['issued']);
        $marketOrder->location_id = $order['location_id'];
        $marketOrder->min_volume = $order['min_volume'];
        $marketOrder->order_id = $order['order_id'];
        $marketOrder->price = $order['price'];
        $marketOrder->range = $order['range'];
        $marketOrder->system_id = $order['system_id'];
        $marketOrder->type_id = $order['type_id'];
        $marketOrder->volume_remain = $order['volume_remain'];
        $marketOrder->volume_total = $order['volume_total'];
        $marketOrder->last_seen = Carbon::now();

        return $marketOrder;
    }
}
