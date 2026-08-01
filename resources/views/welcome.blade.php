<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Skills Share') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="antialiased text-slate-900">
<div class="min-h-screen flex flex-col" style="background: #f8fafc;">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-bold text-lg tracking-tight text-teal-800">Skills Share</a>
            <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="ss-btn-primary">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="ss-btn-secondary">Connexion</a>
                    <a href="{{ route('register') }}" class="ss-btn-primary">Inscription</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #134e4a 55%, #0f172a 100%);"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #5eead4 0, transparent 40%), radial-gradient(circle at 80% 60%, #fbbf24 0, transparent 35%);"></div>
            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
                <p class="text-teal-200 text-sm font-semibold tracking-wide uppercase mb-4">Plateforme collaborative</p>
                <h1 class="max-w-3xl text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight tracking-tight">
                    Échangez vos compétences, apprenez ensemble
                </h1>
                <p class="mt-6 max-w-xl text-lg text-teal-50/90 leading-relaxed">
                    Présentez vos expertises, exprimez vos besoins d’apprentissage et discutez avec les membres pour organiser un vrai partage de savoir.
                </p>
                <div class="mt-10 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('members.index') }}" class="inline-flex items-center justify-center rounded-lg px-5 py-3 text-sm font-semibold bg-white text-teal-900 hover:bg-teal-50 transition">
                            Voir les membres
                        </a>
                        <a href="{{ route('chat.index') }}" class="inline-flex items-center justify-center rounded-lg px-5 py-3 text-sm font-semibold border border-white/30 text-white hover:bg-white/10 transition">
                            Messages
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-sm font-bold bg-amber-400 text-slate-900 hover:bg-amber-300 transition shadow-lg shadow-amber-900/20">
                            Créer un compte
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-sm font-semibold border-2 border-white text-white hover:bg-white/10 transition">
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="ss-card p-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold mb-4">1</div>
                <h2 class="font-semibold text-lg">Offrir</h2>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Publiez les compétences que vous maîtrisez et votre niveau.</p>
            </div>
            <div class="ss-card p-6">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-700 flex items-center justify-center font-bold mb-4">2</div>
                <h2 class="font-semibold text-lg">Apprendre</h2>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Exprimez vos besoins et trouvez quelqu’un prêt à vous aider.</p>
            </div>
            <div class="ss-card p-6">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold mb-4">3</div>
                <h2 class="font-semibold text-lg">Échanger & discuter</h2>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Envoyez une demande, acceptez-la, puis chattez pour organiser l’échange.</p>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 py-6 text-center text-sm text-slate-400">
        Skills Share — entraide et partage de compétences
        <span class="mx-2">·</span>
        <a href="{{ route('admin.login') }}" class="hover:text-teal-700">Admin</a>
    </footer>
</div>
</body>
</html>
