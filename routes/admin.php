<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductVariantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::delete('products/gallery/{image}', [ProductController::class, 'destroyImage'])->name('products.gallery.destroy');

Route::prefix('products/{product}/attributes')->name('products.attributes.')->group(function () {
    Route::get('/', [ProductAttributeController::class, 'index'])->name('index');
    Route::post('/', [ProductAttributeController::class, 'store'])->name('store');
    Route::delete('/{attribute}', [ProductAttributeController::class, 'destroy'])->name('destroy');
    Route::post('/{attribute}/values', [ProductAttributeController::class, 'storeValue'])->name('values.store');
    Route::delete('/values/{value}', [ProductAttributeController::class, 'destroyValue'])->name('values.destroy');
});

Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
    Route::post('/generate', [ProductVariantController::class, 'generate'])->name('generate');
    Route::patch('/{variant}', [ProductVariantController::class, 'update'])->name('update');
    Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
});

Route::resource('categories', CategoryController::class)->except(['show']);

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    Route::patch('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
});