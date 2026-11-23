@extends('layouts.pembeli')

@section('title', $product->name . ' - HasilLaut')

@section('content')
<div class="space-y-8 pb-12">

    {{-- Notifikasi Sukses / Error dari Cart --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
            <a href="{{ route('pembeli.cart') }}" class="font-bold underline hover:text-emerald-950">Lihat Keranjang &rarr;</a>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Kartu Utama Detail Produk (2 Kolom) --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 lg:p-8 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Kolom Kiri: Gambar Utama & Thumbnail --}}
            <div class="lg:col-span-6 space-y-4">
                @php
                    $mainImage = $product->image
                        ? asset('storage/' . $product->image)
                        : 'https://images.unsplash.com/photo-1534482421-64566f976cfa?auto=format&fit=crop&w=800&q=80';
                @endphp

                <div class="relative rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/70">
                    @if($flashSaleItem)
                        <div class="absolute top-4 left-4 z-10 bg-amber-500 text-white text-xs font-extrabold px-3 py-1.5 rounded-full shadow-md flex items-center gap-1">
                            ⚡ FLASH SALE -{{ $flashSaleItem->discount->percent ?? 0 }}%
                        </div>
                    @endif
                    <img id="mainProductImage"
                         src="{{ $mainImage }}"
                         alt="{{ $product->name }}"
                         class="w-full h-72 sm:h-96 object-cover">
                </div>

                {{-- Thumbnail Kecil --}}
                <div class="flex gap-3 overflow-x-auto pb-1">
                    <button type="button"
                            onclick="document.getElementById('mainProductImage').src='{{ $mainImage }}'"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border-2 border-[#013137] shrink-0">
                        <img src="{{ $mainImage }}" alt="Thumbnail" class="w-full h-full object-cover">
                    </button>
                </div>
            </div>

            {{-- Kolom Kanan: Informasi Produk & Tombol Aksi --}}
            <div class="lg:col-span-6 flex flex-col justify-between space-y-5">
                <div class="space-y-3">
                    {{-- Badge Kategori --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="bg-teal-50 text-[#013137] text-xs font-semibold px-3 py-1 rounded-full inline-flex items-center gap-1.5">
                            🐟 {{ $product->category->name ?? 'Ikan Segar Laut' }}
                        </span>
                        @if($flashSaleItem)
                            <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-3 py-1 rounded-full">
                                Harga Promo Stok Terbatas
                            </span>
                        @endif
                    </div>

                    {{-- Nama Produk --}}
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating & Stok --}}
                    <div class="flex flex-wrap items-center gap-2.5 text-xs lg:text-sm text-slate-500">
                        <span class="text-amber-500 font-bold">★★★★★ <span class="text-slate-700">4.8</span></span>
                        <span>(124 Ulasan)</span>
                        <span>&bull;</span>
                        <span class="font-semibold {{ $availableStock > 0 ? 'text-slate-700' : 'text-rose-600' }}">
                            Stok Tersedia: {{ rtrim(rtrim(number_format($availableStock, 2, ',', '.'), '0'), ',') }} kg
                        </span>
                    </div>

                    {{-- Harga Produk (Flash Sale vs Reguler) --}}
                    <div class="pt-1">
                        @if($flashSaleItem)
                            <div class="flex items-baseline gap-3">
                                <span class="text-2xl lg:text-3xl font-extrabold text-[#013137]">
                                    Rp {{ number_format($flashSaleItem->flash_sale_price, 0, ',', '.') }}
                                    <span class="text-xs lg:text-sm font-normal text-slate-500">/ kg</span>
                                </span>
                                <span class="text-sm text-slate-400 line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <div class="text-2xl lg:text-3xl font-extrabold text-[#013137]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                <span class="text-xs lg:text-sm font-normal text-slate-500">/ kg</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info Hub & Standar Rantai Dingin --}}
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-2 text-xs lg:text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <span>📍</span>
                            <span><strong>Asal Hub Pesisir:</strong> HasilLaut Hub Pangandaran</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>📅</span>
                            <span>
                                <strong>Tanggal Masuk Gudang:</strong>
                                {{ $receivedDate ? \Carbon\Carbon::parse($receivedDate)->translatedFormat('d F Y') : 'Tangkapan Segar Terbaru' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>❄️</span>
                            <span><strong>Standar Rantai Dingin:</strong> Terjaga suhu 0-4°C dengan Ice Box Steril</span>
                        </div>
                    </div>

                    {{-- Deskripsi Produk --}}
                    <p class="text-xs lg:text-sm text-slate-600 leading-relaxed pt-1">
                        {{ $product->description ?? 'Ikan segar pilihan langsung dari nelayan tradisional binaan HasilLaut. Diproses melalui standar Quality Control (QC) ketat, dibersihkan, dan dikemas higienis untuk menjaga kesegaran dagingnya hingga sampai di dapur Anda.' }}
                    </p>
                </div>

                {{-- Form Aksi: Masukkan Keranjang & Beli Sekarang (Sejajar sesuai Figma) --}}
                <form action="{{ route('cart.store') }}" method="POST" class="pt-3 border-t border-slate-100 space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                    @if($flashSaleItem)
                        <input type="hidden" name="flash_sale_item_id" value="{{ $flashSaleItem->id_flash_sale_item }}">
                    @endif

                    {{-- Input Kuantitas (kg) --}}
                    <div class="flex items-center gap-4">
                        <label for="quantity" class="text-xs lg:text-sm font-semibold text-slate-700">Jumlah (kg):</label>
                        <div class="inline-flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white">
                            <button type="button"
                                    onclick="let q=document.getElementById('quantity'); if(q.value>1) q.value=parseInt(q.value)-1;"
                                    class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold">-</button>
                            <input type="number"
                                   id="quantity"
                                   name="quantity"
                                   value="1"
                                   min="1"
                                   max="{{ max(1, floor($availableStock)) }}"
                                   class="w-14 text-center text-sm font-bold text-slate-800 border-x border-slate-200 py-1.5 focus:outline-none">
                            <button type="button"
                                    onclick="let q=document.getElementById('quantity'); let max=parseInt(q.max); if(parseInt(q.value)<max) q.value=parseInt(q.value)+1;"
                                    class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold">+</button>
                        </div>
                    </div>

                    {{-- 2 Tombol Sejajar --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="submit"
                                dusk="btn-add-cart"
                                {{ $availableStock <= 0 ? 'disabled' : '' }}
                                class="w-full border border-[#013137] text-[#013137] font-semibold py-3 px-4 rounded-xl text-xs lg:text-sm hover:bg-teal-50 transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            🛒 Masukkan Keranjang
                        </button>

                        <button type="submit"
                                dusk="btn-buy-now"
                                name="redirect_to"
                                value="checkout"
                                {{ $availableStock <= 0 ? 'disabled' : '' }}
                                class="...">
                            ⚡ Beli Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Section Ulasan & Penilaian Pembeli --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 lg:p-8 shadow-sm space-y-5">
        <h2 class="font-bold text-lg text-slate-900">Ulasan & Penilaian Pembeli</h2>

        <div class="divide-y divide-slate-100 text-xs lg:text-sm">
            <div class="pb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-slate-800">Siti Rahmawati</span>
                    <span class="text-slate-400 text-xs">14 Oktober 2026</span>
                </div>
                <div class="text-amber-500 text-xs mb-1.5">★★★★★</div>
                <p class="text-slate-600 leading-relaxed">
                    Ikannya bener-bener masih segar banget kayak baru ditangkap dari laut! Pengemasan es batunya juga rapi, daging ikannya manis waktu dimasak kuah kuning. Mantap HasilLaut!
                </p>
            </div>

            <div class="pt-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-slate-800">Budi Santoso</span>
                    <span class="text-slate-400 text-xs">12 Oktober 2026</span>
                </div>
                <div class="text-amber-500 text-xs mb-1.5">★★★★☆</div>
                <p class="text-slate-600 leading-relaxed">
                    Pengiriman cepat pakai kurir khusus Hub, sampai rumah masih dingin dan segar. Next pasti order lagi buat stok mingguan.
                </p>
            </div>
        </div>
    </div>

    {{-- Section Pilihan Produk Lainnya --}}
    <section class="space-y-4">
        <h3 class="text-lg font-bold text-slate-900">Pilihan Produk Lainnya</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($relatedProducts as $rel)
                @php
                    $relImage = $rel->image
                        ? asset('storage/' . $rel->image)
                        : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80';
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <a href="{{ route('pembeli.produk.detail', $rel->id_product) }}" class="block overflow-hidden rounded-xl mb-3">
                            <img src="{{ $relImage }}"
                                 alt="{{ $rel->name }}"
                                 class="w-full h-36 object-cover hover:scale-105 transition duration-300">
                        </a>
                        <a href="{{ route('pembeli.produk.detail', $rel->id_product) }}"
                           class="font-bold text-sm text-slate-800 hover:text-[#013137] line-clamp-1 mb-1 block">
                            {{ $rel->name }}
                        </a>
                        <p class="text-xs lg:text-sm font-extrabold text-[#013137] mb-3">
                            Rp {{ number_format($rel->price, 0, ',', '.') }}
                            <span class="text-[11px] font-normal text-slate-500">/ kg</span>
                        </p>
                    </div>

                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $rel->id_product }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                                {{ $rel->available_stock <= 0 ? 'disabled' : '' }}
                                class="w-full bg-[#245A5E] text-white font-semibold text-xs py-2.5 rounded-xl hover:bg-[#013137] transition disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ $rel->available_stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection
