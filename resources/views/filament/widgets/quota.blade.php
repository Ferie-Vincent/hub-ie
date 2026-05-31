<x-filament-widgets::widget>
    <x-filament::section heading="Quotas inclusivité">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
            {{ $total }} / {{ $quota }} auditeurs retenus
        </p>

        <div class="space-y-5">
            {{-- Femmes ≥ 50 % --}}
            <div>
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Femmes</span>
                    <span class="text-xs font-semibold" style="color: hsl(330 60% 55%)">
                        {{ $women }} · {{ $womenPct }} % <span class="text-gray-400">(cible {{ $womenTarget }} %)</span>
                    </span>
                </div>
                <div class="relative h-3 w-full rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $womenPct }}%; background: hsl(330 60% 55%);"></div>
                    <div class="absolute top-0 h-full w-px bg-gray-400 dark:bg-gray-500" style="left: {{ $womenTarget }}%;"></div>
                </div>
            </div>

            {{-- Moins de 35 ans ≥ 40 % --}}
            <div>
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Moins de 35 ans</span>
                    <span class="text-xs font-semibold" style="color: hsl(25 68% 44%)">
                        {{ $young }} · {{ $youngPct }} % <span class="text-gray-400">(cible {{ $youngTarget }} %)</span>
                    </span>
                </div>
                <div class="relative h-3 w-full rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $youngPct }}%; background: hsl(25 68% 44%);"></div>
                    <div class="absolute top-0 h-full w-px bg-gray-400 dark:bg-gray-500" style="left: {{ $youngTarget }}%;"></div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
