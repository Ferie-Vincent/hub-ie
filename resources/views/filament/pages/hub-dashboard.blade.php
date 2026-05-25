<x-filament-panels::page>

@php
/* ── Shared helpers ─────────────────────────────────────── */
$exclude = [];

/* ── KPI accent colors ──────────────────────────────────── */
$kpiColors = [
    'orange' => 'hsl(25 68% 44%)',
    'vert'   => 'hsl(140 42% 36%)',
    'blue'   => 'hsl(210 68% 52%)',
    'gold'   => 'hsl(46 58% 42%)',
];

/* ── Timeline SVG ───────────────────────────────────────── */
$tlMax  = max(max($sparklineCumul ?: [0]), 1);
$tlN    = count($sparklineCumul);
$tlW    = 440; $tlH = 72;
$tlPad  = 4;
$tlPts  = collect($sparklineCumul)->map(function($v, $i) use ($tlN, $tlW, $tlH, $tlPad, $tlMax) {
    $x = round($i / max($tlN - 1, 1) * $tlW, 2);
    $y = round($tlH - $tlPad - ($v / $tlMax * ($tlH - $tlPad * 2)), 2);
    return "$x,$y";
})->join(' ');
$tlFirst = $sparklineCumul[0] ?? 0;
$tlLast  = $sparklineCumul[$tlN - 1] ?? 0;
$tlFill  = "M0,{$tlH} " . collect($sparklineCumul)->map(function($v, $i) use ($tlN, $tlW, $tlH, $tlPad, $tlMax) {
    $x = round($i / max($tlN - 1, 1) * $tlW, 2);
    $y = round($tlH - $tlPad - ($v / $tlMax * ($tlH - $tlPad * 2)), 2);
    return "L{$x},{$y}";
})->join(' ') . " L{$tlW},{$tlH} Z";

/* ── Gender SVG donut ───────────────────────────────────── */
$gTotal  = max($genderTotal, 1);
$gR      = 38; $gCx = 50; $gCy = 50;
$gCircum = 2 * M_PI * $gR;
$gDashF  = $genderData['F'] / $gTotal * $gCircum;
$gDashM  = $genderData['M'] / $gTotal * $gCircum;
$gDashX  = $genderData['X'] / $gTotal * $gCircum;
$gOffM   = round(-$gDashF, 4);
$gOffX   = round(-$gDashF - $gDashM, 4);

/* ── Bar chart helpers ──────────────────────────────────── */
$catMax   = max(array_values($categoryData ?: [1]));
$ageMax   = max(array_values($ageData ?: [1]));
$wsMax    = max(array_values($workshopData ?: [1]));
$geoMax   = max(array_values($geographyData ?: [1]));
$refMax   = max(array_values($referralData ?: [1]));
$attMax   = max(max($attendanceData ?: [1]), 10);
$acceptedPct = round($accepted / max($quota, 1) * 100);
@endphp

{{-- ══════════════════════════════════════════════════════ --}}
{{-- Custom styles                                          --}}
{{-- ══════════════════════════════════════════════════════ --}}
<style>
/* ── Theme tokens ─────────────────────────────────────── */
:root {
    --hd-card:        #ffffff;
    --hd-border:      rgba(15,12,8,0.08);
    --hd-shadow:      0 1px 3px rgba(15,12,8,0.06);
    --hd-track:       rgba(15,12,8,0.08);
    --hd-marker:      rgba(15,12,8,0.25);
    --hd-t1:          #1A1208;
    --hd-t2:          rgba(15,12,8,0.62);
    --hd-t3:          rgba(15,12,8,0.38);
    --hd-dim:         rgba(15,12,8,0.06);
    --hd-donut-ring:  rgba(15,12,8,0.09);
    --hd-gender-x:    rgba(15,12,8,0.20);
}
.dark {
    --hd-card:        rgba(255,255,255,0.05);
    --hd-border:      rgba(255,255,255,0.10);
    --hd-shadow:      none;
    --hd-track:       rgba(255,255,255,0.09);
    --hd-marker:      rgba(255,255,255,0.28);
    --hd-t1:          rgba(255,255,255,0.90);
    --hd-t2:          rgba(255,255,255,0.62);
    --hd-t3:          rgba(255,255,255,0.38);
    --hd-dim:         rgba(255,255,255,0.06);
    --hd-donut-ring:  rgba(255,255,255,0.12);
    --hd-gender-x:    rgba(255,255,255,0.22);
}

