<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PembeliProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PencarianProdukController;
use App\Http\Controllers\PencarianIkanController;

Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product.index');
Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
Route::post('/admin/product', [ProductController::class, 'store'])->name('admin.product.store');
Route::post('/admin/product/{id}/batch', [ProductController::class, 'storeBatch'])->name('admin.product.batch.store');
Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
Route::put('/admin/product/{id}', [ProductController::class, 'update'])->name('admin.product.update');
Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');

Route::get('/pembeli/pencarian-ikan', [PencarianIkanController::class, 'index'])
    ->name('pembeli.pencarian-ikan');

// Rute Keranjang Belanja (Dikeluarkan dari prefix pembeli agar namanya pas: cart, cart.store, dll)
Route::get('/pembeli/keranjang', [CartController::class, 'index'])->name('pembeli.cart');
Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
Route::put('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/', WelcomeController::class)->name('welcome');


// Pastikan dibungkus middleware auth jika memerlukan login pembeli
Route::prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/profile/edit', [PembeliProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [PembeliProfileController::class, 'update'])->name('profile.update');
    Route::get('/produk/{id}', [KatalogController::class, 'show'])->name('produk.detail');
});

// Route Modul PBI-02: Penyesuaian Stok Defect / Rusak
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