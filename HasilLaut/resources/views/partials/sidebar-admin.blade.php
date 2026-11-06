<aside class="w-[280px] bg-white border-r border-gray-200 flex flex-col h-full flex-shrink-0">
    <!-- Logo -->
    <div class="p-6 flex items-center justify-center border-b border-gray-100">
        <!-- Ganti src dengan logo asli jika ada -->
        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="h-12 object-contain">
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8">
        
        <!-- Grup: Beranda -->
        <div>
            <h3 class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Beranda</h3>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Analitik & Grafik
            </a>
        </div>

        <!-- Grup: Gudang & Katalog -->
        <div class="space-y-1">
            <h3 class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Gudang & Katalog</h3>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Stok Gudang
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Flash Sale & Diskon
            </a>
            <!-- Menu Aktif (Sesuai Figma) -->
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-teal-800 bg-teal-50 rounded-xl transition border border-teal-100">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Penyesuaian Defect / Rusak
            </a>
        </div>

        <!-- Grup: Transaksi & Armada -->
        <div>
            <h3 class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Transaksi & Armada</h3>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Pesanan Masuk
            </a>
        </div>
    </nav>

    <!-- Kartu Profil Dummy (Sesuai Figma) -->
    <div class="p-5 border-t border-gray-100">
        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl border border-gray-200">
            <!-- Gambar avatar dummy -->
            <img src="https://ui-avatars.com/api/?name=Andi+M&background=0D8ABC&color=fff" alt="User" class="w-10 h-10 rounded-full shadow-sm">
            <div>
                <p class="text-sm font-bold text-gray-900 leading-tight">Andi M. (Admin)</p>
                <p class="text-[11px] font-medium text-gray-500">Hub Pangandaran</p>
            </div>
        </div>
    </div>
</aside>