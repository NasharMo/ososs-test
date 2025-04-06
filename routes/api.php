<?php

use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])
    ->prefix('products')
    ->name('products.')
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
    });
