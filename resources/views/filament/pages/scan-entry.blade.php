<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- QR Scan column --}}
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Scanner QR</h3>
            <div id="qr-reader" class="w-full rounded-lg overflow-hidden" style="min-height:250px;"></div>
            <p class="text-xs text-gray-400 mt-2 text-center">Positionnez le badge face à la caméra</p>
        </div>

        {{-- Manual code column --}}
        <div class="rounded-xl bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Code manuel</h3>
                <form wire:submit.prevent="submitManualCode" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Code à 6 chiffres
                        </label>
                        <input wire:model="manualCode"
                               type="text"
                               inputmode="numeric"
                               pattern="\d{6}"
                               maxlength="6"
                               placeholder="000000"
                               autofocus
                               class="w-full text-center text-3xl font-mono tracking-widest border-2 border-gray-300 dark:border-gray-600 rounded-lg py-3 px-4 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:bg-gray-700 dark:text-white outline-none">
                    </div>
                    <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                        Valider
                    </button>
                </form>
            </div>

            {{-- Last scan result --}}
            @if($lastScan)
            <div class="mt-6 rounded-lg p-4
                @if($scanResult === 'success') bg-green-50 border border-green-200 dark:bg-green-950 dark:border-green-800
                @elseif($scanResult === 'warning') bg-amber-50 border border-amber-200 dark:bg-amber-950 dark:border-amber-800
                @else bg-red-50 border border-red-200 dark:bg-red-950 dark:border-red-800
                @endif">
                <p class="text-sm font-semibold
                    @if($scanResult === 'success') text-green-700 dark:text-green-300
                    @elseif($scanResult === 'warning') text-amber-700 dark:text-amber-300
                    @else text-red-700 dark:text-red-300
                    @endif">
                    {{ $lastScan['message'] }}
                </p>
                @if(isset($lastScan['app']))
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-0.5">
                    <p><strong>{{ $lastScan['app']->user->full_name }}</strong></p>
                    <p>{{ $lastScan['app']->organization_name }}</p>
                    <p>Groupe : <strong>{{ $lastScan['app']->group_label }}</strong></p>
                    <p class="font-mono text-xs">{{ $lastScan['app']->reference_code }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- html5-qrcode lazy-loaded from CDN --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.onload = function () {
            const html5QrCode = new Html5Qrcode('qr-reader');
            html5QrCode.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                function (decodedText) {
                    const url = new URL(decodedText);
                    const token = url.pathname.split('/').pop();
                    @this.call('processQrToken', token);
                    html5QrCode.pause();
                    setTimeout(() => html5QrCode.resume(), 3000);
                },
                null
            ).catch(err => console.error('QR start error:', err));
        };
        document.head.appendChild(script);
    });
    </script>
</x-filament-panels::page>
