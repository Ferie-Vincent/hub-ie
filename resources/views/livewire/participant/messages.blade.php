<div class="space-y-4" style="min-height: calc(100vh - 14rem);">

    @if($unreadTotal > 0)
    <div class="flex items-center gap-2 rounded-xl border px-4 py-3 text-sm"
         style="background: hsl(var(--orange-soft-bg)); border-color: hsl(var(--orange-ivoire)/0.3); color: hsl(var(--orange-brule));">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span class="font-medium">{{ $unreadTotal }} message{{ $unreadTotal > 1 ? 's' : '' }} non lu{{ $unreadTotal > 1 ? 's' : '' }}</span>
    </div>
    @endif

    <div class="flex gap-4" style="min-height: 600px;">

        {{-- ── Colonne gauche : liste conversations ────────────────── --}}
        <div class="w-72 shrink-0 flex flex-col rounded-2xl bg-white shadow-card overflow-hidden">

            {{-- Nouvelle conversation --}}
            @if($participantWorkshops->isNotEmpty())
            <div class="border-b border-sable p-3">
                <select wire:model="workshopId"
                        class="w-full rounded-lg border border-sable bg-blanc-creme px-3 py-2 text-sm text-noir-profond focus:outline-none focus:ring-1"
                        style="focus-ring-color: hsl(var(--orange-ivoire));">
                    <option value="">Démarrer une conversation…</option>
                    @foreach($participantWorkshops as $ws)
                    <option value="{{ $ws->id }}">{{ $ws->title }}</option>
                    @endforeach
                </select>
                @if($workshopId)
                <button wire:click="startConversation({{ $workshopId }})"
                        class="btn-fill mt-2 w-full rounded-lg px-3 py-2 text-sm font-semibold">
                    Créer la conversation
                </button>
                @endif
            </div>
            @endif

            {{-- Liste conversations --}}
            <div class="flex-1 overflow-y-auto divide-y divide-sable">
                @forelse($conversations as $conv)
                @php
                    $isActive = $activeConversation === $conv->id;
                    $unread   = collect($conv->messages)->filter(fn($m) => is_null($m['read_at']) && $m['sender_id'] !== auth()->id())->count();
                @endphp
                <button wire:click="selectConversation({{ $conv->id }})"
                        class="w-full px-4 py-3 text-left transition hover:bg-blanc-creme {{ $isActive ? 'bg-blanc-creme border-l-2' : '' }}"
                        style="{{ $isActive ? 'border-color: hsl(var(--orange-ivoire));' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <span class="truncate text-sm font-semibold text-noir-profond">
                            {{ $conv->workshop?->title ?? $conv->subject ?? 'Discussion générale' }}
                        </span>
                        <div class="flex shrink-0 flex-col items-end gap-1">
                            @if($conv->messages->isNotEmpty())
                            <span class="text-[10px] text-gris-500">
                                {{ $conv->messages->last()->created_at->format('d/m') }}
                            </span>
                            @endif
                            @if($unread > 0)
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold text-blanc-pur"
                                  style="background: hsl(var(--orange-ivoire));">{{ $unread }}</span>
                            @endif
                        </div>
                    </div>
                    @if($conv->messages->isNotEmpty())
                    <p class="mt-0.5 truncate text-xs text-gris-500">
                        {{ mb_substr($conv->messages->last()->body, 0, 55) }}
                    </p>
                    @endif
                    @if($conv->is_closed)
                    <span class="mt-1 inline-block rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-500">Fermée</span>
                    @endif
                </button>
                @empty
                <p class="px-4 py-8 text-center text-sm text-gris-500">
                    Aucune conversation.<br>
                    <span class="text-xs">Sélectionnez un atelier ci-dessus pour démarrer.</span>
                </p>
                @endforelse
            </div>
        </div>

        {{-- ── Colonne droite : thread ─────────────────────────────── --}}
        <div class="flex flex-1 flex-col rounded-2xl bg-white shadow-card overflow-hidden">

            @if($activeConversation)
            @php $conv = $conversations->firstWhere('id', $activeConversation); @endphp

            {{-- Header thread --}}
            <div class="flex items-center gap-3 border-b border-sable px-6 py-4">
                <div class="flex-1">
                    <p class="font-semibold text-noir-profond">
                        {{ $conv?->workshop?->title ?? $conv?->subject ?? 'Discussion' }}
                    </p>
                    <p class="text-xs text-gris-500">
                        Formateur : {{ $conv?->trainer?->name ?? 'Équipe Hub Import-Export' }}
                    </p>
                </div>
                @if($conv?->is_closed)
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500">Conversation fermée</span>
                @endif
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-blanc-creme/40"
                 x-data
                 x-on:conversation-selected.window="$el.scrollTop = $el.scrollHeight"
                 x-on:message-sent.window="$el.scrollTop = $el.scrollHeight">
                @forelse($activeMessages as $msg)
                @php $isMine = $msg['sender_id'] === auth()->id(); @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-sm rounded-2xl px-4 py-3 shadow-sm
                        {{ $isMine ? 'text-blanc-pur' : 'bg-white text-noir-profond border border-sable' }}"
                         style="{{ $isMine ? 'background: hsl(var(--orange-ivoire));' : '' }}">
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg['body'] }}</p>
                        <p class="mt-1 text-right text-[10px] {{ $isMine ? 'text-blanc-pur/70' : 'text-gris-500' }}">
                            {{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m à H:i') }}
                            @if(!$isMine && $msg['read_at'])
                            · Lu
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <p class="py-10 text-center text-sm text-gris-500">Commencez la conversation…</p>
                @endforelse
            </div>

            {{-- Formulaire réponse --}}
            @unless($conv?->is_closed)
            <div class="border-t border-sable px-6 py-4">
                <div class="flex gap-3">
                    <textarea wire:model="newMessage"
                              wire:keydown.ctrl.enter="sendMessage"
                              rows="2"
                              placeholder="Votre message… (Ctrl+Entrée pour envoyer)"
                              class="flex-1 resize-none rounded-xl border border-sable bg-blanc-creme px-4 py-3 text-sm text-noir-profond placeholder:text-gris-500/60 focus:border-orange-ivoire focus:outline-none focus:ring-1 focus:ring-orange-ivoire/30"></textarea>
                    <button wire:click="sendMessage"
                            wire:loading.attr="disabled"
                            class="btn-fill self-end rounded-xl px-4 py-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                @error('newMessage')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            @endunless

            @else
            {{-- Aucune conversation sélectionnée --}}
            <div class="flex flex-1 items-center justify-center bg-blanc-creme/40">
                <div class="text-center">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gris-500/25" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <p class="text-sm text-gris-500">Sélectionnez une conversation à gauche</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
