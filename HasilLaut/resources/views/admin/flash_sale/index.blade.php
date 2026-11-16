@extends('layouts.admin') 

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Flash Sale & Ambang Batas Kesegaran</h1>
        <p class="text-sm text-gray-500 mt-1">Terbitkan diskon kilat untuk komoditas gudang yang mendekati batas waktu cold chain demi menjaga perputaran stok.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-teal-800 bg-teal-100 rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 bg-red-100 rounded-xl font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Section 1: Kotak Kuning (Warning & Atur Diskon) -->
    <div class="bg-[#FFFDF5] border border-yellow-200 rounded-2xl p-6 mb-10 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start gap-6 mb-6">
            
            <!-- Warning Message -->
            <div class="flex items-start gap-4 bg-[#FFF8D6] p-4 rounded-xl flex-1 border border-yellow-300">
                <div class="mt-0.5 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-yellow-800">Perhatian Standar Rantai Dingin (Cold Chain)</h3>
                    <p class="text-xs text-yellow-700 mt-1 leading-relaxed">
                        Produk di bawah ini telah berada di gudang mendekati batas maksimum waktu penyimpanan aman (H-1). 
                        Sistem akan <span class="font-bold">otomatis menerbitkan Flash Sale</span> pada pukul 00:00 menggunakan aturan diskon global.
                    </p>
                </div>
            </div>

            <!-- Pengaturan Diskon Global -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm shrink-0">
                <form action="{{ route('admin.flash_sale.discount.store') }}" method="POST" class="flex flex-col gap-3">
                    @csrf
                    <label class="text-xs font-bold text-gray-700">Atur Besaran Diskon Global (%)</label>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="number" name="percent" min="1" max="99" 
                                value="{{ $discount->percent ?? 45 }}" 
                                class="w-24 border border-gray-300 rounded-lg pl-3 pr-8 py-2.5 text-sm font-bold text-gray-800 focus:ring-teal-500 focus:border-teal-500" required>
                            <span class="absolute right-3 top-2.5 text-gray-500 font-bold">%</span>
                        </div>
                        <button type="submit" class="bg-[#1C4E55] hover:bg-[#13363b] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                            Simpan Aturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Ikan H-1 Kadaluarsa -->
        <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">Mendekati Ambang Batas Kesegaran:</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($warningBatches as $batch)
                <div class="bg-white border border-gray-200 rounded-xl p-3 flex gap-4 items-center shadow-sm">
                    @if($batch->product && $batch->product->image)
                        <img src="{{ asset('storage/' . $batch->product->image) }}" alt="{{ $batch->product->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-100 shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-400 text-[10px] font-semibold text-center p-1 shrink-0">
                            No Image
                        </div>
                    @endif
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">{{ $batch->product->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">Stok: <span class="font-semibold">{{ $batch->stock }} kg</span></p>
                        <div class="mt-1.5 inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded">
                            Sisa Waktu: < 24 Jam
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl p-4 text-center border border-dashed border-gray-300 text-sm text-gray-500 font-medium">
                    Kondisi aman. Tidak ada stok yang mendekati batas kadaluarsa hari ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 2: Daftar Flash Sale Aktif -->
    <div>
        <div class="flex justify-between items-end mb-4 border-b border-gray-200 pb-2">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path>
                </svg>
                Flash Sale Aktif di Etalase B2C
            </h2>
            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-md">Status: Live</span>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($activeFlashSales as $item)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between shadow-sm hover:shadow-md transition-shadow">
                    
                    <!-- Info Produk -->
                    <div class="flex items-center gap-5">
                        @if($item->inventoryBatch->product && $item->inventoryBatch->product->image)
                            <img src="{{ asset('storage/' . $item->inventoryBatch->product->image) }}" alt="{{ $item->inventoryBatch->product->name }}" class="w-20 h-20 object-cover rounded-xl border border-gray-100 shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-semibold text-center p-2 shrink-0">
                                No Image
                            </div>
                        @endif
                        <div>
                            <h3 class="text-base font-bold text-gray-900">{{ $item->inventoryBatch->product->name }}</h3>
                            <div class="flex items-center gap-3 mt-1 text-sm">
                                <span class="text-gray-400 font-medium line-through">Rp {{ number_format($item->inventoryBatch->product->price, 0, ',', '.') }}</span>
                                <span class="font-bold text-teal-700">Rp {{ number_format($item->flash_sale_price, 0, ',', '.') }} / kg</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Sisa Stok: {{ $item->stock }} kg &bull; ID Batch: #{{ $item->inventoryBatch->id_inventory_batches }}</p>
                        </div>
                    </div>

                    <!-- Label Diskon & Tombol Aksi -->
                    <div class="flex items-center gap-5 mt-4 md:mt-0 w-full md:w-auto justify-between md:justify-end">
                        <div class="bg-red-50 text-red-600 font-extrabold px-4 py-1.5 rounded-lg text-sm border border-red-100">
                            -{{ $item->discount->percent }}% Diskon
                        </div>
                        
                        <button type="button"
                            data-url="{{ route('admin.flash_sale.destroy', $item->id_flash_sale_item) }}"
                            data-name="{{ $item->inventoryBatch->product->name }}"
                            onclick="openStopModal(this.dataset.url, this.dataset.name)"
                            class="border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                            Hentikan Flash Sale
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-10 text-center">
                    <p class="text-gray-500 font-medium">Belum ada produk Flash Sale yang aktif saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $activeFlashSales->links() }}
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Alasan Penghentian Flash Sale -->
<div id="stopFlashSaleModal" onclick="if(event.target === this) closeStopModal()" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Hentikan Flash Sale</h3>
        <p class="text-xs text-gray-500 mt-1">Pilih alasan penarikan produk <span id="modalProductName" class="font-bold text-gray-800"></span> dari etalase promo:</p>

        <form id="stopFlashSaleForm" method="POST" class="mt-4 space-y-3">
            @csrf
            @method('DELETE')

            <label class="flex items-start gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-red-50/50 has-[:checked]:border-red-500 has-[:checked]:bg-red-50/40">
                <input type="radio" name="reason" value="spoiled" class="mt-1 text-red-600 focus:ring-red-500" required>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Stok Busuk / Tidak Layak Jual</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Hapus dari promo dan nolkan sisa stok di gudang agar tidak terjual.</span>
                </div>
            </label>

            <label class="flex items-start gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50/50 has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/40">
                <input type="radio" name="reason" value="mistake" class="mt-1 text-teal-600 focus:ring-teal-500" required>
                <div>
                    <span class="block text-sm font-bold text-gray-900">Kesalahan Input Tanggal / Batas Simpan</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Kembalikan ke stok normal dan tahan dari jadwal otomatis malam ini.</span>
                </div>
            </label>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeStopModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStopModal(actionUrl, productName) {
        const form = document.getElementById('stopFlashSaleForm');
        form.reset();
        form.action = actionUrl;
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('stopFlashSaleModal').classList.remove('hidden');
    }

    function closeStopModal() {
        document.getElementById('stopFlashSaleModal').classList.add('hidden');
    }
</script>
@endsection