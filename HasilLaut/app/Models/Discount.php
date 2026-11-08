<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';
    protected $primaryKey = 'id_discount';

    protected $fillable = [
        'percent'
    ];

    // Relasi One-to-Many ke FlashSaleItem
    public function flashSaleItems()
    {
        return $this->hasMany(FlashSaleItem::class, 'discount_id_discount', 'id_discount');
    }
}