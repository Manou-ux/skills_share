@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Demandes d’échange</h1>
        <p class="text-slate-400 text-sm mt-1">Supervision des interactions</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 rounded-2xl border border-slate-800 bg-slate-900 p-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Membre ou skill…" class="flex-1 min-w-[180px] rounded-lg bg-slate-950 border-slate-700 text-white">
        <select name="status" class="rounded-lg bg-slate-950 border-slate-700 text-white">
            <option value="">Tous les statuts</option>
            <option value="en_attente" @selected(request('status') === 'en_attente')>En attente</option>
            <option value="acceptee" @selected(request('status') === 'acceptee')>Acceptée</option>
            <option value="refusee" @selected(request('status') === 'refusee')>Refusée</option>
        </select>
        <button class="rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 font-semibold text-sm">Filtrer</button>
        @if (request()->hasAny(['q', 'status']))
            <a href="{{ route('admin.requests') }}" class="text-sm text-slate-400 self-center hover:text-white">Réinitialiser</a>
        @endif
    </form>

    <div class="rounded-2xl border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-900 text-slate-400 text-left">
                <tr>
                    <th class="px-4 py-3">De</th>
                    <th class="px-4 py-3">Vers</th>
                    <th class="px-4 py-3">Skill</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $req)
                    <tr class="border-t border-slate-800">
                        <td class="px-4 py-3">{{ $req->sender->name }}</td>
                        <td class="px-4 py-3">{{ $req->receiver->name }}</td>
                        <td class="px-4 py-3 text-teal-400">{{ $req->skill->name }}</td>
                        <td class="px-4 py-3">{{ $req->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.requests.destroy', $req) }}" onsubmit="return confirm('Supprimer ?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucune demande trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $requests->links() }}
</div>
@endsection
