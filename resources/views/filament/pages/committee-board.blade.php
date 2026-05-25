<x-filament-panels::page>
    {{-- Quotas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-4 text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $quotas['accepted'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Retenu(e)s</div>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-4 text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $quotas['quota'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Quota total</div>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-4 text-center">
            <div class="text-3xl font-bold {{ $quotas['remaining'] > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $quotas['remaining'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Places restantes</div>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-4 text-center">
            <div class="text-3xl font-bold text-amber-600">{{ $quotas['shortlisted'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Présélectionné(e)s</div>
        </div>
    </div>

    {{-- Kanban columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Éligibles --}}
        <div>
            <h3 class="text-sm font-semibold text-blue-600 uppercase tracking-widest mb-3">
                Éligibles ({{ $eligible->count() }})
            </h3>
            <div class="space-y-2">
                @forelse($eligible as $app)
                <div class="rounded-lg bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-3">
                    <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $app->user?->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $app->reference_code }}</p>
                    <div class="mt-2 flex gap-1 flex-wrap">
                        <button wire:click="transition({{ $app->id }}, 'under_review')"
                                class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700 hover:bg-purple-200">
                            Évaluer
                        </button>
                        <button wire:click="transition({{ $app->id }}, 'incomplete')"
                                class="text-xs px-2 py-0.5 rounded bg-orange-100 text-orange-700 hover:bg-orange-200">
                            Incomplet
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 italic">Aucun dossier éligible</p>
                @endforelse
            </div>
        </div>

        {{-- En évaluation --}}
        <div>
            <h3 class="text-sm font-semibold text-purple-600 uppercase tracking-widest mb-3">
                En évaluation ({{ $underReview->count() }})
            </h3>
            <div class="space-y-2">
                @forelse($underReview as $app)
                <div class="rounded-lg bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-3">
                    <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $app->user?->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $app->reference_code }} · Score : {{ $app->average_score !== null ? number_format($app->average_score, 1) : '—' }}</p>
                    <div class="mt-2 flex gap-1 flex-wrap">
                        <button wire:click="transition({{ $app->id }}, 'shortlisted')"
                                class="text-xs px-2 py-0.5 rounded bg-amber-100 text-amber-700 hover:bg-amber-200">
                            Présélectionner
                        </button>
                        <button wire:click="transition({{ $app->id }}, 'rejected')"
                                class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-700 hover:bg-red-200">
                            Refuser
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 italic">Aucun dossier en évaluation</p>
                @endforelse
            </div>
        </div>

        {{-- Présélectionnés --}}
        <div>
            <h3 class="text-sm font-semibold text-amber-600 uppercase tracking-widest mb-3">
                Présélectionné(e)s ({{ $shortlisted->count() }})
            </h3>
            <div class="space-y-2">
                @forelse($shortlisted as $app)
                <div class="rounded-lg bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-3">
                    <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $app->user?->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $app->reference_code }} · Score : {{ $app->average_score !== null ? number_format($app->average_score, 1) : '—' }}</p>
                    <div class="mt-2 flex gap-1 flex-wrap">
                        <button wire:click="transition({{ $app->id }}, 'accepted')"
                                class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700 hover:bg-green-200">
                            Retenir
                        </button>
                        <button wire:click="transition({{ $app->id }}, 'waitlisted')"
                                class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Liste d'attente
                        </button>
                        <button wire:click="transition({{ $app->id }}, 'rejected')"
                                class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-700 hover:bg-red-200">
                            Refuser
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 italic">Aucun dossier présélectionné</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
