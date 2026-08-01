<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Profil de {{ $user->name }}</h2>
                <p class="ss-muted mt-1">Compétences, besoins et contact.</p>
            </div>
            <a href="{{ route('members.index') }}" class="ss-btn-ghost">← Retour</a>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container-sm space-y-6">
            <div class="ss-card p-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="ss-avatar w-16 h-16 text-xl">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                            @if ($user->city)
                                <p class="text-slate-500">{{ $user->city }}</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('chat.start', $user) }}" class="ss-btn-primary">Envoyer un message</a>
                </div>
                @if ($user->bio)
                    <p class="mt-4 text-slate-600 leading-relaxed">{{ $user->bio }}</p>
                @endif
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-3">Compétences proposées</h3>
                <div class="space-y-3">
                    @forelse ($user->userSkills->where('type', 'offre') as $userSkill)
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50/40 p-3">
                            <span class="ss-badge-offer">
                                {{ $userSkill->skill->name }}
                                @if ($userSkill->niveau)
                                    · {{ ucfirst(str_replace('_', ' ', $userSkill->niveau)) }}
                                @endif
                            </span>
                            @if ($userSkill->description)
                                <p class="text-sm text-slate-600 mt-2">{{ $userSkill->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="ss-muted">Aucune offre pour le moment.</p>
                    @endforelse
                </div>
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-3">Besoins d’apprentissage</h3>
                <div class="space-y-3">
                    @forelse ($user->userSkills->where('type', 'besoin') as $userSkill)
                        <div class="rounded-xl border border-orange-100 bg-orange-50/40 p-3">
                            <span class="ss-badge-need">{{ $userSkill->skill->name }}</span>
                            @if ($userSkill->description)
                                <p class="text-sm text-slate-600 mt-2">{{ $userSkill->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="ss-muted">Aucun besoin exprimé.</p>
                    @endforelse
                </div>
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-3">Proposer un échange</h3>

                @if ($errors->any())
                    <div class="ss-alert-error mb-3">{{ $errors->first() }}</div>
                @endif

                @if ($user->userSkills->where('type', 'offre')->isEmpty())
                    <p class="ss-muted">Ce membre n’a pas encore d’offre disponible.</p>
                @else
                    <form method="POST" action="{{ route('exchange-requests.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <div>
                            <label class="ss-label">Compétence concernée</label>
                            <select name="skill_id" class="ss-input" required>
                                <option value="">-- Choisir --</option>
                                @foreach ($user->userSkills->where('type', 'offre') as $userSkill)
                                    <option value="{{ $userSkill->skill_id }}" @selected(old('skill_id') == $userSkill->skill_id)>
                                        {{ $userSkill->skill->name }}@if($userSkill->niveau) ({{ $userSkill->niveau }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ss-label">Message (optionnel)</label>
                            <textarea name="message" rows="3" class="ss-input" placeholder="Bonjour, je suis intéressé par...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="ss-btn-primary">Envoyer la demande</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
