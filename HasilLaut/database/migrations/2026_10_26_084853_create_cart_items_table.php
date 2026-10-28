<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('id_cart_items');
            $table->integer('quantity');
            $table->unsignedBigInteger('carts_id_carts');
            $table->unsignedBigInteger('product_id_product');
            $table->timestamps();
            $table->foreign('carts_id_carts')->references('id_carts')->on('carts')->onDelete('cascade');
            $table->foreign('product_id_product')->references('id_product')->on('product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};