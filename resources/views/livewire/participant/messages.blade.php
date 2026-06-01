<div class="flex h-[calc(100vh-4rem)] overflow-hidden">
    {{-- Liste des conversations --}}
    <aside class="hidden w-72 shrink-0 flex-col border-r border-white/10 bg-white/5 md:flex">
        <div class="flex items-center justify-between border-b border-white/10 px-4 py-4">
            <h2 class="font-semibold text-white">Messages</h2>
            @if($unreadTotal > 0)
                <span class="rounded-full bg-[hsl(var(--brand-hsl))] px-2 py-0.5 text-xs font-bold text-white">
                    {{ $unreadTotal }}
                </span>
            @endif
        </div>

        {{-- Nouvelle conversation --}}
        @if($participantWorkshops->isNotEmpty())
            <div class="border-b border-white/10 p-3">
                <select wire:model="workshopId" class="w-full rounded-lg border border-white/20 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-1 focus:ring-[hsl(var(--brand-hsl))]">
                    <option value="">Démarrer une conversation…</option>
                    @foreach($participantWorkshops as $ws)
                        <option value="{{ $ws->id }}">{{ $ws->title }}</option>
                    @endforeach
                </select>
                @if($workshopId)
                    <button
                        wire:click="startConversation({{ $workshopId }})"
                        class="btn-fill mt-2 w-full rounded-lg px-3 py-2 text-sm font-medium"
                    >
                        Créer la conversation
                    </button>
                @endif
            </div>
        @endif

        {{-- Liste --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $conv)
                @php
                    $lastMsg = $conv->messages->last();
                    $isActive = $activeConversation === $conv->id;
                    $unread = $conv->messages->whereNull('read_at')->where('sender_id', '!=', auth()->id())->count();
                @endphp
                <button
                    wire:click="selectConversation({{ $conv->id }})"
                    class="w-full border-b border-white/5 px-4 py-3 text-left transition hover:bg-white/10 {{ $isActive ? 'bg-white/10' : '' }}"
                >
                    <div class="flex items-start justify-between gap-2">
                        <span class="truncate text-sm font-medium text-white">
                            {{ $conv->workshop?->title ?? $conv->subject ?? 'Discussion générale' }}
                        </span>
                        <div class="flex shrink-0 flex-col items-end gap-1">
                            @if($lastMsg)
                                <span class="text-[10px] text-white/30">{{ $lastMsg->created_at->format('d/m') }}</span>
                            @endif
                            @if($unread > 0)
                                <span class="rounded-full bg-[hsl(var(--brand-hsl))] px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unread }}</span>
                            @endif
                        </div>
                    </div>
                    @if($lastMsg)
                        <p class="mt-0.5 truncate text-xs text-white/40">
                            {{ mb_substr($lastMsg['body'] ?? '', 0, 60) }}
                        </p>
                    @endif
                    @if($conv->is_closed)
                        <span class="mt-1 inline-block rounded-full bg-white/10 px-1.5 py-0.5 text-[10px] text-white/40">Fermée</span>
                    @endif
                </button>
            @empty
                <p class="px-4 py-6 text-center text-sm text-white/40">
                    Aucune conversation.<br>Démarrez une discussion ci-dessus.
                </p>
            @endforelse
        </div>
    </aside>

    {{-- Thread actif --}}
    <main class="flex flex-1 flex-col overflow-hidden">
        @if($activeConversation)
            @php
                $conv = $conversations->firstWhere('id', $activeConversation);
            @endphp

            {{-- Header thread --}}
            <div class="flex items-center gap-3 border-b border-white/10 px-6 py-4">
                <div class="flex-1">
                    <p class="font-semibold text-white">
                        {{ $conv?->workshop?->title ?? $conv?->subject ?? 'Discussion' }}
                    </p>
                    <p class="text-xs text-white/40">
                        Formateur : {{ $conv?->trainer?->name ?? 'Équipe Hub Import-Export' }}
                    </p>
                </div>
                @if($conv?->is_closed)
                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-white/50">Conversation fermée</span>
                @endif
            </div>

            {{-- Messages --}}
            <div
                class="flex-1 overflow-y-auto px-6 py-4 space-y-4"
                x-data
                x-on:conversation-selected.window="$el.scrollTop = $el.scrollHeight"
                x-on:message-sent.window="$el.scrollTop = $el.scrollHeight"
            >
                @forelse($activeMessages as $msg)
                    @php $isMine = $msg['sender_id'] === auth()->id(); @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-sm {{ $isMine ? 'bg-[hsl(var(--brand-hsl)/0.8)] text-white' : 'glass text-white/90' }} rounded-2xl px-4 py-3 shadow-sm">
                            <p class="text-sm leading-relaxed">{{ $msg['body'] }}</p>
                            <p class="mt-1 text-right text-[10px] {{ $isMine ? 'text-white/60' : 'text-white/30' }}">
                                {{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m à H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-white/30">Commencez la conversation…</p>
                @endforelse
            </div>

            {{-- Formulaire réponse --}}
            @unless($conv?->is_closed)
                <div class="border-t border-white/10 px-6 py-4">
                    <div class="flex gap-3">
                        <textarea
                            wire:model="newMessage"
                            wire:keydown.ctrl.enter="sendMessage"
                            rows="2"
                            placeholder="Votre message… (Ctrl+Entrée pour envoyer)"
                            class="flex-1 resize-none rounded-xl border border-white/20 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-white/30 focus:border-[hsl(var(--brand-hsl))] focus:outline-none focus:ring-1 focus:ring-[hsl(var(--brand-hsl))]"
                        ></textarea>
                        <button
                            wire:click="sendMessage"
                            wire:loading.attr="disabled"
                            class="btn-fill self-end rounded-xl px-4 py-3"
                        >
                            <x-heroicon-o-paper-airplane class="h-5 w-5" />
                        </button>
                    </div>
                    @error('newMessage')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endunless
        @else
            <div class="flex flex-1 items-center justify-center">
                <div class="text-center">
                    <x-heroicon-o-chat-bubble-left-right class="mx-auto mb-4 h-12 w-12 text-white/20" />
                    <p class="text-sm text-white/40">Sélectionnez une conversation à gauche</p>
                </div>
            </div>
        @endif
    </main>
</div>
