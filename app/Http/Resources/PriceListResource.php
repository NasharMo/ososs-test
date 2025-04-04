<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'country_currencies' => CountryCurrencyResource::make($this->whenLoaded('countryCurrencies')),
            'priority' => $this->priority,
            'priceListItems' => PriceListItemResource::collection($this->whenLoaded('priceListItems')),

        ];
    }
}
