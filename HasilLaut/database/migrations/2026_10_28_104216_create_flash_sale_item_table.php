<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_item', function (Blueprint $table) {
            $table->id('id_flash_sale_item');
            $table->decimal('flash_sale_price', 12, 2);
            $table->integer('stock');
            $table->foreignId('inventory_batches_id_inventory_batches')->constrained('inventory_batches', 'id_inventory_batches')->onDelete('cascade');
            $table->foreignId('discount_id_discount')->constrained('discount', 'id_discount')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_item');
    }
};