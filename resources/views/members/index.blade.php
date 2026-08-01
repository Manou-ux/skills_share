<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Annuaire des membres</h2>
            <p class="ss-muted mt-1">Recherchez par nom, ville, compétence ou catégorie.</p>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container">
            <div class="ss-card p-4 sm:p-5 mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div class="lg:col-span-2">
                        <label class="ss-label">Recherche</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, ville, skill…" class="ss-input">
                    </div>
                    <div>
                        <label class="ss-label">Catégorie</label>
                        <select name="category" class="ss-input">
                            <option value="">Toutes</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="ss-label">Skill</label>
                        <select name="skill" class="ss-input">
                            <option value="">Tous</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}" @selected(request('skill') == $skill->id)>{{ $skill->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <div class="flex-1 min-w-[120px]">
                            <label class="ss-label">Type</label>
                            <select name="type" class="ss-input">
                                <option value="">Tous</option>
                                <option value="offre" @selected(request('type') === 'offre')>Offre</option>
                                <option value="besoin" @selected(request('type') === 'besoin')>Besoin</option>
                            </select>
                        </div>
                        <button type="submit" class="ss-btn-primary self-end">Rechercher</button>
                    </div>
                </form>
                @if (request()->hasAny(['q', 'category', 'skill', 'type']))
                    <div class="mt-3">
                        <a href="{{ route('members.index') }}" class="text-sm text-slate-500 hover:text-teal-700">Réinitialiser les filtres</a>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($members as $member)
                    <div class="ss-card p-5 flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="ss-avatar w-12 h-12 text-base">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $member->name }}</h3>
                                @if ($member->city)
                                    <p class="text-sm text-slate-500">{{ $member->city }}</p>
                                @endif
                            </div>
                        </div>

                        @php
                            $offres = $member->userSkills->where('type', 'offre')->take(3);
                            $besoins = $member->userSkills->where('type', 'besoin')->take(2);
                        @endphp

                        @if ($offres->count())
                            <div class="mb-3">
                                <p class="text-xs uppercase tracking-wide text-slate-400 mb-1.5">Propose</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($offres as $userSkill)
                                        <span class="ss-badge-offer">{{ $userSkill->skill->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($besoins->count())
                            <div class="mb-4">
                                <p class="text-xs uppercase tracking-wide text-slate-400 mb-1.5">Cherche</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($besoins as $userSkill)
                                        <span class="ss-badge-need">{{ $userSkill->skill->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto flex gap-2">
                            <a href="{{ route('members.show', $member) }}" class="ss-btn-secondary flex-1 text-center">Profil</a>
                            <a href="{{ route('chat.start', $member) }}" class="ss-btn-primary flex-1 text-center">Chat</a>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 col-span-full text-center py-12">Aucun membre trouvé pour cette recherche.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $members->links() }}</div>
        </div>
    </div>
</x-app-layout>
