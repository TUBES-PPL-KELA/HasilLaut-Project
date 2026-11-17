<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliDetail extends Model
{
    use HasFactory;

    protected $table = 'pembeli_detail';
    
    // Sesuaikan dengan nama kolom primary key asli di tabel pembeli_detail
    protected $primaryKey = 'id_pembeli_detail'; 

    protected $fillable = [
        'users_id_users',
        'name',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'is_valid_geofencing',
    ];
}