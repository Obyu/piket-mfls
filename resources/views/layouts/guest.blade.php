<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Aplikasi Piket MNCU Future Leader</title>

        <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-navy-800 via-navy-700 to-navy-900">
            <div class="mb-2">
                <a href="/" class="flex flex-col items-center gap-3">
                    <img src="{{ asset('logo.svg') }}" alt="Logo MNCU" class="w-20 h-20">
                    <div class="text-center">
                        <h1 class="text-xl font-bold text-white">Aplikasi Piket</h1>
                        <p class="text-sm text-brand-200">MNCU Future Leader</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-white/95 backdrop-blur-sm shadow-2xl border border-white/20 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-sm text-navy-300">&copy; {{ date('Y') }} MNCU Future Leader. All rights reserved.</p>
        </div>
    </body>
</html>
