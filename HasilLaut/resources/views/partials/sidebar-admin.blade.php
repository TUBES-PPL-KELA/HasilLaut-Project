<aside class="w-64 shrink-0 min-h-screen bg-midnight-teal text-pale-ice flex flex-col shadow-lg">
    <div class="p-6 text-2xl font-black text-white tracking-widest border-b border-ocean-teal/50">
        HASILLAUT
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/product') }}"
           class="block px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-ocean-teal text-white shadow-sm' : 'text-pale-ice hover:bg-dusty-turq hover:text-white' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.product.index') }}"
           class="block px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.product.*') ? 'bg-ocean-teal text-white shadow-sm' : 'text-pale-ice hover:bg-dusty-turq hover:text-white' }}">
            Produk
        </a>

        <a href="{{ route('admin.stok.penyesuaian') }}"
           class="block px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.stok.*') ? 'bg-ocean-teal text-white shadow-sm' : 'text-pale-ice hover:bg-dusty-turq hover:text-white' }}">
            Stok Gudang
        </a>

        <a href="{{ Route::has('admin.flashsale.index') ? route('admin.flashsale.index') : '#' }}"
           class="block px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.flashsale.*') ? 'bg-ocean-teal text-white shadow-sm' : 'text-pale-ice hover:bg-dusty-turq hover:text-white' }}">
            Flash Sale
        </a>

        <a href="{{ Route::has('admin.pesanan.index') ? route('admin.pesanan.index') : '#' }}"
           class="block px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-ocean-teal text-white shadow-sm' : 'text-pale-ice hover:bg-dusty-turq hover:text-white' }}">
            Pesanan Masuk
        </a>
    </nav>
</aside>