<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HasilLaut | Hasil Laut Segar Indonesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-9 max-w-6xl items-center justify-between gap-4 px-4 sm:px-8">
            <a href="{{ route('pembeli.landing') }}" aria-label="HasilLaut beranda" class="flex shrink-0 items-center">
                <img src="{{ asset('images/logo.png') }}" alt="HASILLAUT Logo" class="h-7 w-auto max-w-14 object-contain">
                <span class="ml-2 text-[11px] font-bold text-midnight-teal">HASILLAUT</span>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button" aria-label="Notifikasi" class="grid size-7 place-items-center rounded-full border border-slate-200 text-midnight-teal transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-ocean-teal/30">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
                </button>
                <button type="button" aria-label="Pengaturan" class="grid size-7 place-items-center rounded-full border border-slate-200 text-midnight-teal transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-ocean-teal/30">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m19.4 15 .1.1a1.8 1.8 0 1 1-2.5 2.5l-.1-.1a1.8 1.8 0 0 0-3 .9v.2a1.8 1.8 0 1 1-3.6 0v-.2a1.8 1.8 0 0 0-3-.9l-.1.1a1.8 1.8 0 1 1-2.5-2.5l.1-.1a1.8 1.8 0 0 0-.9-3H3.7a1.8 1.8 0 1 1 0-3.6h.2a1.8 1.8 0 0 0 .9-3l-.1-.1a1.8 1.8 0 1 1 2.5-2.5l.1.1a1.8 1.8 0 0 0 3-.9v-.2a1.8 1.8 0 1 1 3.6 0v.2a1.8 1.8 0 0 0 3 .9l.1-.1a1.8 1.8 0 1 1 2.5 2.5l-.1.1a1.8 1.8 0 0 0 .9 3h.2a1.8 1.8 0 1 1 0 3.6h-.2a1.8 1.8 0 0 0-.9 3Z"/></svg>
                </button>
                <span class="inline-flex h-6 items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 text-[9px] font-semibold text-emerald-800">
                    <span class="size-1 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    Pembeli
                </span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 pb-9 pt-3 sm:px-8 sm:pt-4">
        <section aria-labelledby="hero-title" class="relative isolate flex min-h-[240px] overflow-hidden rounded-xl bg-midnight-teal sm:min-h-[240px]">
            <img src="https://images.unsplash.com/photo-1616690571998-85f9c9d89a81?auto=format&amp;fit=crop&amp;w=1800&amp;q=85" alt="Penyelam menjelajahi laut dan terumbu karang" class="absolute inset-0 z-0 size-full object-cover object-center" fetchpriority="high">
            <div class="absolute inset-0 z-10 bg-gradient-to-r from-midnight-teal/90 via-midnight-teal/60 to-midnight-teal/20" aria-hidden="true"></div>
            <div class="relative z-20 flex w-full items-center px-6 py-8 sm:px-9">
                <div class="max-w-xl text-white">
                    <p class="mb-2 text-[9px] font-semibold uppercase tracking-[0.12em] text-teal-100">Segar dari pesisir Indonesia</p>
                    <h1 id="hero-title" class="max-w-sm text-2xl font-bold leading-tight">Ikan Segar Langsung dari Nelayan Pesisir ke Dapur Anda</h1>
                    <p class="mt-2 max-w-md text-[10px] leading-4 text-white/85 sm:text-[11px]">Nikmati hasil laut berkualitas sambil mendukung pemberdayaan nelayan tradisional dan kesejahteraan komunitas pesisir Indonesia.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="#produk" class="inline-flex h-8 items-center justify-center gap-2 rounded-md bg-ocean-teal px-3.5 text-[10px] font-semibold text-white transition hover:bg-white hover:text-midnight-teal focus:outline-none focus:ring-2 focus:ring-white/80">
                            Beli Ikan Segar
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </a>
                        <a href="#produk" class="inline-flex h-8 items-center justify-center rounded-md border border-white/60 bg-white/5 px-3.5 text-[10px] font-semibold text-white transition hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white/80">Jelajahi Katalog</a>
                    </div>
                </div>
            </div>
        </section>

        
        <section aria-label="Statistik HasilLaut" class="mt-4 grid gap-3 sm:grid-cols-3">
            <article class="flex min-h-28 gap-3 rounded-lg border border-slate-200 border-l-4 border-l-ocean-teal bg-white p-3">
                <div class="min-w-0">
                    <span class="grid size-7 place-items-center rounded-md bg-ocean-teal/10 text-ocean-teal" aria-hidden="true">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7l-8-4Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                    </span>
                    <p class="mt-1 text-xl font-bold leading-none text-midnight-teal">12.5+ Ton</p>
                    <h2 class="mt-1 text-[10px] font-semibold text-ocean-teal">Total Ikan Terjual</h2>
                    <p class="mt-1 text-[8px] leading-3 text-slate-500">Tangkapan segar dikirim langsung dari hub ke konsumen.</p>
                </div>
            </article>
            <article class="flex min-h-28 gap-3 rounded-lg border border-slate-200 border-l-4 border-l-ocean-teal bg-white p-3">
                <div class="min-w-0">
                    <span class="grid size-7 place-items-center rounded-md bg-ocean-teal/10 text-ocean-teal" aria-hidden="true">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m6-9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm6-6.5a3.5 3.5 0 0 1 0 6.8m1 3.2a3.5 3.5 0 0 1 3 3.5V20"/></svg>
                    </span>
                    <p class="mt-1 text-xl font-bold leading-none text-midnight-teal">450+</p>
                    <h2 class="mt-1 text-[10px] font-semibold text-ocean-teal">Nelayan Terhubung</h2>
                    <p class="mt-1 text-[8px] leading-3 text-slate-500">Nelayan pesisir mendapat harga jual yang adil.</p>
                </div>
            </article>
            <article class="flex min-h-28 gap-3 rounded-lg border border-slate-200 border-l-4 border-l-ocean-teal bg-white p-3">
                <div class="min-w-0">
                    <span class="grid size-7 place-items-center rounded-md bg-ocean-teal/10 text-ocean-teal" aria-hidden="true">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.7 2.8 8 7 10 4.2-2 7-5.3 7-10V6l-7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/></svg>
                    </span>
                    <p class="mt-1 text-xl font-bold leading-none text-midnight-teal">100%</p>
                    <h2 class="mt-1 text-[10px] font-semibold text-ocean-teal">Jaminan Rantai Cold Chain</h2>
                    <p class="mt-1 text-[8px] leading-3 text-slate-500">Kesegaran terjaga dari hub hingga sampai ke rumah.</p>
                </div>
            </article>
        </section>

        @php
            $featuredProducts = [
                ['name' => 'Ikan Kakap Merah Segar', 'hub' => 'Hub Pangandaran', 'price' => 'Rp 85.000', 'fisher' => 'Pak Pakih', 'badge' => 'Baru Masuk Hub', 'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=900&q=85'],
                ['name' => 'Udang Windu Laut', 'hub' => 'Hub Jepara', 'price' => 'Rp 115.000', 'fisher' => 'Pak Kristiyanto', 'badge' => 'Kualitas Super', 'image' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&w=900&q=85'],
                ['name' => 'Tuna Loin Segar', 'hub' => 'Hub Makassar', 'price' => 'Rp 95.000', 'fisher' => 'Pak Andi', 'badge' => 'Tangkapan Pagi', 'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=900&q=85'],
            ];
        @endphp

        <section id="produk" aria-labelledby="products-title" class="mt-6">
            <div class="mb-1.5">
                <h2 id="products-title" class="text-base font-bold leading-5 text-midnight-teal">Produk Unggulan Hari Ini</h2>
                <p class="mt-0.5 text-[10px] leading-3 text-slate-500">Tangkapan segar terbaru yang masuk melalui hub pesisir.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                @foreach ($featuredProducts as $product)
                    <article class="group overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="relative h-24 overflow-hidden bg-slate-100 sm:h-24">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-2 top-2 rounded-full bg-ocean-teal px-2.5 py-1 text-[9px] font-semibold text-white">{{ $product['badge'] }}</span>
                        </div>
                        <div class="p-1.5">
                            <h3 class="text-[11px] font-bold leading-4 text-midnight-teal">{{ $product['name'] }}</h3>
                            <p class="flex items-center gap-1 text-[9px] leading-3 text-slate-500">
                                <svg class="size-3 text-ocean-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                {{ $product['hub'] }}
                            </p>
                            <p class="mt-0.5 text-[13px] font-bold leading-4 text-ocean-teal">{{ $product['price'] }} <span class="text-[9px] font-medium text-slate-500">/ kg</span></p>
                            <div class="mt-1 flex items-center justify-between gap-2 border-t border-slate-100 pt-1.5">
                                <p class="flex min-w-0 items-center gap-1.5 truncate text-[9px] text-slate-500">
                                    <span class="grid size-5 shrink-0 place-items-center rounded-full bg-emerald-50 text-[8px] font-bold text-emerald-800">{{ substr($product['fisher'], 4, 1) }}</span>
                                    {{ $product['fisher'] }}
                                </p>
                                <a href="mailto:info@hasillaut.id?subject={{ rawurlencode('Pesanan ' . $product['name']) }}" class="inline-flex h-6 shrink-0 items-center justify-center rounded-md bg-midnight-teal px-2.5 text-[9px] font-semibold text-white transition hover:bg-ocean-teal focus:outline-none focus:ring-2 focus:ring-ocean-teal focus:ring-offset-2">Beli Sekarang</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-7 sm:px-6 lg:px-8">
            <div class="grid gap-5 sm:grid-cols-4">
                <div>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="HASILLAUT Logo" loading="lazy" class="h-7 w-auto max-w-14 object-contain">
                        <span class="text-[11px] font-bold text-midnight-teal">HASILLAUT</span>
                    </div>
                    <p class="mt-2 max-w-xs text-[10px] leading-4 text-slate-500">Platform B2C penyedia hasil laut segar langsung dari nelayan pesisir Indonesia dengan jaminan kualitas rantai dingin.</p>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-midnight-teal">Navigasi</h2>
                    <ul class="mt-2 space-y-1.5 text-[10px] text-slate-500">
                        <li><a href="#tentang" class="hover:text-ocean-teal">Tentang Kami</a></li>
                        <li><a href="#produk" class="hover:text-ocean-teal">Katalog Ikan</a></li>
                        <li><a href="#produk" class="hover:text-ocean-teal">Lokasi Hub</a></li>
                        <li><a href="mailto:info@hasillaut.id?subject=Kemitraan%20Nelayan" class="hover:text-ocean-teal">Kemitraan Nelayan</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-midnight-teal">Layanan</h2>
                    <ul class="mt-2 space-y-1.5 text-[10px] text-slate-500">
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Lacak Pengiriman</a></li>
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Metode Pembayaran &amp; COD</a></li>
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Garansi Kesegaran</a></li>
                        <li><a href="mailto:info@hasillaut.id" class="hover:text-ocean-teal">Bantuan &amp; FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-midnight-teal">Legal</h2>
                    <ul class="mt-2 space-y-1.5 text-[10px] text-slate-500">
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Kebijakan Privasi</a></li>
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Syarat &amp; Ketentuan</a></li>
                        <li><a href="#footer-contact" class="hover:text-ocean-teal">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>
            <div id="footer-contact" class="mt-6 flex flex-col gap-2 border-t border-slate-100 pt-3 text-[9px] text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>© 2026 HASILLAUT. Seluruh hak cipta dilindungi.</p>
                <div class="flex flex-wrap gap-x-4 gap-y-1">
                    <a href="mailto:info@hasillaut.id" class="hover:text-ocean-teal">info@hasillaut.id</a>
                    <a href="tel:+6281234567890" class="hover:text-ocean-teal">+62 812-3456-7890</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>