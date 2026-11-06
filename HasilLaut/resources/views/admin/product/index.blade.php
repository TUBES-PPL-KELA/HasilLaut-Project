<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etalase Master Katalog & Stok Gudang Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: { 700: '#0f766e', 800: '#115e59', 900: '#134e4a' },
                        darkgreen: '#022c22',
                        btnprimary: '#164e63',
                        btnhover: '#083344'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">

    <!-- SIDEBAR KIRI -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col h-full shrink-0 shadow-sm z-20">
        <!-- Logo Area -->
        <div class="h-24 flex items-center px-8 border-b border-gray-50 mt-2">
            <!-- Panggilan gambar logo diperbaiki ukurannya agar besar -->
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut Logo" class="h-14 w-auto object-contain">
        </div>

        <!-- Menu Navigasi Sidebar -->
        <div class="flex-1 overflow-y-auto py-8 px-5 space-y-8">
            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Beranda</p>
                <a href="#" class="flex items-center px-3 py-3 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition duration-200">
                    <svg class="w-5 h-5 mr-3.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <span class="text-sm font-semibold">Analitik & Grafik</span>
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Gudang & Katalog</p>
                <a href="{{ route('admin.product.index') }}" class="flex items-center px-3 py-3 bg-teal-50 text-teal-700 rounded-lg transition duration-200 mb-1.5 shadow-sm">
                    <svg class="w-5 h-5 mr-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm font-bold">Stok Gudang</span>
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-bold text-teal-800 uppercase tracking-widest mb-4 opacity-80">Transaksi & Armada</p>
                <a href="#" class="flex items-center px-3 py-3 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition duration-200">
                    <svg class="w-5 h-5 mr-3.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-sm font-semibold">Pesanan Masuk</span>
                </a>
            </div>
        </div>

        <div class="p-6 border-t border-gray-50 bg-white">
            <div class="flex items-center bg-gray-50 p-3 rounded-xl border border-gray-100 cursor-pointer">
                <img src="https://ui-avatars.com/api/?name=Andi+M&background=312e81&color=fff&rounded=true&bold=true" alt="Admin Avatar" class="h-10 w-10 rounded-full shadow-sm">
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-800 leading-tight">Andi M. (Admin)</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Hub Pangandaran</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 relative z-10">
        
        <div class="px-10 py-6 border-b border-gray-200 flex justify-between items-center bg-white sticky top-0 z-20 shadow-sm">
            <div class="relative w-2/5">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Cari nama produk ikan, udang, atau etalase pesisir..." 
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-full text-sm font-medium text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-700 transition">
            </div>
            
            <a href="{{ route('admin.product.create') }}" 
                class="px-6 py-3 bg-btnprimary hover:bg-btnhover text-white text-sm font-bold rounded-lg shadow-md transition-all flex items-center">
                <div class="bg-white/20 rounded-full p-1 mr-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                Tambah Hasil Laut Baru
            </a>
        </div>

        <div class="flex-1 overflow-y-auto p-10">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Etalase Master Katalog & Stok Gudang Hub</h2>
                <p class="text-sm text-gray-500 font-medium mt-2">Kelola inventaris gudang, perbarui batch tangkapan nelayan, dan atur ketersediaan etalase B2C.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-xl text-sm font-semibold flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-teal-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex space-x-3 mb-10 overflow-x-auto pb-2 scrollbar-hide">
                <button class="px-5 py-2.5 bg-darkgreen text-white text-sm font-bold rounded-lg shadow-md whitespace-nowrap flex items-center">
                    <svg class="w-4 h-4 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Semua Produk ({{ $products->count() }})
                </button>
                @foreach($categories as $category)
                <button class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold rounded-lg whitespace-nowrap transition shadow-sm">
                    {{ $category->name }} ({{ $category->products_count ?? 0 }})
                </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($products as $item)
                    @php $totalStock = $item->inventoryBatches->sum('stock'); @endphp
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition-all hover:shadow-lg hover:-translate-y-1">
                        <div class="relative h-48 bg-gray-100">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50 text-sm">Tidak ada foto</div>
                            @endif
                            <div class="absolute top-0 left-0 right-0 h-16 bg-gradient-to-b from-black/50 to-transparent"></div>
                            <div class="absolute top-4 left-4 bg-slate-900/70 backdrop-blur-md text-white text-xs font-bold tracking-wide px-3 py-1.5 rounded-full flex items-center shadow-sm">
                                📍 Hub Pangandaran
                            </div>
                            @if($totalStock <= 5)
                                <div class="absolute top-4 right-4 bg-red-600 text-white text-xs font-extrabold tracking-wide px-3 py-1.5 rounded-full shadow-md flex items-center">
                                    ⚠️ Krisis Stok
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg font-extrabold text-gray-900 leading-tight mb-3 line-clamp-2 h-14">{{ $item->name }}</h3>
                            <div class="flex items-center text-sm mb-4 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="text-gray-500 font-semibold flex items-center">📦 Stok Gudang:</span>
                                <span class="ml-2 font-extrabold {{ $totalStock <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $totalStock }} kg @if($totalStock <= 5) <span class="text-xs uppercase ml-1">(Kritis)</span> @endif
                                </span>
                            </div>
                            <div class="text-xl font-black text-teal-700 mb-6">
                                Rp {{ number_format($item->price, 0, ',', '.') }} <span class="text-sm font-bold text-gray-400">/ kg</span>
                            </div>
                            <div class="mt-auto space-y-3">
                                <button class="w-full py-3 bg-teal-50 hover:bg-teal-100 text-teal-700 text-sm font-extrabold rounded-xl transition border border-teal-200 flex items-center justify-center">
                                    + Tambah Batch Baru
                                </button>
                                <div class="flex space-x-3">
                                    <button class="flex-1 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition shadow-sm">Edit</button>
                                    <button class="flex-1 py-2.5 bg-red-50 border border-red-100 hover:bg-red-100 text-red-600 text-sm font-bold rounded-xl transition shadow-sm">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white border-2 border-gray-200 rounded-3xl border-dashed">
                        <div class="bg-gray-100 p-5 rounded-full mb-5">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada produk di etalase</h3>
                        <p class="text-sm text-gray-500 mb-6 font-medium">Mulai kelola inventaris dengan menambahkan hasil laut pertama Anda.</p>
                        <a href="{{ route('admin.product.create') }}" class="px-6 py-3 bg-white border border-gray-200 text-teal-700 font-bold text-sm rounded-xl shadow-sm hover:bg-gray-50 transition">
                            + Tambah Produk Pertama
                        </a>
                    </div>
                @endforelse
            </div>
            <div class="h-24"></div> 
        </div>
    </main>

</body>
</html>