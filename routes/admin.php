<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| كل الروابط الخاصة بلوحة التحكم موجودة هون، محمية بـ auth + admin middleware
| ومسبوقة بـ prefix('admin') و name('admin.') تلقائيًا من ملف bootstrap/app.php
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::delete('products/gallery/{image}', [ProductController::class, 'destroyImage'])->name('products.gallery.destroy');
});

// كل قسم رح ينضاف هون تباعًا مع بناء كل جزء من لوحة التحكم:
// Route::resource('products', ProductController::class);
// Route::resource('categories', CategoryController::class);
// Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
// Route::resource('promo-codes', PromoCodeController::class);
// Route::resource('partners', PartnerController::class)->only(['index', 'update']);
// Route::resource('articles', ArticleController::class);
// Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);