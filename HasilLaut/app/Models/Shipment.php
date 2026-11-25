<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $table = 'shipment';
    protected $primaryKey = 'id_shipment';

    protected $fillable = [
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'image',
        'orders_id_orders',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id_orders', 'id_orders');
    }
}