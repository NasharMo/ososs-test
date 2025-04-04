<?php

namespace App\Actions;

use App\Models\CountryCurrency;
use App\Models\PriceList;
use Illuminate\Support\Facades\DB;

class CreatePriceListAction
{
    public static function execute(array $data): bool
    {
        try {
            DB::beginTransaction();

            $countryCurrency = null;
            // Check if country_id and currency_id are provided
            if($data['country_id'] || $data['currency_id'] ) {
                $countryCurrency =  CountryCurrency::updateOrCreate([
                    'country_id' => $data['country_id'],
                    'currency_id' => $data['currency_id'],
                ]);
            }
        
            // Create price list
            $priceList = PriceList::create([
                'country_currency_id' => $countryCurrency?->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'priority' => $data['priority'],
            ]);

            // Add price list priceListItems
            foreach ($data['products'] as $productData) {
                $priceList->priceListItems()->create([
                    'product_id' => $productData['id'],
                    'price' => $productData['price'],
                ]);
            }

            DB::commit();

            return true; 

        }catch (\Exception $e) {
            DB::rollBack();
            return false; // or handle the error as needed
        }

        
    }

}