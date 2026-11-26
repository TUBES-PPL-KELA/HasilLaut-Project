@php
    $user = Auth::user();
    $userName =$user->name ?? 'Pembeli HasilLaut';
    $userUsername =$user->username ?? 'pembeli';
    $initials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $navCart = $user ? \App\Models\Cart::where('users_id_users', $user->id_users)->first() : null;
    $sidebarCartCount = $navCart ? (int) $navCart->cartItems()->sum('quantity') : 0;
@endphp

<!-- Backdrop Overlay Khusus Mobile (< lg) -->
<div id="pembeliSidebarBackdrop"
     class="fixed inset-0 z-40 bg-[#013137]/50 backdrop-blur-[1px] hidden lg:hidden transition-opacity duration-300">
</div>

<!-- Sidebar Utama Pembeli -->
<aside id="pembeliSidebar"
       class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full lg:translate-x-0 bg-white border-r border-[#C1D9DE]/70 flex flex-col justify-between h-screen transition-transform duration-300 ease-in-out">

    <!-- Bagian Atas: Logo & Menu Navigasi -->
    <div class="flex-1 overflow-y-auto">
        <!-- Header Logo -->
        <div class="relative h-24 px-5 flex items-center justify-center border-b border-[#C1D9DE]/50">
            <a href="{{ route('welcome') }}" class="flex items-center justify-center">
                <img src="{{ asset('images/hasillaut-logo.png') }}" alt="Logo HasilLaut" class="h-16 w-auto object-contain">
            </a>

            <!-- Tombol Tutup Drawer di HP -->
            <button type="button" id="closePembeliSidebarBtn" aria-label="Tutup Menu"
                    class="lg:hidden absolute right-4 flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Navigasi Utama -->
        <div class="px-4 py-5">
            <p class="px-3 mb-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5C9396]">
                Menu Belanja
            </p>

            <nav class="space-y-1.5">
                <!-- Beranda -->
                <a href="{{ route('welcome') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('welcome') ? 'bg-[#013137] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#C1D9DE]/30 hover:text-[#013137] font-medium' }}">
                    <i class="bi bi-house-door text-base {{ request()->routeIs('welcome') ? 'text-[#C1D9DE]' : 'text-[#5C9396]' }}"></i>
                    <span>Beranda</span>
                </a>

                <!-- Katalog Ikan -->
                <a href="{{ route('pembeli.katalog') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pembeli.katalog*') || request()->routeIs('pembeli.produk*') ? 'bg-[#013137] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#C1D9DE]/30 hover:text-[#013137] font-medium' }}">
                    <i class="bi bi-shop text-base {{ request()->routeIs('pembeli.katalog*') || request()->routeIs('pembeli.produk*') ? 'text-[#C1D9DE]' : 'text-[#5C9396]' }}"></i>
                    <span>Katalog Ikan</span>
                </a>

                <!-- Keranjang Belanja -->
                <a href="{{ route('pembeli.cart') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pembeli.cart*') ? 'bg-[#013137] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#C1D9DE]/30 hover:text-[#013137] font-medium' }}">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-cart3 text-base {{ request()->routeIs('pembeli.cart*') ? 'text-[#C1D9DE]' : 'text-[#5C9396]' }}"></i>
                        <span>Keranjang</span>
                    </span>
                    @if($sidebarCartCount > 0)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ request()->routeIs('pembeli.cart*') ? 'bg-[#5C9396] text-white' : 'bg-[#C1D9DE]/60 text-[#013137]' }}">
                            {{ $sidebarCartCount }}
                        </span>
                    @endif
                </a>

                <!-- Pesanan & Checkout -->
                <a href="{{ route('pembeli.checkout.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pembeli.checkout*') || request()->routeIs('pembeli.pesanan*') ? 'bg-[#013137] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#C1D9DE]/30 hover:text-[#013137] font-medium' }}">
                    <i class="bi bi-receipt text-base {{ request()->routeIs('pembeli.checkout*') || request()->routeIs('pembeli.pesanan*') ? 'text-[#C1D9DE]' : 'text-[#5C9396]' }}"></i>
                    <span>Pesanan Saya</span>
                </a>

                <!-- Pengaturan Profil & Alamat -->
                <a href="{{ route('pembeli.profile.edit') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pembeli.profile*') ? 'bg-[#013137] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#C1D9DE]/30 hover:text-[#013137] font-medium' }}">
                    <i class="bi bi-gear text-base {{ request()->routeIs('pembeli.profile*') ? 'text-[#C1D9DE]' : 'text-[#5C9396]' }}"></i>
                    <span>Pengaturan Akun</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Bagian Bawah: Kartu Profil Akun & Tombol Logout -->
    <div class="p-4 border-t border-[#C1D9DE]/50 bg-[#f8fbfb] space-y-2.5">
        <a href="{{ route('pembeli.profile.edit') }}"
           class="flex items-center gap-3 p-2.5 bg-white border border-[#C1D9DE]/80 rounded-2xl hover:border-[#215E68] transition group">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#013137] to-[#297376] text-xs font-extrabold text-white shadow-sm">
                {{ $initials ?: 'PB' }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-extrabold text-[#013137] truncate group-hover:text-[#215E68]">
                    {{ $userName }}
                </p>
                <p class="text-[11px] font-medium text-[#5C9396] truncate">
                    &#64;{{ $userUsername }} &bull; Pembeli
                </p>
            </div>
            <i class="bi bi-chevron-right text-xs text-[#5C9396] group-hover:translate-x-0.5 transition-transform"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white hover:bg-red-50 text-red-600 py-2.5 text-xs font-bold transition cursor-pointer">
                <i class="bi bi-box-arrow-right text-sm"></i>
                <span>Keluar dari Akun</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('pembeliSidebar');
        const backdrop = document.getElementById('pembeliSidebarBackdrop');
        const openBtn = document.getElementById('openPembeliSidebarBtn');
        const closeBtn = document.getElementById('closePembeliSidebarBtn');

        function openDrawer() {
            sidebar?.classList.remove('-translate-x-full');
            backdrop?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
        }

        function closeDrawer() {
            sidebar?.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openBtn?.addEventListener('click', openDrawer);
        closeBtn?.addEventListener('click', closeDrawer);
        backdrop?.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                closeDrawer();
            }
        });
    });
</script>