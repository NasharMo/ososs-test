<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PriceListController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Products Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Price Lists Routes
    Route::get('/price-lists', [PriceListController::class, 'index'])->name('price-lists.index');
    Route::get('/price-lists/create', [PriceListController::class, 'create'])->name('price-lists.create');
    Route::post('/price-lists', [PriceListController::class, 'store'])->name('price-lists.store');
    Route::get('/price-lists/{price_list}/edit', [PriceListController::class, 'edit'])->name('price-lists.edit');
    Route::put('/price-lists/{price_list}', [PriceListController::class, 'update'])->name('price-lists.update');
    Route::delete('/price-lists/{price_list}', [PriceListController::class, 'destroy'])->name('price-lists.destroy');
    
});

// Redirect /admin to products index
Route::get('/admin', function () {
    return redirect()->route('admin.products.index');
});

Route::get('/', function () {
    return redirect()->route('admin.price-lists.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
