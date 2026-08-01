@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Offres & besoins</h1>
        <p class="text-slate-400 text-sm mt-1">Compétences liées aux utilisateurs</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 rounded-2xl border border-slate-800 bg-slate-900 p-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Utilisateur ou skill…" class="flex-1 min-w-[180px] rounded-lg bg-slate-950 border-slate-700 text-white">
        <select name="type" class="rounded-lg bg-slate-950 border-slate-700 text-white">
            <option value="">Tous les types</option>
            <option value="offre" @selected(request('type') === 'offre')>Offre</option>
            <option value="besoin" @selected(request('type') === 'besoin')>Besoin</option>
        </select>
        <select name="category" class="rounded-lg bg-slate-950 border-slate-700 text-white">
            <option value="">Toutes catégories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 font-semibold text-sm">Filtrer</button>
        @if (request()->hasAny(['q', 'type', 'category']))
            <a href="{{ route('admin.user-skills') }}" class="text-sm text-slate-400 self-center hover:text-white">Réinitialiser</a>
        @endif
    </form>

    <div class="rounded-2xl border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-900 text-slate-400 text-left">
                <tr>
                    <th class="px-4 py-3">Utilisateur</th>
                    <th class="px-4 py-3">Compétence</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Niveau</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($userSkills as $item)
                    <tr class="border-t border-slate-800">
                        <td class="px-4 py-3">{{ $item->user->name }}</td>
                        <td class="px-4 py-3">{{ $item->skill->name }}</td>
                        <td class="px-4 py-3">{{ $item->type }}</td>
                        <td class="px-4 py-3">{{ $item->niveau ?: '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.user-skills.destroy', $item) }}" onsubmit="return confirm('Supprimer ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucun résultat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $userSkills->links() }}
</div>
@endsection
