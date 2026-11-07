@extends('layouts.pembeli')

@section('title', 'Beranda Pembeli | HasilLaut')

@section('content')
    @php
        $flashProducts = [
            ['name' => 'Ikan Kakap Merah Segar', 'hub' => 'Hub Pangandaran', 'arrived' => 'Masuk 2 jam lalu', 'oldPrice' => 'Rp 90.000', 'price' => 'Rp 67.500', 'discount' => '-25%', 'category' => 'ikan', 'image' => 'https://images.unsplash.com/photo-1753537358141-a72fbfea529c?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Udang Windu Laut', 'hub' => 'Hub Jepara', 'arrived' => 'Masuk 3 jam lalu', 'oldPrice' => 'Rp 120.000', 'price' => 'Rp 84.000', 'discount' => '-30%', 'category' => 'udang', 'image' => 'https://images.unsplash.com/photo-1548587468-971ebe4c8c3b?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Tuna Loin Fillet Segar', 'hub' => 'Hub Makassar', 'arrived' => 'Masuk 1 jam lalu', 'oldPrice' => 'Rp 110.000', 'price' => 'Rp 93.500', 'discount' => '-15%', 'category' => 'frozen', 'image' => 'https://images.unsplash.com/photo-1682457569891-53e4f6ef9271?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Ikan Kerapu Bintang', 'hub' => 'Hub Tual', 'arrived' => 'Masuk 4 jam lalu', 'oldPrice' => 'Rp 95.000', 'price' => 'Rp 76.000', 'discount' => '-20%', 'category' => 'today', 'image' => 'https://images.unsplash.com/photo-1762509255765-bbd5903bba00?auto=format&fit=crop&w=600&q=80'],
        ];

        $latestProducts = [
            ['name' => 'Gurame Pesisir', 'hub' => 'Hub Jepara', 'supplier' => 'Penyuplai Pak Dedi', 'price' => 'Rp 55.000', 'category' => 'ikan', 'image' => 'https://images.unsplash.com/photo-1763048890512-2c2d5105e776?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Cumi-Cumi Sero Segar', 'hub' => 'Hub Pangandaran', 'supplier' => 'Penyuplai Pak Donang', 'price' => 'Rp 78.000', 'category' => 'today', 'image' => 'https://images.unsplash.com/photo-1758448786233-2051ecd150c8?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Kepiting Bakau Super', 'hub' => 'Hub Makassar', 'supplier' => 'Penyuplai Pak Andi', 'price' => 'Rp 135.000', 'category' => 'udang', 'image' => 'https://images.unsplash.com/photo-1509415173911-37ff7a1aa29c?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Ikan Tenggiri Batang', 'hub' => 'Hub Bengkulu', 'supplier' => 'Penyuplai Pak Wahid', 'price' => 'Rp 88.000', 'category' => 'frozen', 'image' => 'https://images.unsplash.com/photo-1722798485956-1950607bc177?auto=format&fit=crop&w=600&q=80'],
        ];
    @endphp

    <section aria-labelledby="hero-title" class="relative isolate overflow-hidden rounded-2xl bg-midnight-teal px-5 py-6 text-white sm:px-8 sm:py-7 lg:px-9">
        <div class="absolute -right-12 -top-20 size-64 rounded-full border border-white/10" aria-hidden="true"></div>
        <div class="relative grid items-center gap-6 md:grid-cols-[1fr_auto]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-rose-500 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-white">
                    <span class="size-1.5 rounded-full bg-white"></span> Flash Sale Tangkapan Pagi
                </span>
                <h1 id="hero-title" class="mt-3 max-w-2xl text-2xl font-bold leading-tight sm:text-3xl">Diskon Hingga 35% Ikan Kakap &amp;<br class="hidden sm:block"> Udang Gerung Segar</h1>
                <p class="mt-2 max-w-xl text-xs leading-5 text-white/65">Tangkapan pilihan dari Hub Pesisir Pangandaran &amp; Jepara. Jaminan kesegaran rantai dingin tiba di depan Anda.</p>
                <div class="mt-4 flex items-center gap-2 text-[10px] text-white/75">
                    <span>Berakhir dalam:</span>
                    <span data-countdown-part class="grid h-8 min-w-9 place-items-center rounded-md bg-white/15 px-2 font-bold text-white">02</span><span>:</span>
                    <span data-countdown-part class="grid h-8 min-w-9 place-items-center rounded-md bg-white/15 px-2 font-bold text-white">45</span><span>:</span>
                    <span data-countdown-part class="grid h-8 min-w-9 place-items-center rounded-md bg-white/15 px-2 font-bold text-white">12</span>
                </div>
            </div>
            <img src="https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&amp;fit=crop&amp;w=520&amp;q=85" alt="Hidangan hasil laut segar" class="h-32 w-full rounded-xl object-cover shadow-lg sm:h-36 md:w-56">
        </div>
    </section>
    

    <nav aria-label="Filter kategori produk" class="mt-4 flex gap-2 overflow-x-auto pb-1">
        <button type="button" data-category-filter="all" aria-pressed="true" class="shrink-0 rounded-full bg-ocean-teal px-4 py-2 text-[11px] font-semibold text-white">Semua Ikan</button>
        <button type="button" data-category-filter="udang" aria-pressed="false" class="shrink-0 rounded-full border border-slate-200 bg-white px-4 py-2 text-[11px] font-medium text-slate-600 hover:border-ocean-teal hover:text-ocean-teal">Udang &amp; Lobster</button>
        <button type="button" data-category-filter="frozen" aria-pressed="false" class="shrink-0 rounded-full border border-slate-200 bg-white px-4 py-2 text-[11px] font-medium text-slate-600 hover:border-ocean-teal hover:text-ocean-teal">Ikan Beku/Loin</button>
        <button type="button" data-category-filter="today" aria-pressed="false" class="shrink-0 rounded-full border border-slate-200 bg-white px-4 py-2 text-[11px] font-medium text-slate-600 hover:border-ocean-teal hover:text-ocean-teal">Tangkapan Hari Ini</button>
    </nav>

    <section id="flash-sale" class="mt-6" aria-labelledby="flash-title">
        <div class="mb-3 flex items-center justify-between">
            <h2 id="flash-title" class="text-lg font-bold text-midnight-teal">Promo Flash Sale Hari Ini</h2>
            <a href="#katalog" class="text-[11px] font-semibold text-ocean-teal hover:text-midnight-teal">Lihat Semua <span aria-hidden="true">›</span></a>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($flashProducts as $product)
                <article data-product-category="{{ $product['category'] }}" class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative h-36 overflow-hidden bg-slate-100">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-105">
                        <span class="absolute left-2 top-2 rounded-full bg-rose-500 px-2 py-1 text-[10px] font-bold text-white">{{ $product['discount'] }}</span>
                        <span class="absolute bottom-2 left-2 rounded-full bg-midnight-teal/90 px-2 py-1 text-[9px] font-medium text-white">⌖ {{ $product['hub'] }}</span>
                    </div>
                    <div class="p-3">
                        <h3 class="truncate text-xs font-bold text-midnight-teal">{{ $product['name'] }}</h3>
                        <p class="mt-1 text-[10px] text-slate-500">{{ $product['arrived'] }}</p>
                        <p class="mt-2 text-[10px] text-slate-400 line-through">{{ $product['oldPrice'] }}</p>
                        <p class="text-sm font-extrabold text-ocean-teal">{{ $product['price'] }}<span class="text-[10px] font-medium text-slate-500"> / kg</span></p>
                        <button type="button" data-add-to-cart class="mt-2.5 flex h-9 w-full items-center justify-center gap-2 rounded-lg bg-ocean-teal px-3 text-[10px] font-semibold text-white transition hover:bg-midnight-teal focus:outline-none focus:ring-2 focus:ring-ocean-teal focus:ring-offset-2">
                            <span aria-hidden="true">+</span> Tambah ke Keranjang
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section id="katalog" class="mt-7" aria-labelledby="catalog-title">
        <div class="mb-3 flex items-center justify-between">
            <h2 id="catalog-title" class="text-lg font-bold text-midnight-teal">Katalog Hasil Laut Terbaru</h2>
            <a href="#katalog" class="text-[11px] font-semibold text-ocean-teal hover:text-midnight-teal">Lihat Semua <span aria-hidden="true">›</span></a>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($latestProducts as $product)
                <article data-product-category="{{ $product['category'] }}" class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative h-36 overflow-hidden bg-slate-100">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-105">
                        <span class="absolute bottom-2 left-2 rounded-full bg-midnight-teal/90 px-2 py-1 text-[9px] font-medium text-white">⌖ {{ $product['hub'] }}</span>
                    </div>
                    <div class="p-3">
                        <h3 class="truncate text-xs font-bold text-midnight-teal">{{ $product['name'] }}</h3>
                        <p class="mt-1 text-[10px] text-slate-500">♙ {{ $product['supplier'] }}</p>
                        <p class="mt-2 text-sm font-extrabold text-ocean-teal">{{ $product['price'] }}<span class="text-[10px] font-medium text-slate-500"> / kg</span></p>
                        <button type="button" data-add-to-cart class="mt-2.5 flex h-9 w-full items-center justify-center gap-2 rounded-lg bg-ocean-teal px-3 text-[10px] font-semibold text-white transition hover:bg-midnight-teal focus:outline-none focus:ring-2 focus:ring-ocean-teal focus:ring-offset-2">
                            <span aria-hidden="true">+</span> Tambah ke Keranjang
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const categoryButtons = document.querySelectorAll('[data-category-filter]');
        const productCards = document.querySelectorAll('[data-product-category]');

        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const selectedCategory = button.dataset.categoryFilter;

                categoryButtons.forEach((categoryButton) => {
                    const isSelected = categoryButton === button;
                    categoryButton.setAttribute('aria-pressed', String(isSelected));
                    categoryButton.classList.toggle('bg-ocean-teal', isSelected);
                    categoryButton.classList.toggle('text-white', isSelected);
                    categoryButton.classList.toggle('border', !isSelected);
                    categoryButton.classList.toggle('border-slate-200', !isSelected);
                    categoryButton.classList.toggle('bg-white', !isSelected);
                    categoryButton.classList.toggle('text-slate-600', !isSelected);
                });

                productCards.forEach((card) => {
                    card.hidden = selectedCategory !== 'all' && card.dataset.productCategory !== selectedCategory;
                });
            });
        });

        let cartCount = 0;
        document.querySelectorAll('[data-add-to-cart]').forEach((button) => {
            button.addEventListener('click', () => {
                cartCount += 1;
                document.querySelector('[data-cart-count]').textContent = cartCount;
                button.innerHTML = '<span aria-hidden="true">✓</span> Ditambahkan';
                window.setTimeout(() => {
                    button.innerHTML = '<span aria-hidden="true">+</span> Tambah ke Keranjang';
                }, 1200);
            });
        });

        const countdownParts = document.querySelectorAll('[data-countdown-part]');
        let remainingSeconds = (2 * 60 * 60) + (45 * 60) + 12;

        window.setInterval(() => {
            if (remainingSeconds > 0) remainingSeconds -= 1;
            const hours = Math.floor(remainingSeconds / 3600);
            const minutes = Math.floor((remainingSeconds % 3600) / 60);
            const seconds = remainingSeconds % 60;
            [hours, minutes, seconds].forEach((value, index) => {
                countdownParts[index].textContent = String(value).padStart(2, '0');
            });
        }, 1000);
    </script>
@endpush