<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_orders';

    protected $fillable = [
        'order_number',
        'is_pangandaran_area',
        'subtotal_amount',
        'shipping_cost',
        'total_amount',
        'kurir_latitude',
        'kurir_longitude',
        'users_id_users',
    ];

    protected $casts = [
        'is_pangandaran_area' => 'boolean',
        'subtotal_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'kurir_latitude' => 'decimal:8',
        'kurir_longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }

    public function pembeliDetail()
    {
        return $this->belongsTo(PembeliDetail::class, 'users_id_users', 'users_id_users');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'orders_id_orders', 'id_orders');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'orders_id_orders', 'id_orders');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'orders_id_orders', 'id_orders');
    }

    public function getTotalWeightAttribute(): float
    {
        if (!$this->relationLoaded('orderItems')) {
            return 0;
        }

        return (float) $this->orderItems->sum(function ($item) {
            $weightPerUnit = $item->product->weight
                ?? $item->product->berat
                ?? 1;

            return (float) $item->quantity * (float) $weightPerUnit;
        });
    }
}