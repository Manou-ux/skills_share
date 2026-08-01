<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tableau de bord</h2>
            <p class="ss-muted mt-1">Bonjour {{ auth()->user()->name }} — fil d’actualité et activité.</p>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container space-y-8">
            @if (session('success'))
                <div class="ss-alert-success">{{ session('success') }}</div>
            @endif

            <div class="ss-card p-4 sm:p-5">
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[220px]">
                        <label class="ss-label">Rechercher un membre ou une compétence</label>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Ex. Alice, Laravel, Anglais…" class="ss-input">
                    </div>
                    <button type="submit" class="ss-btn-primary">Rechercher</button>
                    @if ($search !== '')
                        <a href="{{ route('dashboard') }}" class="ss-btn-ghost">Effacer</a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="ss-card p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Offres</p>
                    <p class="text-3xl font-bold text-emerald-700 mt-1">{{ $offresCount }}</p>
                </div>
                <div class="ss-card p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Besoins</p>
                    <p class="text-3xl font-bold text-orange-700 mt-1">{{ $besoinsCount }}</p>
                </div>
                <div class="ss-card p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Demandes reçues</p>
                    <p class="text-3xl font-bold text-teal-700 mt-1">{{ $pendingReceived }}</p>
                </div>
                <div class="ss-card p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Messages non lus</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $unreadMessages }}</p>
                </div>
            </div>

            @if ($search !== '' && $memberResults->isNotEmpty())
                <div>
                    <h3 class="ss-section-title mb-3">Membres trouvés</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($memberResults as $member)
                            <a href="{{ route('members.show', $member) }}" class="ss-card p-4 hover:border-teal-300 transition">
                                <div class="flex items-center gap-3">
                                    <div class="ss-avatar w-10 h-10">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <p class="font-semibold truncate">{{ $member->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $member->city ?: '—' }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="ss-section-title">
                            @if ($search !== '')
                                Actualités pour « {{ $search }} »
                            @else
                                Fil d’actualité
                            @endif
                        </h3>
                        <a href="{{ route('user-skills.index') }}" class="text-sm font-medium text-teal-700 hover:text-teal-900">Publier une offre / besoin</a>
                    </div>

                    @forelse ($feed as $item)
                        <article class="ss-card p-5">
                            <div class="flex items-start gap-3">
                                <div class="ss-avatar w-11 h-11">{{ strtoupper(substr($item->user->name, 0, 1)) }}</div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('members.show', $item->user) }}" class="font-semibold text-slate-900 hover:text-teal-700">
                                            {{ $item->user->name }}
                                        </a>
                                        @if ($item->type === 'offre')
                                            <span class="ss-badge-offer">propose</span>
                                        @else
                                            <span class="ss-badge-need">cherche</span>
                                        @endif
                                        <span class="font-medium text-slate-800">{{ $item->skill->name }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">
                                        {{ $item->skill->category->name ?? '' }}
                                        @if ($item->niveau)
                                            · {{ ucfirst(str_replace('_', ' ', $item->niveau)) }}
                                        @endif
                                        · {{ $item->created_at->diffForHumans() }}
                                    </p>
                                    @if ($item->description)
                                        <p class="mt-3 text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $item->description }}</p>
                                    @endif
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('members.show', $item->user) }}" class="ss-btn-secondary text-xs py-1.5 px-3">Voir le profil</a>
                                        <a href="{{ route('chat.start', $item->user) }}" class="ss-btn-primary text-xs py-1.5 px-3">Discuter</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="ss-card p-8 text-center">
                            <p class="ss-muted">
                                @if ($search !== '')
                                    Aucun résultat pour « {{ $search }} ».
                                @else
                                    Aucune actualité pour le moment.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="space-y-6">
                    <div class="ss-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="ss-section-title">Demandes en attente</h3>
                            <a href="{{ route('exchange-requests.index') }}" class="text-sm font-medium text-teal-700">Voir</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($recentReceived as $req)
                                <div class="rounded-xl border border-slate-200 p-3">
                                    <p class="text-sm font-medium">{{ $req->sender->name }} — {{ $req->skill->name }}</p>
                                    <a href="{{ route('exchange-requests.index') }}" class="text-xs text-teal-700 font-medium mt-1 inline-block">Répondre</a>
                                </div>
                            @empty
                                <p class="ss-muted">Aucune demande en attente.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="ss-card p-6 space-y-3">
                        <h3 class="ss-section-title mb-2">Actions rapides</h3>
                        <a href="{{ route('user-skills.index') }}" class="ss-btn-primary w-full">Publier offre / besoin</a>
                        <a href="{{ route('members.index') }}" class="ss-btn-secondary w-full">Parcourir les membres</a>
                        <a href="{{ route('chat.index') }}" class="ss-btn-secondary w-full">Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
