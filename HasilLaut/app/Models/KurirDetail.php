<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurirDetail extends Model
{
    use HasFactory;

    protected $table = 'kurir_detail';
    protected $primaryKey = 'id_kurir_detail';

    protected $fillable = [
        'vehicle_number',
        'phone_number',
        'status',
        'users_id_users',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }
}