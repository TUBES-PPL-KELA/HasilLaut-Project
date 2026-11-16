<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HasilLaut - Ikan Segar Langsung dari Nelayan</title>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- 1. KOLOM KIRI: Sidebar Pembeli -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 h-screen">
        
        <!-- BAGIAN LOGO -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-center">
            <a href="#" class="flex items-center gap-2">
                <!-- Ubah path asset('images/logo-hasillaut.png') sesuai nama file logo kamu -->
                <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HASILLAUT Logo" style="max-width: 140px; height: auto;">
            </a>
        </div>
        
        <!-- Menu Navigasi Sidebar -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
                <i class="bi bi-house-door text-lg"></i> Beranda
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
                <i class="bi bi-grid text-lg"></i> Katalog Ikan
            </a>
            <a href="{{ route('pembeli.checkout.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-emerald-50 text-[#1b4d3e]">
                <i class="bi bi-box-seam text-lg"></i> Pesanan Saya
            </a>
            <a href="{{ route('pembeli.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
                <i class="bi bi-gear text-lg"></i> Pengaturan
            </a>
        </nav>

        <!-- Bagian Profil User di Bawah Sidebar -->
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-[#1b4d3e] text-white flex items-center justify-center font-bold text-xs">
                    HS
                </div>
                <div class="overflow-hidden">
                    <h6 class="font-bold text-xs text-gray-900 truncate">Hilya Shafwa</h6>
                    <span class="text-[10px] text-gray-500 truncate block">Pembeli B2C</span>
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
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-[#1b4d3e]">Beranda</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-[#1b4d3e]">Katalog</a>
                <a href="{{ route('pembeli.cart') }}" class="relative text-gray-600 hover:text-[#1b4d3e]">
                    <i class="bi bi-cart3 text-xl"></i>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">3</span>
                </a>
            </div>
        </header>

        <!-- Area Konten Utama (Scrollable) -->
        <main class="flex-1 overflow-y-auto bg-slate-50 p-8">
            <div class="max-w-[1400px] mx-auto w-full">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-8 text-center text-xs text-gray-500 flex-shrink-0">
            &copy; 2026 HasilLaut. Seluruh hak cipta dilindungi.
        </footer>
    </div>

</body>
</html>