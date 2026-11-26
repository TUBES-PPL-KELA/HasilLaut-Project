<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pembeliDetail(): HasOne
    {
        return $this->hasOne(PembeliDetail::class, 'users_id_users', 'id_users');
    }

    public function kurirDetail(): HasOne
    {
        return $this->hasOne(KurirDetail::class, 'users_id_users', 'id_users');
    }

    public function adminDetail(): HasOne
    {
        return $this->hasOne(AdminDetail::class, 'users_id_users', 'id_users');
    }
}