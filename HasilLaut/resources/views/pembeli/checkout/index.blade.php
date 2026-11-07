
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Keranjang</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Checkout Pesanan</li>
        </ol>
    </nav>

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Konfirmasi Pesanan & Pembayaran</h3>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pembeli.checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            
            <!-- Kolom Kiri: Alamat, Produk, dan Metode Pembayaran -->
            <div class="col-lg-7">
                
                <!-- 1. Alamat Pengiriman Tujuan -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-geo-alt-fill text-dark fs-5 me-2"></i>
                        <h5 class="fw-bold mb-0 text-dark">Alamat Pengiriman Tujuan</h5>
                    </div>

                    <div class="p-3 border rounded-3 bg-light position-relative">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-dark">
                                {{ $pembeliDetail->name ?? 'Hilya Shafwa Najla Abadi' }} 
                                <span class="text-muted fw-normal">({{ $pembeliDetail->phone_number ?? '+62 812-3456-7890' }})</span>
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-check-circle me-1"></i> Dalam Wilayah Pangandaran
                            </span>
                        </div>
                        <p class="text-secondary small mb-2">
                            {{ $pembeliDetail->address ?? 'Jl. Pantai Pangandaran No. 12, RT 02 / RW 05, Kecamatan Pangandaran, Kabupaten Pangandaran, Jawa Barat (Titik GPS Terverifikasi dekat HasilLaut Hub).' }}
                        </p>
                        <a href="#" class="text-success text-decoration-none small fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i> Ubah / Geser Titik Alamat di Peta
                        </a>
                    </div>
                </div>

                <!-- 2. Ringkasan Produk Dipesan -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-box-seam text-dark fs-5 me-2"></i>
                        <h5 class="fw-bold mb-0 text-dark">Ringkasan Produk Dipesan</h5>
                    </div>

                    @if($cart && $cart->cartItems && count($cart->cartItems) > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($cart->cartItems as $item)
                                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $item->product->name ?? 'Produk Hasil Laut' }}</h6>
                                            <small class="text-muted">{{ $item->quantity }} kg • Hub Pangandaran</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark">Rp {{ number_format($item->quantity * ($item->product->price ?? 0), 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted fst-italic mb-0">Keranjang belanja kosong.</p>
                    @endif
                </div>

                <!-- 3. Pilih Metode Pembayaran -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-wallet2 text-dark fs-5 me-2"></i>
                        <h5 class="fw-bold mb-0 text-dark">Pilih Metode Pembayaran</h5>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Opsi COD -->
                        <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer shadow-sm bg-white" style="cursor: pointer;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-cash-stack text-success fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Cash on Delivery (COD)</h6>
                                    <small class="text-muted">Bayar tunai ke kurir saat ikan tiba di rumah</small>
                                </div>
                            </div>
                            <input class="form-check-input mt-0" type="radio" name="payment_method" value="cod" checked>
                        </label>

                        <!-- Opsi QRIS -->
                        <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer bg-white" style="cursor: pointer;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-qr-code text-primary fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">QRIS (Instant Payment)</h6>
                                    <small class="text-muted">Scan via GoPay, OVO, Dana, ShopeePay, m-BCA.</small>
                                </div>
                            </div>
                            <input class="form-check-input mt-0" type="radio" name="payment_method" value="qris">
                        </label>

                        <!-- Opsi Virtual Account -->
                        <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer bg-white" style="cursor: pointer;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-bank text-secondary fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Virtual Account Bank (BCA / Mandiri / BRI)</h6>
                                    <small class="text-muted">Otomatis terverifikasi oleh sistem</small>
                                </div>
                            </div>
                            <input class="form-check-input mt-0" type="radio" name="payment_method" value="va">
                        </label>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Rincian Pembayaran & Tombol Eksekusi -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-receipt text-dark fs-5 me-2"></i>
                        <h5 class="fw-bold mb-0 text-dark">Rincian Pembayaran</h5>
                    </div>
                    <hr class="text-muted opacity-25">

                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Subtotal Harga Barang</span>
                        <span class="fw-semibold text-dark">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Ongkos Kirim Kurir Internal (Cold Chain)</span>
                        <span class="fw-semibold text-dark">Rp {{ number_format($shippingCost ?? 15000, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 text-secondary">
                        <span>Biaya Layanan & Penanganan</span>
                        <span class="fw-semibold text-dark">Rp 2.500</span>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0">Total Pembayaran</h5>
                        <h4 class="fw-bold text-success mb-0">Rp {{ number_format(($totalAmount ?? 0) + 2500, 0, ',', '.') }}</h4>
                    </div>

                    <!-- Tombol Buat Pesanan Sekarang -->
                    <button type="submit" class="btn text-white w-100 py-3 fw-bold rounded-pill shadow-sm mb-3 {{ (!$cart || !$cart->cartItems || count($cart->cartItems) == 0) ? 'disabled' : '' }}" style="background-color: #1b4d3e;">
                        <i class="bi bi-lock-fill me-2"></i> Buat Pesanan Sekarang
                    </button>

                    <div class="text-center">
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-shield-check text-success me-1"></i> Transaksi dijamin aman & bergaransi kesegaran
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection