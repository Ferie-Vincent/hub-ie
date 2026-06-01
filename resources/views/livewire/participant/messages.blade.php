<div class="flex gap-4" style="height: calc(100vh - 10rem);">

    {{-- ═══ COLONNE GAUCHE : liste conversations ═══════════════════════════ --}}
    <aside class="w-72 shrink-0 flex flex-col rounded-2xl bg-white shadow-card overflow-hidden border border-gray-100">

        {{-- Entête --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-sm text-noir-profond">Conversations</h2>
            @if($unreadTotal > 0)
            <span class="rounded-full px-2 py-0.5 text-[11px] font-bold text-blanc-pur"
                  style="background: hsl(var(--orange-ivoire));">{{ $unreadTotal }}</span>
            @endif
        </div>

        {{-- Nouvelle conversation --}}
        @if($participantWorkshops->isNotEmpty())
        <div class="px-3 py-2 border-b border-gray-100 bg-gray-50/60">
            <select wire:model="workshopId"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-noir-profond focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-200 cursor-pointer"
                    aria-label="Démarrer une conversation avec un formateur">
                <option value="">+ Nouvelle conversation…</option>
                @foreach($participantWorkshops as $ws)
                <option value="{{ $ws->id }}">{{ $ws->title }}</option>
                @endforeach
            </select>
            @if($workshopId)
            <button wire:click="startConversation({{ $workshopId }})"
                    class="mt-2 w-full btn-fill rounded-lg px-3 py-2 text-sm font-semibold min-h-[44px] cursor-pointer">
                Démarrer la conversation
            </button>
            @endif
        </div>
        @endif

        {{-- Liste --}}
        <nav class="flex-1 overflow-y-auto divide-y divide-gray-50" aria-label="Liste des conversations">
            @forelse($conversations as $conv)
            @php
                $isActive = $activeConversation === $conv->id;
                $lastMsg  = $conv->messages->last();
                $unread   = $conv->messages->filter(fn($m) => is_null($m->read_at) && $m->sender_id !== auth()->id())->count();
            @endphp
            <button wire:click="selectConversation({{ $conv->id }})"
                    class="w-full px-4 py-3 text-left transition-colors duration-150 cursor-pointer hover:bg-gray-50
                           {{ $isActive ? 'bg-orange-50 border-l-[3px] border-orange-400' : '' }}"
                    aria-current="{{ $isActive ? 'true' : 'false' }}">

                <div class="flex items-start justify-between gap-2">
                    <span class="truncate text-sm font-semibold {{ $isActive ? 'text-orange-700' : 'text-slate-800' }}">
                        {{ $conv->workshop?->title ?? $conv->subject ?? 'Discussion' }}
                    </span>
                    <div class="flex shrink-0 flex-col items-end gap-1 ml-2">
                        @if($lastMsg)
                        <span class="text-[11px] text-slate-400 whitespace-nowrap">
                            {{ $lastMsg->created_at->locale('fr')->diffForHumans(short: true) }}
                        </span>
                        @endif
                        @if($unread > 0)
                        <span class="rounded-full px-1.5 py-px text-[10px] font-bold text-blanc-pur"
                              style="background: hsl(var(--orange-ivoire));">{{ $unread }}</span>
                        @endif
                    </div>
                </div>

                @if($lastMsg)
                <p class="mt-0.5 text-xs text-slate-500 line-clamp-1">
                    {{ $lastMsg->sender_id === auth()->id() ? 'Vous : ' : '' }}{{ $lastMsg->body }}
                </p>
                @endif

                @if($conv->is_closed)
                <span class="mt-1 inline-block rounded-full bg-gray-100 px-2 py-px text-[10px] font-medium text-gray-500">
                    Fermée
                </span>
                @endif
            </button>
            @empty
            <div class="px-4 py-10 text-center">
                <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <p class="text-sm text-slate-500">Aucune conversation</p>
                <p class="text-xs text-slate-400 mt-1">Sélectionnez un atelier ci-dessus.</p>
            </div>
            @endforelse
        </nav>
    </aside>

    {{-- ═══ COLONNE DROITE : thread ════════════════════════════════════════ --}}
    <main class="flex flex-1 flex-col rounded-2xl bg-white shadow-card overflow-hidden border border-gray-100"
          aria-label="Thread de conversation">

        @if($activeConversation)
        @php
            $conv = $conversations->firstWhere('id', $activeConversation);
            $prevDate = null;
        @endphp

        {{-- ── Header thread ──────────────────────────────────────── --}}
        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4 bg-gray-50/50">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold text-blanc-pur"
                 style="background: hsl(var(--orange-ivoire));">
                {{ mb_strtoupper(mb_substr($conv?->trainer?->first_name ?? 'H', 0, 1) . mb_substr($conv?->trainer?->last_name ?? 'I', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-800 truncate">
                    {{ $conv?->workshop?->title ?? $conv?->subject ?? 'Discussion' }}
                </p>
                <p class="text-xs text-slate-500">
                    Formateur : {{ $conv?->trainer?->first_name }} {{ $conv?->trainer?->last_name ?? 'Équipe Hub Import-Export' }}
                </p>
            </div>
            @if($conv?->is_closed)
            <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">Fermée</span>
            @endif
        </div>

        {{-- ── Thread messages ─────────────────────────────────────── --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-1 bg-slate-50/30"
             id="message-thread"
             x-data
             x-on:conversation-selected.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
             x-on:message-sent.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
             role="log"
             aria-live="polite"
             aria-label="Messages de la conversation">

            @forelse($activeMessages as $msg)
            @php
                $isMine   = $msg['sender_id'] === auth()->id();
                $msgDate  = \Carbon\Carbon::parse($msg['created_at']);
                $dateKey  = $msgDate->toDateString();
                $showDate = $dateKey !== $prevDate;
                $prevDate = $dateKey;

                // Sender display
                $senderName = $isMine
                    ? 'Vous'
                    : ($conv?->trainer?->first_name . ' ' . $conv?->trainer?->last_name ?? 'Formateur');
                $initials = $isMine
                    ? mb_strtoupper(mb_substr(auth()->user()->first_name, 0, 1) . mb_substr(auth()->user()->last_name, 0, 1))
                    : mb_strtoupper(mb_substr($conv?->trainer?->first_name ?? 'H', 0, 1) . mb_substr($conv?->trainer?->last_name ?? 'I', 0, 1));
            @endphp

            {{-- Séparateur de date --}}
            @if($showDate)
            <div class="flex items-center gap-3 py-3" role="separator" aria-label="{{ $msgDate->locale('fr')->isoFormat('dddd D MMMM YYYY') }}">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="shrink-0 rounded-full bg-white border border-gray-200 px-3 py-1 text-[11px] font-medium text-slate-500 shadow-sm">
                    {{ $msgDate->isToday() ? "Aujourd'hui" : ($msgDate->isYesterday() ? 'Hier' : $msgDate->locale('fr')->isoFormat('D MMMM')) }}
                </span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            @endif

            {{-- Bulle de message --}}
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2 group">

                {{-- Avatar (gauche = formateur) --}}
                @if(!$isMine)
                <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-[11px] font-bold text-blanc-pur mb-0.5"
                     style="background: hsl(var(--vert-ivoire));"
                     aria-hidden="true">
                    {{ $initials }}
                </div>
                @endif

                <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} max-w-[72%]">
                    {{-- Nom expéditeur --}}
                    <span class="text-[11px] font-medium mb-1 px-1
                        {{ $isMine ? 'text-orange-600' : 'text-slate-500' }}">
                        {{ $senderName }}
                    </span>

                    {{-- Corps du message --}}
                    <div class="rounded-2xl px-4 py-3 shadow-sm
                        {{ $isMine
                            ? 'rounded-br-sm text-blanc-pur'
                            : 'rounded-bl-sm bg-white text-slate-800 border border-gray-200' }}"
                         style="{{ $isMine ? 'background: hsl(var(--orange-ivoire));' : '' }}">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap break-words" style="max-width: 52ch;">
                            {{ $msg['body'] }}
                        </p>
                    </div>

                    {{-- Timestamp + statut lu --}}
                    <div class="flex items-center gap-1.5 mt-1 px-1">
                        <time class="text-[10px] text-slate-400"
                              datetime="{{ $msgDate->toIso8601String() }}"
                              title="{{ $msgDate->locale('fr')->isoFormat('D MMMM YYYY à HH:mm') }}">
                            {{ $msgDate->format('H:i') }}
                        </time>
                        @if($isMine && $msg['read_at'])
                        <svg class="h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-label="Lu" role="img">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @endif
                    </div>
                </div>

                {{-- Avatar (droite = moi) --}}
                @if($isMine)
                <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-[11px] font-bold text-blanc-pur mb-0.5"
                     style="background: hsl(var(--orange-brule));"
                     aria-hidden="true">
                    {{ $initials }}
                </div>
                @endif

            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full py-16 text-center">
                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-sm text-slate-500">Commencez la conversation…</p>
            </div>
            @endforelse
        </div>

        {{-- ── Zone de réponse ─────────────────────────────────────── --}}
        @unless($conv?->is_closed)
        <div class="border-t border-gray-100 px-4 py-3 bg-white">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label for="msg-input" class="sr-only">Votre message</label>
                    <textarea id="msg-input"
                              wire:model="newMessage"
                              wire:keydown.ctrl.enter="sendMessage"
                              rows="3"
                              maxlength="2000"
                              placeholder="Écrivez votre message… (Ctrl+Entrée pour envoyer)"
                              class="w-full resize-y rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-200 leading-relaxed min-h-[80px] max-h-48"
                              aria-label="Zone de saisie du message"
                              style="field-sizing: content;"></textarea>
                    @error('newMessage')
                    <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <button wire:click="sendMessage"
                        wire:loading.attr="disabled"
                        class="btn-fill shrink-0 rounded-xl px-4 py-3 min-h-[44px] min-w-[44px] cursor-pointer transition-opacity disabled:opacity-50"
                        aria-label="Envoyer le message">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
            <p class="mt-1.5 text-[10px] text-slate-400 text-right">
                {{ mb_strlen($newMessage ?? '') }}/2000 · Ctrl+Entrée pour envoyer
            </p>
        </div>
        @else
        <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 text-center">
            <p class="text-sm text-slate-500">Cette conversation est fermée. Contactez votre formateur pour la rouvrir.</p>
        </div>
        @endunless

        @else
        {{-- Aucune conversation sélectionnée --}}
        <div class="flex flex-1 flex-col items-center justify-center bg-slate-50/30 text-center px-6">
            <div class="rounded-full bg-gray-100 p-5 mb-4">
                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-700 mb-1">Vos messages</h3>
            <p class="text-sm text-slate-500 max-w-xs">Sélectionnez une conversation dans le panneau gauche, ou démarrez une nouvelle discussion avec votre formateur.</p>
        </div>
        @endif

    </main>
</div>
