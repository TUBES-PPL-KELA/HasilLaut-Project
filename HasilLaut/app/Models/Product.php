<?php

namespace App\Models;

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

    protected static function booted(): void
    {
        static::updated(function (Product $product) {
            if ($product->wasChanged('max_storage_days')) {
                $product->inventoryBatches()
                    ->where('stock', '>', 0)
                    ->update(['is_flash_sale_excluded' => false]);
            }
        });
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
}