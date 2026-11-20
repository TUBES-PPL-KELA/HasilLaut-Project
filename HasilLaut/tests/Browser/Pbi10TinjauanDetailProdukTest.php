<?php

namespace Tests\Browser;

use App\Models\Discount;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use App\Models\Product;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Pbi10TinjauanDetailProdukTest extends DuskTestCase
{
    /**
     * TC-TDP-001: [Normal] Menampilkan halaman detail produk reguler yang aktif dan tersedia stoknya
     */
    public function test_tc_tdp_001_detail_produk_reguler(): void
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->assertSee($product->name)
                    ->assertSee('Rp ' . number_format($product->price, 0, ',', '.'))
                    ->assertSee('Stok Tersedia:')
                    ->assertSee('Asal Hub Pesisir: HasilLaut Hub Pangandaran')
                    ->assertSee('Standar Rantai Dingin:')
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-002: [Normal] Menampilkan halaman detail produk yang sedang dalam promo Flash Sale
     */
    public function test_tc_tdp_002_detail_produk_flash_sale(): void
    {
        $batch = InventoryBatch::where('stock', '>', 0)->first();
        $discount = Discount::firstOrCreate(['percent' => 20]);
        $flashSale = FlashSaleItem::firstOrCreate(
            ['inventory_batches_id_inventory_batches' => $batch->id_inventory_batches],
            [
                'flash_sale_price' => 68000,
                'stock' => 10,
                'discount_id_discount' => $discount->id_discount,
            ]
        );

        $this->browse(function (Browser $browser) use ($batch, $flashSale) {
            $browser->visit('/pembeli/produk/' . $batch->product_id_product . '?flash_sale_item_id=' . $flashSale->id_flash_sale_item)
                    ->assertSee('FLASH SALE')
                    ->assertSee('Harga Promo Stok Terbatas')
                    ->assertSee('Rp ' . number_format($flashSale->flash_sale_price, 0, ',', '.'))
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-003: [Normal] Memilih opsi pembelian harga Flash Sale vs harga stok segar normal
     */
    public function test_tc_tdp_003_pilih_opsi_harga(): void
    {
        $flashSale = FlashSaleItem::with('inventoryBatch.product')->where('stock', '>', 0)->first();
        $product = $flashSale->inventoryBatch->product;

        $this->browse(function (Browser $browser) use ($product, $flashSale) {
            // 1. Akses mode Flash Sale
            $browser->visit('/pembeli/produk/' . $product->id_product . '?flash_sale_item_id=' . $flashSale->id_flash_sale_item)
                    ->assertSee('Harga Promo Stok Terbatas')
                    ->assertSee('Rp ' . number_format($flashSale->flash_sale_price, 0, ',', '.'))
                    ->pause(2500);

            // 2. Beralih ke mode Stok Segar Normal
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->assertDontSee('Harga Promo Stok Terbatas')
                    ->assertSee('Rp ' . number_format($product->price, 0, ',', '.'))
                    ->pause(2500);
        });
    }

    /**
     * TC-TDP-004: [Normal] Mengatur kuantitas berat pesanan (kg) dalam batas stok tersedia
     */
    public function test_tc_tdp_004_atur_kuantitas_valid(): void
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->clear('#quantity')
                    ->type('#quantity', '3')
                    ->assertInputValue('#quantity', '3')
                    ->assertSee('Masukkan Keranjang')
                    ->assertSee('Beli Sekarang')
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-005: [Normal] Menampilkan ulasan, rating bintang, dan rekomendasi produk alternatif
     */
    public function test_tc_tdp_005_ulasan_dan_rekomendasi(): void
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->scrollIntoView('section')
                    ->assertSee('Ulasan & Penilaian Pembeli')
                    ->assertSee('Pilihan Produk Lainnya')
                    ->pause(4000);
        });
    }

    /**
     * TC-TDP-006: [Exception] Mengakses detail produk dengan stok habis / tidak tersedia (0 kg)
     */
    public function test_tc_tdp_006_produk_stok_habis(): void
    {
        $emptyProduct = Product::firstOrCreate(
            ['name' => 'Produk Uji Stok Habis'],
            [
                'description' => 'Produk uji stok kosong',
                'price' => 50000,
                'max_storage_days' => 3,
                'categories_id_categories' => Product::first()->categories_id_categories,
            ]
        );

        $this->browse(function (Browser $browser) use ($emptyProduct) {
            $browser->visit('/pembeli/produk/' . $emptyProduct->id_product)
                    ->assertSee('Stok Tersedia: 0 kg')
                    ->assertDisabled('form[action*="keranjang"] button[type="submit"]')
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-007: [Exception] Menginput kuantitas pembelian melebihi sisa stok gudang
     */
    public function test_tc_tdp_007_kuantitas_melebihi_stok(): void
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->clear('#quantity')
                    ->type('#quantity', '9999')
                    ->press('Masukkan Keranjang')
                    ->assertDontSee('Server Error')
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-008: [Exception] Menginput kuantitas bernilai nol (0), negatif (-), atau non-numerik
     */
    public function test_tc_tdp_008_kuantitas_tidak_valid(): void
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/pembeli/produk/' . $product->id_product)
                    ->clear('#quantity')
                    ->type('#quantity', '-2')
                    ->press('Masukkan Keranjang')
                    ->assertDontSee('Server Error')
                    ->pause(3500);
        });
    }

    /**
     * TC-TDP-009: [Exception] Mengakses URL detail produk dengan ID produk yang tidak terdaftar di database
     */
    public function test_tc_tdp_009_id_produk_tidak_ditemukan(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/produk/99999')
                    ->assertSee('404')
                    ->pause(4000);
        });
    }
}