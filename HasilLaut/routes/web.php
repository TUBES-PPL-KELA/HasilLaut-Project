<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PembeliProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK & WEBHOOK EKSTERNAL
|--------------------------------------------------------------------------
| Dapat diakses oleh siapa saja tanpa perlu login.
*/

// Halaman Beranda (Landing Page)
Route::get('/', WelcomeController::class)->name('welcome');

// Webhook Notifikasi Pembayaran Midtrans 
// Dikecualikan dari verifikasi CSRF di bootstrap/app.php
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->name('midtrans.webhook');


/*
|--------------------------------------------------------------------------
| 2. AUTENTIKASI (LOGIN, REGISTER & LOGOUT)
|--------------------------------------------------------------------------
| Middleware 'guest' memastikan user yang sudah login tidak kembali ke form
| login/register. Register dikhususkan untuk role 'pembeli'.
*/

Route::middleware('guest')->group(function () {
    // Halaman & Proses Login (Satu pintu untuk Admin, Pembeli, dan Kurir)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Halaman & Proses Registrasi (Khusus Pembeli)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Proses Logout (Hanya untuk user yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| 3. AREA PEMBELI (WAJIB LOGIN & ROLE: PEMBELI)
|--------------------------------------------------------------------------
| Pengunjung tanpa login otomatis diarahkan ke halaman /login.
| Fitur pencarian ikan sudah terintegrasi langsung ke dalam KatalogController.
*/

Route::middleware(['auth', 'role:pembeli'])->group(function () {

    // Grup Route dengan prefix URL '/pembeli/...' dan prefix nama 'pembeli....'
    Route::prefix('pembeli')->name('pembeli.')->group(function () {
        // Katalog Produk & Pencarian Ikan (terintegrasi dengan PencarianIkanController)
        Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

        // Detail Produk (Reguler maupun Item Flash Sale)
        Route::get('/produk/{id}', [KatalogController::class, 'show'])->name('produk.detail');

        // Halaman & Proses Checkout Pesanan
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');

        // Pengaturan Profil & Alamat Pengiriman Pembeli
        Route::get('/profile/edit', [PembeliProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [PembeliProfileController::class, 'update'])->name('profile.update');
    });

    // Manajemen Keranjang Belanja (Dipisah dari prefix agar nama route tetap ringkas)
    Route::get('/pembeli/keranjang', [CartController::class, 'index'])->name('pembeli.cart');
    Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
    Route::match(['put', 'patch'], '/pembeli/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Halaman Status Pesanan setelah pembayaran Midtrans / COD
    Route::get('/pesanan/status', [CheckoutController::class, 'status'])->name('pembeli.pesanan.status');
});


/*
|--------------------------------------------------------------------------
| 4. AREA ADMIN (WAJIB LOGIN & ROLE: ADMIN)
|--------------------------------------------------------------------------
| Mengelola produk, stok gudang, kategori, flash sale, dan pesanan masuk.
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Utama Admin (Dialihkan sementara ke Pesanan Masuk sampai PBI-08 siap)
    Route::get('/admin/dashboard', function () {
        return redirect()->route('admin.orders.index');
    })->name('admin.dashboard');

    // Modul PBI-01: Manajemen Produk & Stok Batch Gudang
    Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product.index');
    Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/admin/product', [ProductController::class, 'store'])->name('admin.product.store');
    Route::post('/admin/product/{id}/batch', [ProductController::class, 'storeBatch'])->whereNumber('id')->name('admin.product.batch.store');
    Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->whereNumber('id')->name('admin.product.edit');
    Route::put('/admin/product/{id}', [ProductController::class, 'update'])->whereNumber('id')->name('admin.product.update');
    Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->whereNumber('id')->name('admin.product.destroy');

    // Manajemen Kategori Produk (Sudah mencakup index, create, store, edit, update, destroy)
    Route::resource('/admin/category', CategoryController::class)->names('admin.category');

    // Modul PBI-02: Penyesuaian Stok Defect / Rusak
    Route::prefix('admin/stok')->group(function () {
        Route::get('/penyesuaian', [StockAdjustmentController::class, 'index'])->name('admin.stok.penyesuaian');
        Route::get('/get-batches/{productId}', [StockAdjustmentController::class, 'getBatchesByProduct'])->name('admin.stok.get-batches');
        Route::post('/penyesuaian', [StockAdjustmentController::class, 'store'])->name('admin.stok.penyesuaian.store');
    });

    // Modul PBI-03: Flash Sale Otomatis Stok Lama
    Route::prefix('admin/flash-sale')->group(function () {
        Route::get('/', [FlashSaleController::class, 'index'])->name('admin.flash_sale.index');
        Route::post('/discount', [FlashSaleController::class, 'updateGlobalDiscount'])->name('admin.flash_sale.discount.store');
        Route::delete('/hentikan/{id}', [FlashSaleController::class, 'destroy'])->name('admin.flash_sale.destroy');
    });

    // Modul PBI-04: Manajemen Pesanan Masuk & Operasional Hub
    Route::get('/admin/pesanan-masuk', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::match(['put', 'patch'], '/admin/pesanan-masuk/{id}/operasional', [OrderController::class, 'updateOperational'])
        ->whereNumber('id')
        ->name('admin.orders.update-operational');

    // Modul Manajemen Pengguna (Admin, Kurir, Pembeli)
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->whereNumber('id')->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->whereNumber('id')->name('admin.users.destroy');
});


/*
|--------------------------------------------------------------------------
| 5. AREA KURIR (WAJIB LOGIN & ROLE: KURIR)
|--------------------------------------------------------------------------
| Halaman sementara untuk Sprint 2 sampai modul pengiriman kurir selesai.
*/

Route::middleware(['auth', 'role:kurir'])->group(function () {
    Route::get('/kurir/tugas-pengiriman', function () {
        return view('kurir.tugas');
    })->name('kurir.tugas');
});