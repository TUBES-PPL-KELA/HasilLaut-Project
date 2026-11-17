<!-- Top Bar Khusus Mobile & Tablet (Muncul di layar < lg) -->
<header class="lg:hidden sticky top-0 z-30 flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3">
    <div class="flex items-center gap-3">
        <button type="button" id="mobileSidebarOpenBtn" aria-label="Buka Menu"
            class="p-2 rounded-xl text-gray-600 hover:text-teal-800 hover:bg-gray-100 transition focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="h-8 object-contain">
    </div>
    <span class="text-xs font-bold text-teal-800 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-100">
        Admin Hub
    </span>
</header>

<!-- Backdrop Overlay Mobile -->
<div id="adminSidebarBackdrop"
    class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-[1px] hidden lg:hidden transition-opacity duration-300">
</div>

<!-- Sidebar Utama -->
<aside id="adminSidebar" data-collapsed="false"
    class="fixed inset-y-0 left-0 z-50 w-[280px] -translate-x-full lg:static lg:translate-x-0 bg-white border-r border-gray-200 flex flex-col h-screen flex-shrink-0 transition-all duration-300 ease-in-out group/sidebar">

    <!-- Header Logo & Tombol Tutup/Buka -->
    <div class="h-20 px-5 flex items-center justify-between border-b border-gray-100 flex-shrink-0">
        <a href="{{ \Illuminate\Support\Facades\Route::has('admin.product.index') ? route('admin.product.index') : '#' }}"
            class="flex items-center gap-2 overflow-hidden sidebar-logo-wrap">
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="h-10 object-contain transition-all duration-300">
        </a>

        <!-- Tombol Collapse Desktop -->
        <button type="button" id="desktopSidebarToggleBtn" title="Tutup / Buka Sidebar"
            class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-teal-800 hover:bg-teal-50 border border-transparent hover:border-teal-100 transition">
            <svg id="desktopToggleIcon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Tombol Close Mobile Drawer -->
        <button type="button" id="mobileSidebarCloseBtn" aria-label="Tutup Menu"
            class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-6 space-y-7">

        <!-- Grup: Beranda -->
        <div class="space-y-1">
            <h3 class="sidebar-group-title px-3 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2.5 whitespace-nowrap">
                Beranda
            </h3>
            <a href="#" title="Analitik & Grafik"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                <span class="sidebar-label truncate">Analitik & Grafik</span>
            </a>
        </div>

        <!-- Grup: Gudang & Katalog -->
        <div class="space-y-1">
            <h3 class="sidebar-group-title px-3 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2.5 whitespace-nowrap">
                Gudang & Katalog
            </h3>

            @php
                $isProductActive = request()->routeIs('admin.product.*');
                $isFlashSaleActive = request()->routeIs('admin.flash_sale.*');
                $isDefectActive = request()->routeIs('admin.stok.*');
            @endphp

            <!-- Stok Gudang -->
            <a href="{{ \Illuminate\Support\Facades\Route::has('admin.product.index') ? route('admin.product.index') : '#' }}"
                title="Stok Gudang"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition {{ $isProductActive ? 'font-bold text-teal-800 bg-teal-50 border border-teal-100' : 'font-medium text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ $isProductActive ? 'text-teal-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="sidebar-label truncate">Stok Gudang</span>
            </a>

            <!-- Flash Sale & Diskon -->
            <a href="{{ \Illuminate\Support\Facades\Route::has('admin.flash_sale.index') ? route('admin.flash_sale.index') : '#' }}"
                title="Flash Sale & Diskon"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition {{ $isFlashSaleActive ? 'font-bold text-teal-800 bg-teal-50 border border-teal-100' : 'font-medium text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ $isFlashSaleActive ? 'text-teal-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span class="sidebar-label truncate">Flash Sale & Diskon</span>
            </a>

            <!-- Penyesuaian Defect / Rusak -->
            <a href="{{ \Illuminate\Support\Facades\Route::has('admin.stok.penyesuaian') ? route('admin.stok.penyesuaian') : '#' }}"
                title="Penyesuaian Defect / Rusak"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition {{ $isDefectActive ? 'font-bold text-teal-800 bg-teal-50 border border-teal-100' : 'font-medium text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ $isDefectActive ? 'text-teal-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="sidebar-label truncate">Penyesuaian Defect / Rusak</span>
            </a>
        </div>

        <!-- Grup: Transaksi & Armada -->
        <div class="space-y-1">
            <h3 class="sidebar-group-title px-3 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2.5 whitespace-nowrap">
                Transaksi & Armada
            </h3>
            <a href="#" title="Pesanan Masuk"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="sidebar-label truncate">Pesanan Masuk</span>
            </a>
        </div>
    </nav>

    <!-- Kartu Profil Admin -->
    <div class="p-3 border-t border-gray-100 flex-shrink-0">
        <div id="sidebarProfileCard" class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-2xl border border-gray-200 transition-all">
            <img src="https://ui-avatars.com/api/?name=Andi+M&background=0D8ABC&color=fff" alt="User"
                class="w-10 h-10 rounded-full shadow-sm flex-shrink-0">
            <div class="sidebar-label min-w-0">
                <p class="text-sm font-bold text-gray-900 leading-tight truncate">Andi M. (Admin)</p>
                <p class="text-[11px] font-medium text-gray-500 truncate">Hub Pangandaran</p>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminSidebarBackdrop');
        const mobileOpenBtn = document.getElementById('mobileSidebarOpenBtn');
        const mobileCloseBtn = document.getElementById('mobileSidebarCloseBtn');
        const desktopToggleBtn = document.getElementById('desktopSidebarToggleBtn');
        const desktopToggleIcon = document.getElementById('desktopToggleIcon');

        const labels = sidebar.querySelectorAll('.sidebar-label');
        const groupTitles = sidebar.querySelectorAll('.sidebar-group-title');
        const links = sidebar.querySelectorAll('.sidebar-link');
        const logoWrap = sidebar.querySelector('.sidebar-logo-wrap');
        const profileCard = document.getElementById('sidebarProfileCard');

        // 1. Fungsi Collapse / Expand di Desktop (>= 1024px)
        function applyDesktopState(collapsed) {
            sidebar.dataset.collapsed = collapsed ? 'true' : 'false';

            if (collapsed) {
                sidebar.classList.remove('lg:w-[280px]');
                sidebar.classList.add('lg:w-[84px]');
                desktopToggleIcon?.classList.add('rotate-180');
                logoWrap?.classList.add('lg:hidden');
                profileCard?.classList.add('lg:justify-center', 'lg:p-1.5');
                labels.forEach(el => el.classList.add('lg:hidden'));
                groupTitles.forEach(el => el.classList.add('lg:hidden'));
                links.forEach(el => el.classList.add('lg:justify-center', 'lg:px-0'));
            } else {
                sidebar.classList.remove('lg:w-[84px]');
                sidebar.classList.add('lg:w-[280px]');
                desktopToggleIcon?.classList.remove('rotate-180');
                logoWrap?.classList.remove('lg:hidden');
                profileCard?.classList.remove('lg:justify-center', 'lg:p-1.5');
                labels.forEach(el => el.classList.remove('lg:hidden'));
                groupTitles.forEach(el => el.classList.remove('lg:hidden'));
                links.forEach(el => el.classList.remove('lg:justify-center', 'lg:px-0'));
            }
        }

        // Pulihkan status terakhir dari localStorage
        const savedState = localStorage.getItem('hasillaut_admin_sidebar_collapsed') === 'true';
        applyDesktopState(savedState);

        desktopToggleBtn?.addEventListener('click', function () {
            const nextState = sidebar.dataset.collapsed !== 'true';
            applyDesktopState(nextState);
            localStorage.setItem('hasillaut_admin_sidebar_collapsed', nextState);
        });

        // 2. Fungsi Drawer Buka / Tutup di Mobile (< 1024px)
        function openMobileSidebar() {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
        }

        function closeMobileSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        mobileOpenBtn?.addEventListener('click', openMobileSidebar);
        mobileCloseBtn?.addEventListener('click', closeMobileSidebar);
        backdrop?.addEventListener('click', closeMobileSidebar);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeMobileSidebar();
            }
        });
    });
</script>