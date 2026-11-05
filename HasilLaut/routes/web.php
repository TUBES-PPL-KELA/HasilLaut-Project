<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

// Route Modul PBI-02: Penyesuaian Stok Defect / Rusak
Route::prefix('admin/stok')->group(function () {
    Route::get('/penyesuaian', [StockAdjustmentController::class, 'index'])->name('admin.stok.penyesuaian');
    Route::get('/get-batches/{productId}', [StockAdjustmentController::class, 'getBatchesByProduct'])->name('admin.stok.get-batches');
    Route::post('/penyesuaian', [StockAdjustmentController::class, 'store'])->name('admin.stok.penyesuaian.store');
});

// Pastikan dibungkus middleware auth jika memerlukan login pembeli
Route::prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');
});