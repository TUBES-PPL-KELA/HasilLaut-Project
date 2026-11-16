<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_items');
            $table->decimal('quantity', 8, 2);
            $table->decimal('price', 12, 2);
            $table->foreignId('orders_id_orders')->constrained('orders', 'id_orders')->onDelete('cascade');
            $table->foreignId('product_id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};