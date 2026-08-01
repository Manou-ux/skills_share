<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Inscription</h1>
        <p class="text-sm text-slate-500 mt-1">Rejoignez la communauté Skills Share</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" class="block mt-1 w-full ss-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full ss-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="city" value="Ville (optionnel)" />
            <x-text-input id="city" class="block mt-1 w-full ss-input" type="text" name="city" :value="old('city')" autocomplete="address-level2" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full ss-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full ss-input" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <button type="submit" class="ss-btn-primary w-full">Créer mon compte</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-900">Se connecter</a>
    </p>
</x-guest-layout>
