<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('product', 'is_top_tren')) {
            Schema::table('product', function (Blueprint $table) {
                $table->boolean('is_top_tren')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product', 'is_top_tren')) {
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('is_top_tren');
            });
        }
    }
};
