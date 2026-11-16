<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flash_sale_item_id')->nullable()->after('product_id_product');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flash_sale_item_id')->nullable()->after('product_id_product');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('flash_sale_item_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('flash_sale_item_id');
        });
    }
};
