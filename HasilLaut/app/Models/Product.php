<?php

namespace App\Models;
use App\Models\InventoryBatch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'name', 'description', 'price', 'image', 'max_storage_days', 'categories_id_categories', 'hub_name', 'is_top_tren'
    ];

    protected function casts(): array
    {
        return [
            'is_top_tren' => 'boolean',
        ];
    }

   
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id_categories', 'id_categories');
    }

    
    public function inventoryBatches()
    {
        return $this->hasMany(InventoryBatch::class, 'product_id_product', 'id_product');
    }

    public function getTotalStockAttribute()
    {
        return $this->inventoryBatches()->sum('stock');
    }

    protected static function booted(): void
    {
        static::updated(function (Product $product) {
            // Jika admin mengubah batas masa simpan (max_storage_days)
            if ($product->wasChanged('max_storage_days')) {
                InventoryBatch::where('product_id_product', $product->id_product)
                    ->where('stock', '>', 0)
                    ->where('is_flash_sale_excluded', true)
                    ->update(['is_flash_sale_excluded' => false]);
            }
        });
    }
}