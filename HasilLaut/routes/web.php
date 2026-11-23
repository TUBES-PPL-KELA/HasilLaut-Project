<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PembeliProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PencarianProdukController;
use App\Http\Controllers\PencarianIkanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\CategoryController;

// Route Modul PBI-01: Manajemen Produk & Stok Gudang
Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product.index');
Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
Route::post('/admin/product', [ProductController::class, 'store'])->name('admin.product.store');
Route::post('/admin/product/{id}/batch', [ProductController::class, 'storeBatch'])->whereNumber('id')->name('admin.product.batch.store');
Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->whereNumber('id')->name('admin.product.edit');
Route::put('/admin/product/{id}', [ProductController::class, 'update'])->whereNumber('id')->name('admin.product.update');
Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->whereNumber('id')->name('admin.product.destroy');

// Route Kategori (mencakup admin.category.index, create, store, edit, update, destroy)
Route::resource('/admin/category', CategoryController::class)->names('admin.category');

// Route Kelola Kategori Admin
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/category', [CategoryController::class, 'store'])->name('admin.category.store');
Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

Route::get('/pembeli/pencarian-ikan', [PencarianIkanController::class, 'index'])
    ->name('pembeli.pencarian-ikan');

// Rute Keranjang Belanja (Dikeluarkan dari prefix pembeli agar namanya pas: cart, cart.store, dll)
Route::get('/pembeli/keranjang', [CartController::class, 'index'])->name('pembeli.cart');
Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
Route::match(['put', 'patch'], '/pembeli/cart/{id}', [CartController::class, 'update'])->name('cart.update');
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
    Route::get('/', [FlashSaleController::class, 'index'])->name('admin.flash_sale.index');
    Route::post('/discount', [FlashSaleController::class, 'updateGlobalDiscount'])->name('admin.flash_sale.discount.store');
    Route::delete('/hentikan/{id}', [FlashSaleController::class, 'destroy'])->name('admin.flash_sale.destroy');
});

// Route Halaman Status Pesanan setelah selesai bayar di Midtrans (Mockup No. 6)
Route::get('/pesanan/status', [CheckoutController::class, 'status'])->name('pembeli.pesanan.status');

// Route Webhook Midtrans (PBI-15 & PBI-02 FIFO)
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->name('midtrans.webhook');