@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ open: {{ (int) session('open_category', 0) }} }">
    <div>
        <h1 class="text-2xl font-bold">Catégories & compétences</h1>
        <p class="text-slate-400 text-sm mt-1">Cliquez sur une catégorie pour afficher / gérer ses skills.</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 rounded-2xl border border-slate-800 bg-slate-900 p-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Filtrer catégorie ou skill…" class="flex-1 min-w-[200px] rounded-lg bg-slate-950 border-slate-700 text-white">
        <button class="rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 font-semibold text-sm">Filtrer</button>
        @if (request('q'))
            <a href="{{ route('admin.categories') }}" class="text-sm text-slate-400 self-center hover:text-white">Réinitialiser</a>
        @endif
    </form>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-wrap gap-3 rounded-2xl border border-slate-800 bg-slate-900 p-4">
        @csrf
        <input type="text" name="name" placeholder="Nouvelle catégorie" required class="flex-1 min-w-[200px] rounded-lg bg-slate-950 border-slate-700 text-white">
        <button class="rounded-lg bg-teal-600 hover:bg-teal-500 px-4 py-2 font-semibold text-sm">Ajouter la catégorie</button>
    </form>

    <div class="space-y-3">
        @forelse ($categories as $category)
            <div class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-900/60">
                <div class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-900">
                    <button type="button" class="text-left flex-1" @click="open = open === {{ $category->id }} ? 0 : {{ $category->id }}">
                        <p class="font-semibold">{{ $category->name }}</p>
                        <p class="text-xs text-slate-500">{{ $category->skills_count }} skill(s) · {{ $category->slug }}</p>
                    </button>
                    <div class="flex items-center gap-3 shrink-0">
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Supprimer cette catégorie et ses skills ?');">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 text-sm">Supprimer</button>
                        </form>
                        <button type="button" class="p-1" @click="open = open === {{ $category->id }} ? 0 : {{ $category->id }}">
                            <svg class="w-5 h-5 text-slate-400 transition" :class="open === {{ $category->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="open === {{ $category->id }}" x-cloak class="border-t border-slate-800 p-4 space-y-4 bg-slate-950/50">
                    <form method="POST" action="{{ route('admin.skills.store') }}" class="flex flex-wrap gap-2">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <input type="text" name="name" placeholder="Nouveau skill…" required class="flex-1 min-w-[180px] rounded-lg bg-slate-950 border-slate-700 text-white text-sm">
                        <button class="rounded-lg bg-teal-600 hover:bg-teal-500 px-3 py-2 text-sm font-semibold">Ajouter</button>
                    </form>

                    @forelse ($category->skills as $skill)
                        <div class="flex items-center justify-between rounded-lg border border-slate-800 px-3 py-2 text-sm">
                            <span>{{ $skill->name }}</span>
                            <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" onsubmit="return confirm('Supprimer ce skill ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300">Supprimer</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucun skill dans cette catégorie.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <p class="text-slate-500">Aucune catégorie trouvée.</p>
        @endforelse
    </div>
</div>
@endsection
