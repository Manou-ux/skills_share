<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes demandes d'échange</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Reçues --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Demandes reçues</h3>

                <div class="space-y-3">
                    @forelse ($received as $req)
                        <div class="border rounded-md p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $req->sender->name }} — {{ $req->skill->name }}
                                    </p>
                                    @if ($req->message)
                                        <p class="text-sm text-gray-500 mt-1">{{ $req->message }}</p>
                                    @endif
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-700' => $req->status === 'en_attente',
                                        'bg-green-100 text-green-700' => $req->status === 'acceptee',
                                        'bg-red-100 text-red-700' => $req->status === 'refusee',
                                    ])">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </div>

                            @if ($req->status === 'en_attente')
                                <div class="flex gap-2 mt-3">
                                    <form method="POST" action="{{ route('exchange-requests.update', $req) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="acceptee">
                                        <button class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700">
                                            Accepter
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('exchange-requests.update', $req) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="refusee">
                                        <button class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Aucune demande reçue.</p>
                    @endforelse
                </div>
            </div>

            {{-- Envoyées --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Demandes envoyées</h3>

                <div class="space-y-3">
                    @forelse ($sent as $req)
                        <div class="border rounded-md p-4 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">
                                    À {{ $req->receiver->name }} — {{ $req->skill->name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-700' => $req->status === 'en_attente',
                                        'bg-green-100 text-green-700' => $req->status === 'acceptee',
                                        'bg-red-100 text-red-700' => $req->status === 'refusee',
                                    ])">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>

                                @if ($req->status === 'en_attente')
                                    <form method="POST" action="{{ route('exchange-requests.destroy', $req) }}"
                                          onsubmit="return confirm('Annuler cette demande ?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm">Annuler</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Aucune demande envoyée.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>