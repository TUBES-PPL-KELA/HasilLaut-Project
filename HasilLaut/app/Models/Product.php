<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'max_storage_days',
        'categories_id_categories',
    ];

   public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id_categories', 'id_categories');
    }

    public function inventoryBatches()
    {
        return $this->hasMany(InventoryBatch::class, 'product_id_product', 'id_product');
    }
}