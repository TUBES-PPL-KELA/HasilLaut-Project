<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PembeliProfileController;
use App\Http\Controllers\PencarianIkanController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;

Route::get('/admin/kategori', [CategoryController::class, 'index'])->name('admin.category.index');
Route::get('/admin/kategori/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/kategori', [CategoryController::class, 'store'])->name('admin.category.store');

Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product.index');
Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
Route::post('/admin/product', [ProductController::class, 'store'])->name('admin.product.store');
Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
Route::put('/admin/product/{id}', [ProductController::class, 'update'])->name('admin.product.update');
Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');

Route::get('/keranjang', [CartController::class, 'index'])->name('pembeli.cart');
Route::put('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/', WelcomeController::class)->name('welcome');

Route::get('/pembeli/pencarian-ikan', [PencarianIkanController::class, 'index'])
    ->name('pembeli.pencarian-ikan');


Route::prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/profile/edit', [PembeliProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [PembeliProfileController::class, 'update'])->name('profile.update');
    Route::get('/produk/{id}', [KatalogController::class, 'show'])->name('produk.detail');
});


Route::prefix('admin/stok')->group(function () {
    Route::get('/penyesuaian', [StockAdjustmentController::class, 'index'])->name('admin.stok.penyesuaian');
    Route::get('/get-batches/{productId}', [StockAdjustmentController::class, 'getBatchesByProduct'])->name('admin.stok.get-batches');
    Route::post('/penyesuaian', [StockAdjustmentController::class, 'store'])->name('admin.stok.penyesuaian.store');
});

// Route Modul PBI-03: Flash Sale Otomatis Stok Lama
Route::prefix('admin/flash-sale')->group(function () {
    Route::get('/', [App\Http\Controllers\FlashSaleController::class, 'index'])->name('admin.flash_sale.index');
    Route::post('/discount', [App\Http\Controllers\FlashSaleController::class, 'updateGlobalDiscount'])->name('admin.flash_sale.discount.store');
    Route::delete('/hentikan/{id}', [App\Http\Controllers\FlashSaleController::class, 'destroy'])->name('admin.flash_sale.destroy');
});

// Route Webhook Midtrans (PBI-15 & PBI-02 FIFO)
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->name('midtrans.webhook');
