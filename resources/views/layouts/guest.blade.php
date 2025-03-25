<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name', 'ImageConverter') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @php
        $manifestPath = public_path('build/manifest.json');
        $assetExists = file_exists($manifestPath);
        $manifest = $assetExists ? json_decode(file_get_contents($manifestPath), true) : null;
        $cssFile =
            $manifest && isset($manifest['resources/css/app.css']['file'])
                ? 'build/' . $manifest['resources/css/app.css']['file']
                : 'build/assets/app-DPbV5EVT.css';
        $jsFile =
            $manifest && isset($manifest['resources/js/app.js']['file'])
                ? 'build/' . $manifest['resources/js/app.js']['file']
                : 'build/assets/app-DQNOlDuK.js';
    @endphp

    @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset($cssFile) }}">
        <script src="{{ asset($jsFile) }}" defer></script>
    @endif
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="/">
                <div class="text-2xl font-bold text-blue-500">Boas<span class="text-blue-700">far</span></div>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
