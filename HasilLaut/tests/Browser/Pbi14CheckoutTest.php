<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\PembeliDetail;
use App\Models\Product;
use App\Models\InventoryBatch;
use App\Models\FlashSaleItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Pbi14CheckoutTest extends DuskTestCase
{
    private function getValidPangandaranBuyer(): User
    {
        $user = User::first() ?? User::factory()->create([
            'name'  => 'Budi Santoso',
            'email' => 'budi.pangandaran@example.com',
        ]);

        $userId = $user->getKey();

        PembeliDetail::updateOrCreate(
            ['users_id_users' => $userId],
            [
                'name'                => 'Budi Santoso',
                'phone_number'        => '081234567890',
                'address'             => 'Jl. Merdeka No. 10, Kab. Pangandaran 46396',
                'latitude'            => -7.684520,
                'longitude'           => 108.653810,
                'is_pangandaran_area' => 1,
            ]
        );

        return $user;
    }

    private function getOrCreateCart(int $userId): Cart
    {
        $cart = new Cart();
        $fk = Schema::hasColumn($cart->getTable(), 'users_id_users') ? 'users_id_users' : 'user_id';

        return Cart::firstOrCreate([$fk => $userId]);
    }

    private function addItemToCart(Cart $cart, Product $product, int $quantity, ?int $flashSaleItemId = null): CartItem
    {
        $item = new CartItem();
        $table = $item->getTable();
        $columns = Schema::getColumnListing($table);

        $cartFk = collect($columns)->first(fn($col) => str_contains($col, 'cart') && $col !== 'id_cart_items') ?? 'carts_id_carts';
        $prodFk = collect($columns)->first(fn($col) => str_contains($col, 'prod') && $col !== 'id_cart_items') ?? 'product_id_product';

        $data = [
            $cartFk    => $cart->getKey(),
            $prodFk    => $product->getKey(),
            'quantity' => $quantity,
        ];

        if (!is_null($flashSaleItemId)) {
            $fsFk = collect($columns)->first(fn($col) => str_contains($col, 'flash')) ?? 'flash_sale_item_id';
            $data[$fsFk] = $flashSaleItemId;
        }

        return CartItem::forceCreate($data);
    }

    private function dismissAnyOpenAlert(Browser $browser, bool $failIfAlert = false): void
    {
        try {
            $alert = $browser->driver->switchTo()->alert();
            $text = $alert->getText();
            $alert->accept();
            if ($failIfAlert) {
                $this->fail("CheckoutController mengembalikan alert error: " . $text);
            }
        } catch (\Facebook\WebDriver\Exception\NoSuchAlertException $e) {
            // Tidak ada alert aktif
        }
    }

    /**
     * Helper memastikan produk memiliki stok minimal di inventory_batches
     */
    private function ensureProductHasBatchStock(Product $product, float $minStock = 10): InventoryBatch
    {
        $batch = InventoryBatch::where('product_id_product', $product->id_product)->first();
        if (!$batch) {
            $batchCols = Schema::getColumnListing('inventory_batches');
            $data = [
                'product_id_product' => $product->id_product,
                'stock'              => $minStock,
            ];
            if (in_array('initial_stock', $batchCols)) $data['initial_stock'] = $minStock;
            if (in_array('batch_code', $batchCols)) $data['batch_code'] = 'BATCH-DUSK-' . rand(100, 999);
            if (in_array('received_at', $batchCols)) $data['received_at'] = now();
            if (in_array('expired_at', $batchCols)) $data['expired_at'] = now()->addDays(3);

            $batch = InventoryBatch::forceCreate($data);
        } elseif ($batch->stock < $minStock) {
            $batch->update(['stock' => $minStock]);
        }

        return $batch;
    }

    /**
     * TC-CHK-001: Peninjauan rincian biaya dan kalkulasi ongkir transparan
     */
    public function test_tc_chk_001_transparent_cost_breakdown_and_shipping_calculation(): void
    {
        $user = $this->getValidPangandaranBuyer();
        $userId = $user->getKey();
        $product = Product::where('name', 'like', '%Kakap%')->first() ?? Product::first();

        if ($product) {
            $cart = $this->getOrCreateCart($userId);
            $cart->cartItems()->delete();
            $this->addItemToCart($cart, $product, 2);
        }

        $this->browse(function (Browser $browser) use ($user, $product) {
            $this->dismissAnyOpenAlert($browser);

            $browser->loginAs($user)
                    ->visit('/pembeli/checkout')
                    ->assertSee('Ringkasan Produk Dipesan')
                    ->assertSee('2')
                    ->assertSee('Subtotal Harga Barang')
                    ->assertSee('Ongkos Kirim Kurir Internal');

            if ($product) {
                $browser->assertSee($product->name);
            }
        });
    }

    /**
     * TC-CHK-002: Pengujian validasi ulang stok segar dan pembuatan pesanan
     */
    public function test_tc_chk_002_stock_validation_and_order_creation(): void
    {
        $user = $this->getValidPangandaranBuyer();
        $userId = $user->getKey();
        $product = Product::where('name', 'like', '%Tuna%')->first() ?? Product::first();

        $orderCountBefore = DB::table('orders')->where('users_id_users', $userId)->count();

        if ($product) {
            $this->ensureProductHasBatchStock($product, 10);

            $cart = $this->getOrCreateCart($userId);
            $cart->cartItems()->delete();
            $this->addItemToCart($cart, $product, 3);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $this->dismissAnyOpenAlert($browser);

            $browser->loginAs($user)
                    ->visit('/pembeli/checkout')
                    ->assertSee('Ringkasan Produk Dipesan')
                    ->assertSee('3');

            if ($browser->element('input[name="payment_method"][value="qris"]')) {
                $browser->radio('payment_method', 'qris');
            }

            $browser->press('Buat Pesanan Sekarang')
                    ->pause(2500);

            $this->dismissAnyOpenAlert($browser, true);
        });

        $orderCountAfter = DB::table('orders')->where('users_id_users', $userId)->count();
        $this->assertGreaterThan($orderCountBefore, $orderCountAfter, 'Order baru berhasil dibuat di tabel orders.');
    }

    /**
     * TC-CHK-003: Pengujian kegagalan checkout saat stok segar habis dibeli orang lain
     */
    public function test_tc_chk_003_checkout_fails_when_product_stock_is_empty(): void
    {
        $user = $this->getValidPangandaranBuyer();
        $userId = $user->getKey();
        $product = Product::where('name', 'like', '%Udang%')->first() ?? Product::first();

        if ($product) {
            // Backup lalu kosongkan sementara seluruh stok batch produk ini
            $batchBackup = InventoryBatch::where('product_id_product', $product->id_product)
                ->pluck('stock', 'id_inventory_batches')
                ->toArray();

            InventoryBatch::where('product_id_product', $product->id_product)->update(['stock' => 0]);

            $cart = $this->getOrCreateCart($userId);
            $cart->cartItems()->delete();
            $this->addItemToCart($cart, $product, 1);

            $this->browse(function (Browser $browser) use ($user) {
                $this->dismissAnyOpenAlert($browser);

                $browser->loginAs($user)
                        ->visit('/pembeli/checkout');

                if ($browser->element('input[name="payment_method"][value="qris"]')) {
                    $browser->radio('payment_method', 'qris');
                }

                $browser->press('Buat Pesanan Sekarang')
                        ->pause(1500);

                $alert = $browser->driver->switchTo()->alert();
                $alertText = $alert->getText();
                $this->assertStringContainsString('stok', strtolower($alertText));
                $alert->accept();
            });

            // Kembalikan stok batch awal setelah simulasi selesai
            foreach ($batchBackup as $batchId => $oldStock) {
                InventoryBatch::where('id_inventory_batches', $batchId)->update(['stock' => $oldStock]);
            }
        }
    }

    /**
     * TC-CHK-004: Pengujian validasi kuota flash sale saat checkout
     */
    public function test_tc_chk_004_validates_flash_sale_quota_on_checkout(): void
    {
        $user = $this->getValidPangandaranBuyer();
        $userId = $user->getKey();
        $product = Product::where('name', 'like', '%Gurame%')->first() ?? Product::first();
        $flashSaleItem = FlashSaleItem::where('stock', '>', 0)->first();

        // Jika belum ada item flash sale aktif di DB, buat 1 record sementara sesuai skema FlashSaleItem
        if (!$flashSaleItem && $product) {
            $batch = $this->ensureProductHasBatchStock($product, 10);

            $discountId = null;
            if (Schema::hasTable('discount')) {
                $discountId = DB::table('discount')->value('id_discount');
                if (!$discountId) {
                    $discCols = Schema::getColumnListing('discount');
                    $discData = [ 'percent' => 20,];
                    if (in_array('name', $discCols)) $discData['name'] = 'Flash Sale Dusk';
                    if (in_array('title', $discCols)) $discData['title'] = 'Flash Sale Dusk';
                    if (in_array('discount_percentage', $discCols)) $discData['discount_percentage'] = 20;
                    if (in_array('start_date', $discCols)) $discData['start_date'] = now()->subHour();
                    if (in_array('end_date', $discCols)) $discData['end_date'] = now()->addHours(2);
                    if (in_array('is_active', $discCols)) $discData['is_active'] = 1;
                    if (in_array('created_at', $discCols)) $discData['created_at'] = now();
                    if (in_array('updated_at', $discCols)) $discData['updated_at'] = now();

                    $discountId = DB::table('discount')->insertGetId($discData);
                }
            }

            $fsData = [
                'flash_sale_price'                       => 25000,
                'stock'                                  => 5,
                'inventory_batches_id_inventory_batches' => $batch->id_inventory_batches,
            ];
            if ($discountId) {
                $fsData['discount_id_discount'] = $discountId;
            }

            $flashSaleItem = FlashSaleItem::forceCreate($fsData);
        }

        if ($flashSaleItem && $product) {
            $orderCountBefore = DB::table('orders')->where('users_id_users', $userId)->count();

            $cart = $this->getOrCreateCart($userId);
            $cart->cartItems()->delete();
            $this->addItemToCart($cart, $product, 1, $flashSaleItem->id_flash_sale_item);

            $this->browse(function (Browser $browser) use ($user) {
                $this->dismissAnyOpenAlert($browser);

                $browser->loginAs($user)
                        ->visit('/pembeli/checkout')
                        ->assertSee('Ringkasan Produk Dipesan');

                if ($browser->element('input[name="payment_method"][value="qris"]')) {
                    $browser->radio('payment_method', 'qris');
                }

                $browser->press('Buat Pesanan Sekarang')
                        ->pause(2500);

                $this->dismissAnyOpenAlert($browser);
            });

            $orderCountAfter = DB::table('orders')->where('users_id_users', $userId)->count();
            $this->assertGreaterThan($orderCountBefore, $orderCountAfter, 'Order dengan kuota Flash Sale berhasil dibuat.');
        } else {
            $this->markTestSkipped('Data Flash Sale belum dapat disiapkan.');
        }
    }
}