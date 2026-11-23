<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel di database
    protected $table = 'orders';

    // 2. Tentukan Primary Key yang digunakan (karena bukan 'id' bawaan Laravel)
    protected $primaryKey = 'id_orders';

    // 3. Kolom yang diizinkan untuk diisi secara otomatis (Mengatasi Error Mass Assignment)
    protected $fillable = [
        'order_number',
        'is_pangandaran_area',
        'subtotal_amount',
        'shipping_cost',
        'total_amount',
        'users_id_users',
    ];

    // ==========================================
    // 4. RELASI ANTAR TABEL (Untuk Riwayat Pesanan)
    // ==========================================

    // Relasi ke Pembeli (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }

    // Relasi ke Detail Produk yang dibeli (Order Items)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'orders_id_orders', 'id_orders');
    }

    // Relasi ke Data Pembayaran Midtrans (Payment)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'orders_id_orders', 'id_orders');
    }
}