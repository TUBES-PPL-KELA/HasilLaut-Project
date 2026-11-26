<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PembeliDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PembeliProfileController extends Controller
{
    /* Menampilkan halaman edit profil & alamat GPS.*/
    public function edit()
    {
        $userId = Auth::id();
        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();

        return view('pembeli.profile.edit', compact('pembeliDetail'));
    }

    /* Memperbarui data profil, alamat, dan validasi geofencing GPS.*/
    public function update(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|digits_between:10,15',
            'address'      => 'required|string',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
        ], 
        [ 
            'phone_number.digits_between' => 'Nomor telepon tidak valid, minimal harus terdiri dari 10 digit angka.',
            'phone_number.numeric'        => 'Nomor telepon tidak valid, harus berupa angka.',
        ]);

       // Di dalam method update() (sekitar baris 31 & 48)
        $userId = Auth::id();
        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        // Validasi Geofencing Wilayah Kabupaten Pangandaran terpusat di Controller
        $isPangandaran = ($lat >= -7.85 && $lat <= -7.45 && $lng >= 108.30 && $lng <= 108.80);

        if (!$isPangandaran) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Status Geofencing Gagal: Titik koordinat berada di luar jangkauan operasional HasilLaut Hub Pangandaran. Data tidak dapat disimpan.');
        }

        $user = User::findOrFail($userId);
        $user->update(['name' => $request->name]);

        PembeliDetail::updateOrCreate(
            ['users_id_users' => $userId],
            [
                'name'         => $request->name,
                'phone_number' => $request->phone_number,
                'address'      => $request->address,
                'latitude'     => $lat,
                'longitude'    => $lng,
            ]
        );

        // Jika di dalam Pangandaran: simpan & kirim pesan sukses beserta status geofencing
        return redirect()->route('pembeli.profile.edit')
            ->with('success', 'Profil berhasil diperbarui! Status Geofencing Valid: Titik koordinat berada dalam jangkauan operasional HasilLaut Hub Pangandaran.');
    }
}