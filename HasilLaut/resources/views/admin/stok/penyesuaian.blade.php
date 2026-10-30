<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyesuaian Produk Defect / Rusak - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        
        <!-- Header Halaman -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penyesuaian Produk Defect / Rusak (Warehouse Adjustment)</h1>
            <p class="text-sm text-gray-500 mt-1">Catat dan data inventaris hasil laut yang mengalami cacat, busuk, penyusutan timbangan, atau kesalahan input.</p>
        </div>

        <!-- Alert Notifikasi -->
        @if (session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Card Input -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <h2 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Form Input Pengurangan Stok Defect / Susut
                </h2>
                <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full">Hub Pangandaran</span>
            </div>

            <form action="{{ route('admin.stok.penyesuaian.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dropdown Pilih Produk -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pilih Produk Hasil Laut</label>
                        <select id="product_select" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id_product }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Pilih Batch -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pilih Batch Inventaris</label>
                        <select name="inventory_batches_id_inventory_batches" id="batch_select" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" disabled>
                            <option value="">-- Pilih Produk Terlebih Dahulu --</option>
                        </select>
                    </div>
                </div>

                <!-- Box Detail Ringkasan Batch -->
                <div class="bg-slate-100 rounded-xl p-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-medium text-gray-600">
                    <div>
                        <span class="block text-gray-400 uppercase text-[10px]">ID BATCH SISTEM</span>
                        <span id="info_batch_id" class="text-sm font-bold text-gray-800">-</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 uppercase text-[10px]">TANGGAL MASUK BATCH</span>
                        <span id="info_batch_date" class="text-sm font-bold text-gray-800">-</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 uppercase text-[10px]">SISA STOK SAAT INI</span>
                        <span id="info_batch_stock" class="text-sm font-bold text-gray-800">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Input Jumlah Pengurangan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Jumlah Pengurangan (kg)</label>
                        <input type="number" step="0.1" name="quantity" placeholder="0.0" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <!-- Select Alasan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Alasan Pengurangan / Defect</label>
                        <select name="reason" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="Busuk / Rusak">Busuk / Rusak Fisik</option>
                            <option value="Penyusutan Timbangan">Penyusutan Timbangan</option>
                            <option value="Kesalahan Input">Kesalahan Input Data</option>
                        </select>
                    </div>
                </div>

                <!-- Textarea Catatan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Keterangan Detail Fisik / Catatan Admin</label>
                    <textarea name="notes" rows="3" placeholder="Deskripsikan kondisi fisik produk secara detail..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-200 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Simpan & Kurangi Stok Gudang Otomatis
                </button>
            </form>
        </div>

        <!-- Section Riwayat Log Defect -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-gray-800 text-base">Riwayat Pencatatan Produk Defect / Rusak</h2>
                <span class="text-xs text-gray-500 font-medium">Total {{ $adjustments->count() }} Kasus</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse ($adjustments as $adj)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">{{ $adj->inventoryBatch->product->name ?? 'Produk' }}</h3>
                                <p class="text-[11px] text-gray-400">Batch: #BATCH-{{ $adj->inventory_batches_id_inventory_batches }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-red-50 text-red-500 border border-red-100">
                                {{ $adj->reason }}
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Jumlah Pengurangan:</span>
                            <span class="text-sm font-extrabold text-red-500">-{{ $adj->adjusted_quantity }} kg</span>
                        </div>

                        @if($adj->notes)
                            <p class="text-xs text-gray-500 italic line-clamp-2">"{{ $adj->notes }}"</p>
                        @endif

                        <div class="pt-2 border-t flex justify-between items-center text-[10px] text-gray-400">
                            <span>{{ $adj->created_at->format('d M Y, H:i') }}</span>
                            <span>Admin: {{ $adj->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-8 text-center text-sm text-gray-400 rounded-2xl border border-dashed">
                        Belum ada riwayat penyesuaian stok defect.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- JavaScript Interaktif AJAX Batch Filter -->
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
                        batchSelect.innerHTML += `<option value="${b.id_inventory_batches}">Batch #${b.id_inventory_batches} (Tgl: ${b.received_date} - Stok: ${b.stock} kg)</option>`;
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
</body>
</html>