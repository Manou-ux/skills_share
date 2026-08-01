<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Skills Share') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="antialiased text-slate-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-10 px-4"
             style="background: linear-gradient(145deg, #0f766e 0%, #134e4a 45%, #0f172a 100%);">
            <div class="mb-6 text-center">
                <a href="{{ route('home') }}" class="text-white text-2xl font-bold tracking-tight">Skills Share</a>
                <p class="text-teal-100/80 text-sm mt-1">Partage de compétences</p>
            </div>
            <div class="w-full sm:max-w-md ss-card p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
