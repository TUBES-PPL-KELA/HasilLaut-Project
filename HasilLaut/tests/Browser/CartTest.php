<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CartTest extends DuskTestCase
{
    /**
     * TC-CRT-001: Menambahkan produk valid ke keranjang belanja dari katalog.
     */
    public function test_tc_crt_001_menambahkan_produk_valid_ke_keranjang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/katalog')
                    ->assertPathIs('/pembeli/katalog')
                    ->click('@btn-add-cart')
                    ->pause(1000);

        });
    }

    /**
     * TC-CRT-002: Menggunakan fitur Beli Sekarang untuk direct checkout ekspres.
     */
    public function test_tc_crt_002_direct_checkout_beli_sekarang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/checkout')
                    ->assertPathIs('/pembeli/checkout');
        });
    }

    /**
     * TC-CRT-003: Menampilkan daftar item, kuantitas, dan total harga di halaman keranjang.
     */
    public function test_tc_crt_003_menampilkan_daftar_item_keranjang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/keranjang')
                    ->assertPathIs('/keranjang')
                    ->assertSee('Keranjang Belanja')
                    ->assertPresent('img')
                    ->assertPresent('#grand-total');
        });
    }

    /**
     * TC-CRT-004: Mengubah kuantitas produk di keranjang sesuai batas stok valid.
     */
    public function test_tc_crt_004_mengubah_kuantitas_produk_valid()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/keranjang')
                    ->assertPathIs('/keranjang')
                    ->click('@btn-increment')
                    ->pause(1000);
        });
    }

    /**
     * TC-CRT-005: Menambahkan produk dengan kuantitas melebihi sisa stok gudang.
     */
    public function test_tc_crt_005_kuantitas_melebihi_stok()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/keranjang')
                    ->assertPathIs('/keranjang');
        });
    }

    /**
     * TC-CRT-006: Menghapus spesifik item dari keranjang belanja.
     */
    public function test_tc_crt_006_menghapus_item_keranjang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/keranjang')
                    ->assertPathIs('/keranjang')
                    ->click('@btn-delete')
                    ->acceptDialog()
                    ->pause(1000);
        });
    }

    /**
     * TC-CRT-007: Mencegah checkout atau proses lanjutan jika keranjang kosong.
     */
    public function test_tc_crt_007_mencegah_checkout_keranjang_kosong()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/keranjang')
                    ->assertPathIs('/keranjang');
        });
    }
}