<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment', function (Blueprint $table) {
            $table->id('id_shipment');
            $table->enum('status', ['assigned', 'picked_up', 'delivered'])->nullable();
            $table->timestamp('assigned_at');
            $table->timestamp('picked_up_at');
            $table->timestamp('delivered_at');
            $table->string('image', 255)->nullable();
            $table->foreignId('orders_id_orders')->constrained('orders', 'id_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment');
    }
};