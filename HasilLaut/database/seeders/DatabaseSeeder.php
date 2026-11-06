<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Categories;
use App\Models\Product;
use App\Models\InventoryBatch;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        $admin = User::create([
            'name' => 'Andi M. (Admin)',
            'username' => 'admin_pangandaran',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Kategori
       $kategoriIkan = Categories::create(['name' => 'Ikan Segar']);
       $kategoriUdang = Categories::create(['name' => 'Udang & Kepiting']);

        // 3. Buat Produk
        $produk1 = Product::create([
            'name' => 'Ikan Kakap Merah Segar (Loin)',
            'description' => 'Ikan kakap segar kualitas ekspor',
            'price' => 85000,
            'image' => 'kakap.jpg',
            'max_storage_days' => 5,
            'categories_id_categories' => $kategoriIkan->id_categories,
        ]);

        $produk2 = Product::create([
            'name' => 'Udang Windu Laut Segar',
            'description' => 'Udang windu segar tangkapan nelayan',
            'price' => 120000,
            'image' => 'udang.jpg',
            'max_storage_days' => 3,
            'categories_id_categories' => $kategoriUdang->id_categories,
        ]);

        // 4. Buat Batch Inventaris untuk Produk 1
        InventoryBatch::create([
            'received_date' => '2026-10-10',
            'stock' => 45.0,
            'purchase_price' => 60000,
            'users_id_users' => $admin->id_users,
            'product_id_product' => $produk1->id_product,
        ]);

        InventoryBatch::create([
            'received_date' => '2026-10-14',
            'stock' => 30.0,
            'purchase_price' => 62000,
            'users_id_users' => $admin->id_users,
            'product_id_product' => $produk1->id_product,
        ]);

        // 5. Buat Batch Inventaris untuk Produk 2
        InventoryBatch::create([
            'received_date' => '2026-10-12',
            'stock' => 20.0,
            'purchase_price' => 95000,
            'users_id_users' => $admin->id_users,
            'product_id_product' => $produk2->id_product,
        ]);
    }
}