@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Utilisateurs</h1>
        <p class="text-slate-400 text-sm mt-1">Gestion des comptes membres</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 rounded-2xl border border-slate-800 bg-slate-900 p-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email…" class="flex-1 min-w-[180px] rounded-lg bg-slate-950 border-slate-700 text-white">
        <input type="text" name="city" value="{{ request('city') }}" placeholder="Ville…" class="w-40 rounded-lg bg-slate-950 border-slate-700 text-white">
        <button class="rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 font-semibold text-sm">Filtrer</button>
        @if (request()->hasAny(['q', 'city']))
            <a href="{{ route('admin.users') }}" class="text-sm text-slate-400 self-center hover:text-white">Réinitialiser</a>
        @endif
    </form>

    <div class="rounded-2xl border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-900 text-slate-400 text-left">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Ville</th>
                    <th class="px-4 py-3">Skills</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-slate-800">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->city ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $user->user_skills_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
