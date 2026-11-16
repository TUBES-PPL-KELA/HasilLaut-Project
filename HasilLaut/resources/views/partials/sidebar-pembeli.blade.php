<!-- resources/views/partials/sidebar-pembeli.blade.php -->
<aside class="w-64 h-screen bg-white border-r border-gray-200 flex flex-col justify-between fixed top-0 left-0">
    
    <!-- Bagian Atas: Logo & Menu -->
    <div>
        <!-- Logo HasilLaut -->
        <div class="flex items-center justify-center h-24 mt-4 border-b border-gray-100">
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="Logo HasilLaut" class="h-12 w-auto object-contain">
        </div>

        <!-- Navigasi Menu -->
        <nav class="mt-6 px-4 space-y-2">
            <!-- Beranda -->
            <a href="{{ url('/') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('welcome') || request()->is('/') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-800 font-medium' }}">
                <i class="fas fa-home w-5 h-5 {{ request()->routeIs('welcome') || request()->is('/') ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600' }} mr-3"></i>
                <span>Beranda</span>
            </a>

            <!-- Katalog Ikan -->
            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('pembeli.katalog*') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-800 font-medium' }}">
                <i class="fas fa-store w-5 h-5 {{ request()->routeIs('pembeli.katalog*') ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600' }} mr-3"></i>
                <span>Katalog Ikan</span>
            </a>

            <!-- Pesanan Saya -->
            <a href="{{ route('pembeli.checkout.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('pembeli.checkout*') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-800 font-medium' }}">
                <i class="fas fa-receipt w-5 h-5 {{ request()->routeIs('pembeli.checkout*') ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600' }} mr-3"></i>
                <span>Pesanan Saya</span>
            </a>

            <!-- Pengaturan (Mendeteksi semua rute yang mengandung kata 'profile') -->
            <a href="{{ route('pembeli.profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('*profile*') || request()->is('*profile*') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-800 font-medium' }}">
                <i class="fas fa-cog w-5 h-5 {{ request()->routeIs('*profile*') || request()->is('*profile*') ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600' }} mr-3"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
    </div>

    <!-- Bagian Bawah: Profil Pengguna -->
    <div class="p-4 mb-4">
        <a href="{{ route('pembeli.profile.edit') }}" class="flex items-center p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-100 transition">
            <!-- Foto Profil -->
            <img src="{{ asset('images/avatar-hilya.jpg') }}" alt="Hilya Shafwa" class="h-10 w-10 rounded-full object-cover border border-gray-200">
            
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-bold text-gray-800 truncate">Hilya Shafwa</p>
                <p class="text-xs text-gray-500 truncate">Pembeli B2C</p>
            </div>
        </a>
    </div>
</aside>