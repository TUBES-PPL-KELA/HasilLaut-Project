<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    protected $table = 'flash_sale_item';
    protected $primaryKey = 'id_flash_sale_item';

    protected $fillable = [
        'flash_sale_price', 
        'stock', 
        'inventory_batches_id_inventory_batches', 
        'discount_id_discount'
    ];

    protected static function booted(): void
    {
        static::updated(function (FlashSaleItem $item) {
            if ($item->wasChanged('stock')) {
                $oldStock = (float) $item->getOriginal('stock');
                $newStock = (float) $item->stock;
                $diff = $oldStock - $newStock;

                // Jika stok flash sale berkurang (misal dibeli saat checkout B2C),
                // otomatis kurangi juga stok fisik di inventory_batches
                if ($diff > 0) {
                    InventoryBatch::where('id_inventory_batches', $item->inventory_batches_id_inventory_batches)
                        ->where('stock', '>=', $diff)
                        ->decrement('stock', $diff);
                }
            }
        });
    }

    // Relasi belongsTo ke InventoryBatch
    public function inventoryBatch()
    {
        return $this->belongsTo(InventoryBatch::class, 'inventory_batches_id_inventory_batches', 'id_inventory_batches');
    }

    // Relasi belongsTo ke Discount
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id_discount', 'id_discount');
    }
}