.hd-wrap { display:flex; flex-direction:column; gap:1.125rem; padding-bottom:2rem; }

/* Cards */
.hd-card {
    background: var(--hd-card);
    border: 1px solid var(--hd-border);
    border-radius:1rem;
    padding:1.25rem 1.375rem;
    position:relative;
    overflow:hidden;
    box-shadow: var(--hd-shadow);
}
.hd-card-accent {
    position:absolute; top:0; left:0; right:0; height:2.5px;
}

/* Grids */
.hd-grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
.hd-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.hd-grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
.hd-span-2 { grid-column:span 2; }
@media(max-width:1280px) { .hd-grid-4{grid-template-columns:repeat(2,1fr);} }
@media(max-width:900px) {
    .hd-grid-4,.hd-grid-3,.hd-grid-2 { grid-template-columns:1fr; }
    .hd-span-2 { grid-column:span 1; }
}

/* Section kicker */
.hd-kicker {
    font-family:'JetBrains Mono',monospace;
    font-size:0.575rem; font-weight:700;
    letter-spacing:0.2em; text-transform:uppercase;
    color: var(--hd-t3);
    margin-bottom:0.75rem;
    display:block;
}

/* KPI cards */
.hd-kpi-num {
    font-family:'Fraunces',serif; font-size:2.625rem;
    font-weight:900; color: var(--hd-t1); line-height:1;
    margin:0.375rem 0 0.25rem;
}
.hd-kpi-sub {
    font-family:'JetBrains Mono',monospace; font-size:0.6rem;
    font-weight:700; letter-spacing:0.15em; text-transform:uppercase;
    color: var(--hd-t3);
}
.hd-kpi-badge {
    display:inline-flex; align-items:center; gap:0.3rem;
    font-size:0.7rem; font-weight:700;
    padding:0.2rem 0.5rem; border-radius:9999px;
    margin-top:0.625rem;
}

/* Progress bar */
.hd-prog-track {
    height:6px; background: var(--hd-track);
    border-radius:9999px; overflow:hidden; position:relative;
}
.hd-prog-fill {
    height:100%; border-radius:9999px;
    transition:width 0.6s ease;
}
.hd-prog-marker {
    position:absolute; top:0; bottom:0; width:1.5px;
    background: var(--hd-marker);
}

