<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col justify-between">

    <div class="flex flex-1">
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between p-6 hidden; lg:flex">
            <div>
                <div class="flex items-center gap-2 mb-10">
                    <span class="text-xl font-bold tracking-tight text-[#013137]">HASILLAUT</span>
                </div>

                <nav class="space-y-2 text-sm font-medium">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-100 transition">
                        🏠 Beranda
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-100 transition">
                        🐟 Katalog
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#013137] text-white shadow-sm">
                        🛒 Pesanan Saya
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-100 transition">
                        ⚙️ Pengaturan
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
                <div class="w-10 h-10 rounded-full bg-slate-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="User" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Hilya Shafwa</h4>
                    <p class="text-xs text-slate-400">Pembeli</p>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center text-xs text-slate-500">
                <div>
                    <span>Katalog</span> <span class="mx-2">&gt;</span> <span class="text-slate-800 font-medium">Keranjang Belanja</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="cursor-pointer text-base">🔔</span>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto w-full flex-1">
                <h1 class="text-2xl font-bold text-slate-800 mb-6">Keranjang Belanja</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 text-sm">
                            <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                <input type="checkbox" checked class="rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4">
                                Pilih Semua ({{ count($cartItems) }})
                            </label>
                            <button type="button" onclick="return confirm('Apakah Anda yakin ingin menghapus item terpilih?');" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus Terpilih</button>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($cartItems as $item)
                                <div class="py-4 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" checked class="rounded border-slate-300 text-[#013137] focus:ring-[#013137] w-4 h-4">
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-xl border border-slate-100">
                                        <div>
                                            <h3 class="font-semibold text-sm text-slate-800">{{ $item->product->name }}</h3>
                                            <p class="text-xs text-slate-400 mt-0.5">📍 Hub Pangandaran</p>
                                            <p class="text-xs font-semibold text-slate-700 mt-1">Rp {{ number_format($item->product->price, 0, ',', '.') }} / kg</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6">
                                    <form action="{{ route('cart.update', $item->id_cart_items) }}" method="POST" class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" 
                                                data-destroy-route="{{ route('cart.destroy', $item->id_cart_items) }}"
                                                onchange="updateQuantity(this)" 
                                                class="w-14 text-center text-xs bg-transparent py-1.5 focus:outline-none font-medium">
                                            <span class="text-xs text-slate-500 pr-2">kg</span>
                                        </form>
                                        <span class="font-bold text-sm text-slate-800 w-28 text-right">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </span>

                                        <form action="{{ route('cart.destroy', $item->id_cart_items) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini dari keranjang?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1 text-base" title="Hapus Item">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-slate-400">
                                    <p>Keranjang belanja kamu masih kosong.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-6">
                        <h2 class="font-bold text-base text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                            📦 Ringkasan Belanja
                        </h2>

                        @php
                            $totalBerat = collect($cartItems)->sum('quantity');
                            $ongkir = count($cartItems) > 0 ? 15000 : 0;
                            $grandTotal = $totalHarga + $ongkir;
                        @endphp

                        <div class="space-y-3 text-sm text-slate-600 mb-6">
                            <div class="flex justify-between">
                                <span>Total Berat Ikan</span>
                                <span class="font-semibold text-slate-800">{{ $totalBerat }}.0 kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Harga Produk ({{ count($cartItems) }} item)</span>
                                <span class="font-semibold text-slate-800">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimasi Ongkir Hub</span>
                                <span class="font-semibold text-slate-800">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4 mb-6 flex justify-between items-center">
                            <span class="font-bold text-base text-slate-800">Total Belanja</span>
                            <span class="font-bold text-lg text-[#013137]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <a href="#" class="w-full bg-[#013137] text-white font-medium py-3 rounded-xl text-center block hover:bg-[#215E68] transition shadow-sm">
                            Pesan Sekarang &rarr;
                        </a>
                    </div>

                </div>
            </div>

            <footer class="bg-white border-t border-slate-200 px-8 py-10 mt-12 text-xs text-slate-500">
                <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="font-bold text-sm text-slate-800 mb-3">HASILLAUT</h3>
                        <p class="text-slate-400 leading-relaxed">Platform B2C penyedia hasil laut segar langsung dari nelayan pesisir Indonesia dengan jaminan kualitas rantai dingin dan harga yang adil.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3">Navigasi</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                            <li><a href="#" class="hover:underline">Katalog</a></li>
                            <li><a href="#" class="hover:underline">Lokasi Hub</a></li>
                            <li><a href="#" class="hover:underline">Kemitraan Nelayan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3">Layanan</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:underline">Lacak Pengiriman</a></li>
                            <li><a href="#" class="hover:underline">Metode Pembayaran & COD</a></li>
                            <li><a href="#" class="hover:underline">Garansi Kesegaran</a></li>
                            <li><a href="#" class="hover:underline">Bantuan & FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:underline">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="hover:underline">Kontak Kami</a></li>
                        </ul>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto border-t border-slate-100 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center text-slate-400">
                    <p>&copy; 2026 HASILLAUT. Seluruh hak cipta dilindungi.</p>
                    <div class="flex gap-4 mt-4 md:mt-0">
                        <span>📧 info@hasillaut.id</span>
                        <span>📞 +62 812-3456-7890</span>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    <script>
function updateQuantity(input) {
    let form = input.form;
    let destroyRoute = input.getAttribute('data-destroy-route');
    
    if (input.value == 0) {
        if (confirm('Apakah Anda ingin menghapus produk ini dari keranjang?')) {
            form.action = destroyRoute;
            let methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.value = 'DELETE';
            form.submit();
        } else {
            input.value = 1;
        }
    } else {
        form.submit();
    }
}
</script>
</body>
</html>