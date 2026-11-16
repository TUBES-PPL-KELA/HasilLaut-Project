<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_reviews');
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->foreignId('orders_id_orders')->constrained('orders', 'id_orders')->onDelete('cascade');
            $table->foreignId('users_id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};