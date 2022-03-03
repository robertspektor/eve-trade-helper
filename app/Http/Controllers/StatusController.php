<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\MarketOrder;
use App\Models\Region;
use App\Models\TradeOpportunity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class StatusController extends Controller
{
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'OK',
            'timestamp' => Carbon::now(),
            'market_orders' => MarketOrder::query()->count(),
            'locations' => Location::query()->count(),
            'trade_opportunities' => TradeOpportunity::query()->count(),
            'regions_to_sync' => Region::getRegionsForMarketOrderSync()
        ]);
    }
}
