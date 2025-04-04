<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreatePriceListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\PriceListResource;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PriceListController extends Controller
{
    public function index()
    {
        $priceLists = PriceListResource::collection(PriceList::with(['countryCurrencies.country', 'countryCurrencies.currency'])->get())->resolve();

        return view('admin.price_lists.index', compact('priceLists'));
    }

    public function create()
    {
        $products = ProductResource::collection(Product::all());
        $countries = CountryResource::collection(Country::with('currencies')->get());
        $currencies = CurrencyResource::collection(Currency::all()) ;

        return view('admin.price_lists.create', compact('products', 'countries', 'currencies'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'currency_id' => 'nullable|exists:currencies,id',
                'country_id' => 'nullable|exists:countries,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'priority' => 'required|integer|min:0',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.price' => 'required|numeric|min:0',
            ]);

            CreatePriceListAction::execute($validated);
          
            return redirect()->route('admin.price-lists.index')->with('success', 'Price list created successfully.');

        } catch (ValidationException $e) {

            Log::warning('Validation failed', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error creating price list', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit(PriceList $priceList)
    {

        $products = ProductResource::collection(Product::all());
        $countries = CountryResource::collection(Country::with('currencies')->get());
        $currencies = CurrencyResource::collection(Currency::all()) ;
        $priceList = $priceList->load(['priceListItems.product', 'countryCurrencies.country', 'countryCurrencies.currency']);
        return view('admin.price_lists.edit', compact('priceList', 'countries', 'currencies', 'products'));
    }

    public function update(Request $request, PriceList $priceList)
    {
        try {

        $request->validate([
            'currency_id' => 'nullable|exists:currencies,id',
            'country_id' => 'nullable|exists:countries,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'priority' => 'required|integer|min:0',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();


        $priceList->update($request->only([
            'country_currency_id',
            'start_date',
            'end_date',
            'priority'
        ]));

        // Delete existing priceListItems
        $priceList->priceListItems()->delete();

        // Add new priceListItems
        foreach ($request->products as $productData) {
            $priceList->priceListItems()->create([
                'product_id' => $productData['id'],
                'price' => $productData['price']
            ]);
        }

        DB::commit();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Price list updated successfully.');

        } catch (ValidationException $e) {

            Log::warning('Validation failed', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error creating price list', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function destroy(PriceList $priceList)
    {
        try {
            // Delete all associated price list priceListItems first
            $priceList->priceListItems()->delete();
            $priceList->delete();

            return redirect()->route('admin.price-lists.index')
                ->with('success', 'Price list deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.price-lists.index')
                ->with('error', 'Error deleting price list: ' . $e->getMessage());
        }
    }
}
