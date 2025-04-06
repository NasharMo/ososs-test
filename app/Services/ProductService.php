<?php

namespace App\Services;

use App\Repositories\CountryRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(private ProductRepository $productRepository, private CountryRepository $countryRepository, private CurrencyRepository $currencyRepository) {}

    public function getCachedProducts($cacheKey, $cacheTime, $order, $perPage, $page)
    {
        return Cache::remember($cacheKey, $cacheTime, function () use ($order, $perPage, $page) {
            return $this->productRepository->getProducts($order, $perPage, $page);
        });
    }

    public function getProduct($product_id, $countryCode, $currencyCode, $date)
    {
        $country = $this->countryRepository->getByCode($countryCode);
        if (!$country) {
            throw new \Exception('Country not found', 404);
        }

        $currency = $this->currencyRepository->getByCode($currencyCode);
        if (!$currency) {
            throw new \Exception('Currency not found', 404);
        }

        $product = $this->productRepository->getById($product_id, $countryCode, $currencyCode, $date);

        if (!$product) {
            throw new \Exception('Product not found for the current filters', 404);
        }
        $product->country = $product->country ?? $country->name;
        $product->currency = $product->currency ?? $currency->code;

        return $product;
    }
}
