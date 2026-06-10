<x-filament-panels::page>

<style>
:root {
  --hd-orange:#E8741C; --hd-vert:#009A44; --hd-vert-s:#4CAF7A;
  --hd-sable:#C5A96A; --hd-noir:#0D0F0D; --hd-white:#FAFAF8;
  --hd-t1:rgba(13,15,13,.90); --hd-t2:rgba(13,15,13,.60);
  --hd-t3:rgba(13,15,13,.36); --hd-border:rgba(13,15,13,.08);
  --hd-shadow:0 1px 4px rgba(13,15,13,.06);
}
.dark {
  --hd-t1:rgba(250,250,248,.90); --hd-t2:rgba(250,250,248,.60);
  --hd-t3:rgba(250,250,248,.36); --hd-border:rgba(250,250,248,.10);
  --hd-shadow:none;
}
@keyframes hd-fade-up { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
@keyframes se-live { 0%,100%{opacity:1} 50%{opacity:.4} }
@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
.se-wrap { display:flex; flex-direction:column; gap:1rem; animation:hd-fade-up .4s ease both; padding-bottom:2rem; }
.se-card { background:#fff; border:1px solid var(--hd-border); border-radius:1rem; padding:1.5rem; box-shadow:var(--hd-shadow); }
.dark .se-card { background:rgba(255,255,255,.045); }
.se-kicker { font-family:'JetBrains Mono',monospace; font-size:.52rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--hd-t3); display:flex; align-items:center; gap:.375rem; margin-bottom:.875rem; }
.se-input { width:100%; text-align:center; font-family:'JetBrains Mono',monospace; font-size:2.5rem; font-weight:800; letter-spacing:.3em; border:2px solid var(--hd-border); border-radius:.75rem; padding:.75rem 1rem; outline:none; background:transparent; color:var(--hd-t1); transition:border-color .2s,box-shadow .2s; }
.se-input:focus { border-color:var(--hd-orange); box-shadow:0 0 0 3px rgba(232,116,28,.14); }
.se-btn { width:100%; background:var(--hd-orange); color:#fff; font-family:'Manrope',sans-serif; font-size:.875rem; font-weight:700; padding:.75rem; border-radius:.75rem; border:none; cursor:pointer; margin-top:1rem; transition:opacity .15s,transform .15s; letter-spacing:.01em; }
.se-btn:hover { opacity:.88; transform:scale(.99); }
.se-result { border-radius:.875rem; padding:1.25rem 1.375rem; margin-top:1rem; }
.se-result-name { font-family:'Fraunces',serif; font-size:1.375rem; font-weight:900; line-height:1.1; margin:.375rem 0 .125rem; }
.se-detail { font-family:'JetBrains Mono',monospace; font-size:.62rem; font-weight:600; letter-spacing:.06em; opacity:.72; }
.se-loc-select { font-family:'JetBrains Mono',monospace; font-size:.7rem; font-weight:700; background:transparent; border:1px solid var(--hd-border); border-radius:.5rem; padding:.375rem .75rem; color:var(--hd-t1); cursor:pointer; outline:none; }
</style>

<div class="se-wrap">

  {{-- Status bar --}}
  <div style="display:flex;align-items:center;gap:1rem;padding:.75rem 1.125rem;background:#fff;border:1px solid var(--hd-border);border-radius:.875rem;box-shadow:var(--hd-shadow);">
    <div style="display:flex;align-items:center;gap:.5rem;flex:1;">
      <span style="width:8px;height:8px;border-radius:50%;background:var(--hd-vert);display:inline-block;animation:se-live 2s ease-in-out infinite;flex-shrink:0;"></span>
      <span style="font-family:'JetBrains Mono',monospace;font-size:.54rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--hd-t3);">Pointage en direct — Hub Import-Export 2026</span>
    </div>
    <div style="display:flex;align-items:center;gap:.625rem;">
      <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;font-weight:600;color:var(--hd-t3);">Salle :</span>
      <select wire:model.live="location" class="se-loc-select dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600">
        <option value="CGECI">CGECI — Plateau</option>
        <option value="CCI-CI">CCI-CI — Plateau</option>
        <option value="SEEN">SEEN Hôtel — Abidjan-Plateau</option>
      </select>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

    {{-- QR Scanner --}}
    <div class="se-card">
      <div class="se-kicker">
        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16.97 16.97A7 7 0 1 1 7.03 7.03 7 7 0 0 1 16.97 16.97z"/></svg>
        Scanner QR
      </div>
      <div id="qr-reader" style="width:100%;min-height:240px;border-radius:.75rem;overflow:hidden;background:rgba(13,15,13,.04);border:1px solid var(--hd-border);"></div>
      <p style="font-family:'JetBrains Mono',monospace;font-size:.56rem;letter-spacing:.1em;text-transform:uppercase;color:var(--hd-t3);text-align:center;margin-top:.625rem;">Positionnez le badge face à la caméra</p>
    </div>

    {{-- Manual code --}}
    <div class="se-card" style="display:flex;flex-direction:column;">
      <div class="se-kicker">
        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
        Code à 6 chiffres
      </div>
      <div style="flex:1;display:flex;flex-direction:column;">
        <form wire:submit.prevent="submitManualCode">
          <input wire:model="manualCode"
                 type="text"
                 inputmode="numeric"
                 pattern="\d{6}"
                 maxlength="6"
                 placeholder="· · · · · ·"
                 autofocus
                 class="se-input dark:border-gray-600 dark:text-gray-100">
          <button type="submit" class="se-btn"
                  wire:loading.attr="disabled"
                  wire:loading.class="opacity-60 cursor-wait"
                  wire:target="submitManualCode">
            <span wire:loading.remove wire:target="submitManualCode">Valider le pointage</span>
            <span wire:loading wire:target="submitManualCode" style="display:inline-flex;align-items:center;gap:.5rem;justify-content:center;">
              <svg style="width:14px;height:14px;animation:spin 1s linear infinite;" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
              Traitement…
            </span>
          </button>
        </form>

        @if($lastScan)
        @php
        $rColors = match($scanResult) {
            'success' => ['bg'=>'rgba(0,154,68,.10)','border'=>'rgba(0,154,68,.28)','color'=>'var(--hd-vert)'],
            'warning' => ['bg'=>'rgba(232,116,28,.10)','border'=>'rgba(232,116,28,.28)','color'=>'var(--hd-orange)'],
            default   => ['bg'=>'rgba(239,68,68,.08)', 'border'=>'rgba(239,68,68,.24)', 'color'=>'#dc2626'],
        };
        @endphp
        <div class="se-result" style="background:{{ $rColors['bg'] }};border:1px solid {{ $rColors['border'] }};color:{{ $rColors['color'] }};">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;">
            @if($scanResult === 'success')
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($scanResult === 'warning')
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            @else
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
            <span style="font-family:'JetBrains Mono',monospace;font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">{{ $lastScan['message'] }}</span>
          </div>
          @if(isset($lastScan['name']))
          <div class="se-result-name">{{ $lastScan['name'] }}</div>
          <div class="se-detail" style="margin-top:.25rem;">{{ $lastScan['workshop'] }}</div>
          @if(isset($lastScan['check_in_code']))
          <div class="se-detail" style="margin-top:.2rem;">Code : <span style="font-family:'JetBrains Mono',monospace;font-size:.6rem;letter-spacing:.12em;">{{ $lastScan['check_in_code'] }}</span></div>
          @endif
          @endif
        </div>
        @endif
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
    script.onload = function () {
        const html5QrCode = new Html5Qrcode('qr-reader');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 200, height: 200 } },
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
