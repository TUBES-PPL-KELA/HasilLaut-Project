<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HasilLaut - Ikan Segar Langsung dari Nelayan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Memanggil partials navbar pembeli -->
    @include('partials.navbar-pembeli')

    <!-- Area Konten Utama -->
    <main class="flex-grow max-w-7xl mx-auto w-full p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    <footer class="bg-midnight-teal text-pale-ice p-6 text-center text-sm mt-auto">
        &copy; 2026 HasilLaut. Seluruh hak cipta dilindungi.
    </footer>
</body>
</html>