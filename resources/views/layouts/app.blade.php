<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PharmaControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
</head>
<body class="bg-gray-100 p-10">

    <x-navbar />

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
