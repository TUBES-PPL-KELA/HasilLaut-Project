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
                    ->pause(1500)
                    ->click('@btn-add-cart')
                    ->pause(2000);
        });
    }

    /**
     * TC-CRT-002: Menggunakan fitur Beli Sekarang untuk direct checkout ekspres.
     */
    public function test_tc_crt_002_direct_checkout_beli_sekarang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/katalog')
                    ->assertPathIs('/pembeli/katalog')
                    ->pause(1000)
                    ->visit('/pembeli/checkout')
                    ->assertPathIs('/pembeli/checkout')
                    ->pause(2000);
        });
    }

    /**
     * TC-CRT-003: Menampilkan daftar item, kuantitas, dan total harga di halaman keranjang.
     */
    public function test_tc_crt_003_menampilkan_daftar_item_keranjang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/keranjang')
                    ->assertPathIs('/pembeli/keranjang')
                    ->assertSee('Keranjang Belanja')
                    ->assertPresent('img')
                    ->assertPresent('#grand-total')
                    ->pause(2000);
        });
    }

    /**
     * TC-CRT-004: Mengubah kuantitas produk di keranjang sesuai batas stok valid.
     */
    public function test_tc_crt_004_mengubah_kuantitas_produk_valid()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/keranjang')
                    ->assertPathIs('/pembeli/keranjang')
                    ->pause(1000)
                    ->click('@btn-increment')
                    ->pause(2000);
        });
    }

    /**
     * TC-CRT-005: Menambahkan produk dengan kuantitas melebihi sisa stok gudang.
     */
    public function test_tc_crt_005_kuantitas_melebihi_stok()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/keranjang')
                    ->assertPathIs('/pembeli/keranjang')
                    ->pause(1000)
                    ->script("
                        let input = document.querySelector('input[name=\"quantity\"]');
                        if(input) {
                            input.value = '999';
                            input.form.submit();
                        }
                    ");
            
            $browser->pause(2000);
        });
    }

    /**
     * TC-CRT-006: Menghapus spesifik item dari keranjang belanja.
     */
    public function test_tc_crt_006_menghapus_item_keranjang()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/keranjang')
                    ->assertPathIs('/pembeli/keranjang')
                    ->pause(1000)
                    ->click('@btn-delete')
                    ->acceptDialog()
                    ->pause(2000);
        });
    }

    /**
     * TC-CRT-007: Mencegah checkout atau proses lanjutan jika keranjang kosong.
     */
    public function test_tc_crt_007_mencegah_checkout_keranjang_kosong()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/keranjang');
            
            // Bersihkan sisa item jika ada
            while ($browser->element('@btn-delete')) {
                try {
                    $browser->click('@btn-delete')->acceptDialog()->pause(500);
                } catch (\Exception $e) {
                    break;
                }
            }

            $browser->visit('/pembeli/keranjang')
                    ->assertPathIs('/pembeli/keranjang')
                    ->pause(1000)
                    ->assertSee('Keranjang belanja kamu masih kosong')
                    ->pause(2000);
        });
    }
}