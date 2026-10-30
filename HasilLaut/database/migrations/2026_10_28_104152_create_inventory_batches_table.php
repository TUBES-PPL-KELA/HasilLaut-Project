<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id('id_inventory_batches');
            $table->date('received_date');
            $table->decimal('stock', 10, 2);
            $table->decimal('purchase_price', 12, 2);
            $table->foreignId('users_id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->foreignId('product_id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};