<x-filament-panels::page>
    <div class="max-w-lg">
        <x-filament::section>
            <x-slot name="heading">Inviter un collaborateur</x-slot>
            <x-slot name="description">
                L'invitation est valable 7 jours. Le collaborateur recevra un email avec un lien pour créer son compte.
            </x-slot>

            <form wire:submit="invite">
                {{ $this->form }}

                <div class="mt-6">
                    <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                        Envoyer l'invitation
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        @php
            $pending = \App\Models\AdminInvitation::whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->with('invitedBy')
                ->latest()
                ->get();
        @endphp

        @if($pending->isNotEmpty())
        <x-filament::section class="mt-6">
            <x-slot name="heading">Invitations en attente</x-slot>

            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($pending as $inv)
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $inv->email }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $inv->role }} · expire {{ $inv->expires_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-900/20 dark:text-yellow-300">
                        En attente
                    </span>
                </div>
                @endforeach
            </div>
        </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
