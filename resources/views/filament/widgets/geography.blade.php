<x-filament-widgets::widget>
    <x-filament::section heading="Top 15 villes">
        @if(empty($cities))
            <p class="text-sm text-gray-400">Aucune donnée</p>
        @else
            <div class="space-y-2.5">
                @foreach($cities as $city => $count)
                    @php $pct = round($count / $max * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-0.5">
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate max-w-[60%]">{{ $city }}</span>
                            <span class="font-bold tabular-nums text-gray-500 dark:text-gray-400">{{ $count }}</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-full rounded-full" style="width: {{ $pct }}%; background: hsl(25 68% 44%);"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
