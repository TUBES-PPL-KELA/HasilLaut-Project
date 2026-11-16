<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\InventoryBatch;
use App\Models\PembeliDetail;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        $admin = User::updateOrCreate(
            ['username' => 'admin_pangandaran'],
            [
                'name' => 'Andi M. (Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // **Buat Kategori**
        $kategoriIkan = Category::create(['name' => 'Ikan Segar']);
        $kategoriUdang = Category::create(['name' => 'Udang & Kepiting']);

        // **Buat Produk Ikan Kakap dengan Link Gambar Pilihan Anda**
        $produk1 = Product::create([
            'name' => 'Ikan Kakap Merah Segar (Loin)',
            'description' => 'Ikan kakap segar kualitas ekspor',
            'price' => 85000,
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7NBMvNbyUibW3UfWL_rkX7E1DnRHD0Ns2Km2VsGlYvw&s=10',
            'max_storage_days' => 5,
            'categories_id_categories' => $kategoriIkan->id_categories,
        ]);

        // **Buat Produk Udang Windu dengan Link Gambar Pilihan Anda**
        $produk2 = Product::create([
            'name' => 'Udang Windu Laut Segar',
            'description' => 'Udang windu segar tangkapan nelayan',
            'price' => 120000,
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOpbSpoOvc-zfa8EXN0lKGZQjDQW6Dtanb79vprfSmog&s=10',
            'max_storage_days' => 3,
            'categories_id_categories' => $kategoriUdang->id_categories,
        ]);

        // **Buat Batch Inventaris untuk Produk 1**
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

        // **Buat Batch Inventaris untuk Produk 2**
        InventoryBatch::create([
            'received_date' => '2026-10-12',
            'stock' => 20.0,
            'purchase_price' => 95000,
            'users_id_users' => $admin->id_users,
            'product_id_product' => $produk2->id_product,
        ]);

        PembeliDetail::create([
            'name' => 'Hilya Shafwa Najla Abadi',
            'phone_number' => '081234567890',
            'address' => 'Jl. Pantai Pangandaran No. 12, Kec. Pangandaran',
            'users_id_users' => $admin->id_users,
        ]);

        // 7. Buat Keranjang & Item Keranjang untuk pengujian Checkout Midtrans
        $cart = Cart::create([
            'users_id_users' => $admin->id_users,
        ]);

        CartItem::create([
            'carts_id_carts' => $cart->id_carts,
            'product_id_product' => $produk1->id_product,
            'quantity' => 2,
        ]);

        CartItem::create([
            'carts_id_carts' => $cart->id_carts,
            'product_id_product' => $produk2->id_product,
            'quantity' => 1,
        ]);
    }
}