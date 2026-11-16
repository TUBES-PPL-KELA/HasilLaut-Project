@if($flashSaleItems->isNotEmpty())
<section id="promo-flash-sale" class="mb-12 scroll-mt-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-amber-500"></i>
            Promo Flash Sale Hari Ini
        </h2>
        <span class="text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full">
            {{ $flashSaleItems->count() }} Produk Promo
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($flashSaleItems as $item)
            @php
                $product = $item->inventoryBatch->product;
                $imgUrl = ($product && $product->image)
                    ? (\Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                    : 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80';
                $detailUrl = route('pembeli.produk.detail', ['id' => $product->id_product, 'flash_sale_item_id' => $item->id_flash_sale_item]);
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <!-- Gambar & Badge (Klik ke Detail Flash Sale) -->
                    <a href="{{ $detailUrl }}" class="block relative h-44 bg-gray-100 overflow-hidden">
                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        
                        <!-- Badge Diskon Kiri Atas -->
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-extrabold px-2.5 py-1 rounded-lg shadow">
                            -{{ $item->discount->percent ?? 0 }}%
                        </span>

                        <!-- Badge Kategori Kiri Bawah -->
                        <span class="absolute bottom-3 left-3 bg-black/65 backdrop-blur-sm text-white text-[11px] font-medium px-2.5 py-1 rounded-full flex items-center gap-1">
                            <i class="bi bi-tag-fill text-teal-300 text-[10px]"></i>
                            {{ $product->category->name ?? 'Hasil Laut' }}
                        </span>
                    </a>

                    <!-- Info Produk -->
                    <div class="p-4">
                        <a href="{{ $detailUrl }}" class="block">
                            <h3 class="font-bold text-gray-900 text-base truncate group-hover:text-[#164E56] transition" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <p class="text-xs text-amber-700 font-medium mt-1 flex items-center gap-1">
                            <i class="bi bi-box-seam"></i> Sisa stok promo: {{ $item->stock }} kg
                        </p>

                        <div class="mt-3">
                            <span class="block text-xs text-gray-400 line-through">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="text-lg font-extrabold text-[#134E55]">
                                Rp {{ number_format($item->flash_sale_price, 0, ',', '.') }}<span class="text-xs font-semibold text-gray-500">/kg</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Tambah ke Keranjang -->
                <div class="px-4 pb-4">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                        <input type="hidden" name="flash_sale_item_id" value="{{ $item->id_flash_sale_item }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-[#164E56] hover:bg-[#0F393F] text-white text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="bi bi-plus-lg"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif