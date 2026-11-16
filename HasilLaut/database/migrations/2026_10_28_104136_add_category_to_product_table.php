<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('product', 'id') && ! Schema::hasColumn('product', 'id_product')) {
            Schema::table('product', function (Blueprint $table) {
                $table->renameColumn('id', 'id_product');
            });
        }

        if (! Schema::hasColumn('product', 'categories_id_categories')) {
            Schema::table('product', function (Blueprint $table) {
                $table->foreignId('categories_id_categories')
                    ->nullable()
                    ->constrained('categories', 'id_categories')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product', 'categories_id_categories')) {
            Schema::table('product', function (Blueprint $table) {
                $table->dropForeign(['categories_id_categories']);
                $table->dropColumn('categories_id_categories');
            });
        }

        if (Schema::hasColumn('product', 'id_product') && ! Schema::hasColumn('product', 'id')) {
            Schema::table('product', function (Blueprint $table) {
                $table->renameColumn('id_product', 'id');
            });
        }
    }
};