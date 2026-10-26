<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - HasilLaut</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-screen bg-pale-ice/30">

    <!-- Memanggil partials sidebar admin -->
    @include('partials.sidebar-admin')

    <!-- Area Konten Utama -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-midnight-teal">Hub Admin</h1>
            <div class="text-sm font-semibold text-ocean-teal">Budi (Admin)</div>
        </header>
        
        <div class="p-6">
            <!-- Di sinilah kodingan dari file-file di folder admin/ akan masuk -->
            @yield('content')
        </div>
    </main>
</body>
</html>