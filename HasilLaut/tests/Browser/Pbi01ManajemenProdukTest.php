<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Pbi01ManajemenProdukTest extends DuskTestCase
{
    /**
     * Jeda antar aksi dalam milidetik (1500 ms = 1.5 detik)
     * agar proses di browser Chrome terlihat jelas.
     */
    protected int $delay = 1500;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareFixtures();
        $this->prepareDatabase();
    }

    /**
     * Membuat file dummy kakap.jpg dan sertifikat.pdf otomatis untuk uji upload.
     */
    private function prepareFixtures(): void
    {
        $dir = base_path('tests/Browser/fixtures');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $jpgPath = $dir . '/kakap.jpg';
        if (! file_exists($jpgPath)) {
            if (function_exists('imagecreatetruecolor')) {
                $img = imagecreatetruecolor(300, 300);
                $bg = imagecolorallocate($img, 15, 118, 110);
                imagefill($img, 0, 0, $bg);
                imagejpeg($img, $jpgPath, 90);
                imagedestroy($img);
            } else {
                file_put_contents($jpgPath, base64_decode(
                    '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='
                ));
            }
        }

        $pdfPath = $dir . '/sertifikat.pdf';
        if (! file_exists($pdfPath)) {
            file_put_contents($pdfPath, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");
        }
    }

    /**
     * Menyiapkan user ID 1 dan kategori 'Ikan Segar Laut' di database hasilaut_dusk.
     */
    private function prepareDatabase(): int
    {
        // 1. Pastikan user dengan ID = 1 ada (karena ProductController@store mengisi users_id_users = 1)
        if (Schema::hasTable('users')) {
            $userCols = Schema::getColumnListing('users');
            $userPk = in_array('id_users', $userCols) ? 'id_users' : 'id';

            if (! DB::table('users')->where($userPk, 1)->exists()) {
                $userData = [
                    $userPk    => 1,
                    'name'     => 'Andi M. (Admin)',
                    'email'    => 'admin.dusk@hasillaut.test',
                    'password' => Hash::make('password'),
                ];
                if (in_array('role', $userCols)) {
                    $userData['role'] = 'admin';
                }
                DB::table('users')->insert($userData);
            }
        }

        // 2. Pastikan kategori 'Ikan Segar Laut' tersedia
        $catCols = Schema::getColumnListing('categories');
        $catPk = in_array('id_categories', $catCols) ? 'id_categories' : 'id';
        $category = DB::table('categories')->where('name', 'Ikan Segar Laut')->first();

        if (! $category) {
            $catId = DB::table('categories')->insertGetId([
                'name' => 'Ikan Segar Laut',
            ]);
        } else {
            $catId = $category->{$catPk};
        }

        // 3. Bersihkan sisa data produk uji sebelumnya agar test selalu bersih saat diulang
        $testNames = ['Kakap Merah Segar', 'Udang Windu', 'Tuna Loin'];
        $existingIds = DB::table('product')->whereIn('name', $testNames)->pluck('id_product');
        if ($existingIds->isNotEmpty()) {
            DB::table('inventory_batches')->whereIn('product_id_product', $existingIds)->delete();
            DB::table('product')->whereIn('id_product', $existingIds)->delete();
        }

        return (int) $catId;
    }

    /**
     * TC-PBI01-01: [Normal] Menambahkan master produk baru dengan data valid
     */
    public function test_tc_pbi01_01_menambahkan_produk_baru_dengan_data_valid(): void
    {
        $categoryId = $this->prepareDatabase();
        $jpgPath = base_path('tests/Browser/fixtures/kakap.jpg');

        $this->browse(function (Browser $browser) use ($categoryId, $jpgPath) {
            $browser->visit('/admin/product')
                ->pause($this->delay)
                ->assertSee('Etalase Master Katalog & Stok Gudang Hub')
                ->clickLink('Tambah Hasil Laut Baru')
                ->pause($this->delay)
                ->assertPathIs('/admin/product/create')
                ->type('#name', 'Kakap Merah Segar')
                ->pause($this->delay)
                ->select('#categories_id_categories', (string) $categoryId)
                ->pause($this->delay)
                ->type('#purchase_price_view', '45000')
                ->pause($this->delay)
                ->type('#price_view', '65000')
                ->pause($this->delay)
                ->clear('#stock')
                ->type('#stock', '50')
                ->pause($this->delay)
                ->type('#description', 'Ikan kakap merah segar hasil tangkapan nelayan Pangandaran.')
                ->pause($this->delay)
                ->attach('#image', $jpgPath)
                ->pause($this->delay)
                ->press('#submit-btn')
                ->pause($this->delay + 500)
                ->assertPathIs('/admin/product')
                ->assertSee('Produk dan Stok Awal berhasil ditambahkan!')
                ->assertSee('Kakap Merah Segar')
                ->assertSee('50 kg')
                ->assertSee('Rp 65.000')
                ->pause($this->delay);
        });
    }

    /**
     * TC-PBI01-03: [Exception] Menambahkan produk dengan harga atau stok bernilai negatif
     */
    public function test_tc_pbi01_03_menolak_produk_dengan_harga_atau_stok_negatif(): void
    {
        $categoryId = $this->prepareDatabase();
        $jpgPath = base_path('tests/Browser/fixtures/kakap.jpg');

        $this->browse(function (Browser $browser) use ($categoryId, $jpgPath) {
            $browser->visit('/admin/product/create')
                ->pause($this->delay)
                ->type('#name', 'Udang Windu')
                ->pause($this->delay)
                ->select('#categories_id_categories', (string) $categoryId)
                ->pause($this->delay)
                ->type('#purchase_price_view', '40000')
                ->pause($this->delay)
                ->type('#price_view', '-10000')
                ->pause($this->delay)
                ->clear('#stock')
                ->type('#stock', '-5')
                ->pause($this->delay)
                ->type('#description', 'Uji validasi input harga dan stok bernilai negatif.')
                ->pause($this->delay)
                ->attach('#image', $jpgPath)
                ->pause($this->delay)
                ->press('#submit-btn')
                ->pause($this->delay + 500)
                // Harus tertahan di halaman create (tidak tersimpan ke katalog)
                ->assertPathIs('/admin/product/create')
                ->pause($this->delay);
        });
    }

    /**
     * TC-PBI01-04: [Exception] Mengunggah foto produk dengan format berkas tidak didukung (.pdf)
     */
    public function test_tc_pbi01_04_menolak_upload_foto_format_pdf(): void
    {
        $categoryId = $this->prepareDatabase();
        $pdfPath = base_path('tests/Browser/fixtures/sertifikat.pdf');

        $this->browse(function (Browser $browser) use ($categoryId, $pdfPath) {
            $browser->visit('/admin/product/create')
                ->pause($this->delay)
                ->type('#name', 'Tuna Loin')
                ->pause($this->delay)
                ->select('#categories_id_categories', (string) $categoryId)
                ->pause($this->delay)
                ->type('#purchase_price_view', '70000')
                ->pause($this->delay)
                ->type('#price_view', '95000')
                ->pause($this->delay)
                ->clear('#stock')
                ->type('#stock', '20')
                ->pause($this->delay)
                ->type('#description', 'Uji validasi upload berkas PDF pada input foto produk.')
                ->pause($this->delay)
                ->attach('#image', $pdfPath)
                ->pause($this->delay)
                ->press('#submit-btn')
                ->pause($this->delay + 500)
                // Harus tertahan di halaman create karena format PDF ditolak
                ->assertPathIs('/admin/product/create')
                ->pause($this->delay);
        });
    }
}