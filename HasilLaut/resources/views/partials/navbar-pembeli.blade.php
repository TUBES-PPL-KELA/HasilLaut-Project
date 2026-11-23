@php
    $navUserId = auth()->id() ?? 1;
    $navCart = \App\Models\Cart::where('users_id_users', $navUserId)->first();
    $cartBadgeCount = $navCart ? $navCart->cartItems()->sum('quantity') : 0;
@endphp

<nav class="bg-white shadow-sm sticky top-0 z-20 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ url('/') }}" class="text-2xl font-black text-midnight-teal">
                HASILLAUT
            </a>
            <div class="flex items-center space-x-6 text-dusty-turq font-semibold">
                <a href="{{ url('/') }}" class="hover:text-midnight-teal transition">Beranda</a>
                <a href="{{ route('pembeli.katalog') }}" class="hover:text-midnight-teal transition">Katalog</a>
                <!-- Ikon Keranjang dengan Badge Dinamis -->
                <a href="{{ route('pembeli.cart') }}" class="relative p-2 hover:bg-pale-ice rounded-full transition" title="Keranjang Belanja">
                    <svg class="w-6 h-6 text-ocean-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if($cartBadgeCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ (int) $cartBadgeCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>