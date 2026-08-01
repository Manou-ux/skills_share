<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login — Skills Share</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-950 text-slate-100 px-4">
    <div class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-8">
        <p class="text-xs uppercase tracking-widest text-teal-400">Espace administrateur</p>
        <h1 class="text-2xl font-bold mt-2">Connexion admin</h1>
        <p class="text-sm text-slate-400 mt-2">Accès réservé — identifiants conditionnels</p>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-950/50 border border-red-900 text-red-300 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-slate-300 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       class="w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-teal-500 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-1.5">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-teal-500 focus:ring-teal-500">
            </div>
            <button class="w-full rounded-lg bg-teal-600 hover:bg-teal-500 text-white font-semibold py-2.5">
                Se connecter
            </button>
        </form>

        <a href="{{ route('home') }}" class="block text-center text-sm text-slate-500 mt-6 hover:text-teal-400">← Retour au site</a>
    </div>
</body>
</html>
