<x-filament-panels::page>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <x-heroicon-o-table-cells class="w-6 h-6 text-gray-500"/>
                <h3 class="font-semibold text-gray-900 dark:text-white">Toutes les candidatures</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Export complet : tous les dossiers soumis (hors brouillons et retirés), toutes les colonnes.</p>
            <x-filament::button wire:click="exportAll" icon="heroicon-m-arrow-down-tray" color="gray" class="w-full">
                Télécharger XLSX
            </x-filament::button>
        </div>

        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <x-heroicon-o-check-circle class="w-6 h-6 text-green-500"/>
                <h3 class="font-semibold text-gray-900 dark:text-white">Candidatures retenues</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Export filtré sur les dossiers au statut <strong>Retenu(e)</strong> uniquement.</p>
            <x-filament::button wire:click="exportAccepted" icon="heroicon-m-arrow-down-tray" color="success" class="w-full">
                Télécharger XLSX
            </x-filament::button>
        </div>

        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <x-heroicon-o-clock class="w-6 h-6 text-amber-500"/>
                <h3 class="font-semibold text-gray-900 dark:text-white">Liste d'attente</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Export filtré sur les dossiers au statut <strong>Liste d'attente</strong> uniquement.</p>
            <x-filament::button wire:click="exportWaitlisted" icon="heroicon-m-arrow-down-tray" color="warning" class="w-full">
                Télécharger XLSX
            </x-filament::button>
        </div>

    </div>
</x-filament-panels::page>
