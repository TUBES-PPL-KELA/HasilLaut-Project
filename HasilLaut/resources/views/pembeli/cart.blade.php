@extends('layouts.pembeli')

@section('content')
@php
    $isCartEmpty = empty($cartItems) || (method_exists($cartItems, 'isEmpty') ? $cartItems->isEmpty() : count($cartItems) === 0);
@endphp

<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Keranjang Belanja</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="cart-error-alert" class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-sm overflow-x-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-slate-100 text-sm gap-2">
                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                    <input type="checkbox" id="select-all" {{ $isCartEmpty ? 'disabled' : 'checked' }} class="rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4 disabled:opacity-50">
                    Pilih Semua (<span id="selected-count">{{ count($cartItems ?? []) }}</span>)
                </label>
                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item terpilih?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" {{ $isCartEmpty ? 'disabled' : '' }} class="text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">Hapus Terpilih</button>
                </form>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($cartItems ?? [] as $item)
                    @php
                        $itemPrice = $item->effective_price ?? $item->product->price ?? 0;
                        $subtotalItem = $itemPrice * $item->quantity;
                        $beratPerItem = $item->product->weight ?? $item->product->berat ?? 1;
                        $subtotalWeight = $beratPerItem * $item->quantity;

                        // [DIEDIT] Resolusi path gambar agar mendukung URL penuh, storage, maupun path di public/
                        $rawImage = $item->product->image ?? null;
                        if (!$rawImage) {
                            $imgSrc = asset('images/default-ikan.jpg');
                        } elseif (\Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://'])) {
                            $imgSrc = $rawImage;
                        } elseif (\Illuminate\Support\Str::startsWith($rawImage, ['storage/', '/storage/'])) {
                            $imgSrc = asset(ltrim($rawImage, '/'));
                        } elseif (\Illuminate\Support\Str::startsWith($rawImage, 'products/')) {
                            $imgSrc = asset('storage/' . $rawImage);
                        } else {
                            $imgSrc = asset(ltrim($rawImage, '/'));
                        }
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <input type="checkbox" checked 
                                   class="item-checkbox rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4 shrink-0"
                                   data-subtotal="{{ $subtotalItem }}"
                                   data-quantity="{{ $item->quantity }}"
                                   data-weight="{{ $subtotalWeight }}">
                            
                            {{-- [DIEDIT] Menggunakan $imgSrc hasil normalisasi path/URL --}}
                            <img src="{{ $imgSrc }}" 
                                 alt="{{ $item->product->name ?? 'Produk Ikan' }}" 
                                 class="w-16 h-16 min-w-[4rem] min-h-[4rem] object-cover rounded-xl border border-slate-100 shrink-0">
                            
                            <div>
                                <h3 class="font-semibold text-sm text-slate-800">{{ $item->product->name ?? 'Nama Produk' }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">📍 Hub Pangandaran</p>
                                
                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    @if(isset($item->flashSaleItem) && $item->flashSaleItem)
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded flex items-center gap-1">
                                            ⚡ Flash Sale
                                        </span>
                                        <span class="text-xs text-slate-400 line-through">
                                            Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs font-semibold text-emerald-600">
                                            Rp {{ number_format($itemPrice, 0, ',', '.') }} / kg
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-slate-700">
                                            Rp {{ number_format($itemPrice, 0, ',', '.') }} / kg
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4 sm:gap-6 w-full sm:w-auto">
                            <!-- BAGIAN KUANTITAS YANG BISA DIKETIK & TOMBOL +/- -->
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                <!-- Tombol Kurang (-) -->
                                <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                    <button type="submit" 
                                        dusk="btn-decrement"
                                        onclick="return {{ $item->quantity <= 1 ? "confirm('Apakah Anda ingin menghapus produk ini dari keranjang?')" : 'true' }}" 
                                        class="px-2.5 py-1.5 text-xs text-slate-600 hover:bg-slate-200 transition font-bold cursor-pointer">-</button>
                                </form>

                                <!-- Input Kuantitas Bisa Diketik Langsung (Dilengkapi Atribut dusk="input-quantity") -->
                                <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                        dusk="input-quantity"
                                        class="w-16 text-xs font-semibold text-slate-800 text-center bg-transparent border-none focus:outline-none focus:ring-0 py-1"
                                        onchange="this.form.submit()">
                                </form>

                                <!-- Tombol Tambah (+) -->
                                <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                    <button type="submit" dusk="btn-increment" class="px-2.5 py-1.5 text-xs text-slate-600 hover:bg-slate-200 transition font-bold cursor-pointer">+</button>
                                </form>
                            </div>

                            <span class="font-bold text-sm text-slate-800 w-28 text-right">
                                Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                            </span>

                            <form action="{{ route('cart.destroy', $item->id_cart_items) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini dari keranjang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" dusk="btn-delete" class="text-slate-400 hover:text-red-500 transition p-1 text-base cursor-pointer" title="Hapus Item">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div id="empty-cart-message" class="text-center py-12 text-slate-500">
                        <p class="font-semibold text-slate-700">Keranjang belanja kamu masih kosong.</p>
                        <p class="text-xs text-slate-400 mt-1">Silakan tambahkan produk terlebih dahulu sebelum melanjutkan ke checkout.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-6">
            <h2 class="font-bold text-base text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                📦 Ringkasan Belanja
            </h2>

            <div class="space-y-3 text-sm text-slate-600 mb-6">
                <div class="flex justify-between">
                    <span>Total Berat Ikan</span>
                    <span id="total-weight" class="font-semibold text-slate-800">0,0 kg</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Harga Produk (<span id="price-item-count">0</span> item)</span>
                    <span id="total-price" class="font-semibold text-slate-800">Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span>Estimasi Ongkir Hub</span>
                    <span id="shipping-cost" class="font-semibold text-slate-800">Rp 0</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 mb-6 flex justify-between items-center">
                <span class="font-bold text-base text-slate-800">Total Belanja</span>
                <span id="grand-total" class="font-bold text-lg text-[#013137]">Rp 0</span>
            </div>

            @if($isCartEmpty)
                <button type="button"
                        id="btn-checkout"
                        dusk="btn-checkout"
                        disabled
                        aria-disabled="true"
                        class="w-full bg-slate-300 text-slate-500 font-medium py-3 rounded-xl text-center block cursor-not-allowed">
                    Pesan Sekarang
                </button>
                <p id="checkout-disabled-hint" class="text-xs text-red-500 mt-2 text-center">
                    Keranjang kosong. Tidak dapat melakukan checkout.
                </p>
            @else
                <a href="{{ route('pembeli.checkout.index') }}"
                   id="btn-checkout"
                   dusk="btn-checkout"
                   class="w-full bg-[#013137] text-white font-medium py-3 rounded-xl text-center block hover:bg-[#215E68] transition shadow-sm">
                    Pesan Sekarang
                </a>
                <p id="checkout-disabled-hint" class="text-xs text-red-500 mt-2 text-center hidden">
                    Pilih minimal satu produk untuk melanjutkan checkout.
                </p>
            @endif
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isCartEmpty = @json($isCartEmpty);
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedCountEl = document.getElementById('selected-count');
        const totalWeightEl = document.getElementById('total-weight');
        const totalPriceEl = document.getElementById('total-price');
        const priceItemCountEl = document.getElementById('price-item-count');
        const shippingCostEl = document.getElementById('shipping-cost');
        const grandTotalEl = document.getElementById('grand-total');
        const checkoutBtn = document.getElementById('btn-checkout');
        const checkoutHintEl = document.getElementById('checkout-disabled-hint');

        function setCheckoutEnabled(enabled) {
            if (!checkoutBtn) return;

            if (enabled) {
                checkoutBtn.removeAttribute('disabled');
                checkoutBtn.setAttribute('aria-disabled', 'false');
                checkoutBtn.classList.remove('bg-slate-300', 'text-slate-500', 'cursor-not-allowed', 'pointer-events-none', 'opacity-50');
                checkoutBtn.classList.add('bg-[#013137]', 'text-white', 'hover:bg-[#215E68]');
                if (checkoutHintEl) checkoutHintEl.classList.add('hidden');
            } else {
                checkoutBtn.setAttribute('disabled', 'disabled');
                checkoutBtn.setAttribute('aria-disabled', 'true');
                checkoutBtn.classList.remove('bg-[#013137]', 'text-white', 'hover:bg-[#215E68]');
                checkoutBtn.classList.add('bg-slate-300', 'text-slate-500', 'cursor-not-allowed', 'pointer-events-none', 'opacity-60');
                if (checkoutHintEl) {
                    checkoutHintEl.classList.remove('hidden');
                    checkoutHintEl.textContent = isCartEmpty
                        ? 'Keranjang kosong. Tidak dapat melakukan checkout.'
                        : 'Pilih minimal satu produk untuk melanjutkan checkout.';
                }
            }
        }

        function updateSummary() {
            let totalWeight = 0;
            let totalPrice = 0;
            let checkedCount = 0;

            itemCheckboxes.forEach(cb => {
                if (cb.checked) {
                    checkedCount++;
                    totalWeight += parseFloat(cb.dataset.weight || cb.dataset.quantity || 0);
                    totalPrice += parseFloat(cb.dataset.subtotal || 0);
                }
            });

            if (selectedCountEl) selectedCountEl.textContent = checkedCount;
            if (priceItemCountEl) priceItemCountEl.textContent = checkedCount;
            if (totalWeightEl) totalWeightEl.textContent = totalWeight.toFixed(1).replace('.', ',') + ' kg';
            if (totalPriceEl) totalPriceEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

            let ongkir = checkedCount > 0 ? 15000 : 0;
            if (shippingCostEl) shippingCostEl.textContent = 'Rp ' + ongkir.toLocaleString('id-ID');

            let grandTotal = totalPrice + ongkir;
            if (grandTotalEl) grandTotalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === itemCheckboxes.length && itemCheckboxes.length > 0;
            }

            setCheckoutEnabled(!isCartEmpty && checkedCount > 0);
        }

        if (checkoutBtn && checkoutBtn.tagName === 'A') {
            checkoutBtn.addEventListener('click', function (e) {
                if (checkoutBtn.getAttribute('aria-disabled') === 'true') {
                    e.preventDefault();
                }
            });
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateSummary();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });

        updateSummary();
    });
</script>
@endsection