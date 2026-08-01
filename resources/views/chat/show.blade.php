<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="ss-avatar w-10 h-10">{{ strtoupper(substr($other->name, 0, 1)) }}</div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $other->name }}</h2>
                    @if ($conversation->exchangeRequest?->skill)
                        <p class="ss-muted">Échange : {{ $conversation->exchangeRequest->skill->name }}</p>
                    @else
                        <p class="ss-muted">Conversation directe</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('chat.index') }}" class="ss-btn-ghost">← Retour</a>
        </div>
    </x-slot>

    <div class="ss-page">
        <div class="ss-container-sm">
            @if (session('success'))
                <div class="ss-alert-success mb-4">{{ session('success') }}</div>
            @endif

            <div class="ss-card flex flex-col h-[65vh]"
                 x-data="chatBox({
                    fetchUrl: @js(route('chat.messages', $conversation)),
                    postUrl: @js(route('chat.store', $conversation)),
                    csrf: @js(csrf_token()),
                    initial: @js($initialMessages)
                 })"
                 x-init="start()">
                <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50" x-ref="list">
                    <template x-if="messages.length === 0">
                        <p class="text-center text-sm text-slate-400 py-8">Aucun message. Dites bonjour !</p>
                    </template>
                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.mine ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.mine
                                ? 'max-w-[75%] rounded-2xl rounded-br-md bg-teal-700 text-white px-4 py-2.5 shadow-sm'
                                : 'max-w-[75%] rounded-2xl rounded-bl-md bg-white border border-slate-200 text-slate-800 px-4 py-2.5 shadow-sm'">
                                <p class="text-sm whitespace-pre-wrap" x-text="msg.body"></p>
                                <p class="text-[10px] mt-1 opacity-70" x-text="msg.time"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <form class="border-t border-slate-200 p-3 flex gap-2 bg-white" @submit.prevent="send">
                    <input type="text" x-model="body" class="ss-input" placeholder="Écrire un message…" required autocomplete="off" :disabled="sending">
                    <button type="submit" class="ss-btn-primary shrink-0" :disabled="sending || !body.trim()">
                        <span x-show="!sending">Envoyer</span>
                        <span x-show="sending" x-cloak>…</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function chatBox({ fetchUrl, postUrl, csrf, initial }) {
            return {
                messages: initial || [],
                body: '',
                sending: false,
                timer: null,
                async fetchMessages() {
                    try {
                        const res = await fetch(fetchUrl, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.messages = data.messages;
                        this.$nextTick(() => this.scrollDown());
                    } catch (e) {}
                },
                async send() {
                    const text = this.body.trim();
                    if (!text || this.sending) return;
                    this.sending = true;
                    try {
                        const res = await fetch(postUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ body: text }),
                        });
                        if (res.ok) {
                            const data = await res.json();
                            if (data.message) this.messages.push(data.message);
                            this.body = '';
                            this.$nextTick(() => this.scrollDown());
                        } else {
                            await this.fetchMessages();
                        }
                    } catch (e) {
                        await this.fetchMessages();
                    } finally {
                        this.sending = false;
                    }
                },
                scrollDown() {
                    const el = this.$refs.list;
                    if (el) el.scrollTop = el.scrollHeight;
                },
                start() {
                    this.$nextTick(() => this.scrollDown());
                    this.timer = setInterval(() => this.fetchMessages(), 2500);
                }
            }
        }
    </script>
</x-app-layout>
