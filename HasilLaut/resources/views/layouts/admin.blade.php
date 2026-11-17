<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Sidebar Admin --}}
        @include('partials.sidebar-admin')

        {{-- Konten Utama --}}
        <main class="flex-1 min-w-0 overflow-x-hidden">
            @yield('content')
        </main>
    </div>
</body>
</html>