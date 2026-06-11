<x-filament-panels::page>

@php
/* ── Sparkline geometry ───────────────────────────────────── */
$tlMax  = max(max($sparklineCumul ?: [0]), 1);
$tlN    = count($sparklineCumul);
$tlW    = 440; $tlH = 80; $tlPad = 6;
$tlPts  = collect($sparklineCumul)->map(function($v, $i) use ($tlN, $tlW, $tlH, $tlPad, $tlMax) {
    $x = round($i / max($tlN - 1, 1) * $tlW, 2);
    $y = round($tlH - $tlPad - ($v / $tlMax * ($tlH - $tlPad * 2)), 2);
    return "$x,$y";
})->join(' ');
$tlFill = "M0,{$tlH} " . collect($sparklineCumul)->map(function($v, $i) use ($tlN, $tlW, $tlH, $tlPad, $tlMax) {
    $x = round($i / max($tlN - 1, 1) * $tlW, 2);
    $y = round($tlH - $tlPad - ($v / $tlMax * ($tlH - $tlPad * 2)), 2);
    return "L{$x},{$y}";
})->join(' ') . " L{$tlW},{$tlH} Z";

/* ── Gender donut ─────────────────────────────────────────── */
$gTotal  = max($genderTotal, 1);
$gR = 36; $gCircum = 2 * M_PI * $gR;
$gDashF  = $genderData['F'] / $gTotal * $gCircum;
$gDashM  = $genderData['M'] / $gTotal * $gCircum;
$gDashX  = $genderData['X'] / $gTotal * $gCircum;
$gOffM   = round(-$gDashF, 4);
$gOffX   = round(-$gDashF - $gDashM, 4);

/* ── Computed values ──────────────────────────────────────── */
$acceptedPct = round($accepted / max($quota, 1) * 100);
$catMax  = max(array_values($categoryData ?: [1]));
$ageMax  = max(array_values($ageData ?: [1]));
$wsMax   = max(array_values($workshopData ?: [1]));
$geoMax  = max(array_values($geographyData ?: [1]));
$refMax  = max(array_values($referralData ?: [1]));
$attMax  = max(max($attendanceData ?: [1]), 10);
@endphp

{{-- ══ STYLES ══════════════════════════════════════════════════════════════ --}}
<style>
/* ── Hub design tokens ──────────────────────────────────── */
:root {
  --hd-orange:  #E8741C;
  --hd-amber:   #C45A0A;
  --hd-gold:    #F09E62;
  --hd-vert:    #009A44;
  --hd-vert-s:  #4CAF7A;
  --hd-sable:   #C5A96A;
  --hd-noir:    #0D0F0D;
  --hd-cream:   #F5F2ED;
  --hd-white:   #FAFAF8;
  --hd-t1:      rgba(13,15,13,0.90);
  --hd-t2:      rgba(13,15,13,0.60);
  --hd-t3:      rgba(13,15,13,0.36);
  --hd-border:  rgba(13,15,13,0.08);
  --hd-track:   rgba(13,15,13,0.07);
  --hd-shadow:  0 1px 4px rgba(13,15,13,0.06);
}
.dark {
  --hd-t1:    rgba(250,250,248,0.90);
  --hd-t2:    rgba(250,250,248,0.60);
  --hd-t3:    rgba(250,250,248,0.36);
  --hd-border: rgba(250,250,248,0.10);
  --hd-track:  rgba(250,250,248,0.09);
  --hd-shadow: none;
}

/* ── Keyframes ──────────────────────────────────────────── */
@keyframes hd-pulse-dot {
  0%,100% { opacity:1; transform:scale(1); }
  50%      { opacity:.35; transform:scale(.7); }
}
@keyframes hd-fade-up {
  from { opacity:0; transform:translateY(10px); }
  to   { opacity:1; transform:translateY(0); }
}
@keyframes hd-grow-x {
  from { width:0; }
  to   { width:100%; }
}

