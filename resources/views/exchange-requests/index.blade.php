<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Mes demandes d’échange</h2>
            <p class="ss-muted mt-1">Acceptez, refusez ou suivez vos échanges.</p>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container-sm space-y-6">
            @if (session('success'))
                <div class="ss-alert-success">{{ session('success') }}</div>
            @endif

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-4">Demandes reçues</h3>
                <div class="space-y-3">
                    @forelse ($received as $req)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex justify-between items-start gap-3">
                                <div>
                                    <p class="font-medium">{{ $req->sender->name }} — {{ $req->skill->name }}</p>
                                    @if ($req->message)
                                        <p class="ss-muted mt-1">{{ $req->message }}</p>
                                    @endif
                                </div>
                                <span @class([
                                    'ss-badge-pending' => $req->status === 'en_attente',
                                    'ss-badge-ok' => $req->status === 'acceptee',
                                    'ss-badge-ko' => $req->status === 'refusee',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </div>

                            @if ($req->status === 'en_attente')
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <form method="POST" action="{{ route('exchange-requests.update', $req) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="acceptee">
                                        <button class="ss-btn-primary">Accepter & chatter</button>
                                    </form>
                                    <form method="POST" action="{{ route('exchange-requests.update', $req) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="refusee">
                                        <button class="ss-btn-danger">Refuser</button>
                                    </form>
                                </div>
                            @elseif ($req->status === 'acceptee')
                                <a href="{{ route('chat.start', $req->sender) }}" class="inline-block mt-3 ss-btn-secondary">Ouvrir le chat</a>
                            @endif
                        </div>
                    @empty
                        <p class="ss-muted">Aucune demande reçue.</p>
                    @endforelse
                </div>
            </div>

            <div class="ss-card p-6">
                <h3 class="ss-section-title mb-4">Demandes envoyées</h3>
                <div class="space-y-3">
                    @forelse ($sent as $req)
                        <div class="rounded-xl border border-slate-200 p-4 flex justify-between items-center gap-3">
                            <div>
                                <p class="font-medium">À {{ $req->receiver->name }} — {{ $req->skill->name }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span @class([
                                    'ss-badge-pending' => $req->status === 'en_attente',
                                    'ss-badge-ok' => $req->status === 'acceptee',
                                    'ss-badge-ko' => $req->status === 'refusee',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                                @if ($req->status === 'en_attente')
                                    <form method="POST" action="{{ route('exchange-requests.destroy', $req) }}" onsubmit="return confirm('Annuler ?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 text-sm font-medium">Annuler</button>
                                    </form>
                                @elseif ($req->status === 'acceptee')
                                    <a href="{{ route('chat.start', $req->receiver) }}" class="text-sm font-medium text-teal-700">Chat</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="ss-muted">Aucune demande envoyée.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
