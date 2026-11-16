<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model
{
    protected $table = 'inventory_batches';
    protected $primaryKey = 'id_inventory_batches';

    protected $fillable = [
        'received_date', 'stock', 'purchase_price', 'users_id_users', 'product_id_product', 'is_flash_sale_excluded'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }

    public function adjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'inventory_batches_id_inventory_batches', 'id_inventory_batches');
    }

     public function flashSaleItem()
    {
        return $this->hasOne(FlashSaleItem::class, 'inventory_batches_id_inventory_batches', 'id_inventory_batches');
    }

    protected static function booted(): void
    {
        static::updated(function (InventoryBatch $batch) {
            if ($batch->wasChanged('stock')) {
                $newStock = max(0, (float) $batch->stock);

                FlashSaleItem::where('inventory_batches_id_inventory_batches', $batch->id_inventory_batches)
                    ->where('stock', '>', $newStock)
                    ->update(['stock' => $newStock]);
            }
        });
    }
}