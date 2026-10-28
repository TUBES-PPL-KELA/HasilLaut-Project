<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id_carts'; // Sesuai ERD
    protected $fillable = ['users_id_users'];

    public function user() {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }

    public function items() {
        return $this->hasMany(CartItem::class, 'carts_id_carts', 'id_carts');
    }
}
