<?php

use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware([])
->prefix('products')
->name('products.')
->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
});