<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // 1. Sesuaikan nama tabel
    protected $table = 'payments';

    // 2. Sesuaikan nama Primary Key
    protected $primaryKey = 'id_payments';

    // 3. Daftarkan kolom agar tidak kena Mass Assignment Error
    protected $fillable = [
        'orders_id_orders',
        'payment_method',         // Sebelumnya: metode_pembayaran
        'amount',                 // Sebelumnya: jumlah_bayar
        'status',                 // Sebelumnya: status_pembayaran
        'payment_gateway_inv_id',       // Ini nanti diisi oleh Webhook saat status "Lunas"
    ];

    // 4. Relasi ke pesanan
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id_orders', 'id_orders');
    }
}