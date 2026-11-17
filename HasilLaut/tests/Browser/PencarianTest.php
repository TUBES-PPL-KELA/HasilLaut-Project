<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PencarianTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_pbi11_01_searches_products_by_valid_name_keyword(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');
        $this->createProduct('Udang Windu Laut', 'Hub Jepara');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', 'Kakap')
                ->keys('#search-query', '{ENTER}')
                ->assertPathIs('/pembeli/pencarian-ikan')
                ->assertSeeIn('[data-testid="search-results"]', 'Ikan Kakap Merah Segar')
                ->assertDontSeeIn('[data-testid="search-results"]', 'Udang Windu Laut');
        });
    }

    public function test_tc_pbi11_02_search_is_case_insensitive(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', 'iKaN kAkAp')
                ->keys('#search-query', '{ENTER}')
                ->assertSeeIn('[data-testid="search-results"]', 'Ikan Kakap Merah Segar');
        });
    }

    public function test_tc_pbi11_03_searches_products_by_coastal_hub_name(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');
        $this->createProduct('Udang Windu Laut', 'Hub Jepara');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', 'Pangandaran')
                ->keys('#search-query', '{ENTER}')
                ->assertSeeIn('[data-testid="search-results"]', 'Ikan Kakap Merah Segar')
                ->assertDontSeeIn('[data-testid="search-results"]', 'Udang Windu Laut');
        });
    }

    public function test_tc_pbi11_04_unknown_keyword_shows_empty_results_state(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', 'ProdukTidakAda')
                ->keys('#search-query', '{ENTER}')
                ->assertVisible('[data-testid="search-empty-state"]')
                ->assertSeeIn('[data-testid="search-empty-state"]', 'Produk tidak ditemukan')
                ->assertMissing('[data-testid="search-result-card"]');
        });
    }

    public function test_tc_pbi11_05_blank_and_extra_whitespace_search_shows_all_products(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');
        $this->createProduct('Udang Windu Laut', 'Hub Jepara');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', '   ')
                ->keys('#search-query', '{ENTER}')
                ->assertPathIs('/pembeli/pencarian-ikan')
                ->assertSeeIn('[data-testid="search-results"]', 'Ikan Kakap Merah Segar')
                ->assertSeeIn('[data-testid="search-results"]', 'Udang Windu Laut')
                ->assertInputValue('#search-query', '');
        });
    }

    public function test_tc_pbi11_06_search_is_resistant_to_sql_injection_input(): void
    {
        $this->createProduct('Ikan Kakap Merah Segar', 'Hub Pangandaran');
        $this->createProduct('Udang Windu Laut', 'Hub Jepara');

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/pencarian-ikan')
                ->type('#search-query', "' OR 1=1 --")
                ->keys('#search-query', '{ENTER}')
                ->assertPathIs('/pembeli/pencarian-ikan')
                ->assertVisible('[data-testid="search-empty-state"]')
                ->assertDontSeeIn('[data-testid="search-results"]', 'Ikan Kakap Merah Segar')
                ->assertDontSeeIn('[data-testid="search-results"]', 'Udang Windu Laut');
        });
    }

    public function test_tc_pbi11_07_search_input_is_resistant_to_xss_payloads(): void
    {
        $payload = '"><img src=x onerror=alert(1)>';

        $this->browse(function (Browser $browser) use ($payload) {
            $browser->visit('/pembeli/pencarian-ikan?q='.urlencode($payload))
                ->assertPathIs('/pembeli/pencarian-ikan')
                ->assertVisible('[data-testid="search-empty-state"]')
                ->assertInputValue('#search-query', $payload)
                ->assertMissing('[data-testid="search-results"] img[onerror]')
                ->assertMissing('[data-testid="search-results"] script');
        });
    }

    private function createProduct(string $name, string $hubName): Product
    {
        return Product::create([
            'name' => $name,
            'hub_name' => $hubName,
            'description' => 'Produk untuk pengujian pencarian.',
            'price' => 85000,
            'max_storage_days' => 7,
            'is_top_tren' => false,
        ]);
    }
}