/* Bar rows */
.hd-bar-row {
    display:grid; align-items:center;
    grid-template-columns:1fr 120px 36px;
    gap:0.625rem; margin-bottom:0.625rem;
}
.hd-bar-row:last-child { margin-bottom:0; }
.hd-bar-label { font-size:0.75rem; color: var(--hd-t2); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.hd-bar-track { height:5px; background: var(--hd-track); border-radius:9999px; overflow:hidden; }
.hd-bar-fill  { height:100%; border-radius:9999px; }
.hd-bar-count { font-family:'JetBrains Mono',monospace; font-size:0.7rem; font-weight:700; color: var(--hd-t2); text-align:right; }

/* Vertical bars */
.hd-vbar-group { display:flex; align-items:flex-end; gap:0.75rem; height:90px; }
.hd-vbar-col   { display:flex; flex-direction:column; align-items:center; gap:0.375rem; flex:1; }
.hd-vbar-bar   { width:100%; border-radius:3px 3px 0 0; min-height:2px; }
.hd-vbar-label { font-family:'JetBrains Mono',monospace; font-size:0.55rem; font-weight:700; letter-spacing:0.06em; color: var(--hd-t3); text-align:center; white-space:nowrap; }
.hd-vbar-count { font-family:'JetBrains Mono',monospace; font-size:0.65rem; font-weight:700; color: var(--hd-t2); }

/* Status funnel */
.hd-funnel-row { margin-bottom:0.625rem; }
.hd-funnel-row:last-child { margin-bottom:0; }
.hd-funnel-header { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.3rem; }
.hd-funnel-name { font-size:0.78rem; color: var(--hd-t2); }
.hd-funnel-num  { font-family:'JetBrains Mono',monospace; font-size:0.72rem; font-weight:700; color: var(--hd-t1); }
.hd-funnel-bar  { height:4px; background: var(--hd-track); border-radius:9999px; overflow:hidden; }
.hd-funnel-fill { height:100%; border-radius:9999px; }

</style>

{{-- ══════════════════════════════════════════════════════ --}}
{{-- CONTENT                                                --}}
{{-- ══════════════════════════════════════════════════════ --}}
<div class="hd-wrap">

{{-- ── KPI Strip ─────────────────────────────────────────── --}}
<div class="hd-grid-4">

    {{-- Candidatures reçues --}}
    <div class="hd-card">
        <div class="hd-card-accent" style="background:hsl(25 68% 44%);"></div>
        <span class="hd-kicker">Candidatures reçues</span>
        <p class="hd-kpi-num">{{ number_format($totalSubmitted) }}</p>
        <p class="hd-kpi-sub">total soumises</p>
        @if($delta24h > 0)
        <div class="hd-kpi-badge" style="background:hsla(25,68%,44%,0.14);color:hsl(25 68% 66%);">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M5 8V2M5 2L2 5M5 2L8 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            +{{ $delta24h }} dernières 24h
        </div>
        @endif
    </div>

    {{-- Auditeurs retenus --}}
    <div class="hd-card">
        <div class="hd-card-accent" style="background:hsl(140 42% 36%);"></div>
        <span class="hd-kicker">Auditeurs retenus</span>
        <p class="hd-kpi-num" style="font-size:2.25rem;">
            {{ $accepted }}<span style="font-size:1.1rem; font-weight:400; color:var(--hd-t3);"> / {{ $quota }}</span>
        </p>
        <div class="hd-prog-track" style="margin:0.75rem 0 0.5rem;">
            <div class="hd-prog-fill" style="width:{{ $acceptedPct }}%;background:hsl(140 42% 36%);"></div>
            <div class="hd-prog-marker" style="left:100%;"></div>
        </div>
        <p class="hd-kpi-sub">{{ $acceptedPct }} % du quota atteint</p>
    </div>

    {{-- Présents aujourd'hui --}}
    <div class="hd-card">
        <div class="hd-card-accent" style="background:hsl(210 68% 52%);"></div>
        <span class="hd-kicker">Présents aujourd'hui</span>
        <p class="hd-kpi-num">{{ $presentToday }}</p>
        <p class="hd-kpi-sub">pointage {{ now()->translatedFormat('d M') }}</p>
        <div class="hd-kpi-badge" style="background:var(--hd-dim);color:var(--hd-t3);">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 3v2l1.5 1" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Données en temps réel
        </div>
    </div>

    {{-- À évaluer --}}
    <div class="hd-card">
        <div class="hd-card-accent" style="background:hsl(46 58% 42%);"></div>
        <span class="hd-kicker">Dossiers à évaluer</span>
        <p class="hd-kpi-num" style="color:hsl(46 48% 34%);">{{ $toEvaluate }}</p>
        <p class="hd-kpi-sub">éligibles + en cours</p>
        <div class="hd-kpi-badge" style="background:hsla(46,58%,42%,0.14);color:hsl(46 58% 66%);">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M1.5 3h7M1.5 5h5M1.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Comité de sélection
        </div>
    </div>

</div>

{{-- ── Row: Timeline + Gender ─────────────────────────────── --}}
<div class="hd-grid-3">

    {{-- Timeline --}}
    <div class="hd-card hd-span-2">
        <span class="hd-kicker">Évolution des candidatures — 30 derniers jours (cumulé)</span>
        <div style="position:relative; height:90px; margin-top:0.5rem;">
            <svg viewBox="0 0 440 72" preserveAspectRatio="none" style="position:absolute;inset:0;width:100%;height:100%;">
                <defs>
                    <linearGradient id="tlGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="hsl(25,68%,44%)" stop-opacity="0.28"/>
                        <stop offset="100%" stop-color="hsl(25,68%,44%)" stop-opacity="0.02"/>
                    </linearGradient>
                </defs>
                <path d="{{ $tlFill }}" fill="url(#tlGrad)"/>
                <polyline points="{{ $tlPts }}" fill="none" stroke="hsl(25,68%,52%)" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/>
                {{-- Last data point dot --}}
                @php
                    $lastPt = explode(' ', $tlPts);
                    [$lx, $ly] = explode(',', end($lastPt));
                @endphp
                <circle cx="{{ $lx }}" cy="{{ $ly }}" r="3" fill="hsl(25,68%,52%)"/>
            </svg>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:0.375rem;">
            @foreach($sparklineLabels as $i => $label)
                @if($label)
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.52rem;color:var(--hd-t3);font-weight:700;">{{ $label }}</span>
                @endif
            @endforeach
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;">
            <div>
                <p style="font-family:'Fraunces',serif;font-size:1.5rem;font-weight:900;color:var(--hd-t1);line-height:1;">{{ end($sparklineCumul) }}</p>
                <p style="font-family:'JetBrains Mono',monospace;font-size:0.55rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:var(--hd-t3);">Cumulé total</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:0.78rem;color:var(--hd-t2);">Taux d'évolution</p>
                @php $growth = $sparklineCumul[0] > 0 ? round((end($sparklineCumul) - $sparklineCumul[0]) / $sparklineCumul[0] * 100) : 100; @endphp
                <p style="font-family:'Fraunces',serif;font-size:1.125rem;font-weight:900;color:hsl(140,42%,36%);">+{{ $growth }} %</p>
            </div>
        </div>
    </div>

    {{-- Gender donut --}}
    <div class="hd-card" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
        <span class="hd-kicker" style="width:100%;text-align:center;">Répartition par genre</span>
        <svg viewBox="0 0 100 100" style="width:110px;height:110px;transform:rotate(-90deg);">
            <circle cx="50" cy="50" r="{{ $gR }}" fill="none" style="stroke:var(--hd-donut-ring)" stroke-width="12"/>
            @if($genderData['F'] > 0)
            <circle cx="50" cy="50" r="{{ $gR }}" fill="none"
                stroke="hsl(330 58% 58%)" stroke-width="12"
                stroke-dasharray="{{ round($gDashF,3) }} {{ round($gCircum,3) }}"
                stroke-dashoffset="0"
                stroke-linecap="butt"/>
            @endif
            @if($genderData['M'] > 0)
            <circle cx="50" cy="50" r="{{ $gR }}" fill="none"
                stroke="hsl(210 68% 55%)" stroke-width="12"
                stroke-dasharray="{{ round($gDashM,3) }} {{ round($gCircum,3) }}"
                stroke-dashoffset="{{ round($gOffM,3) }}"
                stroke-linecap="butt"/>
            @endif
            @if($genderData['X'] > 0)
            <circle cx="50" cy="50" r="{{ $gR }}" fill="none"
                stroke-width="12"
                style="stroke:var(--hd-gender-x)"
                stroke-dasharray="{{ round($gDashX,3) }} {{ round($gCircum,3) }}"
                stroke-dashoffset="{{ round($gOffX,3) }}"
                stroke-linecap="butt"/>
            @endif
            {{-- Center label (needs counter-rotation) --}}
            <text x="50" y="44" text-anchor="middle"
                font-family="Fraunces,serif" font-size="16" font-weight="900"
                style="fill:var(--hd-t1)"
                transform="rotate(90 50 50)">{{ $genderTotal }}</text>
            <text x="50" y="57" text-anchor="middle"
                font-family="JetBrains Mono,monospace" font-size="5" font-weight="700"
                style="fill:var(--hd-t3)"
                transform="rotate(90 50 50)">CANDIDATS</text>
        </svg>
        <div style="display:flex;flex-direction:column;gap:0.375rem;margin-top:0.625rem;width:100%;max-width:160px;">
            @foreach([['F','Femmes','hsl(330 58% 58%)'],['M','Hommes','hsl(210 68% 55%)'],['X','Autre / NR','var(--hd-gender-x)']] as [$key,$lbl,$col])
            <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;">
                <div style="display:flex;align-items:center;gap:0.375rem;">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $col }};flex-shrink:0;display:inline-block;"></span>
                    <span style="font-size:0.72rem;color:var(--hd-t2);">{{ $lbl }}</span>
                </div>
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.7rem;font-weight:700;color:var(--hd-t2);">{{ $genderData[$key] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ── Row: Category + Age ────────────────────────────────── --}}
<div class="hd-grid-3">

    {{-- Category horizontal bars --}}
    <div class="hd-card hd-span-2">
        <span class="hd-kicker">Profils professionnels</span>
        @forelse($categoryData as $label => $count)
        @php $pct = round($count / max($catMax,1) * 100); @endphp
        <div class="hd-bar-row">
            <span class="hd-bar-label">{{ $label }}</span>
            <div class="hd-bar-track">
                <div class="hd-bar-fill" style="width:{{ $pct }}%;background:hsl(25 68% 44%);"></div>
            </div>
            <span class="hd-bar-count">{{ $count }}</span>
        </div>
        @empty
        <p style="font-size:0.8rem;color:var(--hd-t3);">Aucune donnée</p>
        @endforelse
    </div>

    {{-- Age vertical bars --}}
    <div class="hd-card">
        <span class="hd-kicker">Tranches d'âge</span>
        <div class="hd-vbar-group">
            @foreach($ageData as $label => $count)
            @php $h = round($count / max($ageMax,1) * 80); @endphp
            <div class="hd-vbar-col">
                <span class="hd-vbar-count">{{ $count }}</span>
                <div class="hd-vbar-bar" style="height:{{ $h }}px;background:hsl(25 68% 44%);"></div>
                <span class="hd-vbar-label">{{ str_replace(' ', "\n", $label) }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ── Row: Workshop + Funnel + Quota ─────────────────────── --}}
