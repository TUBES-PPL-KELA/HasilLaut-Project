<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
        public function product()
    {
        // Ganti 'products_id_product' dengan nama kolom asli yang ada di tabel 'reviews' Anda
        return $this->belongsTo(Product::class, 'products_id_product', 'id_product');
    }
}
