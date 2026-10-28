<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurir_detail', function (Blueprint $table) {
            $table->id('id_kurir_detail');
            $table->string('vehicle_number', 20);
            $table->string('phone_number', 20);
            $table->enum('status', ['available', 'busy', 'offline']);
            $table->foreignId('users_id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurir_detail');
    }
};