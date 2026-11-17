<?php

namespace Tests\Browser;

use App\Models\PembeliDetail;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Pbi13ProfileGeofencingTest extends DuskTestCase
{
    /**
     * TC-SHP-001: Input data alamat lengkap dan titik koordinat peta secara valid
     */
    public function test_tc_shp_001_input_valid_address_and_pangandaran_coordinates(): void
    {
        $userId = 1;

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/profile/edit')
                    ->assertSee('Informasi Data Diri Pembeli')
                    ->clear('name')->type('name', 'Budi Santoso')
                    ->clear('phone_number')->type('phone_number', '081234567890')
                    ->clear('address')->type('address', 'Jl. Merdeka No. 10, Kec. Pangandaran, Kab. Pangandaran 46396')
                    ->script([
                        "document.querySelector('input[name=\"latitude\"]').value = '-7.684520';",
                        "document.querySelector('input[name=\"longitude\"]').value = '108.653810';"
                    ]);

            $browser->press('Simpan Profil & Alamat')
                    ->waitForText('Profil berhasil diperbarui!')
                    ->assertSee('Status Geofencing Valid: Titik koordinat berada dalam jangkauan operasional HasilLaut Hub Pangandaran');
        });

        $this->assertDatabaseHas('pembeli_detail', [
            'users_id_users' => $userId,
            'phone_number'   => '081234567890',
            'address'        => 'Jl. Merdeka No. 10, Kec. Pangandaran, Kab. Pangandaran 46396',
            'latitude'       => -7.684520,
            'longitude'      => 108.653810,
        ]);
    }

    /**
     * TC-SHP-002: Pengujian validasi sukses wilayah dalam Kabupaten Pangandaran (mengizinkan lanjut checkout)
     */
    public function test_tc_shp_002_valid_pangandaran_area_allows_checkout(): void
    {
        $userId = 1;

        PembeliDetail::updateOrCreate(
            ['users_id_users' => $userId],
            [
                'name'                => 'Budi Santoso',
                'phone_number'        => '081234567890',
                'address'             => 'Kecamatan Pangandaran, Kab. Pangandaran 46396',
                'latitude'            => -7.684520,
                'longitude'           => 108.653810,
                'is_pangandaran_area' => 1,
            ]
        );

        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/checkout')
                    ->assertPathIs('/pembeli/checkout')
                    ->assertSee('Kecamatan Pangandaran, Kab. Pangandaran 46396');
        });
    }

    /**
     * TC-SHP-003: Pengujian pembatasan/blokir wilayah di luar Pangandaran (Kota Bandung, Kec. Coblong, 40132)
     */
    public function test_tc_shp_003_blocks_location_outside_pangandaran(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/profile/edit')
                    ->clear('name')->type('name', 'Budi Santoso')
                    ->clear('phone_number')->type('phone_number', '081234567890')
                    ->clear('address')->type('address', 'Kec. Coblong, Kota Bandung 40132')
                    ->script([
                        "document.querySelector('input[name=\"latitude\"]').value = '-6.891480';",
                        "document.querySelector('input[name=\"longitude\"]').value = '107.610659';"
                    ]);

            $browser->press('Simpan Profil & Alamat')
                    ->waitForText('Status Geofencing Gagal')
                    ->assertSee('Titik koordinat berada di luar jangkauan operasional HasilLaut Hub Pangandaran');
        });
    }

    /**
     * TC-SHP-004: Pengujian validasi format nomor telepon tidak valid (< 10 digit: 08123)
     */
    public function test_tc_shp_004_rejects_invalid_phone_number_less_than_10_digits(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pembeli/profile/edit')
                    ->clear('name')->type('name', 'Budi Santoso')
                    ->clear('phone_number')->type('phone_number', '08123')
                    ->clear('address')->type('address', 'Jl. Merdeka No. 10, Kab. Pangandaran')
                    ->script([
                        "document.querySelector('input[name=\"latitude\"]').value = '-7.684520';",
                        "document.querySelector('input[name=\"longitude\"]').value = '108.653810';"
                    ]);

            $browser->press('Simpan Profil & Alamat')
                    ->pause(1000)
                    ->assertPathIs('/pembeli/profile/edit')
                    ->assertSee('Nomor telepon tidak valid');
        });
    }
}