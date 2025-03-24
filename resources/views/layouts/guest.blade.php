<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ImageConverter') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Include SweetAlert Toast -->
    @include('sweetalert::alert')
</head>

<body class="h-full font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="/" class="flex items-center justify-center">
                <x-application-logo
                    class="w-20 h-20 fill-current text-gray-500 transition-colors duration-150 hover:text-gray-700 dark:hover:text-gray-300" />
            </a>
        </div>

        <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
            <div class="px-6 py-8">
                {{ $slot }}
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'ImageConverter') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
