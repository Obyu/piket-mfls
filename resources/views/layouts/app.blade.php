<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aplikasi Piket - Pengurus MNCU Future Leader</title>

    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-navy-50 text-slate-800">
    <div class="min-h-screen">
        @include('layouts.partials.sidebar')
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-slate-200/60 md:ml-[272px]">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-8 md:ml-[272px]">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
