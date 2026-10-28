<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_items';
    protected $fillable = ['quantity', 'carts_id_carts', 'product_id_product'];

    public function cart() {
        return $this->belongsTo(Cart::class, 'carts_id_carts', 'id_carts');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id_product', 'id_product');
    }
}