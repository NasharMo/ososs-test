<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\Product;
use App\Repositories\ProductRepository;

test('it retrieves paginated products with correct pricing from repository', function () {
    $currency = Currency::factory()->create(['code' => 'USD']);
    $country = Country::factory()->create(['code' => 'US', 'name' => 'United States']);
    
    $priceList = PriceList::factory()->create([
        'currency_id' => $currency->id,
        'country_id' => $country->id,
        'priority' => 1,
    ]);

    $product1 = Product::factory()->create(['base_price' => 100]);
    $product2 = Product::factory()->create(['base_price' => 150]);

    // Add products to price list
    PriceListItem::factory()->create([
        'product_id' => $product1->id,
        'price_list_id' => $priceList->id,
        'price' => 90,  // Discounted price
    ]);
    
    PriceListItem::factory()->create([
        'product_id' => $product2->id,
        'price_list_id' => $priceList->id,
        'price' => 140,
    ]);

    $repository = new ProductRepository();
    $result = $repository->getProducts('lowest-to-highest', 10, 1);

    // Assertions for normal case
    expect($result->total())->toBe(2)
        ->and((float) $result->items()[0]->price)->toBe(90.00)
        ->and((float) $result->items()[1]->price)->toBe(140.00)
        ->and($result->items()[0]->currency)->toBe('USD')
        ->and($result->items()[0]->country)->toBe('United States');
});

test('it retrieves product with valid country, currency, and price', function () {
    $currency = Currency::factory()->create(['code' => 'USD']);
    $country = Country::factory()->create(['code' => 'US', 'name' => 'United States']);
    
    $priceList = PriceList::factory()->create([
        'currency_id' => $currency->id,
        'country_id' => $country->id,
        'start_date' => now()->subDays(1),
        'end_date' => now()->addDays(1)
    ]);

    $product = Product::factory()->create(['base_price' => 100]);

    // Add price list item
    PriceListItem::factory()->create([
        'product_id' => $product->id,
        'price_list_id' => $priceList->id,
        'price' => 90,
    ]);

    // Call the repository method
    $repository = new ProductRepository();
    $result = $repository->getById($product->id, 'US', 'USD', now());

    // Assertions
    expect($result->id)->toBe($product->id)
        ->and((float) $result->price)->toBe(90.00)
        ->and($result->currency)->toBe('USD')
        ->and($result->country)->toBe('United States');
});

