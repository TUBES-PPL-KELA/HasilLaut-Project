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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50);
            $table->tinyInteger('is_pangandaran_area')->unique();
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('kurir_latitude', 10, 8);
            $table->decimal('kurir_longitude', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
