<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products =  ProductResource::collection(Product::orderBy('id', 'desc')->get());

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'base_price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ]);

            $this->clearProductListCache();

            Product::create($validated);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (ValidationException $e) {

            Log::warning('Validation failed', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'base_price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ]);

            $product->update($request->all());

            $this->clearProductListCache();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (ValidationException $e) {

            Log::warning('Validation failed', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        try {

            $product->delete();

            $this->clearProductListCache();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting product', [
                'error' => $e->getMessage(),
                'product_id' => $product->id
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    private function clearProductListCache(): void
    {
        $cache_key_prefix = Product::CACHE_LIST_KEY_PREFIX;

        $cacheKeys = Cache::getKeysByPattern("*" . $cache_key_prefix . "*");
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
