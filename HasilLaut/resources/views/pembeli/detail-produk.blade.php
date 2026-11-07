<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar Mobile & Desktop -->
    <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <a href="{{ route('pembeli.cart') }}" class="text-sm font-bold text-[#013137] flex items-center gap-2">
            &larr; Kembali
        </a>
        <span class="text-xs lg:text-sm text-slate-500 font-medium">Detail Produk</span>
        <a href="#" class="relative p-2 bg-slate-100 rounded-full hover:bg-slate-200 transition">
            🛒 <span class="absolute -top-1 -right-1 bg-teal-800 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">3</span>
        </a>
    </header>

    <!-- Main Content Container (Responsive Grid: Mobile 1 kolom, Desktop 2 kolom) -->
    <main class="max-w-6xl mx-auto w-full p-4 lg:p-8 flex-1 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- Kolom Kiri: Gambar Produk -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm p-2">
                <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1534482421-64566f976cfa?auto=format&fit=crop&w=600&q=80' }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-72 lg:h-96 object-cover rounded-xl">
            </div>
            <!-- Thumbnail kecil -->
            <div class="flex gap-3 overflow-x-auto pb-2">
                <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1534482421-64566f976cfa?auto=format&fit=crop&w=150&q=80' }}" class="w-20 h-20 object-cover rounded-xl border-2 border-[#013137] cursor-pointer">
               
            </div>
        </div>

        <!-- Kolom Kanan: Informasi Produk & Aksi -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <span class="bg-teal-50 text-[#013137] text-xs font-semibold px-3 py-1 rounded-full inline-block">
                    🐟 Ikan Segar Laut
                </span>
                
                <h1 class="text-xl lg:text-2xl font-bold text-slate-900">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 text-xs lg:text-sm text-slate-500">
                    <span class="text-amber-500 font-bold">⭐ 4.8</span> 
                    <span>(124 Ulasan)</span> 
                    <span>&bull;</span>
                    <span class="font-medium text-slate-700">Stok Tersedia: {{ $product->stock ?? '45' }} kg</span>
                </div>

                <div class="text-2xl lg:text-3xl font-extrabold text-[#013137]">
                    Rp {{ number_format($product->price, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">/ kg</span>
                </div>

                <!-- Info Hub & Rantai Dingin -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-2 text-xs lg:text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <span>📍</span> <span><strong>Asal Hub Pesisir:</strong> Hasil Laut Hub Pangandaran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>📅</span> <span><strong>Tanggal Masuk Gudang:</strong> 15 Oktober 2026 (Tangkapan Pagi)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>❄️</span> <span><strong>Standar Rantai Dingin:</strong> Terjaga suhu 0-4°C dengan Ice Box Steril</span>
                    </div>
                </div>

                <p class="text-xs lg:text-sm text-slate-600 leading-relaxed">
                    {{ $product->description ?? 'Ikan segar pilihan langsung dari nelayan tradisional binaan HasilLaut Hub Pangandaran. Diproses melalui standar Quality Control (QC) ketat, dibersihkan, dan dikemas higienis untuk menjaga kesegaran dagingnya hingga sampai di dapur Anda.' }}
                </p>
            </div>

            <!-- Ulasan & Penilaian Pembeli -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h2 class="font-bold text-base text-slate-800 pb-2 border-b border-slate-100">Ulasan & Penilaian Pembeli</h2>
                
                <div class="space-y-4 text-xs lg:text-sm">
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-800">Siti Rahmawati</span>
                            <span class="text-slate-400 text-[11px]">14 Oktober 2026</span>
                        </div>
                        <div class="text-amber-500 text-xs mb-1">★★★★★</div>
                        <p class="text-slate-600">Ikannya bener-bener masih segar banget kayak baru ditangkap dari laut! Pengemasan es batunya juga rapi, daging ikannya manis waktu dimasak kuah kuning. Mantap HasilLaut!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Rekomendasi Produk Lainnya -->
    <section class="max-w-6xl mx-auto w-full px-4 lg:px-8 py-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Pilihan Produk Lainnya</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $rel)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col justify-between">
                <img src="{{ $rel->image ?? 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=300&q=80' }}" class="w-full h-32 object-cover rounded-xl mb-3">
                <h4 class="font-semibold text-xs lg:text-sm text-slate-800 mb-1 line-clamp-1">{{ $rel->name }}</h4>
                <p class="text-xs font-bold text-[#013137] mb-3">Rp {{ number_format($rel->price, 0, ',', '.') }} / kg</p>
                <a href="{{ route('pembeli.produk.detail', $rel->id_product) }}" class="bg-[#013137] text-white text-center text-xs py-2 rounded-xl hover:bg-teal-900 transition">
                    Lihat Detail
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Footer Action Bar (Fixed Bottom untuk Mobile & Desktop) -->
    <div class="bg-white border-t border-slate-200 p-4 sticky bottom-0 z-50 shadow-lg">
        <div class="max-w-6xl mx-auto flex gap-4">
            <a href="#" class="w-1/2 border border-[#013137] text-[#013137] font-medium py-3 rounded-xl text-center text-xs lg:text-sm hover:bg-slate-50 transition">
                🛒 Masukkan Keranjang
            </a>
            <a href="#" class="w-1/2 bg-[#013137] text-white font-medium py-3 rounded-xl text-center text-xs lg:text-sm hover:bg-teal-900 transition shadow-sm">
                Beli Sekarang &rarr;
            </a>
        </div>
    </div>

</body>
</html>