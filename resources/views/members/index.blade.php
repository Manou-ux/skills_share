<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Annuaire des membres</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtre par catégorie --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="category" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">Toutes</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                        Filtrer
                    </button>
                </form>
            </div>

            {{-- Liste des membres --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($members as $member)
                    <div class="bg-white rounded-lg shadow p-5 flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $member->name }}</h3>
                                @if ($member->city)
                                    <p class="text-sm text-gray-500">{{ $member->city }}</p>
                                @endif
                            </div>
                        </div>

                        @php
                            $offres = $member->userSkills->where('type', 'offre')->take(3);
                        @endphp

                        @if ($offres->count())
                            <div class="mb-4">
                                <p class="text-xs uppercase text-gray-400 mb-1">Propose</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($offres as $userSkill)
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                            {{ $userSkill->skill->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('members.show', $member) }}"
                           class="mt-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-md text-sm">
                            Voir le profil
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full text-center py-8">Aucun membre trouvé.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $members->links() }}
            </div>
        </div>
    </div>
</x-app-layout>