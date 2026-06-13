<div class="relative" x-data="{ open: @entangle('open') }">

    {{-- Bouton cloche --}}
    <button wire:click="toggle"
            class="relative flex h-9 w-9 items-center justify-center rounded-xl transition-colors hover:bg-sable/40"
            aria-label="Notifications">
        <svg class="h-5 w-5 text-gris-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($this->unreadCount > 0)
        <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full text-[10px] font-bold text-blanc-pur"
              style="background: hsl(var(--vert-ivoire));">
            {{ min($this->unreadCount, 9) }}
        </span>
        @endif
    </button>

    {{-- Panneau --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 top-11 z-50 w-80 rounded-2xl bg-blanc-pur shadow-xl ring-1 ring-noir-profond/8 overflow-hidden"
         x-cloak>

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-sable px-4 py-3">
            <p class="text-sm font-semibold text-noir-profond">Notifications</p>
            @if($this->unreadCount > 0)
            <button wire:click="markAllRead"
                    class="text-xs font-medium transition-colors hover:text-vert-fonce"
                    style="color: hsl(var(--vert-ivoire));">
                Tout marquer lu
            </button>
            @endif
        </div>

        {{-- Liste --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-sable/60">
            @forelse($this->notifications as $notif)
            @php
                $data = $notif->data;
                $isUnread = is_null($notif->read_at);
                $isMessage = ($data['type'] ?? '') === 'new_message';
                $isFile    = ($data['type'] ?? '') === 'new_course_file';
                $link = $isMessage ? route('participant.messages') : route('participant.downloads');
            @endphp
            <a href="{{ $link }}"
               wire:click="markRead('{{ $notif->id }}')"
               class="flex items-start gap-3 px-4 py-3 transition-colors hover:bg-sable/30 {{ $isUnread ? 'bg-vert-soft-bg/20' : '' }}">

                {{-- Icône --}}
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl"
                     style="background: hsl(var(--{{ $isMessage ? 'vert' : 'orange' }}-soft-bg));">
                    @if($isMessage)
                    <svg class="h-4 w-4" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    @else
                    <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @endif
                </div>

                {{-- Texte --}}
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-noir-profond leading-snug">
                        @if($isMessage)
                            {{ $data['sender_name'] ?? 'Message reçu' }}
                        @else
                            Nouveau document
                        @endif
                    </p>
                    <p class="mt-0.5 text-xs text-gris-500 line-clamp-2 leading-snug">
                        @if($isMessage)
                            {{ $data['preview'] ?? '' }}
                        @else
                            {{ $data['file_title'] ?? '' }}
                        @endif
                    </p>
                    <p class="mt-1 text-[10px] text-gris-500/60">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>

                @if($isUnread)
                <span class="mt-2 h-2 w-2 shrink-0 rounded-full" style="background:hsl(var(--vert-ivoire));"></span>
                @endif
            </a>
            @empty
            <div class="px-4 py-8 text-center text-sm text-gris-500">
                Aucune notification
            </div>
            @endforelse
        </div>
    </div>
</div>
