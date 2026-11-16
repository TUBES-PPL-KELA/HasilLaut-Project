<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom status_pesanan jika belum ada
            if (!Schema::hasColumn('orders', 'status_pesanan')) {
                $table->string('status_pesanan', 50)->default('Menunggu Pembayaran')->after('users_id_users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });
    }
};