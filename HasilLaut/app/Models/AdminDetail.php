<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDetail extends Model
{
    use HasFactory;

    protected $table = 'admin_detail';
    protected $primaryKey = 'id_admin_detail';

    protected $fillable = [
        'phone_number',
        'users_id_users',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }
}