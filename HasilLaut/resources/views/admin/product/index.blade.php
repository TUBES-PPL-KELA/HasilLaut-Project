<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etalase Master Katalog - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: { 50: '#f0fdfa', 700: '#0f766e', 800: '#115e59', 900: '#134e4a' },
                        btnprimary: '#164e63',
                        btnhover: '#083344',
                        darkgreen: '#022c22',
                        batchbtn: '#e6f3f4'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-gray-800">

    <!-- SIDEBAR -->
    <aside class="w-[260px] bg-white border-r border-gray-100 flex flex-col h-full shrink-0 shadow-[2px_0_10px_rgba(0,0,0,0.02)] z-20">
        <!-- Logo -->
        <div class="h-24 flex items-center px-8 mt-2">
            <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut Logo" class="h-12 w-auto object-contain">
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-5 space-y-6">
            <!-- Beranda -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Beranda</p>
                <a href="#" class="flex items-center px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition">
                    <svg class="w-5 h-5 mr-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <span class="text-[13px] font-semibold">Analitik & Grafik</span>
                </a>
            </div>

            <!-- Gudang & Katalog -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Gudang & Katalog</p>
                <a href="{{ route('admin.product.index') }}" class="flex items-center px-3 py-2.5 bg-teal-50 text-teal-800 rounded-lg transition mb-1">
                    <svg class="w-5 h-5 mr-3 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-[13px] font-bold">Stok Gudang</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition">
                    <svg class="w-5 h-5 mr-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-[13px] font-semibold">Flash Sale & Diskon</span>
                </a>
            </div>

            <!-- Transaksi & Armada -->
            <div>
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Transaksi & Armada</p>
                <a href="#" class="flex items-center px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-teal-800 rounded-lg group transition">
                    <svg class="w-5 h-5 mr-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-[13px] font-semibold">Pesanan Masuk</span>
                </a>
            </div>
        </div>

        <!-- Profil Admin -->
        <div class="p-6">
            <div class="flex items-center bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                <img src="https://ui-avatars.com/api/?name=Andi+M&background=312e81&color=fff&rounded=true&bold=true" alt="Admin Avatar" class="h-9 w-9 rounded-full shadow-sm">
                <div class="ml-3">
                    <p class="text-[13px] font-bold text-gray-800 leading-tight">Andi M. (Admin)</p>
                    <p class="text-[11px] text-gray-400 font-semibold mt-0.5">Hub Pangandaran</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN KONTEN -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 z-10">
        
        <!-- Topbar -->
        <div class="px-8 py-5 flex justify-between items-center bg-white sticky top-0 z-20 border-b border-gray-100">
            <div class="relative w-[400px]">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Cari nama produk ikan, udang, atau etalase pesisir..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-[13px] font-medium text-gray-600 focus:outline-none focus:ring-1 focus:ring-teal-700">
            </div>
            <a href="{{ route('admin.product.create') }}" class="px-5 py-2.5 bg-btnprimary hover:bg-btnhover text-white text-[13px] font-bold rounded-lg shadow-md transition flex items-center">
                <div class="bg-white/20 rounded-full p-0.5 mr-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                Tambah Hasil Laut Baru
            </a>
        </div>

        <!-- Konten Scroll -->
        <div class="flex-1 overflow-y-auto p-8">
            
            <div class="mb-6">
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Etalase Master Katalog & Stok Gudang Hub</h2>
                <p class="text-[13px] text-gray-500 font-medium mt-1">Kelola inventaris gudang, perbarui batch tangkapan nelayan, dan atur ketersediaan etalase B2C.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-teal-50 border border-teal-100 text-teal-800 rounded-xl text-sm font-semibold flex items-center shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabs Kategori dengan ID dan data-category -->
            <div class="flex space-x-3 mb-8 overflow-x-auto pb-2 scrollbar-hide" id="category-filters">
                <button data-category="all" class="filter-btn px-4 py-2 bg-darkgreen text-white text-[13px] font-bold rounded-lg shadow-sm whitespace-nowrap flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Semua Produk ({{ $products->count() }})
                </button>
                @foreach($categories as $category)
                <button data-category="{{ $category->id_categories }}" class="filter-btn px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-[13px] font-semibold rounded-lg whitespace-nowrap shadow-sm flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    {{ $category->name }} ({{ $category->products_count ?? 0 }})
                </button>
                @endforeach
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-grid">
                @forelse($products as $item)
                    @php 
                        $totalStock = $item->inventoryBatches->sum('stock'); 
                    @endphp

                    <!-- Card dengan penambahan class product-card dan data-category -->
                    <div class="product-card bg-white rounded-[16px] shadow-sm border border-gray-200 overflow-hidden flex flex-col" data-category="{{ $item->categories_id_categories }}">
                        
                        <!-- Header Gambar & Badge -->
                        <div class="relative h-[180px] bg-gray-100">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tanpa Foto</div>
                            @endif
                            
                            <!-- Gradasi Hitam di Atas Gambar -->
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-transparent"></div>
                            
                            <!-- Badge Lokasi -->
                            <div class="absolute top-3 left-3 bg-[#111827]/80 backdrop-blur-sm text-white text-[10px] font-bold tracking-wide px-2.5 py-1 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                Hub Pangandaran
                            </div>
                            
                            <!-- Badge Krisis -->
                            @if($totalStock <= 5)
                                <div class="absolute top-3 right-3 bg-[#dc2626] text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    Krisis Stok
                                </div>
                            @endif
                        </div>

                        <!-- Body Card -->
                        <div class="p-5 flex-1 flex flex-col">
                            
                            <!-- Judul -->
                            <h3 class="text-[14px] font-extrabold text-gray-900 leading-tight mb-2 h-[38px] line-clamp-2">
                                {{ $item->name }}
                            </h3>
                            
                            <!-- Info Stok -->
                            <div class="flex items-center text-[12px] mb-3">
                                <span class="text-gray-400 font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    Stok Gudang:
                                </span>
                                <span class="ml-1 font-bold {{ $totalStock <= 5 ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ $totalStock }} kg @if($totalStock <= 5) <span class="text-[10px] uppercase">(Kritis)</span> @endif
                                </span>
                            </div>
                            
                            <!-- Harga -->
                            <div class="text-[16px] font-black text-teal-800 mb-5">
                                Rp {{ number_format($item->price, 0, ',', '.') }} 
                                <span class="text-[12px] font-semibold text-gray-400">/ kg</span>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-auto space-y-2.5">
                                <!-- Tombol Tambah Batch -->
                                <button class="w-full py-2 bg-batchbtn hover:bg-[#d4ecee] text-teal-800 text-[12px] font-bold rounded-lg transition flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Batch Baru
                                </button>
                                
                                <!-- Tombol Edit & Hapus -->
                                <div class="flex space-x-2.5">
                                    <button class="flex-1 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[12px] font-bold rounded-lg transition flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <button class="flex-1 py-2 bg-white border border-[#fecdd3] hover:bg-[#fff1f2] text-[#e11d48] text-[12px] font-bold rounded-lg transition flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white border-2 border-gray-200 rounded-2xl border-dashed">
                        <div class="bg-gray-50 p-4 rounded-full mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700 mb-1">Belum ada produk di etalase</h3>
                        <p class="text-[12px] text-gray-400 mb-4">Tambahkan hasil laut pertama Anda.</p>
                        <a href="{{ route('admin.product.create') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-teal-700 font-bold text-[12px] rounded-lg shadow-sm hover:bg-gray-50 transition">
                            + Tambah Produk Pertama
                        </a>
                    </div>
                @endforelse
            </div>
            
            <div class="h-20"></div> 
        </div>
    </main>

    <!-- SCRIPT FILTER KATEGORI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const selectedCategory = this.getAttribute('data-category');

                    // 1. Reset gaya semua tombol kembali ke default (putih)
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-darkgreen', 'text-white');
                        b.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
                    });

                    // 2. Beri gaya aktif (hijau gelap) pada tombol yang sedang diklik
                    this.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
                    this.classList.add('bg-darkgreen', 'text-white');

                    // 3. Tampilkan/Sembunyikan produk berdasarkan kategori
                    productCards.forEach(card => {
                        if (selectedCategory === 'all') {
                            card.style.display = 'flex';
                        } else {
                            if (card.getAttribute('data-category') === selectedCategory) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>