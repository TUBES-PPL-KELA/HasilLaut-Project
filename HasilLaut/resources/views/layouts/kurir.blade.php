<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kurir - HasilLaut</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 pb-20"> <!-- pb-20 memberi jarak agar tidak tertutup bottom navbar -->

    <!-- Area Konten Utama Peta & Tugas -->
    <main class="p-4">
        @yield('content')
    </main>

    <!-- Memanggil partials navigasi kurir (diletakkan di bawah) -->
    @include('partials.navbar-kurir')

</body>
</html>