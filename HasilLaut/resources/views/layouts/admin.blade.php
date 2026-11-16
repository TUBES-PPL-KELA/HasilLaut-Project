<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahan font Inter agar mirip Figma -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar Admin -->
    @include('partials.sidebar-admin')

    <!-- Area Konten Utama (Scrollable) -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-8 max-w-[1400px] mx-auto">
            @yield('content')
        </div>
    </main>
    
</body>
</html>