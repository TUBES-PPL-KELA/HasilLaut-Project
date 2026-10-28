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
        Schema::create('users', function (Blueprint $table) {
            // Menyesuaikan primary key dari $table->id() menjadi id_users sesuai ERD
            $table->id('id_users');
            $table->string('name', 100);
            $table->string('username', 100)->unique();
            $table->string('password');
            // Menambahkan kolom role dari ERD (misal: admin, pembeli, kurir)
            $table->enum('role', ['admin', 'pembeli', 'kurir']); 
            $table->rememberToken(); // Tetap dipertahankan agar fitur bawaan Laravel aman
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // Menyesuaikan foreignId agar merujuk ke id_users pada tabel users
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};