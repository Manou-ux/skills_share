<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Messages</h2>
            <p class="ss-muted mt-1">Discutez des offres et demandes d’échange.</p>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container-sm">
            <div class="ss-card overflow-hidden">
                @forelse ($conversations as $conversation)
                    @php $other = $conversation->otherUser(auth()->id()); @endphp
                    <a href="{{ route('chat.show', $conversation) }}"
                       class="flex items-center gap-4 px-5 py-4 border-b border-slate-100 hover:bg-teal-50/50 transition {{ $conversation->unread_count > 0 ? 'bg-red-50/40' : '' }}">
                        <div class="relative">
                            <div class="ss-avatar w-11 h-11">{{ strtoupper(substr($other->name, 0, 1)) }}</div>
                            @if ($conversation->unread_count > 0)
                                <span class="absolute -top-1 -right-1 ss-badge-unread">{{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between gap-2 items-center">
                                <p class="font-semibold truncate {{ $conversation->unread_count > 0 ? 'text-slate-900' : '' }}">{{ $other->name }}</p>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if ($conversation->unread_count > 0)
                                        <span class="ss-badge-unread">{{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}</span>
                                    @endif
                                    @if ($conversation->latestMessage)
                                        <span class="text-xs text-slate-400">{{ $conversation->latestMessage->created_at->format('d/m H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm truncate {{ $conversation->unread_count > 0 ? 'text-slate-800 font-medium' : 'text-slate-500' }}">
                                @if ($conversation->exchangeRequest?->skill)
                                    <span class="text-teal-700">{{ $conversation->exchangeRequest->skill->name }}</span> ·
                                @endif
                                {{ $conversation->latestMessage->body ?? 'Nouvelle conversation' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <p class="ss-muted mb-4">Aucun message pour le moment.</p>
                        <a href="{{ route('members.index') }}" class="ss-btn-primary">Trouver un membre</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
