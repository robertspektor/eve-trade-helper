<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TradeOpportunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'start_hub' => $this->start_hub,
            'start_hub_price' => $this->start_hub_price,
            'end_hub_price' => $this->end_hub_price,
            'hub2hub_margin' => $this->hub2hub_margin,
            'taxes' => $this->taxes,
        ];
    }
}
