<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryBatch;
use App\Models\FlashSaleItem;
use App\Models\Discount;
use Carbon\Carbon;

class ProcessFlashSale extends Command
{
    // Nama perintah untuk dijalankan di terminal: php artisan flashsale:process
    protected $signature = 'flashsale:process';

    // Deskripsi perintah
    protected $description = 'Otomatis memindahkan stok batch yang melampaui max_storage_days ke etalase Flash Sale';

     public function handle()
    {
        $discount = Discount::first();
        if (!$discount) {
            $this->error('Gagal: Admin belum mengatur Persentase Diskon Global di Dasbor.');
            return;
        }

        $today = Carbon::today();
        
        // Cari batch yang ada stoknya dan BELUM masuk ke flash sale
        $batches = InventoryBatch::with('product')
            ->doesntHave('flashSaleItem')
            ->where('stock', '>', 0)
            ->get();

        $processedCount = 0;

         foreach ($batches as $batch) {
            // Lewati jika produk tidak memiliki batas hari simpan
            if (!$batch->product || !$batch->product->max_storage_days) {
                continue; 
            }  

            $receivedDate = Carbon::parse($batch->received_date);
            // Rumus: Tanggal Masuk + Batas Hari = Tanggal Basi
            $expiryDate = $receivedDate->copy()->addDays($batch->product->max_storage_days);

            // Jika Hari Ini sudah melewati atau tepat pada Tanggal Basi
            if ($today->greaterThanOrEqualTo($expiryDate)) {
                
                // Kalkulasi Harga Coret otomatis
                $normalPrice = $batch->product->price;
                $discountAmount = $normalPrice * ($discount->percent / 100);
                $flashSalePrice = $normalPrice - $discountAmount;

                // Masukkan ke etalase Flash Sale
                FlashSaleItem::create([
                    'flash_sale_price' => $flashSalePrice,
                    'stock' => $batch->stock, // Tarik semua sisa stok batch ini
                    'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
                    'discount_id_discount' => $discount->id_discount
                ]);

                $processedCount++;
                $this->info("Memproses: {$batch->product->name} (Batch ID: {$batch->id_inventory_batches})");
            }
        }

        $this->info("Selesai! {$processedCount} batch stok lama berhasil dimasukkan ke Flash Sale.");
    }
}