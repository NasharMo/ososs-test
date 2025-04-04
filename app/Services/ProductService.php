<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getCachedProducts($cacheKey, $cacheTime, $countryCode, $order, $perPage, $page)
    {
        return Cache::remember($cacheKey, $cacheTime, function () use ($countryCode, $order, $perPage, $page) {
            return $this->productRepository->getProducts($countryCode, $order, $perPage, $page);
        });
    }

    public function getProduct($product_id, $countryCode, $currencyCode, $date) 
    {
        return $this->productRepository->getById($product_id, $countryCode, $currencyCode, $date);
    }
}
