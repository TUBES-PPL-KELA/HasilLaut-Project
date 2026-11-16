<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id_order_items';

    protected $fillable = [
        'quantity',
        'price',
        'orders_id_orders',
        'product_id_product',
        'flash_sale_item_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id_orders', 'id_orders');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }

    public function flashSaleItem()
    {
        return $this->belongsTo(FlashSaleItem::class, 'flash_sale_item_id', 'id_flash_sale_item');
    }
}