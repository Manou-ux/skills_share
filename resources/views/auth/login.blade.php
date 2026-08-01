<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Connexion</h1>
        <p class="text-sm text-slate-500 mt-1">Accédez à votre espace membre</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full ss-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full ss-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-teal-700 hover:text-teal-900" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit" class="ss-btn-primary w-full">Se connecter</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:text-teal-900">Créer un compte</a>
    </p>
</x-guest-layout>
