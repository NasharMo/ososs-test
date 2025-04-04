<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository {
    public function getProducts($countryCode, $order, $perPage, $page) {
        $query = Product::query()
            ->leftJoin('price_list_items', 'products.id', '=', 'price_list_items.product_id')
            ->leftJoinSub(
                DB::table('price_lists')->orderBy('priority', 'asc')->limit(1),
                'price_lists',
                function ($join) {
                    $join->on('price_list_items.price_list_id', '=', 'price_lists.id');
                }
            )
            ->leftJoin('country_currencies', 'price_lists.country_currency_id', '=', 'country_currencies.id')
            ->join('countries', function ($join) use ($countryCode) {
                $join->on('countries.id', '=', 'country_currencies.country_id');
                $join->when($countryCode, function ($q) use ($countryCode) {
                    $q->where('countries.code', '=', $countryCode);
                });
            })
            ->join('currencies', 'currencies.id', '=', 'country_currencies.currency_id')
            ->select(
                'products.id',
                'products.name',
                'products.description',
                DB::raw('ifnull(price_list_items.price, products.base_price) as price'),
                'countries.name as country',
                'currencies.code as currency'
            )
            ->orderBy('price_lists.priority', 'asc');

        if ($order) {
            $query = $order === 'lowest-to-highest'
                ? $query->orderBy('price', 'asc')
                : $query->orderBy('price', 'desc');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($product_id, $countryCode, $currencyCode, $date) {

        $product = Product::query()
                ->leftJoin('price_list_items', 'products.id', '=', 'price_list_items.product_id')
                ->leftJoinSub(
                    DB::table('price_lists')
                        ->when($date, function ($q) use ($date) {
                            $q->whereDate('start_date', '<=', $date)
                                ->orWhereNull('start_date');
                        })
                        ->when($date, function ($q) use ($date) {
                            $q->whereDate('end_date', '>=', $date)
                                ->orWhereNull('end_date');
                        })
                        ->orderBy('priority', 'asc')
                        ->limit(1),
                    'price_lists',
                    function ($join) {
                        $join->on('price_list_items.price_list_id', '=', 'price_lists.id');
                    }
                )
                ->leftJoin('country_currencies', 'price_lists.country_currency_id', '=', 'country_currencies.id')
                ->leftJoin('countries', function ($join) use ($countryCode) {
                    $join->on('countries.id', '=', 'country_currencies.country_id');
                })
                ->leftJoin('currencies', function ($join) use ($currencyCode) {
                    $join->on('currencies.id', '=', 'country_currencies.currency_id');
                })
                ->where('products.id', $product_id)
                ->when($countryCode, function ($q) use ($countryCode) {
                    $q->where('countries.code', '=', $countryCode);
                })
                ->when($currencyCode, function ($q) use ($currencyCode) {
                    $q->where('currencies.code', '=', $currencyCode);
                })
                ->select('products.id', 'products.name', 'products.description', DB::raw('ifnull(price_list_items.price, products.base_price) as price'),  'countries.name as country', 'currencies.code as currency')
                ->orderBy('price_lists.priority', 'asc')
                ->first();

        return $product;
    }
}