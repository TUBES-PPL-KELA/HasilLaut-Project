<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model
{
    protected $table = 'inventory_batches';
    protected $primaryKey = 'id_inventory_batches';

    protected $fillable = [
        'received_date', 'stock', 'purchase_price', 'users_id_users', 'product_id_product'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }
}