<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('id_cart_items');
            $table->decimal('quantity', 8, 2);
            $table->foreignId('carts_id_carts')->constrained('carts', 'id_carts')->onDelete('cascade');
            $table->foreignId('product_id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};