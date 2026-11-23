<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HasilLaut | Pembeli')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    <!-- 1. Sidebar Pembeli (lebar w-64) -->
    @include('partials.sidebar-pembeli')

    <!-- 2. Kolom Kanan: Diberi lg:pl-64 sesuai lebar w-64 pada sidebar -->
    <div class="flex min-h-screen flex-col lg:pl-64">

        <!-- Navbar Atas (sesuaikan nama file jika berbeda, misal partials.navbar-pembeli) -->
        @include('partials.navbar-pembeli')

        <!-- Konten Utama -->
        <main class="mx-auto w-full max-w-[1440px] flex-1 px-4 py-5 sm:px-7 lg:px-8">
            @yield('content')
        </main>

        <!-- Footer (sesuaikan nama file jika berbeda, misal partials.footer-pembeli) -->
        @include('partials.footer-pembeli')

    </div>

    @stack('scripts')
</body>
</html>