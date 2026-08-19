<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductVariantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ContactMessageController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::delete('products/gallery/{image}', [ProductController::class, 'destroyImage'])->name('products.gallery.destroy');
Route::get('products-trashed', [ProductController::class, 'trashed'])->name('products.trashed');
Route::patch('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

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
Route::get('categories-trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
Route::patch('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    Route::patch('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
});

Route::resource('promo-codes', PromoCodeController::class)->except(['show']);

Route::prefix('partners')->name('partners.')->group(function () {
    Route::get('/', [PartnerController::class, 'index'])->name('index');
    Route::patch('/{partner}/approve', [PartnerController::class, 'approve'])->name('approve');
    Route::patch('/{partner}/reject', [PartnerController::class, 'reject'])->name('reject');
    Route::patch('/{partner}/toggle-visibility', [PartnerController::class, 'toggleVisibility'])->name('toggle-visibility');
    Route::delete('/{partner}', [PartnerController::class, 'destroy'])->name('destroy');
    Route::get('partners-trashed', [PartnerController::class, 'trashed'])->name('trashed');
    Route::patch('partners/{id}/restore', [PartnerController::class, 'restore'])->name('restore');
    Route::delete('partners/{id}/force-delete', [PartnerController::class, 'forceDelete'])->name('force-delete');
});

Route::resource('articles', ArticleController::class)->except(['show']);
Route::get('articles-trashed', [ArticleController::class, 'trashed'])->name('articles.trashed');
Route::patch('articles/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');
Route::delete('articles/{id}/force-delete', [ArticleController::class, 'forceDelete'])->name('articles.force-delete');

Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [ContactMessageController::class, 'index'])->name('index');
    Route::get('/{message}', [ContactMessageController::class, 'show'])->name('show');
    Route::delete('/{message}', [ContactMessageController::class, 'destroy'])->name('destroy');
});
