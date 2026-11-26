<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Pengiriman Aktif | Kurir HasilLaut</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    <header class="bg-white border-b border-slate-200 px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HasilLaut" class="w-9 h-8 object-contain">
                <div>
                    <h1 class="text-base font-extrabold text-[#013137]">Tugas Pengiriman Aktif</h1>
                    <p class="text-xs text-slate-500">Kurir: <strong>{{ Auth::user()->name }}</strong> • HasilLaut Hub Pangandaran</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold px-4 py-2 transition cursor-pointer">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10">
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold px-3.5 py-1 mb-3">
                <i class="bi bi-truck"></i> Modul Kurir (Sprint 2)
            </span>
            <h2 class="text-lg font-extrabold text-slate-900">Halaman Tugas Pengiriman Siap Dihubungkan</h2>
            <p class="mt-2 text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Autentikasi peran <strong>Kurir</strong> sudah aktif. Fitur daftar antrian pengiriman, pelacakan GPS, dan konfirmasi COD akan ditampilkan di halaman ini setelah modul kurir selesai dikembangkan.
            </p>
        </div>
    </main>

</body>
</html>