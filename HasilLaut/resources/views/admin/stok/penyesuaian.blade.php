@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <!-- Header Halaman -->
    <div>
        <h1 class="text-[26px] font-extrabold text-gray-900 tracking-tight">Penyesuaian Produk Defect / Rusak (Warehouse Adjustment)</h1>
        <p class="text-sm text-gray-500 mt-1.5 font-medium">Catat dan data inventaris hasil laut yang mengalami cacat, busuk, penyusutan timbangan, atau kesalahan input.</p>
    </div>

    <!-- Alert Notifikasi -->
    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <!-- 1. Card Form Input -->
    <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-200">
        
        <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
            <h2 class="font-bold text-gray-900 flex items-center gap-2 text-base">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Form Input Pengurangan Stok Defect / Susut
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-500 rounded-lg">Hub Pangandaran</span>
        </div>

        <form action="{{ route('admin.stok.penyesuaian.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Pilih Produk -->
                <div>
                    <label class="block text-[13px] font-bold text-gray-800 mb-2">Pilih Produk Hasil Laut</label>
                    <select id="product_select" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition shadow-sm">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id_product }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Pilih Batch -->
                <div>
                    <label class="block text-[13px] font-bold text-gray-800 mb-2">Pilih Batch Inventaris</label>
                    <select name="inventory_batches_id_inventory_batches" id="batch_select" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition shadow-sm" disabled>
                        <option value="">-- Pilih Produk Terlebih Dahulu --</option>
                    </select>
                </div>
            </div>

            <!-- Box Detail Ringkasan Batch (Sesuai Desain Figma: bg biru/teal keputihan) -->
            <div class="bg-[#EAF3F5] border border-[#D1E5E9] rounded-xl p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="block text-teal-700/60 font-bold uppercase text-[10px] tracking-wider mb-1">ID BATCH SISTEM</span>
                    <span id="info_batch_id" class="text-sm font-black text-gray-900">-</span>
                </div>
                <div>
                    <span class="block text-teal-700/60 font-bold uppercase text-[10px] tracking-wider mb-1">TANGGAL MASUK BATCH</span>
                    <span id="info_batch_date" class="text-sm font-black text-gray-900">-</span>
                </div>
                <div>
                    <span class="block text-teal-700/60 font-bold uppercase text-[10px] tracking-wider mb-1">SISA STOK SAAT INI</span>
                    <span id="info_batch_stock" class="text-sm font-black text-gray-900">-</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input Jumlah Pengurangan -->
                <div>
                    <label class="block text-[13px] font-bold text-gray-800 mb-2">Jumlah Pengurangan (kg)</label>
                    <input type="number" step="0.1" name="quantity" placeholder="0.0" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm shadow-sm focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition">
                </div>

                <!-- Select Alasan -->
                <div>
                    <label class="block text-[13px] font-bold text-gray-800 mb-2">Alasan Pengurangan / Defect</label>
                    <select name="reason" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm shadow-sm focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition">
                        <option value="Busuk / Rusak">Ikan Busuk atau Rusak Fisik</option>
                        <option value="Penyusutan Timbangan">Penyusutan Timbangan</option>
                        <option value="Kesalahan Input">Kesalahan Input Data</option>
                    </select>
                </div>
            </div>

            <!-- Textarea Catatan -->
            <div>
                <label class="block text-[13px] font-bold text-gray-800 mb-2">Keterangan Detail Fisik / Catatan Admin</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Ditemukan 3.5 kg daging ikan mengalami pelunakan tekstur..." class="w-full bg-white border border-gray-300 rounded-xl p-4 text-sm shadow-sm focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition"></textarea>
            </div>

            <!-- Tombol Submit (Merah Sesuai Figma) -->
            <button type="submit" class="w-full bg-[#EF4444] hover:bg-red-600 text-white font-bold py-3.5 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Simpan & Kurangi Stok Gudang Otomatis
            </button>
        </form>
    </div>

    <!-- 2. Card Riwayat Log Defect -->
    <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-200 mt-6">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Pencatatan Produk Defect / Rusak
            </h2>
            <span class="text-xs text-gray-500 font-semibold">Total {{ $adjustments->count() }} Kasus Bulan Ini</span>
        </div>

        <!-- Search Bar History -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Cari berdasarkan nama produk, ID batch, atau alasan kerusakan..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-full text-sm focus:outline-none focus:border-gray-500 transition">
            </div>
        </div>

        <!-- Grid Cards History -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            @forelse ($adjustments as $adj)
                <!-- Kartu History (Mirip Figma) -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">{{ $adj->inventoryBatch->product->name ?? 'Produk' }}</h3>
                                <p class="text-[11px] text-gray-400 mt-0.5">Batch: #BATCH-{{ $adj->inventory_batches_id_inventory_batches }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-[9px] font-bold rounded border border-red-200 text-red-500 uppercase">
                                {{ $adj->reason }}
                            </span>
                        </div>

                        <div class="border-t border-b border-gray-100 py-3 my-3 flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-600">Jumlah Pengurangan:</span>
                            <span class="text-sm font-black text-red-500">-{{ $adj->adjusted_quantity }} kg</span>
                        </div>

                        @if($adj->notes)
                            <p class="text-[11px] text-gray-500 leading-relaxed mb-4">"{{ $adj->notes }}"</p>
                        @endif
                    </div>

                    <div class="flex justify-between items-center text-[10px] font-medium text-gray-400 mt-2">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $adj->created_at->format('d M Y, H:i') }}
                        </span>
                        <span>Admin: {{ $adj->user->name ?? 'Andi M.' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-gray-50 p-8 text-center text-sm font-medium text-gray-400 rounded-2xl border border-dashed border-gray-300">
                    Belum ada riwayat penyesuaian stok defect.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    let batchData = [];

    document.getElementById('product_select').addEventListener('change', function () {
        const productId = this.value;
        const batchSelect = document.getElementById('batch_select');
        
        batchSelect.innerHTML = '<option value="">-- Memuat Batch... --</option>';
        batchSelect.disabled = true;
        resetBatchInfo();

        if (!productId) {
            batchSelect.innerHTML = '<option value="">-- Pilih Produk Terlebih Dahulu --</option>';
            return;
        }

        fetch(`/admin/stok/get-batches/${productId}`)
            .then(res => res.json())
            .then(data => {
                batchData = data;
                batchSelect.innerHTML = '<option value="">-- Pilih Batch Inventaris --</option>';
                if (data.length === 0) {
                    batchSelect.innerHTML = '<option value="">-- Tidak ada batch aktif --</option>';
                    return;
                }
                data.forEach(b => {
                    batchSelect.innerHTML += `<option value="${b.id_inventory_batches}">Batch #${b.id_inventory_batches} (Masuk: ${b.received_date})</option>`;
                });
                batchSelect.disabled = false;
            });
    });

    document.getElementById('batch_select').addEventListener('change', function () {
        const batchId = parseInt(this.value);
        const selected = batchData.find(b => b.id_inventory_batches === batchId);

        if (selected) {
            document.getElementById('info_batch_id').innerText = `#BATCH-${selected.id_inventory_batches}`;
            document.getElementById('info_batch_date').innerText = selected.received_date;
            document.getElementById('info_batch_stock').innerText = `${selected.stock} kg`;
        } else {
            resetBatchInfo();
        }
    });

    function resetBatchInfo() {
        document.getElementById('info_batch_id').innerText = '-';
        document.getElementById('info_batch_date').innerText = '-';
        document.getElementById('info_batch_stock').innerText = '-';
    }
</script>
@endsection