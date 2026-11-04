<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustments';
    protected $primaryKey = 'id_stock_adjustments';

    protected $fillable = [
        'adjusted_quantity',
        'reason',
        'notes',
        'inventory_batches_id_inventory_batches',
        'users_id_users',
    ];

    public function inventoryBatch()
    {
        return $this->belongsTo(InventoryBatch::class, 'inventory_batches_id_inventory_batches', 'id_inventory_batches');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_users', 'id_users');
    }
}