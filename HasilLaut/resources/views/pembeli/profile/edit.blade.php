@extends('layouts.pembeli')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Status Geofencing Banner -->
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
        <span>Status Geofencing: Titik koordinat valid dalam wilayah jangkauan operasional HasilLaut Hub Pangandaran. Akun memenuhi syarat transaksi.</span>
    </div>

    <!-- Tombol Navigasi / Tab Atas -->
    <div class="flex flex-wrap gap-3 mb-6">
        <button class="px-5 py-2.5 bg-[#1b4d3e] text-white rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i class="bi bi-person-gear"></i> Edit Profil & Alamat GPS
        </button>
        <a href="#faq-section" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-medium flex items-center gap-2 hover:bg-gray-50">
            <i class="bi bi-question-circle"></i> Pusat Bantuan & FAQ
        </a>
    </div>

    <form action="{{ route('pembeli.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Kolom Kiri: Informasi Data Diri Pembeli -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-card-heading text-gray-700 text-lg"></i>
                        <h3 class="font-bold text-gray-900 text-base">Informasi Data Diri Pembeli</h3>
                    </div>
                    <hr class="border-gray-100 mb-6">

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1b4d3e]" value="{{ auth()->user()->name ?? 'Hilya Shafwa Najla Abadi' }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed" value="{{ auth()->user()->email ?? 'sendtohilya@gmail.com' }}" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon</label>
                            <input type="text" name="phone_number" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1b4d3e]" value="{{ $pembeliDetail->phone_number ?? '+62 812-3456-7890' }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap Rumah</label>
                        <textarea name="address" rows="4" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1b4d3e]">{{ $pembeliDetail->address ?? 'Jl. Sukapura No. 50, Bojongsoang, Kabupaten Bandung (Area Layanan Hub Terhubung)' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Titik Koordinat Alamat Pengiriman & Peta -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-map-fill text-gray-700 text-lg"></i>
                        <h3 class="font-bold text-gray-900 text-base">Titik Koordinat Alamat Pengiriman</h3>
                    </div>
                    <hr class="border-gray-100 mb-4">
                    <p class="text-xs text-gray-500 mb-4">Geser pin (marker) pada peta di bawah untuk menentukan titik lokasi rumah Anda secara presisi guna verifikasi ongkir dan area Hub.</p>

                    <!-- Peta Interaktif Leaflet -->
                    <div id="map" class="rounded-xl border border-gray-200 mb-4" style="height: 220px; width: 100%;"></div>

                    <!-- Info Koordinat -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Latitude:</label>
                            <input type="text" id="latitude" name="latitude" class="w-full bg-transparent text-xs font-semibold text-gray-600 p-0 border-0 focus:ring-0" value="{{ $pembeliDetail->latitude ?? '-7.614502' }}" readonly>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Longitude:</label>
                            <input type="text" id="longitude" name="longitude" class="w-full bg-transparent text-xs font-semibold text-gray-600 p-0 border-0 focus:ring-0" value="{{ $pembeliDetail->longitude ?? '108.654120' }}" readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#1b4d3e] hover:bg-[#153b30] text-white font-semibold rounded-xl text-sm shadow-sm transition duration-200 flex items-center justify-center gap-2">
                    <i class="bi bi-floppy"></i> Simpan Profil & Alamat
                </button>
            </div>

        </div>
    </form>

    <!-- Bagian Pusat Bantuan & FAQ -->
    <div id="faq-section" class="mt-10">
        <h3 class="font-bold text-gray-900 text-lg mb-4">Pusat Bantuan & Pertanyaan Umum (FAQ)</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="text-[#1b4d3e] mb-3">
                        <i class="bi bi-snow text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-2">Bagaimana menjaga kesegaran ikan saat dikirim?</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">HasilLaut menggunakan standar rantai dingin (cold chain) dengan ice box steril di setiap pengiriman Hub.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="text-[#1b4d3e] mb-3">
                        <i class="bi bi-wallet2 text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-2">Apa saja metode pembayaran yang tersedia?</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Didukung oleh payment gateway terpercaya (QRIS, VA Bank, E-Wallet) serta fitur Cash on Delivery (COD).</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="text-[#1b4d3e] mb-3">
                        <i class="bi bi-shield-check text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-2">Bagaimana sistem garansi jika ikan rusak?</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Anda dapat mengajukan komplain melalui detail pesanan maksimal 2 jam setelah barang diterima kurir.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let initialLat = {{ $pembeliDetail->latitude ?? -7.614502 }};
        let initialLng = {{ $pembeliDetail->longitude ?? 108.654120 }};

        let map = L.map('map').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            let position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(6);
            document.getElementById('longitude').value = position.lng.toFixed(6);
        });
    });
</script>
@endsection