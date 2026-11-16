@extends('layouts.pembeli')

@section('content')
<div class="w-full">

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-sm font-medium flex items-center justify-between">
            <span><i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}</span>
            <a href="{{ route('pembeli.cart') }}" class="underline font-bold hover:text-teal-950">Lihat Keranjang &rarr;</a>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            <i class="bi bi-exclamation-circle-fill mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Search Bar -->
    <form action="{{ route('pembeli.katalog') }}" method="GET" class="mb-6">
        @if($categoryId)
            <input type="hidden" name="category" value="{{ $categoryId }}">
        @endif
        <div class="relative max-w-2xl">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari ikan segar, udang, kepiting, atau cumi..."
                class="w-full bg-white border border-gray-200 rounded-full pl-11 pr-24 py-3 text-sm text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#164E56]">
            @if($search)
                <a href="{{ route('pembeli.katalog', $categoryId ? ['category' => $categoryId] : []) }}"
                   class="absolute right-20 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600">
                    Reset
                </a>
            @endif
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#164E56] hover:bg-[#0F393F] text-white text-xs font-semibold px-4 py-1.5 rounded-full transition">
                Cari
            </button>
        </div>
    </form>

    <!-- Banner Promo Flash Sale -->
    @include('partials.flash-sale-banner')

    <!-- Filter Pill Kategori -->
    <div class="flex items-center gap-2.5 overflow-x-auto pb-2 mb-8">
        <a href="{{ route('pembeli.katalog', $search ? ['search' => $search] : []) }}"
           class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition border {{ !$categoryId ? 'bg-[#164E56] text-white border-[#164E56] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            <i class="bi bi-grid-fill mr-1"></i> Semua Ikan
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('pembeli.katalog', array_filter(['category' => $cat->id_categories, 'search' => $search])) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition border {{ $categoryId == $cat->id_categories ? 'bg-[#164E56] text-white border-[#164E56] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Section 1: Promo Flash Sale Hari Ini -->
    @include('partials.flash-sale-section')

    <!-- Section 2: Katalog Hasil Laut Terbaru (Reguler) -->
    <section>
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                Katalog Hasil Laut Terbaru
            </h2>
            <span class="text-xs text-gray-500 font-medium">
                Menampilkan {{ $products->count() }} komoditas
            </span>
        </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                @php
                    $imgUrl = $product->image
                        ? (\Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                        : 'https://images.unsplash.com/photo-1534482498418-fef13c323f46?auto=format&fit=crop&w=500&q=80';
                    
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <!-- Gambar & Badge Kategori (Klik ke Detail Reguler) -->
                        <a href="" class="block relative h-44 bg-gray-100 overflow-hidden">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            
                            <span class="absolute bottom-3 left-3 bg-black/65 backdrop-blur-sm text-white text-[11px] font-medium px-2.5 py-1 rounded-full flex items-center gap-1">
                                <i class="bi bi-tag-fill text-teal-300 text-[10px]"></i>
                                {{ $product->category->name ?? 'Hasil Laut' }}
                            </span>
                        </a>

                        <!-- Info Produk -->
                        <div class="p-4">
                            <a href="" class="block">
                                <h3 class="font-bold text-gray-900 text-base truncate group-hover:text-[#164E56] transition" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-box-seam"></i>
                                @if($product->available_stock > 0)
                                    Stok tersedia: <span class="font-semibold text-gray-700">{{ $product->available_stock }} kg</span>
                                @else
                                    <span class="text-red-500 font-semibold">Stok habis</span>
                                @endif
                            </p>

                            <div class="mt-3">
                                <span class="text-lg font-extrabold text-[#134E55]">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}<span class="text-xs font-semibold text-gray-500">/kg</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Tambah ke Keranjang -->
                    <div class="px-4 pb-4">
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                {{ $product->available_stock <= 0 ? 'disabled' : '' }}
                                class="w-full text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-1.5 {{ $product->available_stock > 0 ? 'bg-[#164E56] hover:bg-[#0F393F] text-white cursor-pointer' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                                <i class="bi bi-plus-lg"></i> {{ $product->available_stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center text-gray-500 text-sm">
                    Tidak ada produk hasil laut yang ditemukan.
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection