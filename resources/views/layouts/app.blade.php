<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PharmaControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

    <meta name="theme-color" content="#3b82f6"/>
    
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <link rel="apple-touch-icon" href="{{ asset('img/icon-192x192.png') }}">

    @livewireStyles
</head>
<body class="bg-gray-100 p-10">

    <x-navbar />

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        {{ $slot }}
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('PWA ServiceWorker registrado com sucesso: ', registration.scope);
                }, function(err) {
                    console.log('Falha ao registrar PWA ServiceWorker: ', err);
                });
            });
        }
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
