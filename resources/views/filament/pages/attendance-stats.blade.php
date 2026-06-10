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
@keyframes as-live { 0%,100%{opacity:1} 50%{opacity:.4} }
.as-wrap   { display:flex; flex-direction:column; gap:1.25rem; animation:hd-fade-up .4s ease both; padding-bottom:2.5rem; }
.as-card   { background:#fff; border:1px solid var(--hd-border); border-radius:1rem; padding:1.375rem 1.5rem; box-shadow:var(--hd-shadow); }
.dark .as-card { background:rgba(255,255,255,.045); }
.as-kicker { font-family:'JetBrains Mono',monospace; font-size:.52rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--hd-t3); margin-bottom:1rem; display:flex; align-items:center; gap:.375rem; }
.as-grid4  { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
.as-grid2  { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.as-kpi-val { font-family:'Fraunces',serif; font-size:2.75rem; font-weight:900; line-height:1; color:var(--hd-t1); }
.as-kpi-lbl { font-family:'Manrope',sans-serif; font-size:.68rem; font-weight:600; color:var(--hd-t2); margin-top:.375rem; }
.as-kpi-sub { font-family:'JetBrains Mono',monospace; font-size:.58rem; color:var(--hd-t3); margin-top:.125rem; }
.as-bar-wrap { display:flex; flex-direction:column; gap:.5rem; }
.as-bar-row  { display:flex; align-items:center; gap:.625rem; }
.as-bar-label { font-family:'Manrope',sans-serif; font-size:.72rem; font-weight:600; color:var(--hd-t2); width:6rem; flex-shrink:0; text-align:right; }
.as-bar-track { flex:1; height:10px; background:var(--hd-border); border-radius:99px; overflow:hidden; }
.as-bar-fill  { height:100%; border-radius:99px; background:var(--hd-vert); transition:width .6s ease; }
.as-bar-count { font-family:'JetBrains Mono',monospace; font-size:.62rem; font-weight:700; color:var(--hd-t1); width:2.5rem; flex-shrink:0; }
.as-day-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; }
.as-day-card { background:rgba(0,154,68,.06); border:1px solid rgba(0,154,68,.16); border-radius:.75rem; padding:.875rem .75rem; text-align:center; }
.as-day-val  { font-family:'Fraunces',serif; font-size:1.75rem; font-weight:900; color:var(--hd-vert); }
.as-day-lbl  { font-family:'JetBrains Mono',monospace; font-size:.54rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--hd-t3); margin-top:.25rem; }
.as-scan-row { display:flex; align-items:center; justify-content:space-between; padding:.5rem 0; border-bottom:1px solid var(--hd-border); }
.as-scan-row:last-child { border-bottom:none; }
.as-scan-name { font-family:'Manrope',sans-serif; font-size:.78rem; font-weight:700; color:var(--hd-t1); }
.as-scan-ws   { font-family:'Manrope',sans-serif; font-size:.66rem; color:var(--hd-t2); margin-top:.1rem; }
.as-scan-meta { text-align:right; flex-shrink:0; }
.as-scan-time { font-family:'JetBrains Mono',monospace; font-size:.62rem; font-weight:700; color:var(--hd-t1); }
.as-scan-method { font-family:'JetBrains Mono',monospace; font-size:.52rem; color:var(--hd-t3); }
.as-method-row { display:flex; align-items:center; gap:.75rem; padding:.5rem 0; }
.as-method-pill { display:inline-flex; align-items:center; gap:.375rem; font-family:'JetBrains Mono',monospace; font-size:.62rem; font-weight:700; padding:.25rem .625rem; border-radius:99px; }
.as-taux-ring { position:relative; display:flex; align-items:center; justify-content:center; }
.as-live-dot { width:7px; height:7px; border-radius:50%; background:var(--hd-vert); animation:as-live 2s ease-in-out infinite; display:inline-block; flex-shrink:0; }
</style>

