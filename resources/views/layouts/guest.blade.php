<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TutorMatch') }} - Tempatles.id</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md">

            <!-- Logo -->
            <div class="flex justify-center mb-10">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('image/logo.png') }}"
                         alt="TutorMatch"
                         class="h-14 w-auto">
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 md:p-10">
                {{ $slot }}
            </div>

        </div>
    </div>

</body>
</html>
