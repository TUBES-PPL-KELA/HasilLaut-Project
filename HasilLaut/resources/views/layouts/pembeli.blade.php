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

    <!-- 2. KOLOM KANAN: Berisi Navbar Atas & Konten Utama -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Navbar Atas -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between flex-shrink-0 z-10">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Area Pelanggan</span>
            </div>
            <form action="{{ route('pembeli.pencarian-ikan') }}" method="GET" class="relative ml-auto flex w-full max-w-xl items-center">
                <label for="header-search" class="sr-only">Cari ikan</label>
                <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama ikan, hub, atau kategori..." class="w-full rounded-lg border border-gray-200 py-2 pl-4 pr-11 text-sm focus:border-emerald-700 focus:outline-none focus:ring-1 focus:ring-emerald-700">
                <button type="submit" aria-label="Cari" class="absolute right-3 text-gray-500 hover:text-[#1b4d3e]">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <span class="sr-only">Cari</span>
                </button>
            </form>
            <div class="ml-6 flex items-center gap-6">
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-[#1b4d3e]">Beranda</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-[#1b4d3e]">Katalog</a>
                <a href="{{ route('pembeli.cart') }}" class="relative text-gray-600 hover:text-[#1b4d3e]">
                    <i class="bi bi-cart3 text-xl"></i>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">3</span>
                </a>
            </div>
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