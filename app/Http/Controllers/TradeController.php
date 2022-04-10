<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTradesRequest;
use App\Http\Resources\TradeOpportunityResource;
use App\Models\TradeOpportunity;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TradeController extends Controller
{
    public function get(GetTradesRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $trades = TradeOpportunity::query()->withOnly(['type', 'start_hub', 'end_hub']);

        $endHub = $validated['end_hub'] ?? '';
        if (!empty($endHub)) {
            $trades->where('end_hub', $endHub);
        }

        $startHub = $validated['start_hub'] ?? '';
        if (!empty($startHub)) {
            $trades->where('start_hub', $startHub);
        }

        $search = $validated['type_id'] ?? '';
        if (!empty($search)) {
            $trades->where('type_id', $search);
        }

        $favorite = $validated['favorite'] ?? '';
        if (!empty($favorite)) {
            $trades->where('favorite', $favorite);
        }

        return TradeOpportunityResource::collection($trades->simplePaginate(25));
    }
}
