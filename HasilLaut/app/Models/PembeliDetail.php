<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliDetail extends Model
{
    use HasFactory;

    protected $table = 'pembeli_detail';
    
    protected $primaryKey = 'id_pembeli_detail'; 

    protected $fillable = [
        'users_id_users',
        'name',
        'phone_number',
        'address',
        'users_id_users',
        'latitude',
        'longitude',
        'is_valid_geofencing',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }
}