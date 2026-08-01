<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin — {{ config('app.name', 'Skills Share') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-slate-100">
        <div class="min-h-screen flex">
            <aside class="w-64 shrink-0 border-r border-slate-800 bg-slate-900 hidden md:flex md:flex-col">
                <div class="px-5 py-6 border-b border-slate-800">
                    <p class="text-xs uppercase tracking-widest text-teal-400">Administration</p>
                    <p class="font-bold text-lg mt-1">Skills Share</p>
                </div>
                <nav class="flex-1 p-3 space-y-1 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">Tableau de bord</a>
                    <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">Utilisateurs</a>
                    <a href="{{ route('admin.categories') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">Catégories & skills</a>
                    <a href="{{ route('admin.user-skills') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.user-skills') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">Offres / Besoins</a>
                    <a href="{{ route('admin.requests') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.requests') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">Demandes</a>
                </nav>
                <div class="p-4 border-t border-slate-800 space-y-2">
                    <a href="{{ route('home') }}" class="block text-sm text-slate-400 hover:text-white">← Site public</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="text-sm text-red-400 hover:text-red-300">Déconnexion admin</button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 min-w-0">
                <header class="md:hidden flex items-center justify-between px-4 py-3 border-b border-slate-800 bg-slate-900">
                    <span class="font-semibold">Admin</span>
                    <a href="{{ route('admin.dashboard') }}" class="text-teal-400 text-sm">Menu</a>
                </header>
                <div class="md:hidden flex gap-2 overflow-x-auto px-4 py-2 border-b border-slate-800 bg-slate-900 text-xs">
                    <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap px-2 py-1 rounded bg-slate-800">Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="whitespace-nowrap px-2 py-1 rounded bg-slate-800">Users</a>
                    <a href="{{ route('admin.categories') }}" class="whitespace-nowrap px-2 py-1 rounded bg-slate-800">Catégories</a>
                    <a href="{{ route('admin.user-skills') }}" class="whitespace-nowrap px-2 py-1 rounded bg-slate-800">Offres</a>
                    <a href="{{ route('admin.requests') }}" class="whitespace-nowrap px-2 py-1 rounded bg-slate-800">Demandes</a>
                </div>
                <main class="p-4 sm:p-8">
                    @if (session('success'))
                        <div class="ss-alert-success mb-6 bg-emerald-950/40 text-emerald-300 border-emerald-800">{{ session('success') }}</div>
                    @endif
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
