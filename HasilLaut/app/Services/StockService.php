<?php

namespace App\Services;

use App\Models\InventoryBatch;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Skenario 1: Penyesuaian stok manual per batch akibat barang defect/rusak
     */
    public static function deductManualBatch($batchId, $quantity, $reason, $notes, $userId)
    {
        return DB::transaction(function () use ($batchId, $quantity, $reason, $notes, $userId) {
            $batch = InventoryBatch::where('id_inventory_batches', $batchId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($quantity > $batch->stock) {
                throw new Exception("Jumlah pengurangan ({$quantity} kg) melebihi sisa stok batch saat ini ({$batch->stock} kg).");
            }

            // 1. Potong stok batch
            $batch->stock -= $quantity;
            $batch->save();

            // 2. Simpan log riwayat penyesuaian
            return StockAdjustment::create([
                'adjusted_quantity' => $quantity,
                'reason' => $reason,
                'notes' => $notes,
                'inventory_batches_id_inventory_batches' => $batchId,
                'users_id_users' => $userId,
            ]);
        });
    }

    /**
     * Skenario 2: Pemotongan otomatis metode FIFO saat pembayaran transaksi lunas
     */
    public static function deductFifo($productId, $quantityToDeduct)
    {
        return DB::transaction(function () use ($productId, $quantityToDeduct) {
            // Ambil batch dengan stok > 0, urutkan dari tanggal masuk paling lama (FIFO)
            $batches = InventoryBatch::where('product_id_product', $productId)
                ->where('stock', '>', 0)
                ->orderBy('received_date', 'asc')
                ->lockForUpdate()
                ->get();

            $remainingDeduction = $quantityToDeduct;

            foreach ($batches as $batch) {
                if ($remainingDeduction <= 0) {
                    break;
                }

                if ($batch->stock >= $remainingDeduction) {
                    $batch->stock -= $remainingDeduction;
                    $batch->save();
                    $remainingDeduction = 0;
                } else {
                    $remainingDeduction -= $batch->stock;
                    $batch->stock = 0;
                    $batch->save();
                }
            }

            if ($remainingDeduction > 0) {
                throw new Exception("Total stok produk di seluruh batch tidak mencukupi.");
            }
        });
    }
}