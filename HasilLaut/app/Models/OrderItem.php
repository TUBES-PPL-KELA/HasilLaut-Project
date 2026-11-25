<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'id_order_items';

    protected $fillable = [
        'quantity',
        'price',
        'orders_id_orders',
        'product_id_product',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id_orders', 'id_orders');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->quantity ?? 0) * (float) ($this->price ?? 0);
    }
}