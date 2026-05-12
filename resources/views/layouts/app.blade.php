<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background: #F5F6FA; font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading (opsional, bisa dihapus jika tidak dipakai) -->
            @isset($header)
                <div style="max-width: 80rem; margin: 0 auto; padding: 1.25rem 1.5rem 0;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #111827;">{{ $header }}</h2>
                </div>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
