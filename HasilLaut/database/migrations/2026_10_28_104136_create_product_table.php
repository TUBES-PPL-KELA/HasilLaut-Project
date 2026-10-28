<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('id_product');
            $table->string('name', 45);
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->string('image', 255);
            $table->integer('max_storage_days');
            $table->foreignId('categories_id_categories')->constrained('categories', 'id_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};