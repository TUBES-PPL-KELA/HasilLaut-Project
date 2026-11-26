<?php

namespace App\Http\Controllers;

use App\Models\PembeliDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user()->role);
        }

        return back()
            ->withErrors(['username' => 'Username atau password yang Anda masukkan salah.'])
            ->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

   public function register(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'username'     => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', 'unique:users,username'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address'      => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.alpha_dash'   => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), atau garis bawah (_).',
            'username.unique'       => 'Username sudah digunakan, silakan pilih yang lain.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'address.required'      => 'Alamat lengkap wajib diisi.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($validated) {
            $newUser = User::create([
                'name'     => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role'     => 'pembeli',
            ]);

            PembeliDetail::create([
                'users_id_users' => $newUser->id_users,
                'name'           => $validated['name'],
                'phone_number'   => $validated['phone_number'],
                'address'        => $validated['address'],
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk menggunakan username dan password yang baru dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'kurir' => redirect()->intended(route('kurir.tugas')),
            default => redirect()->intended(route('pembeli.katalog')),
        };
    }
}