<div class="hd-grid-3">

    {{-- Workshop --}}
    <div class="hd-card">
        <span class="hd-kicker">Choix d'ateliers</span>
        @php
            $wsColors = ['hsl(18 58% 42%)', 'hsl(25 68% 44%)', 'hsl(140 42% 33%)', 'hsl(46 58% 40%)'];
            $wsIdx = 0;
        @endphp
        @forelse($workshopData as $label => $count)
        @php $pct = round($count / max($wsMax,1) * 100); $col = $wsColors[$wsIdx++ % 4]; @endphp
        <div class="hd-bar-row" style="grid-template-columns:1fr 80px 30px;">
            <span class="hd-bar-label">{{ $label }}</span>
            <div class="hd-bar-track">
                <div class="hd-bar-fill" style="width:{{ $pct }}%;background:{{ $col }};"></div>
            </div>
            <span class="hd-bar-count">{{ $count }}</span>
        </div>
        @empty
        <p style="font-size:0.8rem;color:var(--hd-t3);">Aucune donnée</p>
        @endforelse
    </div>

    {{-- Status funnel --}}
    <div class="hd-card">
        <span class="hd-kicker">Entonnoir de sélection</span>
        @php
            $fColors = ['hsl(210 68% 52%)','hsl(180 55% 38%)','hsl(240 48% 55%)','hsl(270 48% 55%)','hsl(140 42% 36%)'];
        @endphp
        @foreach($statusFunnel as $i => $row)
        <div class="hd-funnel-row">
            <div class="hd-funnel-header">
                <span class="hd-funnel-name">{{ $row['label'] }}</span>
                <span class="hd-funnel-num" style="color:{{ $fColors[$i] ?? 'var(--hd-t2)' }};">{{ $row['count'] }}</span>
            </div>
            <div class="hd-funnel-bar">
                <div class="hd-funnel-fill" style="width:{{ $row['pct'] }}%;background:{{ $fColors[$i] ?? 'var(--hd-track)' }};"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quota inclusivité --}}
    <div class="hd-card">
        <span class="hd-kicker">Quotas inclusivité</span>
        <p style="font-size:0.72rem;color:var(--hd-t3);margin-bottom:1.125rem;">{{ $accepted }} / {{ $quota }} auditeurs retenus</p>

        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.4rem;">
                <span style="font-size:0.78rem;font-weight:600;color:var(--hd-t2);">Femmes</span>
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;font-weight:700;color:hsl(330 58% 46%);">{{ $women }} · {{ $womenPct }} %<span style="color:var(--hd-t3);"> (cible 50 %)</span></span>
            </div>
            <div class="hd-prog-track">
                <div class="hd-prog-fill" style="width:{{ $womenPct }}%;background:hsl(330 58% 56%);"></div>
                <div class="hd-prog-marker" style="left:50%;"></div>
            </div>
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.4rem;">
                <span style="font-size:0.78rem;font-weight:600;color:var(--hd-t2);">Moins de 35 ans</span>
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;font-weight:700;color:hsl(25 68% 40%);">{{ $young }} · {{ $youngPct }} %<span style="color:var(--hd-t3);"> (cible 40 %)</span></span>
            </div>
            <div class="hd-prog-track">
                <div class="hd-prog-fill" style="width:{{ $youngPct }}%;background:hsl(25 68% 44%);"></div>
                <div class="hd-prog-marker" style="left:40%;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ── Row: Attendance + Referral ─────────────────────────── --}}
