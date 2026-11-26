@php
    $navUserId = auth()->id();
    $navCart = $navUserId ? \App\Models\Cart::where('users_id_users', $navUserId)->first() : null;
    $cartBadgeCount = $navCart ? (int) $navCart->cartItems()->sum('quantity') : 0;
@endphp

<nav class="bg-white/95 backdrop-blur-md shadow-xs sticky top-0 z-20 border-b border-[#C1D9DE]/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Kiri: Tombol Menu Mobile + Brand -->
            <div class="flex items-center gap-3">
                <button type="button" id="openPembeliSidebarBtn" aria-label="Buka Menu"
                        class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl border border-[#C1D9DE] text-[#013137] hover:bg-[#C1D9DE]/30 transition cursor-pointer">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <a href="{{ route('welcome') }}" class="flex items-center gap-2 text-xl font-black tracking-tight text-[#013137]">
                    <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="h-7 w-7 object-contain lg:hidden">
                    <span>HASILLAUT</span>
                </a>
            </div>

            <!-- Kanan: Tautan Cepat & Keranjang -->
            <div class="flex items-center space-x-3 sm:space-x-6 text-sm font-semibold text-[#297376]">
                <a href="{{ route('welcome') }}" class="hidden sm:inline-block hover:text-[#013137] transition">Beranda</a>
                <a href="{{ route('pembeli.katalog') }}" class="hover:text-[#013137] transition">Katalog</a>

                <!-- Ikon Keranjang dengan Badge Dinamis -->
                <a href="{{ route('pembeli.cart') }}" class="relative p-2 hover:bg-[#C1D9DE]/40 rounded-full transition" aria-label="Keranjang Belanja">
                    <svg class="w-6 h-6 text-[#215E68]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>

                    @if($cartBadgeCount > 0)
                        <span id="cart-badge-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $cartBadgeCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>