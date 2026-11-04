<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockAdjustmentController;

Route::get('/', function () {
    return view('welcome');
});

// Route Modul PBI-02: Penyesuaian Stok Defect / Rusak
Route::prefix('admin/stok')->group(function () {
    Route::get('/penyesuaian', [StockAdjustmentController::class, 'index'])->name('admin.stok.penyesuaian');
    Route::get('/get-batches/{productId}', [StockAdjustmentController::class, 'getBatchesByProduct'])->name('admin.stok.get-batches');
    Route::post('/penyesuaian', [StockAdjustmentController::class, 'store'])->name('admin.stok.penyesuaian.store');
});