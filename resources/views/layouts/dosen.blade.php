<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dosen Panel</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-blue-700 text-white px-6 py-4">
        <div class="flex justify-between">
            <span class="font-bold">Panel Dosen</span>
            <span>{{ auth()->user()->nama ?? 'Dosen' }}</span>
        </div>
    </nav>

    {{-- Content --}}
    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
