@extends('layouts.pembeli')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Keranjang Belanja</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Kolom Kiri: Daftar Item Keranjang (2 Span) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-sm overflow-x-auto">
            <!-- Header Pilih Semua -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-slate-100 text-sm gap-2">
                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                    <input type="checkbox" checked class="rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4">
                    Pilih Semua ({{ count($cartItems ?? []) }})
                </label>
                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item terpilih?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer">Hapus Terpilih</button>
                </form>
            </div>

            <!-- List Item -->
            <div class="divide-y divide-slate-100">
                @forelse($cartItems ?? [] as $item)
                    <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <!-- Kiri: Checkbox, Gambar, & Detail Produk -->
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <input type="checkbox" checked class="rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4 shrink-0">
                            
                            <img src="{{ $item->product->image ?? 'https://images.unsplash.com/photo-1534482498418-fef13c323f46?auto=format&fit=crop&w=150&q=80' }}" 
                                 alt="{{ $item->product->name ?? 'Produk Ikan' }}" 
                                 class="w-16 h-16 min-w-[4rem] min-h-[4rem] object-cover rounded-xl border border-slate-100 shrink-0">
                            
                            <div>
                                <h3 class="font-semibold text-sm text-slate-800">{{ $item->product->name ?? 'Nama Produk' }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">📍 Hub Pangandaran</p>
                                <p class="text-xs font-semibold text-slate-700 mt-1">Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }} / kg</p>
                            </div>
                        </div>

                        <!-- Kanan: Stepper Kuantitas, Subtotal, & Tombol Hapus -->
                        <div class="flex items-center justify-between sm:justify-end gap-4 sm:gap-6 w-full sm:w-auto">
                            <!-- Stepper Kuantitas -->
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                    <button type="submit" 
                                        onclick="return {{ $item->quantity <= 1 ? "confirm('Apakah Anda ingin menghapus produk ini dari keranjang?')" : 'true' }}" 
                                        class="px-2.5 py-1.5 text-xs text-slate-600 hover:bg-slate-200 transition font-bold cursor-pointer">-</button>
                                </form>

                                <span class="px-3 text-xs font-semibold text-slate-800 text-center min-w-[45px]">{{ $item->quantity }} kg</span>

                                <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                    <button type="submit" class="px-2.5 py-1.5 text-xs text-slate-600 hover:bg-slate-200 transition font-bold cursor-pointer">+</button>
                                </form>
                            </div>

                            <!-- Subtotal Item -->
                            <span class="font-bold text-sm text-slate-800 w-24 text-right">
                                Rp {{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}
                            </span>

                            <!-- Tombol Hapus Satuan -->
                            <form action="{{ route('cart.destroy', $item->id_cart_items) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini dari keranjang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1 text-base cursor-pointer" title="Hapus Item">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <p>Keranjang belanja kamu masih kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Belanja (1 Span) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-6">
            <h2 class="font-bold text-base text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                📦 Ringkasan Belanja
            </h2>

            @php
                $totalBerat = collect($cartItems ?? [])->sum('quantity');
                $totalHarga = collect($cartItems ?? [])->sum(fn($i) => ($i->product->price ?? 0) * $i->quantity);
                $ongkir = (count($cartItems ?? []) > 0) ? 15000 : 0;
                $grandTotal = $totalHarga + $ongkir;
            @endphp

            <div class="space-y-3 text-sm text-slate-600 mb-6">
                <div class="flex justify-between">
                    <span>Total Berat Ikan</span>
                    <span class="font-semibold text-slate-800">{{ number_format($totalBerat, 1, ',', '.') }} kg</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Harga Produk ({{ count($cartItems ?? []) }} item)</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Estimasi Ongkir Hub</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 mb-6 flex justify-between items-center">
                <span class="font-bold text-base text-slate-800">Total Belanja</span>
                <span class="font-bold text-lg text-[#013137]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>

            <a href="#" class="w-full bg-[#013137] text-white font-medium py-3 rounded-xl text-center block hover:bg-[#215E68] transition shadow-sm">
                Pesan Sekarang &rarr;
            </a>
        </div>

    </div>
</div>
@endsection