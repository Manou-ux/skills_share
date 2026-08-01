<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Mes compétences</h2>
            <p class="ss-muted mt-1">Créez des domaines, des skills, puis publiez vos offres et besoins.</p>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container-sm space-y-6">
            @if (session('success'))
                <div class="ss-alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="ss-alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Créer domaine + skill --}}
            <div class="ss-card p-6 space-y-6">
                <div>
                    <h3 class="ss-section-title">Enrichir le catalogue</h3>
                    <p class="ss-muted mt-1">Tout le monde peut ajouter un domaine (ex. Informatique) et ses skills (ex. Python, React).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <form method="POST" action="{{ route('catalog.categories.store') }}" class="space-y-3 rounded-xl border border-slate-200 p-4 bg-slate-50/50">
                        @csrf
                        <p class="text-sm font-semibold text-slate-800">Nouveau domaine / catégorie</p>
                        <input type="text" name="name" class="ss-input" placeholder="Ex. Informatique, Design…" required>
                        <button class="ss-btn-secondary w-full">Créer le domaine</button>
                    </form>

                    <form method="POST" action="{{ route('catalog.skills.store') }}" class="space-y-3 rounded-xl border border-slate-200 p-4 bg-slate-50/50">
                        @csrf
                        <p class="text-sm font-semibold text-slate-800">Nouveau skill rattaché</p>
                        <select name="category_id" class="ss-input" required>
                            <option value="">Choisir un domaine</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(session('focus_category') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="name" class="ss-input" placeholder="Ex. Réseaux, Python, React…" required>
                        <button class="ss-btn-secondary w-full">Ajouter le skill</button>
                    </form>
                </div>
            </div>

            {{-- Publier offre/besoin --}}
            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-4">Publier une offre ou un besoin</h3>
                <form method="POST" action="{{ route('user-skills.store') }}" class="space-y-4" x-data="{ type: '{{ old('type', 'offre') }}' }">
                    @csrf
                    <div>
                        <label class="ss-label">Skill</label>
                        <select name="skill_id" class="ss-input" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach ($category->skills as $skill)
                                        <option value="{{ $skill->id }}" @selected(old('skill_id') == $skill->id)>{{ $skill->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('skill_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="ss-label">Type</label>
                        <select name="type" x-model="type" class="ss-input" required>
                            <option value="offre">Offre (je propose)</option>
                            <option value="besoin">Besoin (j’apprends)</option>
                        </select>
                    </div>
                    <div x-show="type === 'offre'" x-cloak>
                        <label class="ss-label">Niveau</label>
                        <select name="niveau" class="ss-input">
                            <option value="">-- Choisir --</option>
                            <option value="debutant" @selected(old('niveau') === 'debutant')>Débutant</option>
                            <option value="intermediaire" @selected(old('niveau') === 'intermediaire')>Intermédiaire</option>
                            <option value="expert" @selected(old('niveau') === 'expert')>Expert</option>
                        </select>
                        @error('niveau') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="ss-label">Description (visible dans le fil d’actualité)</label>
                        <textarea name="description" rows="4" class="ss-input" required
                                  placeholder="Ex. Je peux aider sur Laravel (API, Eloquent) le week-end. Ou : Je cherche un mentor React pour un projet perso.">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="ss-btn-primary">Publier</button>
                </form>
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-4 text-emerald-800">Mes offres</h3>
                <div class="space-y-3">
                    @forelse ($userSkills->where('type', 'offre') as $userSkill)
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50/40 p-4 flex justify-between items-start gap-3">
                            <div>
                                <p class="font-medium">{{ $userSkill->skill->name }}</p>
                                <p class="ss-muted">{{ $userSkill->skill->category->name ?? '' }}@if($userSkill->niveau) · {{ ucfirst(str_replace('_', ' ', $userSkill->niveau)) }}@endif</p>
                                @if ($userSkill->description)
                                    <p class="text-sm text-slate-600 mt-2">{{ $userSkill->description }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('user-skills.destroy', $userSkill) }}" onsubmit="return confirm('Supprimer ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm font-medium hover:text-red-800">Supprimer</button>
                            </form>
                        </div>
                    @empty
                        <p class="ss-muted">Aucune offre pour le moment.</p>
                    @endforelse
                </div>
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-4 text-orange-800">Mes besoins</h3>
                <div class="space-y-3">
                    @forelse ($userSkills->where('type', 'besoin') as $userSkill)
                        <div class="rounded-xl border border-orange-100 bg-orange-50/40 p-4 flex justify-between items-start gap-3">
                            <div>
                                <p class="font-medium">{{ $userSkill->skill->name }}</p>
                                <p class="ss-muted">{{ $userSkill->skill->category->name ?? '' }}</p>
                                @if ($userSkill->description)
                                    <p class="text-sm text-slate-600 mt-2">{{ $userSkill->description }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('user-skills.destroy', $userSkill) }}" onsubmit="return confirm('Supprimer ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm font-medium hover:text-red-800">Supprimer</button>
                            </form>
                        </div>
                    @empty
                        <p class="ss-muted">Aucun besoin pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
