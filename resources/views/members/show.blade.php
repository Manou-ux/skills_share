<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil de {{ $user->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Infos --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                        @if ($user->city)
                            <p class="text-gray-500">{{ $user->city }}</p>
                        @endif
                    </div>
                </div>
                @if ($user->bio)
                    <p class="mt-4 text-gray-600">{{ $user->bio }}</p>
                @endif
            </div>

            {{-- Offres --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Compétences proposées</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse ($user->userSkills->where('type', 'offre') as $userSkill)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            {{ $userSkill->skill->name }}
                            <span class="text-green-500">· {{ $userSkill->niveau }}</span>
                        </span>
                    @empty
                        <p class="text-gray-400 text-sm">Aucune compétence proposée pour le moment.</p>
                    @endforelse
                </div>
            </div>

            {{-- Besoins --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Besoins d'apprentissage</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse ($user->userSkills->where('type', 'besoin') as $userSkill)
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                            {{ $userSkill->skill->name }}
                        </span>
                    @empty
                        <p class="text-gray-400 text-sm">Aucun besoin exprimé pour le moment.</p>
                    @endforelse
                </div>
            </div>

            {{-- Envoyer une demande --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Proposer un échange</h3>

                @if (session('success'))
                    <p class="text-green-600 text-sm mb-3">{{ session('success') }}</p>
                @endif

                <form method="POST" action="{{ route('exchange-requests.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Compétence concernée</label>
                        <select name="skill_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($user->userSkills as $userSkill)
                                <option value="{{ $userSkill->skill_id }}">
                                    {{ $userSkill->skill->name }} ({{ $userSkill->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('skill_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message (optionnel)</label>
                        <textarea name="message" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"
                                  placeholder="Bonjour, je suis intéressé par..."></textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                        Envoyer la demande
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>