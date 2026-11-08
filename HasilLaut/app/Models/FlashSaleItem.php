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