<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Menyuntikkan kategori umum ke database
        $categories = [
            ['name' => 'Ikan Segar Laut'],
            ['name' => 'Kepiting & Rajungan'],
            ['name' => 'Udang & Lobster'],
            ['name' => 'Olahan Laut'],
            ['name' => 'Hasil Tangkapan Nelayan Lainnya']
        ];

        DB::table('categories')->insert($categories);
    }
}