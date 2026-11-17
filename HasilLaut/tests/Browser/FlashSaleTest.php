<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Discount;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FlashSaleTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan minimal ada 1 user admin untuk foreign key users_id_users
        if (!User::find(1)) {
            User::factory()->create(['id_users' => 1, 'name' => 'Admin Gudang']);
        }

        // Pastikan tabel discount memiliki minimal 1 baris aturan diskon global
        if (!Discount::first()) {
            Discount::create(['percent' => 20]);
        }
    }

    /**
     * Helper membuat produk & batch inventaris (dibiarkan tersimpan di DB sebagai bukti)
     */
    private function createProductWithBatch(
        int $daysAgo = 4,
        int $maxStorageDays = 3,
        float $price = 100000,
        string $tcCode = 'TC'
    ): array {
        $category = Category::firstOrCreate(['name' => 'Ikan Laut Segar']);
        $timeTag = now()->format('His');

        $product = Product::create([
            'name' => "FS-{$tcCode} Kakap {$timeTag}",
            'description' => "Produk bukti pengujian otomatis PBI-03 ({$tcCode}) - " . now()->format('Y-m-d H:i:s'),
            'price' => $price,
            'image' => 'products/dummy-kakap.jpg',
            'max_storage_days' => $maxStorageDays,
            'categories_id_categories' => $category->id_categories,
        ]);

        $batch = InventoryBatch::create([
            'received_date' => Carbon::today()->subDays($daysAgo)->toDateString(),
            'stock' => 10.00,
            'is_flash_sale_excluded' => false,
            'purchase_price' => 75000,
            'users_id_users' => 1,
            'product_id_product' => $product->id_product,
        ]);

        return [$product, $batch];
    }

    /**
     * Menghitung URL halaman pagination admin tempat item Flash Sale terbaru tampil
     */
    private function getLatestAdminFlashSaleUrl(): string
    {
        $totalActive = FlashSaleItem::where('stock', '>', 0)
            ->whereHas('inventoryBatch', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->count();

        $lastPage = max(1, (int) ceil($totalActive / 5));

        return $lastPage > 1 ? "/admin/flash-sale?page={$lastPage}" : '/admin/flash-sale';
    }

    /**
     * TC-FS-001 (PBI-03): [Normal] Sistem otomatis mendaftarkan stok lama ke Flash Sale
     */
    public function test_tc_fs_001_otomasi_pendaftaran_stok_lama_ke_flash_sale(): void
    {
        // Precondition: Diskon global 20%, max_storage_days = 3 hari, batch masuk 4 hari lalu
        Discount::first()->update(['percent' => 20]);
        [$product, $batch] = $this->createProductWithBatch(
            daysAgo: 4,
            maxStorageDays: 3,
            price: 100000,
            tcCode: 'TC1'
        );

        // Jalankan command scheduler flashsale:process
        Artisan::call('flashsale:process');

        // Verifikasi record otomatis masuk permanen ke tabel flash_sale_item (100.000 -> 80.000)
        $this->assertDatabaseHas('flash_sale_item', [
            'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            'flash_sale_price' => 80000.00,
            'stock' => 10.00,
        ]);

        $adminUrl = $this->getLatestAdminFlashSaleUrl();

        // Verifikasi tampilan di Dasbor Admin Flash Sale & Etalase Pembeli
        $this->browse(function (Browser $browser) use ($product, $adminUrl) {
            $browser->maximize()
                ->visit($adminUrl)
                ->pause(1200)
                ->assertSee('Manajemen Flash Sale & Ambang Batas Kesegaran')
                ->assertSee($product->name)
                ->assertSee('-20% Diskon')
                ->pause(1500)
                // Buka etalase pembeli untuk memastikan produk promo & harga coret tayang
                ->visit('/pembeli/katalog?search=' . urlencode($product->name))
                ->pause(1500)
                ->assertSee($product->name)
                ->assertSee('-20%')
                ->assertSee('Rp 80.000')
                ->pause(2000);
        });
    }

    /**
     * TC-FS-002 (PBI-03): [Normal] Admin mengatur persentase diskon seragam (20%)
     */
    public function test_tc_fs_002_admin_mengatur_persentase_diskon_seragam(): void
    {
        // Precondition: Diskon awal 40%, ada item aktif di flash_sale_item
        $discount = Discount::first();
        $discount->update(['percent' => 40]);

        [$product, $batch] = $this->createProductWithBatch(
            daysAgo: 4,
            maxStorageDays: 3,
            price: 100000,
            tcCode: 'TC2'
        );

        $flashSaleItem = FlashSaleItem::create([
            'flash_sale_price' => 60000.00,
            'stock' => $batch->stock,
            'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            'discount_id_discount' => $discount->id_discount,
        ]);

        $adminUrl = $this->getLatestAdminFlashSaleUrl();

        $this->browse(function (Browser $browser) use ($product, $adminUrl) {
            $browser->maximize()
                ->visit($adminUrl)
                ->pause(1200)
                ->clear('input[name="percent"]')
                ->type('input[name="percent"]', '20')
                ->pause(1000)
                ->press('Simpan Aturan')
                ->waitForText('Aturan diskon global dan harga produk aktif berhasil diperbarui!', 5)
                ->assertSee('Aturan diskon global dan harga produk aktif berhasil diperbarui!')
                ->assertSee($product->name)
                ->assertSee('-20% Diskon')
                ->assertSee('Rp 80.000')
                ->pause(2000);
        });

        // Verifikasi tabel discount terbarui ke 20% dan flash_sale_price dihitung ulang (100.000 * 80% = 80.000)
        $this->assertDatabaseHas('discount', [
            'id_discount' => $discount->id_discount,
            'percent' => 20,
        ]);

        $this->assertDatabaseHas('flash_sale_item', [
            'id_flash_sale_item' => $flashSaleItem->id_flash_sale_item,
            'flash_sale_price' => 80000.00,
        ]);
    }

    /**
     * TC-FS-003 (PBI-03): [Normal] Admin menghentikan Flash Sale secara manual
     */
    public function test_tc_fs_003_admin_menghentikan_flash_sale_secara_manual(): void
    {
        $discount = Discount::first();
        $discount->update(['percent' => 20]);

        [$product, $batch] = $this->createProductWithBatch(
            daysAgo: 4,
            maxStorageDays: 3,
            price: 95000,
            tcCode: 'TC3'
        );

        $flashSaleItem = FlashSaleItem::create([
            'flash_sale_price' => 76000.00,
            'stock' => $batch->stock,
            'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            'discount_id_discount' => $discount->id_discount,
        ]);

        $adminUrl = $this->getLatestAdminFlashSaleUrl();

        $this->browse(function (Browser $browser) use ($product, $adminUrl) {
            $browser->maximize()
                ->visit($adminUrl)
                ->pause(1200)
                ->assertSee($product->name)
                // Klik tombol 'Hentikan Flash Sale' pada produk uji
                ->click('button[data-name="' . $product->name . '"]')
                ->waitFor('#stopFlashSaleModal', 5)
                ->pause(1000)
                // Pilih alasan 'Kesalahan Input Tanggal / Batas Simpan' (value="mistake")
                ->radio('reason', 'mistake')
                ->pause(1000)
                ->press('Konfirmasi')
                ->waitForText('Flash sale dibatalkan.', 5)
                ->assertSee('Flash sale dibatalkan.')
                ->pause(1500)
                // Verifikasi di etalase pembeli harga kembali normal (Rp 95.000) tanpa badge diskon
                ->visit('/pembeli/katalog?search=' . urlencode($product->name))
                ->pause(1500)
                ->assertSee($product->name)
                ->assertSee('Rp 95.000')
                ->assertDontSee('-20%')
                ->pause(2000);
        });

        // Verifikasi rekaman di flash_sale_item terhapus & is_flash_sale_excluded di inventory_batches menjadi 1
        $this->assertDatabaseMissing('flash_sale_item', [
            'id_flash_sale_item' => $flashSaleItem->id_flash_sale_item,
        ]);

        $this->assertDatabaseHas('inventory_batches', [
            'id_inventory_batches' => $batch->id_inventory_batches,
            'is_flash_sale_excluded' => 1,
        ]);
    }

    /**
     * TC-FS-004 (PBI-03): [Exception] Range Validation - Admin memasukkan persentase diskon di luar batas valid (150%)
     */
    public function test_tc_fs_004_exception_persentase_diskon_di_luar_batas_valid(): void
    {
        Discount::first()->update(['percent' => 20]);

        $this->browse(function (Browser $browser) {
            $browser->maximize()
                ->visit('/admin/flash-sale')
                ->pause(1200)
                ->clear('input[name="percent"]')
                ->type('input[name="percent"]', '150')
                ->pause(1200)
                ->press('Simpan Aturan')
                ->pause(2000)
                ->assertDontSee('Aturan diskon global dan harga produk aktif berhasil diperbarui!');

            // Verifikasi validasi HTML5 browser menolak angka > max (rangeOverflow = true)
            $isRangeOverflow = $browser->script("return document.querySelector('input[name=\"percent\"]').validity.rangeOverflow;")[0];
            $this->assertTrue($isRangeOverflow);
        });

        // Pastikan nilai 150 tidak tersimpan ke database (tetap 20)
        $this->assertDatabaseMissing('discount', [
            'percent' => 150,
        ]);
    }
}