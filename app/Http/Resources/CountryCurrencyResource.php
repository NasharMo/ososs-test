<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryCurrencyResource extends JsonResource
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
            'country' =>  CountryResource::make($this->whenLoaded('country')),
            'currency' =>  CurrencyResource::make($this->whenLoaded('currency')),
        ];
    }
}
