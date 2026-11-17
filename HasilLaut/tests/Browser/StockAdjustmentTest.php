<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StockAdjustmentTest extends DuskTestCase
{
    private array $createdProductIds = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan minimal ada 1 user admin untuk foreign key users_id_users
        if (!User::find(1)) {
            User::factory()->create(['id_users' => 1, 'name' => 'Admin Gudang']);
        }
    }

    protected function tearDown(): void
    {
        // Bersihkan seluruh data dummy hasil test agar database tetap bersih
        if (!empty($this->createdProductIds)) {
            $batchIds = InventoryBatch::whereIn('product_id_product', $this->createdProductIds)
                ->pluck('id_inventory_batches');

            StockAdjustment::whereIn('inventory_batches_id_inventory_batches', $batchIds)->delete();
            InventoryBatch::whereIn('id_inventory_batches', $batchIds)->delete();
            Product::whereIn('id_product', $this->createdProductIds)->delete();
        }

        parent::tearDown();
    }

    /**
     * Helper membuat data produk & batch dummy untuk pengujian
     */
    private function createProductWithBatch(float $initialStock = 5.0): array
    {
        $category = Category::firstOrCreate(['name' => 'Ikan Laut Segar']);

        $product = Product::create([
            'name' => 'Kakap Merah Dusk Test ' . uniqid(),
            'description' => 'Produk uji otomatis PBI-02',
            'price' => 95000,
            'image' => 'products/dummy-kakap.jpg',
            'max_storage_days' => 7,
            'categories_id_categories' => $category->id_categories,
        ]);

        $this->createdProductIds[] = $product->id_product;

        $batch = InventoryBatch::create([
            'received_date' => '2026-10-20',
            'stock' => $initialStock,
            'is_flash_sale_excluded' => 0,
            'purchase_price' => 80000,
            'users_id_users' => 1,
            'product_id_product' => $product->id_product,
        ]);

        return [$product, $batch];
    }

    /**
     * Test Case 2 (PBI-02): [Normal] Pengurangan stok manual per batch akibat ikan rusak
     */
    public function test_normal_pengurangan_stok_manual_per_batch(): void
    {
        [$product, $batch] = $this->createProductWithBatch(10.0);

        $this->browse(function (Browser $browser) use ($product, $batch) {
            $browser->maximize()
                ->visit('/admin/stok/penyesuaian')
                ->assertSee('Penyesuaian Produk Defect / Rusak')
                ->pause(1200)
                // 1. Pilih produk Kakap Merah
                ->select('#product_select', (string) $product->id_product)
                ->waitUntilEnabled('#batch_select', 5)
                ->pause(1200)
                // 2. Pilih ID Batch
                ->select('#batch_select', (string) $batch->id_inventory_batches)
                ->pause(1200)
                // 3. Masukkan jumlah & alasan
                ->type('input[name="quantity"]', '3')
                ->pause(1000)
                ->select('select[name="reason"]', 'Busuk / Rusak')
                ->pause(1000)
                ->type('textarea[name="notes"]', 'Ikan busuk/kualitas turun')
                ->pause(1200)
                // 4. Klik 'Simpan & Kurangi Stok Gudang Otomatis'
                ->press('Simpan & Kurangi Stok Gudang Otomatis')
                ->waitForText('Penyesuaian stok defect berhasil disimpan.', 5)
                ->assertSee('Penyesuaian stok defect berhasil disimpan.')
                ->assertSee($product->name)
                ->assertSee('-3.00 kg')
                ->assertSee('Ikan busuk/kualitas turun')
                ->pause(2000);
        });

        // Verifikasi stok batch di database berkurang dari 10 kg menjadi 7 kg
        $this->assertDatabaseHas('inventory_batches', [
            'id_inventory_batches' => $batch->id_inventory_batches,
            'stock' => 7.00,
        ]);

        // Verifikasi log tersimpan di tabel stock_adjustments
        $this->assertDatabaseHas('stock_adjustments', [
            'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            'adjusted_quantity' => 3.00,
            'reason' => 'Busuk / Rusak',
        ]);
    }

    /**
     * Test Case 3 (PBI-02): [Exception] Boundary Validation - Pengurangan manual melebihi sisa stok batch
     */
    public function test_exception_pengurangan_melebihi_sisa_stok_batch(): void
    {
        // Precondition: Batch tersisa 5 kg
        [$product, $batch] = $this->createProductWithBatch(5.0);

        $this->browse(function (Browser $browser) use ($product, $batch) {
            $browser->maximize()
                ->visit('/admin/stok/penyesuaian')
                ->pause(1200)
                ->select('#product_select', (string) $product->id_product)
                ->waitUntilEnabled('#batch_select', 5)
                ->pause(1200)
                ->select('#batch_select', (string) $batch->id_inventory_batches)
                ->pause(1200)
                // Input jumlah pengurangan 10 kg (melebihi sisa 5 kg)
                ->type('input[name="quantity"]', '10')
                ->pause(1000)
                ->select('select[name="reason"]', 'Busuk / Rusak')
                ->pause(1200)
                ->press('Simpan & Kurangi Stok Gudang Otomatis')
                ->waitForText('melebihi sisa stok batch saat ini', 5)
                ->assertSee('Jumlah pengurangan (10 kg) melebihi sisa stok batch saat ini (5.00 kg).')
                ->pause(2000);
        });

        // Pastikan stok di database tetap utuh 5 kg (tidak terpotong)
        $this->assertDatabaseHas('inventory_batches', [
            'id_inventory_batches' => $batch->id_inventory_batches,
            'stock' => 5.00,
        ]);
    }

    /**
     * Test Case 4 (PBI-02): [Exception] Alasan Wajib - Memproses penyesuaian manual tanpa mengisi alasan
     */
    public function test_exception_submit_tanpa_mengisi_alasan(): void
    {
        [$product, $batch] = $this->createProductWithBatch(5.0);

        $this->browse(function (Browser $browser) use ($product, $batch) {
            $browser->maximize()
                ->visit('/admin/stok/penyesuaian')
                ->pause(1200)
                ->select('#product_select', (string) $product->id_product)
                ->waitUntilEnabled('#batch_select', 5)
                ->pause(1200)
                ->select('#batch_select', (string) $batch->id_inventory_batches)
                ->pause(1200)
                ->type('input[name="quantity"]', '2')
                ->pause(1000)
                // Kosongkan kolom alasan
                ->select('select[name="reason"]', '')
                ->pause(1200)
                ->press('Simpan & Kurangi Stok Gudang Otomatis')
                ->pause(2000)
                // Sistem menolak submit (tidak ada pesan sukses)
                ->assertDontSee('Penyesuaian stok defect berhasil disimpan.');

            // Validasi HTML5 browser menandai field reason wajib diisi (valueMissing = true)
            $isReasonMissing = $browser->script("return document.querySelector('select[name=\"reason\"]').validity.valueMissing;")[0];
            $this->assertTrue($isReasonMissing);
        });

        // Pastikan stok tetap utuh 5 kg
        $this->assertDatabaseHas('inventory_batches', [
            'id_inventory_batches' => $batch->id_inventory_batches,
            'stock' => 5.00,
        ]);
    }

    /**
     * Test Case 4 (PBI-02): [Normal - Boundary] Pengurangan manual tepat sama dengan sisa stok batch (habis 0 kg)
     */
    public function test_normal_boundary_pengurangan_seluruh_sisa_stok_batch(): void
    {
        // Precondition: Batch tersisa 5 kg
        [$product, $batch] = $this->createProductWithBatch(5.0);

        $this->browse(function (Browser $browser) use ($product, $batch) {
            $browser->maximize()
                ->visit('/admin/stok/penyesuaian')
                ->pause(1200)
                ->select('#product_select', (string) $product->id_product)
                ->waitUntilEnabled('#batch_select', 5)
                ->pause(1200)
                ->select('#batch_select', (string) $batch->id_inventory_batches)
                ->pause(1200)
                // Input jumlah pengurangan tepat sama dengan sisa stok (5 kg)
                ->type('input[name="quantity"]', '5')
                ->pause(1000)
                ->select('select[name="reason"]', 'Busuk / Rusak')
                ->pause(1000)
                ->type('textarea[name="notes"]', 'Seluruh sisa batch busuk/rusak')
                ->pause(1200)
                ->press('Simpan & Kurangi Stok Gudang Otomatis')
                ->waitForText('Penyesuaian stok defect berhasil disimpan.', 5)
                ->assertSee('Penyesuaian stok defect berhasil disimpan.')
                ->assertSee($product->name)
                ->assertSee('-5.00 kg')
                ->pause(2000);
        });

        // Verifikasi stok batch di database habis menjadi 0.00 kg
        $this->assertDatabaseHas('inventory_batches', [
            'id_inventory_batches' => $batch->id_inventory_batches,
            'stock' => 0.00,
        ]);

        // Verifikasi log tersimpan di tabel stock_adjustments
        $this->assertDatabaseHas('stock_adjustments', [
            'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            'adjusted_quantity' => 5.00,
            'reason' => 'Busuk / Rusak',
        ]);
    }
}