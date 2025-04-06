<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function getProducts($order = null, $perPage, $page)
    {
        $query = Product::select([
            'products.id',
            'products.name',
            'products.description',
            DB::raw('IFNULL(pli.price, products.base_price) as price'),
            DB::raw("IFNULL(currencies.code, '" . Currency::DEFAULT_CURRENCY_CODE . "') as currency"),
            DB::raw("IFNULL(countries.name, '" . Country::DEFAULT_COUNTRY_CODE . "') as country"),
            'price_lists.priority',
        ])
            ->leftJoin('price_list_items as pli', 'pli.product_id', '=', 'products.id')
            ->leftJoin('price_lists', 'pli.price_list_id', '=', 'price_lists.id')
            ->leftJoin('countries', 'price_lists.country_id', '=', 'countries.id')
            ->leftJoin('currencies', 'price_lists.currency_id', '=', 'currencies.id')
            ->where(function ($query) {
                $query->whereRaw('price_lists.priority = (
                    SELECT MIN(pl2.priority)
                    FROM price_list_items pli2
                    JOIN price_lists pl2 ON pli2.price_list_id = pl2.id
                    WHERE pli2.product_id = products.id
                )')
                    ->orWhereNull('price_lists.priority');
            });

        if ($order) {
            $query = $order === 'lowest-to-highest'
                ? $query->orderBy('price', 'asc')
                : $query->orderBy('price', 'desc');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($product_id, $countryCode, $currencyCode, $date)
    {
        $product = Product::leftJoin('price_list_items', 'products.id', '=', 'price_list_items.product_id')
            ->leftJoin('price_lists', 'price_list_items.price_list_id', '=', 'price_lists.id')
            ->leftJoin('countries', 'countries.id', '=', 'price_lists.country_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'price_lists.currency_id')
            ->where('products.id', $product_id)
            ->where(function ($query) use ($countryCode) {
                $query->whereNull('price_lists.country_id')->orWhere('countries.code', $countryCode);
            })
            ->where(function ($query) use ($currencyCode) {
                $query->whereNull('price_lists.currency_id')->orWhere('currencies.code', $currencyCode);
            })
            ->where(function ($query) use ($date) {
                $query->where(function ($q) use ($date) {
                    $q->whereDate('price_lists.start_date', '<=', $date)
                        ->orWhereNull('price_lists.start_date');
                });
                $query->where(function ($q) use ($date) {
                    $q->whereDate('price_lists.end_date', '>=', $date)
                        ->orWhereNull('price_lists.end_date');
                });
            })
            ->select(
                'products.id',
                'products.name',
                'products.description',
                DB::raw('ifnull(price_list_items.price, products.base_price) as price'),
                'countries.name as country',
                'currencies.code as currency'
            )
            ->orderBy('price_lists.priority', 'asc')
            ->first();

        return $product;
    }
}
