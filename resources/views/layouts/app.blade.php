<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TutorMatch') }} - Tempatles.id</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js']) 

    <style>
        .table thead { background: #1e40af; color: white; }
        .card { border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
        .btn-primary { background: linear-gradient(45deg, #1e40af, #3b82f6); border: none; }
    </style>
</head>
<body class="bg-gray-50">

    @auth
        @include('layouts.navigation')
    @endauth

    <div class="pt-16 min-h-screen">
        @isset($header)
            <header class="bg-white border-b">
                <div class="max-w-screen-2xl mx-auto px-6 py-5">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="max-w-screen-2xl mx-auto px-6 py-8">
            {{ $slot }}
        </main>
    </div>

    <footer class="bg-white border-t py-8">
        <div class="max-w-screen-2xl mx-auto px-6 text-center text-gray-500 text-sm">
            © {{ date('Y') }} Tempatles.id - Belajar Jadi Lebih Mudah
        </div> 
    </footer>

</body>
</html>
