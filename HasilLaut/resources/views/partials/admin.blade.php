<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - HasilLaut')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar-admin')

        <main class="flex-1 p-6 lg:p-8 overflow-x-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>