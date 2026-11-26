<?php

namespace App\Http\Controllers;

use App\Models\AdminDetail;
use App\Models\KurirDetail;
use App\Models\PembeliDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $roleFilter = $request->query('role', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = User::with(['adminDetail', 'kurirDetail', 'pembeliDetail']);

        if (in_array($roleFilter, ['admin', 'kurir', 'pembeli'], true)) {
            $query->where('role', $roleFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('id_users')->get();

        $counts = [
            'all'     => User::count(),
            'admin'   => User::where('role', 'admin')->count(),
            'kurir'   => User::where('role', 'kurir')->count(),
            'pembeli' => User::where('role', 'pembeli')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts', 'roleFilter', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'username'       => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', 'unique:users,username'],
            'role'           => ['required', Rule::in(['admin', 'kurir', 'pembeli'])],
            'password'       => ['required', 'string', 'min:6'],
            'phone_number'   => ['nullable', 'string', 'max:20'],
            'vehicle_number' => ['required_if:role,kurir', 'nullable', 'string', 'max:20'],
            'kurir_status'   => ['nullable', Rule::in(['available', 'busy', 'offline'])],
            'address'        => ['required_if:role,pembeli', 'nullable', 'string', 'max:255'],
        ], [
            'name.required'              => 'Nama lengkap wajib diisi.',
            'username.required'          => 'Username wajib diisi.',
            'username.alpha_dash'        => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), atau garis bawah (_).',
            'username.unique'            => 'Username sudah digunakan.',
            'password.required'          => 'Password wajib diisi.',
            'password.min'               => 'Password minimal 6 karakter.',
            'vehicle_number.required_if' => 'Nomor plat kendaraan wajib diisi untuk peran Kurir.',
            'address.required_if'        => 'Alamat wajib diisi untuk peran Pembeli.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role'     => $validated['role'],
            ]);

            $this->syncRoleDetail($user, $validated);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'username'       => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', Rule::unique('users', 'username')->ignore($user->id_users, 'id_users')],
            'role'           => ['required', Rule::in(['admin', 'kurir', 'pembeli'])],
            'password'       => ['nullable', 'string', 'min:6'],
            'phone_number'   => ['nullable', 'string', 'max:20'],
            'vehicle_number' => ['required_if:role,kurir', 'nullable', 'string', 'max:20'],
            'kurir_status'   => ['nullable', Rule::in(['available', 'busy', 'offline'])],
            'address'        => ['required_if:role,pembeli', 'nullable', 'string', 'max:255'],
        ]);

        if ($user->id_users === Auth::id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Admin yang sedang digunakan.');
        }

        DB::transaction(function () use ($user, $validated) {
            $oldRole = $user->role;

            $userData = [
                'name'     => $validated['name'],
                'username' => $validated['username'],
                'role'     => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $user->update($userData);

            if ($oldRole !== $validated['role']) {
                AdminDetail::where('users_id_users', $user->id_users)->delete();
                KurirDetail::where('users_id_users', $user->id_users)->delete();
                PembeliDetail::where('users_id_users', $user->id_users)->delete();
            }

            $this->syncRoleDetail($user, $validated);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_users === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    private function syncRoleDetail(User $user, array $validated): void
    {
        if ($user->role === 'admin') {
            AdminDetail::updateOrCreate(
                ['users_id_users' => $user->id_users],
                ['phone_number' => $validated['phone_number'] ?? null]
            );
        } elseif ($user->role === 'kurir') {
            KurirDetail::updateOrCreate(
                ['users_id_users' => $user->id_users],
                [
                    'vehicle_number' => strtoupper((string) ($validated['vehicle_number'] ?? '-')),
                    'phone_number'   => $validated['phone_number'] ?? null,
                    'status'         => $validated['kurir_status'] ?? 'available',
                ]
            );
        } elseif ($user->role === 'pembeli') {
            PembeliDetail::updateOrCreate(
                ['users_id_users' => $user->id_users],
                [
                    'name'         => $validated['name'],
                    'phone_number' => $validated['phone_number'] ?? null,
                    'address'      => $validated['address'] ?? null,
                ]
            );
        }
    }
}