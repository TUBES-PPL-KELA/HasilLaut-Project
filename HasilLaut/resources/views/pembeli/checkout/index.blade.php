@extends('layouts.pembeli')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Breadcrumb -->
    <nav class="mb-4 text-xs text-gray-500">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('pembeli.cart') }}" class="hover:text-gray-700">Keranjang</a></li>
            <li><span>/</span></li>
            <li class="font-semibold text-gray-900">Checkout Pesanan</li>
        </ol>
    </nav>

    <!-- Judul Halaman -->
    <div class="mb-6">
        <h3 class="font-bold text-gray-900 text-xl">Konfirmasi Pesanan & Pembayaran</h3>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Checkout -->
    <form id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Kolom Kiri: Alamat, Produk, dan Metode Pembayaran (Span 7) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Alamat Pengiriman Tujuan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-geo-alt-fill text-gray-700 text-lg"></i>
                        <h5 class="font-bold text-gray-900 text-base">Alamat Pengiriman Tujuan</h5>
                    </div>
                    <hr class="border-gray-100 mb-4">

                    <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 relative">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                            <span class="font-bold text-gray-900 text-sm">
                                {{ $pembeliDetail->name ?? 'Hilya Shafwa Najla Abadi' }} 
                                <span class="text-gray-500 font-normal">({{ $pembeliDetail->phone_number ?? '+62 812-3456-7890' }})</span>
                            </span>
                            <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-1 rounded-full text-[11px] font-semibold self-start">
                                <i class="bi bi-check-circle"></i> Dalam Wilayah Pangandaran
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed mb-3">
                            {{ $pembeliDetail->address ?? 'Jl. Pantai Pangandaran No. 12, RT 02 / RW 05, Kecamatan Pangandaran, Kabupaten Pangandaran, Jawa Barat (Titik GPS Terverifikasi dekat HasilLaut Hub).' }}
                        </p>
                        <a href="{{ route('pembeli.profile.edit') }}" class="text-[#1b4d3e] hover:underline text-xs font-semibold inline-flex items-center gap-1">
                            <i class="bi bi-pencil-square"></i> Ubah / Geser Titik Alamat di Peta
                        </a>
                    </div>
                </div>

                <!-- 2. Ringkasan Produk Dipesan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-box-seam text-gray-700 text-lg"></i>
                        <h5 class="font-bold text-gray-900 text-base">Ringkasan Produk Dipesan</h5>
                    </div>
                    <hr class="border-gray-100 mb-4">

                    @if($cart && $cart->cartItems && count($cart->cartItems) > 0)
                        <div class="space-y-4">
                            @foreach($cart->cartItems as $item)
                                <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 flex-shrink-0">
                                            <i class="bi bi-image text-xl"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-gray-900 text-sm mb-1">{{ $item->product->name ?? 'Produk Hasil Laut' }}</h6>
                                            <p class="text-xs text-gray-500">{{ $item->quantity }} kg • Hub Pangandaran</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($item->quantity * ($item->product->price ?? 0), 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-xs italic mb-0">Keranjang belanja kosong.</p>
                    @endif
                </div>

                <!-- 3. Pilih Metode Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-wallet2 text-gray-700 text-lg"></i>
                        <h5 class="font-bold text-gray-900 text-base">Pilih Metode Pembayaran</h5>
                    </div>
                    <hr class="border-gray-100 mb-4">

                    <div class="space-y-3">
                        <!-- Opsi COD -->
                        <label class="p-4 border border-gray-200 rounded-xl flex items-center justify-between cursor-pointer bg-white hover:border-[#1b4d3e] transition duration-200">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-cash-stack text-emerald-600 text-xl"></i>
                                <div>
                                    <h6 class="font-bold text-gray-900 text-sm mb-0.5">Cash on Delivery (COD)</h6>
                                    <p class="text-xs text-gray-500">Bayar tunai ke kurir saat ikan tiba di rumah</p>
                                </div>
                            </div>
                            <input class="w-4 h-4 text-[#1b4d3e] focus:ring-[#1b4d3e]" type="radio" name="payment_method" value="cod" checked>
                        </label>

                        <!-- Opsi QRIS -->
                        <label class="p-4 border border-gray-200 rounded-xl flex items-center justify-between cursor-pointer bg-white hover:border-[#1b4d3e] transition duration-200">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-qr-code text-blue-600 text-xl"></i>
                                <div>
                                    <h6 class="font-bold text-gray-900 text-sm mb-0.5">QRIS (Instant Payment)</h6>
                                    <p class="text-xs text-gray-500">Scan via GoPay, OVO, Dana, ShopeePay, m-BCA.</p>
                                </div>
                            </div>
                            <input class="w-4 h-4 text-[#1b4d3e] focus:ring-[#1b4d3e]" type="radio" name="payment_method" value="qris">
                        </label>

                        <!-- Opsi Virtual Account -->
                        <label class="p-4 border border-gray-200 rounded-xl flex items-center justify-between cursor-pointer bg-white hover:border-[#1b4d3e] transition duration-200">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-bank text-gray-600 text-xl"></i>
                                <div>
                                    <h6 class="font-bold text-gray-900 text-sm mb-0.5">Virtual Account Bank (BCA / Mandiri / BRI)</h6>
                                    <p class="text-xs text-gray-500">Otomatis terverifikasi oleh sistem</p>
                                </div>
                            </div>
                            <input class="w-4 h-4 text-[#1b4d3e] focus:ring-[#1b4d3e]" type="radio" name="payment_method" value="va">
                        </label>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Rincian Pembayaran & Tombol Eksekusi (Span 5) -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-receipt text-gray-700 text-lg"></i>
                        <h5 class="font-bold text-gray-900 text-base">Rincian Pembayaran</h5>
                    </div>
                    <hr class="border-gray-100 mb-4">

                    <div class="space-y-3 text-xs text-gray-600 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal Harga Barang</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim Kurir Internal (Cold Chain)</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($shippingCost ?? 15000, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Layanan & Penanganan</span>
                            <span class="font-semibold text-gray-900">Rp 2.500</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-4">

                    <div class="flex items-center justify-between mb-6">
                        <h5 class="font-bold text-gray-900 text-base mb-0">Total Pembayaran</h5>
                        <h4 class="font-bold text-emerald-600 text-lg mb-0">Rp {{ number_format(($totalAmount ?? 0) + 2500, 0, ',', '.') }}</h4>
                    </div>

                    <!-- Tombol Eksekusi -->
                    <button type="button" id="pay-button" class="w-full py-3.5 bg-[#1b4d3e] hover:bg-[#153b30] text-white font-semibold rounded-xl text-sm shadow-sm transition duration-200 flex items-center justify-center gap-2 mb-4 {{ (!$cart || !$cart->cartItems || count($cart->cartItems) == 0) ? 'opacity-50 cursor-not-allowed' : '' }}" {{ (!$cart || !$cart->cartItems || count($cart->cartItems) == 0) ? 'disabled' : '' }}>
                        <i class="bi bi-lock-fill"></i> Buat Pesanan Sekarang
                    </button>

                    <div class="text-center">
                        <span class="text-xs text-gray-400 inline-flex items-center gap-1">
                            <i class="bi bi-shield-check text-emerald-600"></i> Transaksi dijamin aman & bergaransi kesegaran
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- INTEGRASI JAVASCRIPT MIDTRANS SNAP         -->
<!-- ========================================== -->

{{-- 1. Script Midtrans Sandbox --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

{{-- 2. Logika AJAX & Trigger Pop-up --}}
<script>
    document.getElementById('pay-button').addEventListener('click', async function(e) {
        e.preventDefault();
        
        const payButton = this;
        const originalText = payButton.innerHTML;

        // Ubah tampilan tombol saat loading
        payButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
        payButton.disabled = true;

        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);

        try {
            // Minta Snap Token ke CheckoutController@store
            const response = await fetch("{{ route('pembeli.checkout.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.snap_token) {
                // Panggil pop-up Midtrans
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Pembayaran Sukses!', result);
                        window.location.href = "/pesanan/status"; 
                    },
                    onPending: function(result) {
                        console.log('Menunggu Pembayaran!', result);
                        window.location.href = "/pesanan/status"; 
                    },
                    onError: function(result) {
                        console.log('Pembayaran Gagal!', result);
                        alert('Pembayaran gagal atau dibatalkan.');
                        payButton.innerHTML = originalText;
                        payButton.disabled = false;
                    },
                    onClose: function() {
                        console.log('Pop-up ditutup pelanggan');
                        alert('Anda menutup jendela pembayaran. Pesanan tersimpan di menu Riwayat.');
                        window.location.href = "/pesanan/status";
                    }
                });
            } else {
                alert(data.error || 'Gagal memproses pesanan. Silakan coba lagi.');
                payButton.innerHTML = originalText;
                payButton.disabled = false;
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan pada sistem jaringan.');
            payButton.innerHTML = originalText;
            payButton.disabled = false;
        }
    });
</script>
@endsection