<x-filament-widgets::widget>
    <x-filament::section heading="Entonnoir de sélection">
        <div class="space-y-3">
            @foreach($rows as $row)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $row['label'] }}</span>
                        <span class="font-bold tabular-nums" style="color: {{ $row['color'] }}">{{ $row['count'] }}</span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500"
                             style="width: {{ $row['pct'] }}%; background: {{ $row['color'] }};"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
