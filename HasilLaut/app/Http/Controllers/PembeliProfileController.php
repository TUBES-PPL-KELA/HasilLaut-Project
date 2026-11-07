<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembeliDetail;
use Illuminate\Support\Facades\Auth;

class PembeliProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil & alamat GPS.
     */
    public function edit()
    {
        $userId = Auth::id() ?? 1; // Fallback ID jika belum login penuh
        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();

        return view('pembeli.profile.edit', compact('pembeliDetail'));
    }

    /**
     * Memperbarui data profil, alamat, dan koordinat GPS.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $userId = Auth::id() ?? 1;

        // Simulasi validasi geofencing wilayah Pangandaran
        // Contoh sederhana: memeriksa apakah koordinat berada di kisaran wilayah Pangandaran
        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        
        // Asumsi batas wilayah koordinat Pangandaran
        $isPangandaran = ($lat >= -7.85 && $lat <= -7.60 && $lng >= 108.50 && $lng <= 108.75);

        $pembeliDetail = PembeliDetail::updateOrCreate(
            ['users_id_users' => $userId],
            [
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'latitude' => $lat,
                'longitude' => $lng,
                'is_pangandaran_area' => $isPangandaran ? 1 : 0,
            ]
        );

        return redirect()->route('pembeli.profile.edit')->with('success', 'Profil dan titik koordinat alamat berhasil diperbarui!');
    }
}