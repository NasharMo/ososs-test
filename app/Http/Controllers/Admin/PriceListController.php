<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ClearProductListCacheAction;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $priceLists = PriceList::with(['country', 'currency', 'items.product'])->orderBy('id', 'desc')->get();
        return view('admin.price_lists.index', compact('priceLists'));
    }

    public function create()
    {
        $products = Product::all();
        $countries = Country::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        
        return view('admin.price_lists.create', compact('products', 'countries', 'currencies'));
    }

    public function store(Request $request)
    {
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

        // Create price list
        $priceList = PriceList::create([
            'currency_id' => $validated['currency_id'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'priority' => $validated['priority'],
        ]);

        // Add price list items
        foreach ($validated['products'] as $productData) {
            $priceList->items()->create([
                'product_id' => $productData['id'],
                'price' => $productData['price'],
            ]);
        }

        ClearProductListCacheAction::execute();

        return redirect()->route('admin.price-lists.index')->with('success', 'Price list created successfully.');
    }

    public function edit(PriceList $priceList)
{
    $countries = Country::orderBy('name')->get();
    $currencies = Currency::orderBy('name')->get();
    $products = Product::all();
    
    return view('admin.price_lists.edit', compact('priceList', 'countries', 'currencies', 'products'));
}

public function update(Request $request, PriceList $priceList)
{
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
    
    $priceList->update($request->only([
        'currency_id', 'country_id', 'start_date', 'end_date', 'priority'
    ]));
    
    // Delete existing items
    $priceList->items()->delete();
    
    // Add new items
    foreach ($request->products as $productData) {
        $priceList->items()->create([
            'product_id' => $productData['id'],
            'price' => $productData['price']
        ]);
    }

    ClearProductListCacheAction::execute();
    
    return redirect()->route('admin.price-lists.index')
        ->with('success', 'Price list updated successfully.');
}


public function destroy(PriceList $priceList)
{
    try {
        // Delete all associated price list items first
        $priceList->items()->delete();
        $priceList->delete();

        ClearProductListCacheAction::execute();
        
        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Price list deleted successfully.');
            
    } catch (\Exception $e) {
        return redirect()->route('admin.price-lists.index')
            ->with('error', 'Error deleting price list: ' . $e->getMessage());
    }
}
}