<div class="hd-grid-3">

    {{-- Attendance vertical bars --}}
    <div class="hd-card hd-span-2">
        <span class="hd-kicker">Pointage par jour — Juin 2026</span>
        <div style="display:flex;align-items:flex-end;gap:1.5rem;height:100px;margin-top:0.5rem;">
            @php $attLabels = ['22 juin','23 juin','24 juin','25 juin']; @endphp
            @foreach($attendanceData as $i => $count)
            @php $h = max(round($count / max($attMax,1) * 84), $count > 0 ? 4 : 2); @endphp
            <div style="display:flex;flex-direction:column;align-items:center;gap:0.375rem;flex:1;">
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;font-weight:700;color:hsl(140 42% 36%);">{{ $count }}</span>
                <div style="width:100%;border-radius:4px 4px 0 0;background:{{ $count > 0 ? 'hsl(140 42% 33%)' : 'var(--hd-track)' }};height:{{ $h }}px;"></div>
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.55rem;font-weight:700;letter-spacing:0.04em;color:var(--hd-t3);">{{ $attLabels[$i] }}</span>
            </div>
            @endforeach
        </div>
        <div style="margin-top:0.625rem;display:flex;align-items:center;gap:0.5rem;">
            <span style="width:8px;height:8px;border-radius:2px;background:hsl(140 42% 33%);display:inline-block;"></span>
            <span style="font-size:0.7rem;color:var(--hd-t3);">J-{{ $daysToEvent }} avant l'événement · données à venir</span>
        </div>
    </div>

    {{-- Referral --}}
    <div class="hd-card">
        <span class="hd-kicker">Sources de connaissance</span>
        @forelse($referralData as $source => $count)
        @php $pct = round($count / max($refMax,1) * 100); @endphp
        <div class="hd-bar-row" style="grid-template-columns:1fr 70px 30px;">
            <span class="hd-bar-label">{{ $source }}</span>
            <div class="hd-bar-track">
                <div class="hd-bar-fill" style="width:{{ $pct }}%;background:hsl(46 58% 40%);"></div>
            </div>
            <span class="hd-bar-count">{{ $count }}</span>
        </div>
        @empty
        <p style="font-size:0.8rem;color:var(--hd-t3);">Aucune donnée</p>
        @endforelse
    </div>

</div>

{{-- ── Row: Geography ─────────────────────────────────────── --}}
<div class="hd-card">
    <span class="hd-kicker">Répartition géographique — Top villes</span>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:0.5rem 2rem;">
        @forelse($geographyData as $city => $count)
        @php $pct = round($count / max($geoMax,1) * 100); @endphp
        <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;">
                <span style="font-size:0.78rem;font-weight:600;color:var(--hd-t2);">{{ $city }}</span>
                <span style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;font-weight:700;color:var(--hd-t2);">{{ $count }}</span>
            </div>
            <div class="hd-prog-track" style="height:4px;">
                <div class="hd-prog-fill" style="width:{{ $pct }}%;background:hsl(25 68% 40%);"></div>
            </div>
        </div>
        @empty
        <p style="font-size:0.8rem;color:var(--hd-t3);">Aucune donnée</p>
        @endforelse
    </div>
</div>

</div>{{-- end hd-wrap --}}
</x-filament-panels::page>
