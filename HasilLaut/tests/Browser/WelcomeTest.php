<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WelcomeTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_pbi09_01_landing_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertTitle('HasilLaut | Ikan Segar Langsung dari Nelayan')
                ->assertSee('Ikan Segar Langsung dari Nelayan, Pesisir ke Dapur Anda')
                ->assertSee('Produk Unggulan Hari Ini');
        });
    }

    public function test_tc_pbi09_02_pangandaran_service_banner_is_rendered(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible('[data-testid="pangandaran-service-alert"]')
                ->assertSeeIn('[data-testid="pangandaran-service-alert"]', 'Pangandaran');
        });
    }

    public function test_tc_pbi09_03_top_trend_products_with_stock_are_rendered(): void
    {
        $this->createTopTrendProduct('Ikan Top Tren Dusk', 12);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible('[data-testid="top-trend-product"]')
                ->assertSeeIn('[data-testid="top-trend-product"]', 'Ikan Top Tren Dusk')
                ->assertSeeIn('[data-testid="top-trend-product"]', '12 kg');
        });
    }

    public function test_tc_pbi09_04_call_to_action_navigates_to_featured_products(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Jelajahi Katalog')
                ->assertPathIs('/')
                ->assertScript('return window.location.hash', '#produk-unggulan')
                ->assertVisible('#produk-unggulan');
        });
    }

    public function test_tc_pbi09_05_empty_top_trend_stock_shows_fallback_message(): void
    {
        $this->createTopTrendProduct('Ikan Top Tren Tanpa Stok', 0);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible('[data-testid="top-trend-empty-state"]')
                ->assertSeeIn(
                    '[data-testid="top-trend-empty-state"]',
                    'Saat ini stok produk Top Tren sedang kosong.'
                )
                ->assertMissing('[data-testid="top-trend-product"]');
        });
    }

    public function test_tc_pbi09_06_landing_page_is_responsive_at_375px(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 812)
                ->visit('/')
                ->assertVisible('[data-testid="landing-primary-cta"]')
                ->assertScript('return document.documentElement.scrollWidth <= window.innerWidth', true);
        });
    }

    private function createTopTrendProduct(string $name, int $stock): Product
    {
        $category = Category::create(['name' => 'Ikan Segar']);
        $user = User::create([
            'name' => 'Pengujian Dusk',
            'username' => 'dusk-' . uniqid(),
            'password' => 'password',
            'role' => 'admin',
        ]);
        $product = Product::create([
            'name' => $name,
            'description' => 'Produk untuk pengujian landing page.',
            'price' => 85000,
            'max_storage_days' => 7,
            'categories_id_categories' => $category->id_categories,
            'is_top_tren' => true,
        ]);

        InventoryBatch::create([
            'received_date' => now()->toDateString(),
            'stock' => $stock,
            'purchase_price' => 60000,
            'users_id_users' => $user->id_users,
            'product_id_product' => $product->id_product,
        ]);

        return $product;
    }
}
