<?php

namespace App\Actions;

use App\Models\Country;
use App\Models\Currency;
use App\Models\PriceListItem;
use App\Models\Product;
use Carbon\Carbon;

class GetApplicablePriceAction
{

    public static function execute(
        Product $product,
        ?string $countryCode,
        ?string $currencyCode,
        ?string $date = null
    ) {
        $countryId = $countryCode
            ? Country::where('code', $countryCode)->value('id')
            : null;

        $currencyId = $currencyCode
            ? Currency::where('code', $currencyCode)->value('id')
            : null;

        $date = $date ? Carbon::parse($date) : Carbon::now();


        // $price =  PriceListItem::with(['priceList'])->where('product_id', $product->id)
        //    // ->join('price_lists', 'price_list_items.price_list_id', '=', 'price_lists.id')
        //     ->whereHas('priceList', function ($query) use ($countryId, $currencyId, $date) {
        //         $query->where(function ($q) use ($countryId) {
        //             $q->where('country_id', $countryId)
        //                 ->orWhereNull('country_id'); // Applies to all countries
        //         })
        //             ->where(function ($q) use ($currencyId) {
        //                 $q->where('currency_id', $currencyId)
        //                     ->orWhereNull('currency_id'); // Applies to all currencies
        //             })
        //             ->whereDate('start_date', '<=', $date)
        //             ->whereDate('end_date', '>=', $date);
        //     })
        //    // ->orderBy('price_lists.priority') // Order by priority from joined table
        //     ->first();


            $price = PriceListItem::select('price_list_items.*') // Avoid conflicts
                                    ->join('price_lists', 'price_list_items.price_list_id', '=', 'price_lists.id')
                                    ->where('product_id', $product->id)

                                    // ->when('product_id', $product->id, function ($query) use ($product) {
                                    //     $query->where('product_id', $product->id);
                                    // })
                                    ->where(function ($query) use ($countryId, $currencyId, $date) {
                                        $query->where(function ($q) use ($countryId) {
                                            $q->where('price_lists.country_id', $countryId)
                                                ->orWhereNull('price_lists.country_id'); // Applies to all countries
                                        })
                                        ->where(function ($q) use ($currencyId) {
                                            $q->where('price_lists.currency_id', $currencyId)
                                                ->orWhereNull('price_lists.currency_id'); // Applies to all currencies
                                        })
                                        ->whereDate('price_lists.start_date', '<=', $date)
                                        ->whereDate('price_lists.end_date', '>=', $date);
                                    })
                                    ->orderBy('price_lists.priority', 'asc') // Order by priority
                                    ->first();

            
           return $price?->price ?? $product->base_price;
    }
}
