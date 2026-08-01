@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold">Tableau de bord admin</h1>
        <p class="text-slate-400 text-sm mt-1">Supervision de la plateforme Skills Share</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">Utilisateurs</p>
            <p class="text-3xl font-bold text-teal-400 mt-1">{{ $usersCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">Catégories</p>
            <p class="text-3xl font-bold mt-1">{{ $categoriesCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">Compétences</p>
            <p class="text-3xl font-bold mt-1">{{ $skillsCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">Demandes</p>
            <p class="text-3xl font-bold mt-1">{{ $requestsCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">En attente</p>
            <p class="text-3xl font-bold text-amber-400 mt-1">{{ $pendingCount }}</p>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <p class="text-xs uppercase text-slate-500">Conversations</p>
            <p class="text-3xl font-bold mt-1">{{ $conversationsCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <h2 class="font-semibold mb-4">Derniers utilisateurs</h2>
            <div class="space-y-3 text-sm">
                @foreach ($recentUsers as $user)
                    <div class="flex justify-between border-b border-slate-800 pb-2">
                        <span>{{ $user->name }}</span>
                        <span class="text-slate-500">{{ $user->email }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <h2 class="font-semibold mb-4">Dernières demandes</h2>
            <div class="space-y-3 text-sm">
                @forelse ($recentRequests as $req)
                    <div class="border-b border-slate-800 pb-2">
                        <p>{{ $req->sender->name }} → {{ $req->receiver->name }}</p>
                        <p class="text-slate-500">{{ $req->skill->name }} · {{ $req->status }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">Aucune demande</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