<div class="as-wrap" wire:poll.15s="refresh">

  {{-- Live badge --}}
  <div style="display:flex; align-items:center; gap:.5rem; padding:.625rem 1rem; background:#fff; border:1px solid var(--hd-border); border-radius:.75rem; width:fit-content;">
    <span class="as-live-dot"></span>
    <span style="font-family:'JetBrains Mono',monospace; font-size:.52rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--hd-t3);">Actualisation automatique — 15 s</span>
  </div>

  {{-- KPIs --}}
  <div class="as-grid4">
    <div class="as-card">
      <div class="as-kicker">Total inscrits</div>
      <div class="as-kpi-val">{{ $totalInscrits }}</div>
      <div class="as-kpi-lbl">Participants confirmés</div>
    </div>
    <div class="as-card">
      <div class="as-kicker">Total pointés</div>
      <div class="as-kpi-val" style="color:var(--hd-vert)">{{ $totalPointes }}</div>
      <div class="as-kpi-lbl">Scans uniques enregistrés</div>
    </div>
    <div class="as-card">
      <div class="as-kicker">Taux de présence</div>
      <div class="as-kpi-val" style="color:{{ $tauxPresence >= 80 ? 'var(--hd-vert)' : ($tauxPresence >= 50 ? 'var(--hd-orange)' : '#dc2626') }}">
        {{ $tauxPresence }}&thinsp;%
      </div>
      <div class="as-kpi-lbl">Présents / inscrits</div>
    </div>
    <div class="as-card">
      <div class="as-kicker">Pointés aujourd'hui</div>
      <div class="as-kpi-val" style="color:var(--hd-orange)">{{ $pointesAujourdHui }}</div>
      <div class="as-kpi-sub">{{ now('Africa/Abidjan')->translatedFormat('l d F') }}</div>
    </div>
  </div>

  {{-- Par jour --}}
  <div class="as-card">
    <div class="as-kicker">
      <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Présences par jour
    </div>
    @php $maxJour = max(1, max($parJour)); @endphp
    <div class="as-day-grid">
      @foreach($parJour as $label => $count)
      <div class="as-day-card">
        <div class="as-day-val">{{ $count }}</div>
        <div class="as-day-lbl">{{ $label }}</div>
        <div style="margin-top:.625rem; height:4px; background:rgba(0,154,68,.12); border-radius:99px; overflow:hidden;">
          <div style="height:100%; width:{{ round($count / $maxJour * 100) }}%; background:var(--hd-vert); border-radius:99px;"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="as-grid2">

    {{-- Par atelier --}}
    <div class="as-card">
      <div class="as-kicker">
        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Par atelier
      </div>
      <div class="as-bar-wrap">
        @forelse($parAtelier as $row)
        <div class="as-bar-row">
          <div class="as-bar-label" title="{{ $row['title'] }}">
            {{ Str::limit($row['title'], 22) }}
          </div>
          <div class="as-bar-track">
            <div class="as-bar-fill" style="width:{{ $row['pct'] }}%;"></div>
          </div>
          <div class="as-bar-count">{{ $row['count'] }}</div>
        </div>
        @empty
        <p style="font-family:'Manrope',sans-serif;font-size:.72rem;color:var(--hd-t3);text-align:center;padding:1rem 0;">Aucun pointage enregistré</p>
        @endforelse
      </div>
    </div>

    {{-- Par salle + méthode --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

      {{-- Par salle --}}
      <div class="as-card">
        <div class="as-kicker">
          <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Par salle
        </div>
        @php $totalSalle = max(1, array_sum(array_column($parSalle, 'count'))); @endphp
        <div class="as-bar-wrap">
          @foreach($parSalle as $salle)
          @php
          $fillColor = match($salle['color']) {
            'blue'   => '#3b82f6',
            'indigo' => '#6366f1',
            'purple' => '#a855f7',
            default  => 'var(--hd-vert)',
          };
          @endphp
          <div class="as-bar-row">
            <div class="as-bar-label" title="{{ $salle['label'] }}">
              {{ Str::before($salle['label'], ' —') }}
            </div>
            <div class="as-bar-track">
              <div class="as-bar-fill" style="width:{{ round($salle['count'] / $totalSalle * 100) }}%; background:{{ $fillColor }};"></div>
            </div>
            <div class="as-bar-count">{{ $salle['count'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Par méthode --}}
      <div class="as-card">
        <div class="as-kicker">
          <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16.97 16.97A7 7 0 1 1 7.03 7.03 7 7 0 0 1 16.97 16.97z"/></svg>
          Par méthode de scan
        </div>
        @php $totalMethod = max(1, $countQr + $countCode); @endphp
        <div style="display:flex;flex-direction:column;gap:.625rem;">
          <div class="as-method-row">
            <span class="as-method-pill" style="background:rgba(0,154,68,.12);color:var(--hd-vert);">
              <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16.97 16.97A7 7 0 1 1 7.03 7.03 7 7 0 0 1 16.97 16.97z"/></svg>
              QR Code
            </span>
            <div class="as-bar-track" style="flex:1;">
              <div class="as-bar-fill" style="width:{{ round($countQr / $totalMethod * 100) }}%; background:var(--hd-vert);"></div>
            </div>
            <span class="as-bar-count">{{ $countQr }}</span>
          </div>
          <div class="as-method-row">
            <span class="as-method-pill" style="background:rgba(232,116,28,.10);color:var(--hd-orange);">
              <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
              Code 6 chiffres
            </span>
            <div class="as-bar-track" style="flex:1;">
              <div class="as-bar-fill" style="width:{{ round($countCode / $totalMethod * 100) }}%; background:var(--hd-orange);"></div>
            </div>
            <span class="as-bar-count">{{ $countCode }}</span>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- Derniers scans --}}
  <div class="as-card">
    <div class="as-kicker">
      <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Derniers pointages (10)
    </div>
    @forelse($derniersScans as $scan)
    <div class="as-scan-row">
      <div>
        <div class="as-scan-name">{{ $scan['name'] }}</div>
        <div class="as-scan-ws">{{ $scan['workshop'] }}</div>
      </div>
      <div class="as-scan-meta">
        <div class="as-scan-time">{{ $scan['heure'] }}</div>
        <div class="as-scan-method">{{ $scan['method'] }}</div>
      </div>
    </div>
    @empty
    <p style="font-family:'Manrope',sans-serif;font-size:.72rem;color:var(--hd-t3);text-align:center;padding:1rem 0;">Aucun pointage enregistré</p>
    @endforelse
  </div>

</div>

</x-filament-panels::page>
