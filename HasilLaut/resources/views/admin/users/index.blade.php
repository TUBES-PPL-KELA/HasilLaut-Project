@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C1D9DE]/50 px-3 py-1 text-[11px] font-bold text-[#215E68]">
                Sistem &amp; Akses Akun
            </span>
            <h1 class="mt-1.5 text-2xl font-extrabold tracking-tight text-[#013137]">
                Manajemen Pengguna
            </h1>
            <p class="mt-0.5 text-xs sm:text-sm text-[#5C9396]">
                Kelola akun Admin Hub, Kurir pengiriman lokal, dan pelanggan Pembeli HasilLaut.
            </p>
        </div>

        <button type="button" onclick="openCreateModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#013137] hover:bg-[#215E68] px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Alert Notifikasi -->
    @if (session('success'))
        <div class="rounded-2xl border border-[#5C9396]/40 bg-[#C1D9DE]/30 px-4 py-3 text-xs sm:text-sm font-semibold text-[#013137]">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs sm:text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
            <p class="font-bold">Gagal menyimpan data pengguna:</p>
            <ul class="mt-1 list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Kartu Ringkasan Role -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#5C9396]">Total Pengguna</p>
            <p class="mt-1.5 text-2xl font-extrabold text-[#013137]">{{ $counts['all'] }}</p>
            <p class="mt-0.5 text-[11px] text-gray-400">Seluruh akun terdaftar</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#215E68]">Admin Hub</p>
            <p class="mt-1.5 text-2xl font-extrabold text-[#013137]">{{ $counts['admin'] }}</p>
            <p class="mt-0.5 text-[11px] text-gray-400">Pengelola gudang &amp; pesanan</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#297376]">Kurir Armada</p>
            <p class="mt-1.5 text-2xl font-extrabold text-[#013137]">{{ $counts['kurir'] }}</p>
            <p class="mt-0.5 text-[11px] text-gray-400">Pengantar wilayah Pangandaran</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#5C9396]">Pembeli B2C</p>
            <p class="mt-1.5 text-2xl font-extrabold text-[#013137]">{{ $counts['pembeli'] }}</p>
            <p class="mt-0.5 text-[11px] text-gray-400">Pelanggan ritel terdaftar</p>
        </div>
    </div>

    <!-- Filter Role & Pencarian -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Tab Filter Role -->
        <div class="flex flex-wrap gap-1.5">
            @php
                $tabs = [
                    'all'     => 'Semua (' . $counts['all'] . ')',
                    'admin'   => 'Admin (' . $counts['admin'] . ')',
                    'kurir'   => 'Kurir (' . $counts['kurir'] . ')',
                    'pembeli' => 'Pembeli (' . $counts['pembeli'] . ')',
                ];
            @endphp

            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.users.index', ['role' => $key, 'search' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $roleFilter === $key ? 'bg-[#013137] text-white' : 'bg-gray-100 text-gray-600 hover:bg-[#C1D9DE]/40 hover:text-[#013137]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="role" value="{{ $roleFilter }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau username..."
                   class="w-full md:w-64 rounded-xl border border-gray-200 px-3.5 py-2 text-xs focus:border-[#215E68] focus:outline-none">
            <button type="submit"
                    class="rounded-xl bg-[#215E68] hover:bg-[#013137] px-3.5 py-2 text-xs font-bold text-white transition cursor-pointer">
                Cari
            </button>
            @if($search !== '')
                <a href="{{ route('admin.users.index', ['role' => $roleFilter]) }}"
                   class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Daftar Pengguna -->
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-[#f8fbfb] text-[11px] font-bold uppercase tracking-wider text-[#215E68]">
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Peran (Role)</th>
                        <th class="py-3.5 px-4">Kontak &amp; Detail</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                    @forelse($users as $u)
                        @php
                            $phone = $u->adminDetail->phone_number
                                ?? $u->kurirDetail->phone_number
                                ?? $u->pembeliDetail->phone_number
                                ?? '-';
                            $vehicle = $u->kurirDetail->vehicle_number ?? '';
                            $kurirStatus = $u->kurirDetail->status ?? 'available';
                            $address = $u->pembeliDetail->address ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Nama & Username -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=215E68&color=fff&bold=true"
                                         alt="{{ $u->name }}" class="w-9 h-9 rounded-full shrink-0">
                                    <div>
                                        <p class="font-bold text-[#013137] flex items-center gap-1.5">
                                            <span>{{ $u->name }}</span>
                                            @if($u->id_users === Auth::id())
                                                <span class="rounded-md bg-[#C1D9DE]/60 px-1.5 py-0.5 text-[10px] font-extrabold text-[#013137]">Anda</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-[#5C9396]">&#64;{{ $u->username }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Badge Role -->
                            <td class="py-3.5 px-4">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center rounded-lg bg-[#013137] px-2.5 py-1 text-[11px] font-bold text-white">
                                        Admin Hub
                                    </span>
                                @elseif($u->role === 'kurir')
                                    <span class="inline-flex items-center rounded-lg bg-[#297376]/15 border border-[#297376]/30 px-2.5 py-1 text-[11px] font-bold text-[#215E68]">
                                        Kurir
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-[#C1D9DE]/45 px-2.5 py-1 text-[11px] font-bold text-[#013137]">
                                        Pembeli
                                    </span>
                                @endif
                            </td>

                            <!-- Detail Spesifik Role -->
                            <td class="py-3.5 px-4 text-xs text-gray-600">
                                <p class="font-semibold text-gray-800">Telp: {{ $phone }}</p>
                                @if($u->role === 'kurir')
                                    <p class="text-[11px] text-[#215E68] mt-0.5">
                                        Plat: <strong>{{ $vehicle ?: '-' }}</strong> &bull;
                                        <span class="uppercase font-bold">{{ $kurirStatus }}</span>
                                    </p>
                                @elseif($u->role === 'pembeli' && $address)
                                    <p class="text-[11px] text-gray-500 mt-0.5 max-w-xs truncate" title="{{ $address }}">
                                        {{ $address }}
                                    </p>
                                @endif
                            </td>

                            <!-- Tanggal Dibuat -->
                            <td class="py-3.5 px-4 text-xs text-gray-500">
                                {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Tombol Edit & Hapus -->
                            <!-- Tombol Edit & Hapus -->
                            <td class="py-3.5 px-4 text-right">
                                @php
                                    $editUserData = [
                                        'id'             => $u->id_users,
                                        'name'           => $u->name,
                                        'username'       => $u->username,
                                        'role'           => $u->role,
                                        'phone_number'   => $phone === '-' ? '' : $phone,
                                        'vehicle_number' => $vehicle,
                                        'kurir_status'   => $kurirStatus,
                                        'address'        => $address,
                                    ];
                                @endphp
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button"
                                            data-user="{{ json_encode($editUserData) }}"
                                            onclick="openEditModal(JSON.parse(this.dataset.user))"
                                            class="rounded-lg border border-gray-200 bg-white hover:bg-[#C1D9DE]/30 px-2.5 py-1.5 text-xs font-bold text-[#215E68] transition cursor-pointer">
                                        Edit
                                    </button>

                                    @if($u->id_users !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $u->id_users) }}" method="POST"
                                              onsubmit="return confirm('Hapus akun {{ $u->name }} secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 text-xs font-bold text-red-600 transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-xs text-gray-500">
                                Tidak ada data pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH / EDIT PENGGUNA -->
<div id="userFormModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4 backdrop-blur-[1px]">
    <div class="w-full max-w-lg rounded-3xl bg-white border border-gray-200 shadow-xl overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-[#f8fbfb]">
            <h2 id="modalTitle" class="text-base font-extrabold text-[#013137]">Tambah Pengguna Baru</h2>
            <button type="button" onclick="closeUserModal()" class="text-gray-400 hover:text-gray-700 text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form id="userModalForm" action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethodInput" value="POST">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#013137] mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" id="field_name" required
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#013137] mb-1">Username *</label>
                    <input type="text" name="username" id="field_username" required
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#013137] mb-1">Peran (Role) *</label>
                    <select name="role" id="field_role" onchange="toggleRoleFields()"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
                        <option value="kurir">Kurir Pengiriman</option>
                        <option value="admin">Admin Hub</option>
                        <option value="pembeli">Pembeli</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#013137] mb-1">Nomor Telepon / WA</label>
                    <input type="text" name="phone_number" id="field_phone" placeholder="081234567890"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
                </div>
            </div>

      <!-- Input Khusus Kurir -->
            <div id="kurirFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-2xl bg-[#f4f8f9] p-3.5 border border-[#C1D9DE]/70">
                <div id="kurirVehicleField">
                    <label class="block text-xs font-bold text-[#013137] mb-1">Nomor Plat Kendaraan *</label>
                    <input type="text" name="vehicle_number" id="field_vehicle" placeholder="Contoh: Z 1234 AB"
                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs sm:text-sm uppercase focus:border-[#215E68] focus:outline-none">
                </div>

                <div id="kurirStatusField">
                    <label class="block text-xs font-bold text-[#013137] mb-1">Status Kurir</label>
                    <select name="kurir_status" id="field_kurir_status"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
                        <option value="available">Available (Siap Antar)</option>
                        <option value="busy">Busy (Sedang Mengantar)</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
            </div>

            <!-- Input Khusus Pembeli -->
            <div id="pembeliFields" class="hidden rounded-2xl bg-[#f4f8f9] p-3.5 border border-[#C1D9DE]/70">
                <label class="block text-xs font-bold text-[#013137] mb-1">Alamat Pengiriman *</label>
                <textarea name="address" id="field_address" rows="2" placeholder="Alamat lengkap di wilayah Pangandaran"
                          class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#013137] mb-1">
                    Password <span id="passwordHint" class="font-normal text-gray-400">(Minimal 6 karakter)</span>
                </label>
                <input type="password" name="password" id="field_password"
                       class="w-full rounded-xl border border-gray-200 px-3.5 py-2 text-xs sm:text-sm focus:border-[#215E68] focus:outline-none">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeUserModal()"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-xl bg-[#013137] hover:bg-[#215E68] px-5 py-2 text-xs font-bold text-white transition cursor-pointer">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRoleFields() {
        const role = document.getElementById('field_role').value;
        document.getElementById('kurirFields').classList.toggle('hidden', role !== 'kurir');
        document.getElementById('pembeliFields').classList.toggle('hidden', role !== 'pembeli');
    }

    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Pengguna Baru';
        document.getElementById('userModalForm').action = "{{ route('admin.users.store') }}";
        document.getElementById('formMethodInput').value = 'POST';

        document.getElementById('field_name').value = '';
        document.getElementById('field_username').value = '';
        document.getElementById('field_role').value = 'kurir';
        document.getElementById('field_phone').value = '';
        document.getElementById('field_vehicle').value = '';
        document.getElementById('field_kurir_status').value = 'available';
        document.getElementById('field_address').value = '';
        document.getElementById('field_password').value = '';
        document.getElementById('field_password').required = true;
        document.getElementById('passwordHint').textContent = '* (Minimal 6 karakter)';

        // Sembunyikan Status Kurir & lebarkan input Plat Kendaraan saat tambah baru
        document.getElementById('kurirStatusField').classList.add('hidden');
        document.getElementById('kurirVehicleField').classList.add('sm:col-span-2');

        toggleRoleFields();
        const modal = document.getElementById('userFormModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(user) {
        document.getElementById('modalTitle').textContent = 'Edit Akun: ' + user.name;
        document.getElementById('userModalForm').action = "/admin/users/" + user.id;
        document.getElementById('formMethodInput').value = 'PUT';

        document.getElementById('field_name').value = user.name;
        document.getElementById('field_username').value = user.username;
        document.getElementById('field_role').value = user.role;
        document.getElementById('field_phone').value = user.phone_number || '';
        document.getElementById('field_vehicle').value = user.vehicle_number || '';
        document.getElementById('field_kurir_status').value = user.kurir_status || 'available';
        document.getElementById('field_address').value = user.address || '';
        document.getElementById('field_password').value = '';
        document.getElementById('field_password').required = false;
        document.getElementById('passwordHint').textContent = '(Kosongkan jika tidak ingin mengganti password)';

        // Tampilkan kembali Status Kurir & kembalikan lebar kolom saat edit
        document.getElementById('kurirStatusField').classList.remove('hidden');
        document.getElementById('kurirVehicleField').classList.remove('sm:col-span-2');

        toggleRoleFields();
        const modal = document.getElementById('userFormModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeUserModal() {
        const modal = document.getElementById('userFormModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection