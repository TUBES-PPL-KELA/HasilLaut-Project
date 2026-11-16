<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryBatch;
use App\Models\FlashSaleItem;
use App\Models\Discount;
use Carbon\Carbon;

class ProcessFlashSale extends Command
{
    protected $signature = 'flashsale:process';
    protected $description = 'Otomatis memindahkan stok batch yang melampaui max_storage_days ke etalase Flash Sale';

    public function handle()
    {
        $discount = Discount::first();
        if (!$discount) {
            $this->error('Gagal: Admin belum mengatur Persentase Diskon Global di Dasbor.');
            return;
        }

        $today = Carbon::today();
        
        // Cari batch yang ada stoknya, belum masuk flash sale, dan tidak dikecualikan admin
        $batches = InventoryBatch::with('product')
            ->doesntHave('flashSaleItem')
            ->where('stock', '>', 0)
            ->where('is_flash_sale_excluded', false)
            ->get();

        $processedCount = 0;

        foreach ($batches as $batch) {
            if (!$batch->product || !$batch->product->max_storage_days) {
                continue; 
            }  

            // Reset jam ke 00:00:00 agar perbandingan hari akurat
            $receivedDate = Carbon::parse($batch->received_date)->startOfDay();
            $expiryDate = $receivedDate->copy()->addDays($batch->product->max_storage_days);

            if ($today->greaterThanOrEqualTo($expiryDate)) {
                $normalPrice = $batch->product->price;
                $discountAmount = $normalPrice * ($discount->percent / 100);
                $flashSalePrice = $normalPrice - $discountAmount;

                FlashSaleItem::create([
                    'flash_sale_price' => $flashSalePrice,
                    'stock' => $batch->stock,
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