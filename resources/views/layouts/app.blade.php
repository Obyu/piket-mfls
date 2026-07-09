<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aplikasi Piket - Pengurus MNCU Future Leader</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|hanken-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f9f9ff] text-slate-800">
    <div class="min-h-screen">
        {{-- Sidebar desktop bergaya Stitch (baru). Menu mobile tetap di navigation.blade.php --}}
        @include('layouts.partials.sidebar')

        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-slate-100 md:ml-[280px]">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-8 md:ml-[280px]">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
