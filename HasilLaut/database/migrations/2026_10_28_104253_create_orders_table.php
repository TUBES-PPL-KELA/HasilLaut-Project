<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_orders');
            $table->string('order_number', 45);
            $table->tinyInteger('is_pangandaran_area')->default(1);
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('kurir_latitude', 10, 8)->nullable();
            $table->decimal('kurir_longitude', 11, 8)->nullable();
            $table->foreignId('users_id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};