@php
    $pendingNav = auth()->user()->receivedRequests()->where('status', 'en_attente')->count();
    $unreadNav = \App\Models\Message::whereHas('conversation', function ($q) {
        $q->where(function ($inner) {
            $inner->where('user_one_id', auth()->id())->orWhere('user_two_id', auth()->id());
        });
    })->where('user_id', '!=', auth()->id())->whereNull('read_at')->count();
@endphp

<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur border-b border-slate-200/80 sticky top-0 z-40">
    <div class="ss-container">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="font-bold text-lg tracking-tight" style="color: var(--ss-brand-dark);">
                    Skills Share
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Accueil</x-nav-link>
                    <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">Membres</x-nav-link>
                    <x-nav-link :href="route('user-skills.index')" :active="request()->routeIs('user-skills.*')">Compétences</x-nav-link>
                    <x-nav-link :href="route('exchange-requests.index')" :active="request()->routeIs('exchange-requests.*')">
                        Demandes
                        @if ($pendingNav > 0)
                            <span class="ms-1 ss-badge bg-teal-100 text-teal-800">{{ $pendingNav }}</span>
                        @endif
                    </x-nav-link>
                    <x-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
                        Messages
                        @if ($unreadNav > 0)
                            <span class="ms-1 ss-badge-unread">{{ $unreadNav > 99 ? '99+' : $unreadNav }}</span>
                        @endif
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-100 transition">
                            <span class="ss-avatar w-8 h-8 text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Accueil</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">Membres</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user-skills.index')" :active="request()->routeIs('user-skills.*')">Compétences</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('exchange-requests.index')" :active="request()->routeIs('exchange-requests.*')">
                Demandes
                @if ($pendingNav > 0)
                    <span class="ms-1 ss-badge bg-teal-100 text-teal-800">{{ $pendingNav }}</span>
                @endif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
                Messages
                @if ($unreadNav > 0)
                    <span class="ms-1 ss-badge-unread">{{ $unreadNav > 99 ? '99+' : $unreadNav }}</span>
                @endif
            </x-responsive-nav-link>
        </div>
        <div class="pt-3 pb-4 border-t border-slate-100 px-4">
            <div class="font-medium text-slate-800">{{ Auth::user()->name }}</div>
            <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Déconnexion</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
