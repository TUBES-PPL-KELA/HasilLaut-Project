<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id('id_stock_adjustments');
            $table->decimal('adjusted_quantity', 8, 2);
            $table->string('reason', 50); // Busuk/Rusak, Penyusutan Timbangan, Kesalahan Input
            $table->text('notes')->nullable(); // Catatan Admin
            $table->foreignId('inventory_batches_id_inventory_batches')
                  ->constrained('inventory_batches', 'id_inventory_batches')
                  ->onDelete('cascade');
            $table->foreignId('users_id_users')
                  ->constrained('users', 'id_users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};