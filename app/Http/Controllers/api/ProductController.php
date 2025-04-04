<?php

namespace App\Http\Controllers\api;

use App\Actions\GetCountryCodeFromIpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetApplicablePriceRequest;
use App\Http\Resources\Api\ProductResource;
use App\Models\Country;
use App\Models\CountryCurrency;
use App\Models\PriceList;
use App\Models\PriceListItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $ip = $request->ip();
            $countryCode  = null;
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);
            try {
                $countryCode = GetCountryCodeFromIpAction::execute($ip);
            } catch (\Exception $e) {
                $ip = "193.17.188.77"; // default test ip
            }

            $order = $request->query('order');

            $cache_key_prefix = Product::CACHE_LIST_KEY_PREFIX;
            $cashKey = $cache_key_prefix . $ip . '_page_' . $page . '_order_' . $order . '_perPage_' . $perPage . '_country_' . $countryCode;
            $cacheTime = 600;

            $products = $this->productService->getCachedProducts($cashKey, $cacheTime, $countryCode, $order, $perPage, $page);

            return ProductResource::collection($products);
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GetApplicablePriceRequest $request, int $id)
    {
    //     $country = Country::factory()->create(['code' => 'US', 'name' => 'United States']);
    // $countryCurrency = CountryCurrency::factory()->create([
    //     'country_id' => $country->id,
    //     'currency_id' => null
    // ]);

    // // Create price list with valid date range
    // $priceList = PriceList::factory()->create([
    //     'country_currency_id' => $countryCurrency->id,
    //     'priority' => 1,
    //     'start_date' => now()->subDays(1),
    //     'end_date' => now()->addDays(1),
    // ]);

    // $product = Product::factory()->create(['base_price' => 100]);

    // // Add price list item
    // PriceListItem::factory()->create([
    //     'product_id' => $product->id,
    //     'price_list_id' => $priceList->id,
    //     'price' => 90,
    // ]);

    // return [];


        try {
            $product = Product::findOrFail($id);

            $countryCode = $request->query('country_code');
            $currencyCode = $request->query('currency_code');
            $date = $request->query('date') ?? now();

            $product = $this->productService->getProduct($id, $countryCode, $currencyCode, $date);

            if (!$product) {
                return response()->json([
                    'error' => 'Product not found for the current filters'
                ], 404);
            }
            return ProductResource::make($product);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 404);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