/* ── Layout ─────────────────────────────────────────────── */
.hd-wrap { display:flex; flex-direction:column; gap:1rem; padding-bottom:2.5rem;
           animation:hd-fade-up .45s ease both; }

/* ── Cards ──────────────────────────────────────────────── */
.hd-card {
  background:#fff; border:1px solid var(--hd-border);
  border-radius:1rem; padding:1.25rem 1.375rem;
  box-shadow:var(--hd-shadow); position:relative; overflow:hidden;
}
.dark .hd-card { background:rgba(255,255,255,.045); }
.hd-accent { position:absolute; top:0; left:0; right:0; height:2.5px; }

/* ── Grids ───────────────────────────────────────────────── */
.hd-g4 { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
.hd-g3 { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.hd-g2 { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
.hd-s2 { grid-column:span 2; }
@media(max-width:1280px){ .hd-g4{grid-template-columns:repeat(2,1fr);} }
@media(max-width:900px){
  .hd-g4,.hd-g3,.hd-g2{grid-template-columns:1fr;}
  .hd-s2{grid-column:span 1;}
}

/* ── Typography ─────────────────────────────────────────── */
.hd-kicker {
  font-family:'JetBrains Mono',monospace;
  font-size:.52rem; font-weight:700; letter-spacing:.22em;
  text-transform:uppercase; color:var(--hd-t3);
  display:block; margin-bottom:.5rem;
}
.hd-num {
  font-family:'Fraunces',serif;
  font-size:2.625rem; font-weight:900; line-height:1;
  color:var(--hd-t1); margin:.375rem 0 .25rem;
}
.hd-num-sm  { font-size:1.875rem; }
.hd-num-unit{ font-size:1rem; font-weight:400; color:var(--hd-t3); margin-left:.2rem; }
.hd-sub {
  font-family:'JetBrains Mono',monospace;
  font-size:.57rem; font-weight:700; letter-spacing:.15em;
  text-transform:uppercase; color:var(--hd-t3);
}

/* ── Progress bars ──────────────────────────────────────── */
.hd-track { height:5px; background:var(--hd-track);
            border-radius:9999px; overflow:hidden; position:relative; }
.hd-fill  { height:100%; border-radius:9999px; }
.hd-marker{ position:absolute; top:0; bottom:0; width:1.5px;
            background:rgba(13,15,13,.25); }
.dark .hd-marker{ background:rgba(250,250,248,.25); }

/* ── Horizontal bar rows ────────────────────────────────── */
.hd-bar { display:grid; grid-template-columns:1fr 110px 34px;
          gap:.625rem; align-items:center; margin-bottom:.5rem; }
.hd-bar:last-child{ margin-bottom:0; }
.hd-bar-lbl { font-size:.72rem; color:var(--hd-t2); white-space:nowrap;
              overflow:hidden; text-overflow:ellipsis; }
.hd-bar-val { font-family:'JetBrains Mono',monospace; font-size:.68rem;
              font-weight:700; color:var(--hd-t2); text-align:right; }

/* ── Funnel rows ────────────────────────────────────────── */
.hd-funnel { margin-bottom:.625rem; }
.hd-funnel:last-child{ margin-bottom:0; }
.hd-funnel-hd{ display:flex; justify-content:space-between;
               align-items:baseline; margin-bottom:.3rem; }

/* ── Event header ───────────────────────────────────────── */
.hd-header {
  background:var(--hd-noir); color:var(--hd-white);
  border-radius:1.25rem; overflow:hidden; position:relative;
}
.hd-header-body { position:relative; z-index:10; padding:1.75rem 2rem; }
.hd-header-glow {
  position:absolute; inset:0; pointer-events:none;
  background:
    radial-gradient(ellipse at 75% 40%, rgba(232,116,28,.20) 0%, transparent 58%),
    radial-gradient(ellipse at 15% 75%, rgba(0,154,68,.12) 0%, transparent 55%);
}

/* ── Action pills ───────────────────────────────────────── */
.hd-pill {
  display:inline-flex; align-items:center; gap:.5rem;
  padding:.45rem .9rem; border-radius:.625rem;
  font-size:.75rem; font-weight:600;
  text-decoration:none; border:1px solid transparent;
  transition:transform .2s, box-shadow .2s;
}
.hd-pill:hover{ transform:translateY(-1px); box-shadow:0 4px 16px rgba(0,0,0,.12); }

/* ── KPI badge ──────────────────────────────────────────── */
.hd-badge {
  display:inline-flex; align-items:center; gap:.3rem;
  font-size:.66rem; font-weight:700;
  padding:.2rem .5rem; border-radius:9999px; margin-top:.5rem;
}

/* ── Stat glass (inside dark header) ───────────────────── */
.hd-stat-glass {
  border-radius:.875rem; padding:.875rem 1.125rem;
  text-align:center; min-width:84px;
}
</style>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="hd-wrap">

{{-- ── 1. EVENT COMMAND HEADER ──────────────────────────────────────────── --}}
<div class="hd-header">
  {{-- Tricolor stripe CI --}}
  <div style="height:3px;background:linear-gradient(to right,var(--hd-orange),rgba(250,250,248,.28) 50%,var(--hd-vert));"></div>

  <div class="hd-header-glow"></div>
  <div class="hd-header-body">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;">

      {{-- Identity --}}
      <div>
        <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:.875rem;">
          <span style="width:8px;height:8px;border-radius:50%;background:var(--hd-orange);display:inline-block;animation:hd-pulse-dot 2s ease-in-out infinite;flex-shrink:0;"></span>
          <span style="font-family:'JetBrains Mono',monospace;font-size:.52rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(240,158,98,.82);">Centre de pilotage — Hub Import-Export 2026</span>
        </div>
        <h1 style="font-family:'Fraunces',serif;font-size:clamp(1.5rem,3vw,2.25rem);font-weight:900;color:var(--hd-white);line-height:1.06;letter-spacing:-.02em;margin-bottom:.5rem;">
          Abidjan · 22–25 juin 2026
        </h1>
        <p style="font-family:'Manrope',sans-serif;font-size:.82rem;color:rgba(250,250,248,.48);max-width:340px;line-height:1.5;">
          Direction Générale du Commerce Extérieur — Pilotage opérationnel en temps réel
        </p>
      </div>

      {{-- Stats + countdown --}}
      <div style="display:flex;align-items:stretch;gap:.75rem;flex-wrap:wrap;">

        {{-- J-X countdown --}}
        <div class="hd-stat-glass" style="background:rgba(232,116,28,.15);border:1px solid rgba(232,116,28,.30);">
          <p style="font-family:'Fraunces',serif;font-size:2.625rem;font-weight:900;color:var(--hd-orange);line-height:1;">{{ $daysToEvent }}</p>
          <p style="font-family:'JetBrains Mono',monospace;font-size:.48rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(240,158,98,.70);margin-top:.25rem;">Jours restants</p>
        </div>

        {{-- Hero stat: Total --}}
        <div class="hd-stat-glass" style="background:rgba(250,250,248,.08);border:1px solid rgba(250,250,248,.16);">
          <p style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:var(--hd-white);line-height:1;">{{ $totalSubmitted }}</p>
          <p style="font-family:'JetBrains Mono',monospace;font-size:.48rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(250,250,248,.50);margin-top:.25rem;">Candidatures</p>
        </div>

        {{-- Hero stat: Accepted --}}
        <div class="hd-stat-glass" style="background:rgba(0,154,68,.16);border:1px solid rgba(0,154,68,.32);">
          <p style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:#4CAF7A;line-height:1;">{{ $accepted }}<span style="font-size:.9rem;font-weight:400;color:rgba(76,175,122,.55);"> /{{ $quota }}</span></p>
          <p style="font-family:'JetBrains Mono',monospace;font-size:.48rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(76,175,122,.65);margin-top:.25rem;">Retenus</p>
        </div>

        {{-- Hero stat: To evaluate --}}
        <div class="hd-stat-glass" style="background:rgba(196,90,10,.15);border:1px solid rgba(196,90,10,.32);">
          <p style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:var(--hd-gold);line-height:1;">{{ $toEvaluate }}</p>
          <p style="font-family:'JetBrains Mono',monospace;font-size:.48rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(240,158,98,.60);margin-top:.25rem;">À évaluer</p>
        </div>

      </div>
    </div>

    {{-- Quick action pills --}}
    <div style="display:flex;flex-wrap:wrap;gap:.625rem;margin-top:1.375rem;padding-top:1.25rem;border-top:1px solid rgba(250,250,248,.10);">
      <a href="/admin/committee-board" class="hd-pill" style="color:var(--hd-orange);background:rgba(232,116,28,.15);border-color:rgba(232,116,28,.32);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Tableau de délibération
      </a>
      <a href="/admin/scan-entry" class="hd-pill" style="color:#4CAF7A;background:rgba(0,154,68,.15);border-color:rgba(0,154,68,.32);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16.97 16.97A7 7 0 1 1 7.03 7.03 7 7 0 0 1 16.97 16.97z"/></svg>
        Scan entrée
      </a>
      <a href="/admin/applications" class="hd-pill" style="color:rgba(250,250,248,.75);background:rgba(250,250,248,.08);border-color:rgba(250,250,248,.18);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Candidatures
      </a>
      <a href="/admin/export-center" class="hd-pill" style="color:rgba(250,250,248,.75);background:rgba(250,250,248,.08);border-color:rgba(250,250,248,.18);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Exports
      </a>
      <a href="/admin/news" class="hd-pill" style="color:rgba(250,250,248,.75);background:rgba(250,250,248,.08);border-color:rgba(250,250,248,.18);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        Actualités
      </a>
    </div>

  </div>
</div>

{{-- ── 2. KPI STRIP ──────────────────────────────────────────────────────── --}}
<div class="hd-g4">

  {{-- Candidatures reçues --}}
  <div class="hd-card">
    <div class="hd-accent" style="background:var(--hd-orange);"></div>
    <span class="hd-kicker">Candidatures reçues</span>
    <p class="hd-num">{{ number_format($totalSubmitted) }}</p>
    <p class="hd-sub">total soumises</p>
    @if($delta24h > 0)
    <div class="hd-badge" style="background:rgba(232,116,28,.12);color:var(--hd-amber);">
      <svg width="9" height="9" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M5 8V2M5 2L2 5M5 2L8 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      +{{ $delta24h }} / 24h
    </div>
    @endif
  </div>

  {{-- Auditeurs retenus --}}
  <div class="hd-card">
    <div class="hd-accent" style="background:var(--hd-vert);"></div>
    <span class="hd-kicker">Auditeurs retenus</span>
    <p class="hd-num hd-num-sm">{{ $accepted }}<span class="hd-num-unit">/ {{ $quota }}</span></p>
    <div class="hd-track" style="margin:.75rem 0 .5rem;">
      <div class="hd-fill" style="width:{{ $acceptedPct }}%;background:var(--hd-vert);"></div>
      <div class="hd-marker" style="left:100%;"></div>
    </div>
    <p class="hd-sub">{{ $acceptedPct }} % du quota atteint</p>
  </div>

  {{-- Présents aujourd'hui --}}
  <div class="hd-card">
    <div class="hd-accent" style="background:var(--hd-vert-s);"></div>
    <span class="hd-kicker">Présents aujourd'hui</span>
    <p class="hd-num" style="{{ $presentToday == 0 ? 'color:var(--hd-t3)' : '' }}">{{ $presentToday }}</p>
    <p class="hd-sub">pointage {{ now()->translatedFormat('d M') }}</p>
    <div class="hd-badge" style="background:var(--hd-track);color:var(--hd-t3);">
      <svg width="9" height="9" viewBox="0 0 10 10" fill="none" aria-hidden="true"><circle cx="5" cy="5" r="3.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 3v2l1.5 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
      Données en temps réel
    </div>
  </div>

  {{-- Dossiers à évaluer --}}
  <div class="hd-card">
    <div class="hd-accent" style="background:var(--hd-sable);"></div>
    <span class="hd-kicker">Dossiers à évaluer</span>
    <p class="hd-num" style="{{ $toEvaluate == 0 ? 'color:var(--hd-t3)' : 'color:var(--hd-amber);' }}">{{ $toEvaluate }}</p>
    <p class="hd-sub">éligibles + en cours</p>
    <a href="/admin/committee-board" class="hd-badge" style="background:rgba(197,169,106,.14);color:var(--hd-sable);text-decoration:none;margin-top:.5rem;">
      <svg width="9" height="9" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M1.5 3h7M1.5 5h5M1.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
      Accéder au comité →
    </a>
  </div>

</div>

{{-- ── 3. TIMELINE + GENRE ───────────────────────────────────────────────── --}}
<div class="hd-g3">

  <div class="hd-card hd-s2">
    <span class="hd-kicker">Évolution des candidatures — 30 derniers jours (cumulé)</span>
    <div style="position:relative;height:96px;margin-top:.5rem;">
      <svg viewBox="0 0 440 80" preserveAspectRatio="none" style="position:absolute;inset:0;width:100%;height:100%;">
        <defs>
          <linearGradient id="tlGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#E8741C" stop-opacity=".28"/>
            <stop offset="100%" stop-color="#E8741C" stop-opacity=".02"/>
          </linearGradient>
        </defs>
        <path d="{{ $tlFill }}" fill="url(#tlGrad)"/>
        <polyline points="{{ $tlPts }}" fill="none" stroke="#E8741C" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
        @php $lastPt = explode(' ',$tlPts); [$lx,$ly] = explode(',',end($lastPt)); @endphp
        <circle cx="{{ $lx }}" cy="{{ $ly }}" r="3.5" fill="#E8741C"/>
        <circle cx="{{ $lx }}" cy="{{ $ly }}" r="7" fill="#E8741C" opacity=".18"/>
      </svg>
    </div>
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-top:.75rem;">
      <div>
        <p style="font-family:'Fraunces',serif;font-size:1.5rem;font-weight:900;color:var(--hd-t1);line-height:1;">{{ end($sparklineCumul) }}</p>
        <p class="hd-sub" style="margin-top:.25rem;">Cumulé 30 jours</p>
      </div>
      <div style="text-align:right;">
        <p style="font-size:.75rem;color:var(--hd-t3);">Taux de croissance</p>
        @php $growth = ($sparklineCumul[0] ?? 0) > 0 ? round((end($sparklineCumul) - $sparklineCumul[0]) / $sparklineCumul[0] * 100) : 100; @endphp
        <p style="font-family:'Fraunces',serif;font-size:1.25rem;font-weight:900;color:var(--hd-vert);">+{{ $growth }} %</p>
      </div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:.5rem;">
      @foreach($sparklineLabels as $label)
        @if($label)<span style="font-family:'JetBrains Mono',monospace;font-size:.5rem;color:var(--hd-t3);font-weight:700;">{{ $label }}</span>@endif
      @endforeach
    </div>
  </div>

  {{-- Genre donut --}}
  <div class="hd-card" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
    <span class="hd-kicker" style="width:100%;text-align:center;">Répartition par genre</span>
    <svg viewBox="0 0 100 100" style="width:112px;height:112px;transform:rotate(-90deg);margin:.25rem 0;">
      <circle cx="50" cy="50" r="{{ $gR }}" fill="none" stroke="var(--hd-track)" stroke-width="13"/>
      @if($genderData['F'] > 0)
      <circle cx="50" cy="50" r="{{ $gR }}" fill="none" stroke="#E8741C" stroke-width="13"
        stroke-dasharray="{{ round($gDashF,3) }} {{ round($gCircum,3) }}" stroke-dashoffset="0" stroke-linecap="butt"/>
      @endif
      @if($genderData['M'] > 0)
      <circle cx="50" cy="50" r="{{ $gR }}" fill="none" stroke="#4CAF7A" stroke-width="13"
        stroke-dasharray="{{ round($gDashM,3) }} {{ round($gCircum,3) }}" stroke-dashoffset="{{ round($gOffM,3) }}" stroke-linecap="butt"/>
      @endif
      @if($genderData['X'] > 0)
      <circle cx="50" cy="50" r="{{ $gR }}" fill="none" stroke="var(--hd-track)" stroke-width="13"
        stroke-dasharray="{{ round($gDashX,3) }} {{ round($gCircum,3) }}" stroke-dashoffset="{{ round($gOffX,3) }}" stroke-linecap="butt"/>
      @endif
      <text x="50" y="44" text-anchor="middle" font-family="Fraunces,serif" font-size="17" font-weight="900"
        fill="var(--hd-t1)" transform="rotate(90 50 50)">{{ $genderTotal }}</text>
      <text x="50" y="56" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="5" font-weight="700"
        fill="var(--hd-t3)" transform="rotate(90 50 50)">CANDIDATS</text>
    </svg>
    <div style="display:flex;flex-direction:column;gap:.375rem;width:100%;max-width:148px;">
      @foreach([['F','Femmes','#E8741C'],['M','Hommes','#4CAF7A'],['X','Autre / NR','var(--hd-track)']] as [$k,$lbl,$col])
      <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;">
        <div style="display:flex;align-items:center;gap:.375rem;">
          <span style="width:7px;height:7px;border-radius:50%;background:{{ $col }};flex-shrink:0;display:inline-block;border:{{ $k==='X' ? '1px solid var(--hd-border)' : 'none' }};"></span>
          <span style="font-size:.7rem;color:var(--hd-t2);">{{ $lbl }}</span>
        </div>
        <span style="font-family:'JetBrains Mono',monospace;font-size:.68rem;font-weight:700;color:var(--hd-t2);">{{ $genderData[$k] }}</span>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- ── 4. PROFILS + TRANCHES D'ÂGE ──────────────────────────────────────── --}}
<div class="hd-g3">

  <div class="hd-card hd-s2">
    <span class="hd-kicker">Profils professionnels</span>
    @forelse($categoryData as $lbl => $count)
    @php $pct = round($count / max($catMax,1) * 100); @endphp
    <div class="hd-bar">
      <span class="hd-bar-lbl">{{ $lbl }}</span>
      <div class="hd-track"><div class="hd-fill" style="width:{{ $pct }}%;background:var(--hd-orange);"></div></div>
      <span class="hd-bar-val">{{ $count }}</span>
    </div>
    @empty
    <p style="font-size:.78rem;color:var(--hd-t3);">Aucune donnée</p>
    @endforelse
  </div>

  <div class="hd-card" style="display:flex;flex-direction:column;">
    <span class="hd-kicker">Tranches d'âge</span>
    <div style="display:flex;align-items:flex-end;gap:.5rem;flex:1;min-height:80px;padding-top:.375rem;">
      @foreach($ageData as $lbl => $count)
      <div style="display:flex;flex-direction:column;align-items:center;flex:1;height:100%;">
        <span style="font-family:'JetBrains Mono',monospace;font-size:.6rem;font-weight:700;color:var(--hd-t2);flex-shrink:0;">{{ $count }}</span>
        <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;width:100%;padding-bottom:.3rem;">
          <div style="width:100%;border-radius:3px 3px 0 0;background:{{ $count > 0 ? 'var(--hd-orange)' : 'var(--hd-track)' }};height:{{ max(round($count / max($ageMax,1) * 100), $count > 0 ? 5 : 2) }}%;min-height:{{ $count > 0 ? '4px' : '2px' }};"></div>
        </div>
        <span style="font-family:'JetBrains Mono',monospace;font-size:.47rem;font-weight:700;letter-spacing:.04em;color:var(--hd-t3);text-align:center;line-height:1.2;white-space:pre-line;flex-shrink:0;">{{ str_replace(' ',"\n",$lbl) }}</span>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- ── 5. ATELIERS + QUOTAS ──────────────────────────────────────────────── --}}
<div class="hd-g2">

  {{-- Workshops --}}
  <div class="hd-card">
    <span class="hd-kicker">Choix d'ateliers</span>
    @php $wCols=['var(--hd-amber)','var(--hd-orange)','var(--hd-vert)','var(--hd-sable)']; $wi=0; @endphp
    @forelse($workshopData as $lbl => $count)
    @php $pct=round($count/max($wsMax,1)*100); $col=$wCols[$wi++%4]; @endphp
    <div class="hd-bar" style="grid-template-columns:1fr 76px 28px;">
      <span class="hd-bar-lbl">{{ $lbl }}</span>
      <div class="hd-track"><div class="hd-fill" style="width:{{ $pct }}%;background:{{ $col }};"></div></div>
      <span class="hd-bar-val">{{ $count }}</span>
    </div>
    @empty
    <p style="font-size:.78rem;color:var(--hd-t3);">Aucune donnée</p>
    @endforelse
  </div>

  {{-- Quotas inclusivité --}}
  <div class="hd-card">
    <span class="hd-kicker">Quotas inclusivité</span>
    <p style="font-size:.7rem;color:var(--hd-t3);margin-bottom:1.25rem;">{{ $accepted }} / {{ $quota }} auditeurs retenus</p>

    <div style="margin-bottom:1.25rem;">
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.375rem;">
        <span style="font-size:.75rem;font-weight:600;color:var(--hd-t2);">Femmes</span>
        <span style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:700;color:var(--hd-orange);">{{ $women }} · {{ $womenPct }} %<span style="color:var(--hd-t3);font-weight:400;"> / 50 %</span></span>
      </div>
      <div class="hd-track">
        <div class="hd-fill" style="width:{{ $womenPct }}%;background:var(--hd-orange);"></div>
        <div class="hd-marker" style="left:50%;"></div>
      </div>
    </div>

    <div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.375rem;">
        <span style="font-size:.75rem;font-weight:600;color:var(--hd-t2);">Moins de 35 ans</span>
        <span style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:700;color:var(--hd-amber);">{{ $young }} · {{ $youngPct }} %<span style="color:var(--hd-t3);font-weight:400;"> / 40 %</span></span>
      </div>
      <div class="hd-track">
        <div class="hd-fill" style="width:{{ $youngPct }}%;background:var(--hd-amber);"></div>
        <div class="hd-marker" style="left:40%;"></div>
      </div>
    </div>
  </div>

</div>

{{-- ── 6. SOURCES ────────────────────────────────────────────────────────── --}}
<div class="hd-card">
  <span class="hd-kicker">Sources de connaissance</span>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.375rem 2rem;">
    @forelse($referralData as $src => $count)
    @php $pct = round($count / max($refMax,1) * 100); @endphp
    <div class="hd-bar" style="grid-template-columns:1fr 76px 28px;">
      <span class="hd-bar-lbl">{{ $src }}</span>
      <div class="hd-track"><div class="hd-fill" style="width:{{ $pct }}%;background:var(--hd-sable);"></div></div>
      <span class="hd-bar-val">{{ $count }}</span>
    </div>
    @empty
    <p style="font-size:.78rem;color:var(--hd-t3);">Aucune donnée</p>
    @endforelse
  </div>
</div>


</div>{{-- /.hd-wrap --}}
</x-filament-panels::page>
