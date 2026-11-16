@if($flashSaleItems->isNotEmpty())
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0B3B40] via-[#134E55] to-[#1D6B73] p-6 sm:p-8 lg:p-10 text-white shadow-md mb-8">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
        <!-- Konten Kiri -->
        <div class="max-w-xl">
            <span class="inline-flex items-center gap-1.5 bg-red-500 text-white text-[11px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full mb-4 shadow-sm">
                <i class="bi bi-lightning-fill"></i> Flash Sale Rantai Dingin
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                Diskon Hingga {{ $maxDiscount }}% Hasil Laut Segar Pilihan
            </h1>
            <p class="text-xs sm:text-sm text-teal-100/90 mt-3 leading-relaxed">
                Komoditas kualitas terbaik langsung dari penyimpanan rantai dingin kami. Harga spesial berlaku selama persediaan stok batch promo masih tersedia.
            </p>

            <!-- Pengganti Countdown: Info Kuota Stok & Tombol Aksi -->
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <button type="button"
                    onclick="document.getElementById('promo-flash-sale').scrollIntoView({ behavior: 'smooth' })"
                    class="bg-white text-[#0B3B40] hover:bg-teal-50 text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    Lihat Produk Promo <i class="bi bi-arrow-down-short text-base"></i>
                </button>

                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-3.5 py-2 rounded-xl text-xs font-semibold text-teal-50">
                    <i class="bi bi-snow2 text-teal-200"></i>
                    <span>Sisa Kuota Promo: <strong>{{ $totalPromoStock }} kg</strong></span>
                </div>
            </div>
        </div>

        <!-- Gambar Samping -->
        <div class="w-full lg:w-80 shrink-0">
            @php
                $heroProduct = $flashSaleItems->first()->inventoryBatch->product ?? null;
                $heroImage = $heroProduct && $heroProduct->image
                    ? (\Illuminate\Support\Str::startsWith($heroProduct->image, 'http') ? $heroProduct->image : asset('storage/' . $heroProduct->image))
                    : 'https://images.unsplash.com/photo-1534482498418-fef13c323f46?auto=format&fit=crop&w=600&q=80';
            @endphp
            <img src="{{ $heroImage }}" alt="Promo Hasil Laut" class="w-full h-44 sm:h-52 object-cover rounded-2xl border-2 border-white/20 shadow-lg">
        </div>
    </div>
</div>
@endif