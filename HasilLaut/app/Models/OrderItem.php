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
        'orders_id_orders',
        'product_id_product',
        'flash_sale_item_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Relasi ke tabel orders
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order'); // Sesuaikan 'id_order' jika PK tabel orders berbeda (misal 'id')
    }

    /**
     * Relasi ke tabel products
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product'); // Sesuaikan 'id_product' jika PK tabel products berbeda
    }

    /**
     * Relasi ke tabel flash_sale_items
     */
    public function flashSaleItem()
    {
        return $this->belongsTo(FlashSaleItem::class, 'flash_sale_item_id', 'id_flash_sale_item'); // Sesuaikan PK jika berbeda
    }
}