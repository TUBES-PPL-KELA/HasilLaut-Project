<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HasilLaut | Pembeli')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-56 flex-col border-r border-slate-100 bg-white lg:flex">
        <a href="/" class="flex h-16 items-center border-b border-slate-100 px-6" aria-label="HasilLaut beranda">
            <img src="{{ asset('images/logo.png') }}" alt="HasilLaut" class="h-10 w-auto object-contain">
        </a>
        <nav aria-label="Navigasi utama" class="space-y-1 px-3 py-5 text-sm font-medium">
            <a href="/" class="flex items-center gap-3 rounded-lg bg-ocean-teal/10 px-3 py-2.5 text-ocean-teal">Beranda</a>
            <a href="#katalog" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-midnight-teal">Katalog Ikan</a>
            <a href="#flash-sale" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-midnight-teal">Pesanan Saya</a>
            <a href="#footer" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-midnight-teal">Pengaturan</a>
        </nav>
        <div class="mt-auto border-t border-slate-100 p-4">
            <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-3">
                <span class="grid size-9 place-items-center rounded-full bg-ocean-teal/15 text-xs font-bold text-ocean-teal">HS</span>
                <div class="min-w-0">
                    <p class="truncate text-xs font-semibold text-midnight-teal">Hiya Shafwa</p>
                    <p class="text-[11px] text-slate-500">Pembeli B2C</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-56">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-100 bg-white/95 px-4 backdrop-blur sm:px-7">
            <a href="/" class="flex h-10 shrink-0 items-center lg:hidden" aria-label="HasilLaut beranda">
                <img src="{{ asset('images/logo.png') }}" alt="HasilLaut" class="h-10 w-auto max-w-[140px] object-contain">
            </a>
            <label class="relative ml-auto flex w-full max-w-xl items-center">
                <span class="sr-only">Cari produk</span>
                <svg class="absolute left-3 size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" placeholder="Cari ikan segar, udang, kepiting, atau lokasi hub pesisir..." class="h-10 w-full rounded-full border border-slate-200 bg-slate-50 pl-10 pr-4 text-xs outline-none transition placeholder:text-slate-400 focus:border-ocean-teal focus:bg-white focus:ring-2 focus:ring-ocean-teal/10">
            </label>
            <button type="button" class="relative grid size-9 shrink-0 place-items-center rounded-full border border-slate-200 text-midnight-teal" aria-label="Keranjang">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h8.5a2 2 0 0 0 1.9-1.4L21 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                <span data-cart-count class="absolute -right-1 -top-1 grid size-4 place-items-center rounded-full bg-ocean-teal text-[9px] font-bold text-white">0</span>
            </button>
            <button type="button" class="hidden size-9 shrink-0 place-items-center rounded-full border border-slate-200 text-midnight-teal sm:grid" aria-label="Notifikasi">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
            </button>
        </header>

        <main class="mx-auto w-full max-w-[1440px] px-4 py-5 sm:px-7 lg:px-8">
            @yield('content')
        </main>

        <footer id="footer" class="mt-8 border-t border-slate-200 bg-white px-4 py-8 sm:px-7 lg:px-8">
            <div class="mx-auto grid max-w-[1440px] gap-7 text-xs text-slate-500 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <img src="{{ asset('images/logo.png') }}" alt="HasilLaut" loading="lazy" class="h-10 w-auto object-contain">
                    <p class="mt-2 max-w-sm leading-5">Platform B2C penyedia hasil laut segar langsung dari nelayan pesisir Indonesia dengan jaminan kualitas rantai dingin.</p>
                </div>
                <div>
                    <p class="font-semibold text-midnight-teal">Navigasi</p>
                    <a href="#flash-sale" class="mt-3 block hover:text-ocean-teal">Promo Hari Ini</a>
                    <a href="#katalog" class="mt-2 block hover:text-ocean-teal">Katalog Ikan</a>
                </div>
                <div>
                    <p class="font-semibold text-midnight-teal">Layanan</p>
                    <a href="#footer" class="mt-3 block hover:text-ocean-teal">Lacak Pengiriman</a>
                    <a href="#footer" class="mt-2 block hover:text-ocean-teal">Bantuan & FAQ</a>
                </div>
            </div>
            <p class="mx-auto mt-7 max-w-[1440px] border-t border-slate-100 pt-4 text-[11px] text-slate-400">© 2026 HASILLAUT. Seluruh hak cipta dilindungi.</p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>