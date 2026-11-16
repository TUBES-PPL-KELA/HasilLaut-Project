<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_items';
    protected $fillable = [
        'quantity',
        'carts_id_carts',
        'product_id_product',
        'flash_sale_item_id',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'carts_id_carts', 'id_carts');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }

    public function flashSaleItem()
    {
        return $this->belongsTo(FlashSaleItem::class, 'flash_sale_item_id', 'id_flash_sale_item');
    }

    // Otomatis pakai harga flash sale jika item berasal dari promo, jika tidak pakai harga normal
    public function getEffectivePriceAttribute()
    {
        if ($this->flashSaleItem) {
            return (float) $this->flashSaleItem->flash_sale_price;
        }

        return (float) ($this->product->price ?? 0);
    }
}