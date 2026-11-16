<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliDetail extends Model
{
    use HasFactory;

    protected $table = 'pembeli_detail'; // Menyesuaikan dengan nama tabel di database
    
    protected $primaryKey = 'id_pembeli_detail';

    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'users_id_users',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }
}