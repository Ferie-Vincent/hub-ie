<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription — Hub Import-Export 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&family=Manrope:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Root / Reset ───────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { background: #FAFAF7; }
        [x-cloak] { display: none !important; }
        /* Supprime contour bleu navigateur sur tout élément interactif */
        input:focus, select:focus, textarea:focus, button:focus { outline: none !important; outline: 0 !important; }
        input:focus-visible, select:focus-visible, textarea:focus-visible, button:focus-visible { outline: none !important; }

        /* ── Grid principal ─────────────────────── */
        .w-layout {
            display: grid;
            grid-template-columns: clamp(300px, 42vw, 560px) 1fr;
            min-height: 100vh;
        }
        @media (max-width: 1023px) {
            .w-layout { grid-template-columns: 1fr; }
        }

        /* ── Sidebar ────────────────────────────── */
        .w-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #EFE9E0;
            overflow: hidden;
        }

        /* ── Numéro d'étape géant ───────────────── */
        .step-giant {
            font-family: 'Fraunces', serif;
            font-size: clamp(7rem, 14vw, 12rem);
            font-weight: 900;
            line-height: 0.88;
            letter-spacing: -0.04em;
            background: linear-gradient(160deg,
                hsl(var(--orange-ivoire)) 0%,
                hsl(var(--orange-brule)) 60%,
                hsl(var(--orange-ivoire) / 0.20) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            user-select: none;
        }

        /* ── Onglets étape ──────────────────────── */
        .stab {
            font-family: 'JetBrains Mono', monospace;
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding-bottom: 6px;
            border-bottom: 2px solid transparent;
            color: rgba(50,35,20,0.28);
            white-space: nowrap;
            transition: color 0.2s, border-color 0.2s;
        }
        .stab.active {
            border-bottom-color: hsl(var(--orange-ivoire));
            color: hsl(var(--orange-ivoire));
            font-weight: 800;
        }
        .stab.done {
            border-bottom-color: rgba(50,35,20,0.22);
            color: rgba(50,35,20,0.42);
        }

        /* ── Champs underline only ──────────────── */
        .f-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid rgba(15,12,8,0.16);
            border-radius: 0;
            padding: 1rem 0;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            color: #0F0C08;
            outline: none;
            transition: border-color 0.18s;
            -webkit-appearance: none;
            appearance: none;
        }
        .f-input:focus,
        .f-input:focus-visible { outline: none; outline: 0; box-shadow: none; border-bottom-color: hsl(var(--orange-ivoire)); }
        .f-input:focus-within  { outline: none; }
        .f-input.err    { border-bottom-color: #ef4444; }
        .f-input::placeholder { color: rgba(15,12,8,0.25); font-size: 0.875rem; }

        textarea.f-input { resize: none; line-height: 1.65; }

        .f-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(15,12,8,0.40);
            margin-bottom: 0.25rem;
        }
        .f-label-orange {
            display: block;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: hsl(var(--orange-ivoire));
            margin-bottom: 0.25rem;
        }

        /* ── Carte atelier ──────────────────────── */
        @keyframes card-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%       { transform: translateY(-6px) rotate(-0.5deg); }
        }
        .a-card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 1rem 1.125rem 0.875rem;
            background: #fff;
            border: 1.5px solid rgba(15,12,8,0.08);
            border-top: 2px solid color-mix(in srgb, var(--accent, hsl(var(--orange-ivoire))) 55%, #fff);
            border-radius: 1.125rem;
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.22s, box-shadow 0.22s, transform 0.22s cubic-bezier(.22,1,.36,1);
            will-change: transform;
        }
        /* ghost number watermark */
        .a-ghost {
            position: absolute;
            top: -0.25rem; right: 0.5rem;
            font-family: 'Fraunces', serif;
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            color: rgba(15,12,8,0.12);
            pointer-events: none;
            user-select: none;
            letter-spacing: -0.04em;
        }
        /* colored icon box — light tint background */
        .a-icon {
            width: 2.125rem; height: 2.125rem;
            border-radius: 0.5rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            background: color-mix(in srgb, var(--accent, hsl(var(--orange-ivoire))) 12%, #fff);
        }
        /* tag chips */
        .a-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.4rem;
            border-radius: 9999px;
            font-size: 0.6rem;
            font-weight: 500;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.03em;
            color: rgba(15,12,8,0.50);
            border: 1px solid rgba(15,12,8,0.13);
            background: rgba(15,12,8,0.03);
            white-space: nowrap;
        }
        /* CTA link */
        .a-cta {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: rgba(15,12,8,0.40);
            display: inline-flex; align-items: center; gap: 0.25rem;
            text-decoration: none;
            transition: gap 0.15s, color 0.15s;
        }
        .a-card:hover .a-cta, .a-card.sel .a-cta {
            color: var(--accent, hsl(var(--orange-ivoire)));
            gap: 0.5rem;
        }

        .a-card:hover {
            border-color: var(--accent, hsl(var(--orange-ivoire)));
            box-shadow: 0 12px 40px rgba(15,12,8,0.10), 0 3px 10px rgba(15,12,8,0.06);
            transform: translateY(-5px);
        }
        .a-card.sel {
            border-color: color-mix(in srgb, var(--accent, hsl(var(--orange-ivoire))) 50%, #ddd);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 8%, transparent), 0 10px 32px rgba(15,12,8,0.09);
            animation: card-float 3s ease-in-out infinite;
        }
        .a-card.sel .a-ghost { opacity: 0.08; }

        .rdot {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(15,12,8,0.18);
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .a-card.sel .rdot { border-color: var(--accent); background: var(--accent); }
        .rdot::after {
            content: ''; width: 6px; height: 6px;
            border-radius: 50%; background: #fff;
            opacity: 0; transform: scale(0);
            transition: all 0.15s;
        }
        .a-card.sel .rdot::after { opacity: 1; transform: scale(1); }

        /* ── Bloc récap ─────────────────────────── */
        .recap-block {
            background: rgba(255,255,255,0.65);
            border: 1px solid rgba(15,12,8,0.08);
            border-radius: 0.875rem;
            padding: 1.125rem 1.375rem;
        }

        /* ── Info-box ───────────────────────────── */
        .info-box {
            border-left: 3px solid hsl(var(--orange-ivoire));
            background: hsl(var(--orange-ivoire) / 0.05);
            border-radius: 0 0.625rem 0.625rem 0;
            padding: 0.875rem 1.125rem;
        }

        /* ── Barre nav sticky bas ───────────────── */
        .w-nav {
            position: sticky;
            bottom: 0;
            background: rgba(250,250,247,0.94);
            backdrop-filter: blur(16px);
            border-top: 1px solid rgba(15,12,8,0.07);
            padding: 1rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .btn-prev {
            display: inline-flex; align-items: center; gap: 0.375rem;
            font-size: 0.8125rem; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: rgba(15,12,8,0.38);
            background: none; border: none; cursor: pointer;
            padding: 0.5rem 0;
            transition: color 0.15s;
        }
        .btn-prev:hover:not(:disabled) { color: rgba(15,12,8,0.72); }
        .btn-prev:disabled { opacity: 0.25; cursor: not-allowed; }

        .btn-next {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: hsl(var(--orange-ivoire));
            color: #fff;
            font-size: 0.8125rem; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            border: none; border-radius: 9999px;
            padding: 0.875rem 2rem;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.15s, transform 0.15s;
        }
        .btn-next:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-next:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

        /* ── Écran succès ───────────────────────── */
        .done-screen {
            position: absolute;
            inset: 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            text-align: center;
            background: #FAFAF7;
        }

        @keyframes done-rise {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes check-draw {
            from { stroke-dashoffset: 100; }
            to   { stroke-dashoffset: 0; }
        }
        @keyframes bounce-in {
            0%   { transform: scale(0.4); opacity: 0; }
            55%  { transform: scale(1.14); opacity: 1; }
            75%  { transform: scale(0.93); }
            100% { transform: scale(1); }
        }
        @keyframes halo-pulse {
            0%, 100% { box-shadow: 0 0 0 14px rgba(26,122,60,0.09), 0 12px 40px rgba(26,122,60,0.22); }
            50%       { box-shadow: 0 0 0 22px rgba(26,122,60,0.05), 0 16px 52px rgba(26,122,60,0.18); }
        }
        @keyframes bounce-down {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50%       { transform: translateY(6px); opacity: 0.5; }
        }
    </style>
</head>

<body class="antialiased">

@php
$steps = [
    1 => ['label' => 'Atelier',      'tab' => 'Atelier'],
    2 => ['label' => 'Identité',     'tab' => 'Identité'],
    3 => ['label' => 'Profil Pro',   'tab' => 'Profil Pro'],
    4 => ['label' => 'Motivations',  'tab' => 'Motivations'],
    5 => ['label' => 'Récapitulatif','tab' => 'Final'],
];

$ateliers = [
    'zlecaf-cedeao'         => [
        'num'     => '01',
        'hex'     => '#B8622A',
        'titre'   => 'ZLECAf & CEDEAO',
        'tagline' => 'Conquérir les marchés régionaux',
        'desc'    => 'Maîtriser les règles d\'origine, les protocoles tarifaires et les opportunités ouvertes par la Zone de Libre-Échange Continentale Africaine.',
        'tags'    => ['Protocoles ZLECAf', 'TEC CEDEAO', 'PAPSS', 'Logistique intra-africaine'],
    ],
    'financement-garanties' => [
        'num'     => '02',
        'hex'     => '#963818',
        'titre'   => 'Financement & garanties',
        'tagline' => 'Sécuriser ses opérations',
        'desc'    => 'Découvrir les mécanismes de financement export, les garanties bancaires, l\'assurance-crédit et les dispositifs publics d\'appui à l\'international.',
        'tags'    => ['Crédoc', 'Assurance-crédit', 'Afreximbank', 'Risk management'],
    ],
    'commerce-electronique' => [
        'num'     => '03',
        'hex'     => '#1A6835',
        'titre'   => 'Commerce électronique',
        'tagline' => 'Digitaliser ses échanges',
        'desc'    => 'Utiliser les plateformes B2B/B2C internationales, structurer sa présence digitale et exploiter les guichets uniques numériques pour exporter.',
        'tags'    => ['SEO export', 'Marketplaces B2B', 'GUCE-CI', 'E-commerce transfrontalier'],
    ],
    'conformite-qualite'    => [
        'num'     => '04',
        'hex'     => '#926C12',
        'titre'   => 'Conformité & qualité',
        'tagline' => 'Maîtriser les normes',
        'desc'    => 'Comprendre les exigences normatives (sanitaires, environnementales, techniques) et structurer une démarche de certification pour accéder aux marchés cibles.',
        'tags'    => ['Normes ISO', 'HACCP', 'CODINORM', 'Étiquetage UE'],
    ],
];
@endphp

<div class="w-layout"
     x-data="{
         step: 1,
         total: 5,
         loading: false,
         done: false,
         errors: {},
         form: {
             atelier: '',
             nom: '', prenom: '', email: '', telephone: '',
             entreprise: '', secteur: '', poste: '',
             motivation_projet: '', motivation_objectifs: '',
         },
         pct() { return Math.round((this.step / this.total) * 100); },
         sel(s)  { this.form.atelier = s; this.errors = {}; },
         isSel(s){ return this.form.atelier === s; },
         ok() {
             this.errors = {};
             if (this.step === 1 && !this.form.atelier) { this.errors.atelier = 'Choisissez un atelier pour continuer.'; return false; }
             if (this.step === 2) {
                 if (!this.form.nom)    this.errors.nom    = 'Obligatoire';
                 if (!this.form.prenom) this.errors.prenom = 'Obligatoire';
                 if (!this.form.email || !this.form.email.includes('@')) this.errors.email = 'E-mail invalide';
                 if (Object.keys(this.errors).length) return false;
             }
             if (this.step === 3) {
                 if (!this.form.entreprise) this.errors.entreprise = 'Obligatoire';
                 if (!this.form.secteur)    this.errors.secteur    = 'Obligatoire';
                 if (Object.keys(this.errors).length) return false;
             }
             return true;
         },
         next() { if (this.ok() && this.step < this.total) { this.step++; document.querySelector('.w-form-scroll')?.scrollTo(0,0); } },
         prev() { if (this.step > 1) { this.step--; this.errors = {}; document.querySelector('.w-form-scroll')?.scrollTo(0,0); } },
         async submit() {
             this.errors = {}; this.loading = true;
             try {
                 const r = await fetch('{{ route('pre-inscription.store') }}', {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                     body: JSON.stringify(this.form),
                 });
                 const d = await r.json();
                 if (r.ok && d.success) { this.done = true; }
                 else if (d.errors) {
                     this.errors = d.errors;
                     if (d.errors.atelier) this.step = 1;
                     else if (d.errors.nom || d.errors.prenom || d.errors.email) this.step = 2;
                     else if (d.errors.entreprise || d.errors.secteur) this.step = 3;
                 }
             } catch(e) { this.errors.global = 'Erreur réseau. Réessayez.'; }
             finally { this.loading = false; }
         },
     }"
     x-init="$watch('done', v => { if (v) $nextTick(() => launchConfetti()); })">

    {{-- ═══════════════════════════════ --}}
    {{-- SIDEBAR                         --}}
    {{-- ═══════════════════════════════ --}}
    <aside class="w-sidebar hidden lg:flex flex-col">

        {{-- En-tête brand + retour --}}
        <div class="flex items-center justify-between px-10 py-7" style="border-bottom: 1px solid rgba(50,35,20,0.10);">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background: hsl(var(--orange-ivoire));">
                    <span class="text-white font-mono font-bold" style="font-size: 10px; letter-spacing: -0.02em;">HIE</span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-[0.16em] leading-none" style="color: rgba(50,35,20,0.45);">Hub Import-Export</span>
            </a>
            <a href="{{ route('home') }}"
               class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[0.14em] transition-colors"
               style="color: rgba(50,35,20,0.35);"
               onmouseover="this.style.color='hsl(var(--orange-ivoire))'"
               onmouseout="this.style.color='rgba(50,35,20,0.35)'">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Retour au site
            </a>
        </div>

        {{-- Corps indicateur --}}
        <div class="flex-1 flex flex-col px-10 py-10">

            <p class="text-[9px] font-mono font-bold uppercase tracking-[0.26em] mb-6"
               style="color: hsl(var(--orange-ivoire));">Préinscription officielle</p>

            {{-- Grand numéro --}}
            <div class="step-giant" x-text="String(step).padStart(2,'0')">01</div>

            <div class="mt-5">
                <p class="text-[9px] font-mono font-bold uppercase tracking-[0.22em] mb-1.5"
                   style="color: rgba(50,35,20,0.35);">Étape en cours</p>

                @foreach($steps as $n => $cfg)
                <p class="font-bold leading-tight"
                   style="font-family:'Fraunces',serif; font-size: clamp(1.4rem,2.8vw,2rem); letter-spacing:-0.02em; color:#1A1208;"
                   x-show="step === {{ $n }}" x-cloak>{{ $cfg['label'] }}</p>
                @endforeach
            </div>

            {{-- Badge atelier sélectionné (étapes 2+) --}}
            <div class="mt-7" x-show="step > 1 && form.atelier" x-cloak>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full"
                     style="background: hsl(var(--orange-ivoire)/0.10); border: 1px solid hsl(var(--orange-ivoire)/0.22);">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="hsl(var(--orange-ivoire))" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-[0.12em]"
                          style="color: hsl(var(--orange-ivoire));"
                          x-text="form.atelier.replace(/-/g,' ')"></span>
                </div>
            </div>

            {{-- Indicateur scroll-bas mobile — disparaît dès le premier scroll --}}
            <div x-data="{ vis: true }"
                 x-init="window.addEventListener('scroll', () => { if (window.scrollY > 60) vis = false }, { passive: true })"
                 x-show="vis"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="lg:hidden mt-auto pt-10 flex flex-col items-center gap-2"
                 aria-hidden="true">
                <span style="font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(50,35,20,0.72); white-space:nowrap;">Faites défiler pour commencer</span>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--orange-brule))" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: bounce-down 1.3s ease-in-out infinite;">
                    <path d="M12 5v14M5 12l7 7 7-7"/>
                </svg>
            </div>
        </div>

        {{-- Bas sidebar : onglets + progression --}}
        <div class="px-10 pb-8" style="border-top: 1px solid rgba(50,35,20,0.10);">

            <div class="flex items-end gap-4 pt-6 mb-4 overflow-x-auto">
                @foreach($steps as $n => $cfg)
                <button class="stab flex-shrink-0"
                        :class="step === {{ $n }} ? 'active' : (step > {{ $n }} ? 'done' : '')"
                        @click="if(step > {{ $n }}) { step = {{ $n }}; errors = {}; }">
                    {{ $cfg['tab'] }}
                </button>
                @endforeach
            </div>

            <div class="flex items-center justify-between mb-3">
                <span class="font-mono text-[11px] font-bold tabular-nums"
                      style="color: rgba(50,35,20,0.50);"
                      x-text="String(step).padStart(2,'0') + ' / 05'"></span>
                <span class="font-mono text-[10px] font-bold"
                      style="color: rgba(50,35,20,0.35);"
                      x-text="pct() + '% complété'"></span>
            </div>

            <div class="h-0.5 rounded-full overflow-hidden" style="background: rgba(50,35,20,0.10);">
                <div class="h-full rounded-full transition-all duration-500 ease-out"
                     style="background: linear-gradient(to right, hsl(var(--orange-ivoire)), hsl(var(--orange-brule)));"
                     :style="'width:' + pct() + '%'"></div>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════ --}}
    {{-- MAIN                            --}}
    {{-- ═══════════════════════════════ --}}
    <div class="w-main flex flex-col" style="position: relative; overflow: hidden; max-height: 100vh; background: #FAFAF7;">

        {{-- ── Canvas confetti (fixed, full-screen) ── --}}
        <canvas id="confetti-canvas"
                style="position: fixed; inset: 0; pointer-events: none; z-index: 200; width: 100vw; height: 100vh;"
                aria-hidden="true"></canvas>

        {{-- ═══════════════════════════════ --}}
        {{-- ÉCRAN SUCCÈS — centré, plein pan --}}
        {{-- ═══════════════════════════════ --}}
        <div x-show="done" x-cloak class="done-screen">

            {{-- Icône check animée --}}
            <div style="margin-bottom: 2rem; animation: bounce-in 0.6s cubic-bezier(.22,1,.36,1) 0.05s both;">
                <div style="
                    width: 6rem; height: 6rem;
                    border-radius: 50%;
                    background: linear-gradient(145deg, #1A7A3C 0%, #0F5229 100%);
                    display: flex; align-items: center; justify-content: center;
                    margin: 0 auto;
                    box-shadow: 0 0 0 14px rgba(26,122,60,0.09), 0 12px 40px rgba(26,122,60,0.28);
                    animation: halo-pulse 2.8s ease-in-out 0.8s infinite;
                ">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 13l4 4L19 7"
                              stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                              style="stroke-dasharray: 100; stroke-dashoffset: 100; animation: check-draw 0.45s cubic-bezier(.22,1,.36,1) 0.55s forwards;"/>
                    </svg>
                </div>
            </div>

            {{-- Label mono --}}
            <p style="
                font-family: 'JetBrains Mono', monospace;
                font-size: 9px; font-weight: 700;
                letter-spacing: 0.28em; text-transform: uppercase;
                color: hsl(var(--vert-ivoire));
                margin-bottom: 1.25rem;
                animation: done-rise 0.5s cubic-bezier(.22,1,.36,1) 0.3s both;
            ">Pré-inscription enregistrée</p>

            {{-- Grand titre --}}
            <h1 style="
                font-family: 'Fraunces', serif;
                font-size: clamp(2.25rem, 5vw, 3.5rem);
                font-weight: 900; letter-spacing: -0.03em; line-height: 1.06;
                color: #1A1208;
                margin-bottom: 1.375rem;
                animation: done-rise 0.55s cubic-bezier(.22,1,.36,1) 0.42s both;
            ">
                Félicitations&nbsp;!<br>
                <span style="color: hsl(var(--orange-ivoire));">Votre dossier</span> est déposé.
            </h1>

            {{-- Description --}}
            <p style="
                color: rgba(15,12,8,0.50);
                font-size: 0.9375rem; line-height: 1.75;
                max-width: 30rem;
                margin-bottom: 2.5rem;
                animation: done-rise 0.55s cubic-bezier(.22,1,.36,1) 0.54s both;
            ">
                Un e-mail de confirmation vous sera envoyé sous peu. Le comité de sélection du
                <strong style="color: rgba(15,12,8,0.70); font-weight: 600;">Hub Import-Export 2026</strong>
                examinera votre candidature dans les meilleurs délais.
            </p>

            {{-- Stats chiffrées --}}
            <div style="
                display: flex; align-items: center; gap: 2.25rem;
                justify-content: center;
                margin-bottom: 2.75rem;
                animation: done-rise 0.55s cubic-bezier(.22,1,.36,1) 0.66s both;
            ">
                <div style="text-align: center;">
                    <p style="font-family:'Fraunces',serif; font-size:1.75rem; font-weight:900; color:#1A1208; line-height:1;">22–25</p>
                    <p style="font-size:0.6875rem; color:rgba(15,12,8,0.38); margin-top:0.3rem; text-transform:uppercase; letter-spacing:0.12em; font-weight:700;">Juin 2026</p>
                </div>
                <div style="width:1px; height:2.75rem; background: rgba(15,12,8,0.10); flex-shrink:0;"></div>
                <div style="text-align: center;">
                    <p style="font-family:'Fraunces',serif; font-size:1.75rem; font-weight:900; color:#1A1208; line-height:1;">150</p>
                    <p style="font-size:0.6875rem; color:rgba(15,12,8,0.38); margin-top:0.3rem; text-transform:uppercase; letter-spacing:0.12em; font-weight:700;">Participants</p>
                </div>
                <div style="width:1px; height:2.75rem; background: rgba(15,12,8,0.10); flex-shrink:0;"></div>
                <div style="text-align: center;">
                    <p style="font-family:'Fraunces',serif; font-size:1.75rem; font-weight:900; color:#1A1208; line-height:1;">Abidjan</p>
                    <p style="font-size:0.6875rem; color:rgba(15,12,8,0.38); margin-top:0.3rem; text-transform:uppercase; letter-spacing:0.12em; font-weight:700;">Côte d'Ivoire</p>
                </div>
            </div>

            {{-- CTAs --}}
            <div style="animation: done-rise 0.55s cubic-bezier(.22,1,.36,1) 0.78s both; display:flex; flex-wrap:wrap; gap:1rem; justify-content:center;">
                <a :href="`{{ route('register') }}?email=${encodeURIComponent(form.email)}`" class="btn-next">
                    <span>Déposer ma candidature complète</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('home') }}"
                   style="padding:0.75rem 1.5rem; border-radius:0.75rem; border:1.5px solid rgba(15,12,8,0.15); font-size:0.875rem; font-weight:600; color:rgba(15,12,8,0.55); transition:border-color 0.2s,color 0.2s; text-decoration:none;"
                   onmouseover="this.style.color='rgba(15,12,8,0.8)';this.style.borderColor='rgba(15,12,8,0.3)'"
                   onmouseout="this.style.color='rgba(15,12,8,0.55)';this.style.borderColor='rgba(15,12,8,0.15)'">
                    Retour à l'accueil
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════ --}}
        {{-- FORMULAIRE (scrollable, si !done)   --}}
        {{-- ═══════════════════════════════════ --}}
        <div x-show="!done" class="flex flex-col flex-1 w-form-scroll" style="overflow-y: auto;">

            {{-- Zone contenu --}}
            <div class="flex-1 flex flex-col px-10 md:px-14 xl:px-16 pt-10 pb-4 w-full">

                {{-- Erreur globale --}}
                <div x-show="errors.global" x-cloak
                     class="mb-6 px-5 py-4 rounded-xl text-sm"
                     style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c;"
                     x-text="errors.global"></div>

                {{-- ─── Étape 1 : Atelier ─────────── --}}
                <div x-show="step === 1" class="flex flex-col flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-px w-7" style="background:hsl(var(--orange-ivoire));"></div>
                        <p class="text-[10px] font-mono font-bold uppercase tracking-[0.22em]"
                           style="color:hsl(var(--orange-ivoire));">Étape 01 — Atelier</p>
                    </div>
                    <h1 style="font-family:'Fraunces',serif; font-size:clamp(1.5rem,2.6vw,2.2rem); font-weight:900; letter-spacing:-0.025em; line-height:1.08; color:#1A1208;" class="mb-0.5">
                        Quel atelier souhaitez-vous
                    </h1>
                    <h1 style="font-family:'Fraunces',serif; font-size:clamp(1.5rem,2.6vw,2.2rem); font-weight:900; letter-spacing:-0.025em; line-height:1.08;" class="mb-4">
                        <em style="font-style:italic; color:hsl(var(--orange-ivoire)); font-variation-settings:'opsz' 144,'SOFT' 100;">intégrer ?</em>
                    </h1>

                    <div class="info-box mb-4 text-sm leading-relaxed" style="color:rgba(15,12,8,0.68);">
                        Sélectionnez <strong>un seul atelier</strong>. Ce choix conditionne le programme qui vous sera attribué si votre candidature est retenue.
                        <span class="font-semibold" style="color:hsl(var(--orange-ivoire));"> Cochez celui auquel vous souhaitez participer.</span>
                    </div>

                    <div class="ateliers-grid flex-1 grid grid-cols-1 md:grid-cols-2 gap-3" style="min-height: 0;">
                        @foreach($ateliers as $slug => $a)
                        <div class="a-card" style="--accent: {{ $a['hex'] }};"
                             :class="isSel('{{ $slug }}') ? 'sel' : ''"
                             @click="sel('{{ $slug }}')">

                            {{-- Ghost number watermark --}}
                            <span class="a-ghost" aria-hidden="true">{{ $a['num'] }}</span>

                            {{-- Top row: icon + mono label --}}
                            <div class="flex items-start justify-between gap-3 mb-2.5 relative z-10">
                                <div class="a-icon">
                                    @switch($slug)
                                        @case('zlecaf-cedeao')
                                            <svg width="18" height="18" fill="none" stroke="{{ $a['hex'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
                                        @break
                                        @case('financement-garanties')
                                            <svg width="18" height="18" fill="none" stroke="{{ $a['hex'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        @break
                                        @case('commerce-electronique')
                                            <svg width="18" height="18" fill="none" stroke="{{ $a['hex'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                                        @break
                                        @case('conformite-qualite')
                                            <svg width="18" height="18" fill="none" stroke="{{ $a['hex'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        @break
                                    @endswitch
                                </div>
                                <span style="font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(15,12,8,0.32);">ATELIER {{ $a['num'] }}</span>
                            </div>

                            {{-- Title + tagline --}}
                            <div class="relative z-10 mb-2">
                                <h3 style="font-family:'Fraunces',serif; font-size:1rem; font-weight:900; letter-spacing:-0.02em; line-height:1.15; color:#1A1208;">{{ $a['titre'] }}</h3>
                                <p style="font-size:0.73rem; font-weight:500; color:rgba(15,12,8,0.45); margin-top:0.2rem; letter-spacing:0.01em;">{{ $a['tagline'] }}</p>
                            </div>

                            {{-- Description --}}
                            <p class="relative z-10 mb-2.5 leading-relaxed" style="font-size:0.75rem; color:rgba(15,12,8,0.48);">{{ $a['desc'] }}</p>

                            {{-- Tag chips --}}
                            <div class="relative z-10 flex flex-wrap gap-1 mb-2">
                                @foreach($a['tags'] as $tag)
                                <span class="a-tag">{{ $tag }}</span>
                                @endforeach
                            </div>

                            {{-- Bottom row: CTA + radio dot --}}
                            <div class="relative z-10 flex items-center justify-between mt-auto">
                                <span class="a-cta" tabindex="-1">
                                    Découvrir l'atelier
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                                <div class="rdot"></div>
                            </div>

                        </div>
                        @endforeach
                    </div>

                    <p x-show="errors.atelier" x-cloak class="mt-4 flex items-center gap-2 text-sm" style="color:#ef4444;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="errors.atelier"></span>
                    </p>
                </div>

                {{-- ─── Étape 2 : Identité ─────────── --}}
                <div x-show="step === 2" x-cloak class="flex flex-col flex-1">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-px w-7" style="background:hsl(var(--orange-ivoire));"></div>
                            <p class="text-[10px] font-mono font-bold uppercase tracking-[0.22em]"
                               style="color:hsl(var(--orange-ivoire));">Étape 02 — Identité</p>
                        </div>
                        <h1 style="font-family:'Fraunces',serif; font-size:clamp(2rem,3.5vw,3rem); font-weight:900; letter-spacing:-0.03em; line-height:1.05;" class="mb-4">
                            Faisons <span style="color:hsl(var(--orange-ivoire));">connaissance.</span>
                        </h1>
                        <p class="text-base leading-relaxed mb-8" style="color:rgba(15,12,8,0.45); max-width:36rem;">
                            Quelques informations pour personnaliser votre accueil et votre badge officiel.
                        </p>
                    </div>

                    <div class="flex flex-col gap-20">
                        <div class="grid grid-cols-2 gap-10">
                            <div>
                                <label class="f-label">Nom <span style="color:#ef4444;">*</span></label>
                                <input x-model="form.nom" type="text" placeholder="KOUASSI"
                                       class="f-input" :class="errors.nom ? 'err' : ''">
                                <p x-show="errors.nom" class="mt-1.5 text-xs" style="color:#ef4444;" x-text="errors.nom"></p>
                            </div>
                            <div>
                                <label class="f-label">Prénom(s) <span style="color:#ef4444;">*</span></label>
                                <input x-model="form.prenom" type="text" placeholder="Amara"
                                       class="f-input" :class="errors.prenom ? 'err' : ''">
                                <p x-show="errors.prenom" class="mt-1.5 text-xs" style="color:#ef4444;" x-text="errors.prenom"></p>
                            </div>
                        </div>

                        <div style="margin-top: 2rem;">
                            <label class="f-label">Adresse e-mail <span style="color:#ef4444;">*</span></label>
                            <input x-model="form.email" type="email" placeholder="amara@entreprise.ci"
                                   class="f-input" :class="errors.email ? 'err' : ''">
                            <p x-show="errors.email" class="mt-1.5 text-xs" style="color:#ef4444;" x-text="errors.email"></p>
                        </div>

                        <div style="margin-top: 2rem;">
                            <label class="f-label">Téléphone <span class="font-normal normal-case" style="color:rgba(15,12,8,0.28); font-size:10px;">(optionnel)</span></label>
                            <input x-model="form.telephone" type="tel" placeholder="+225 07 00 00 00 00" class="f-input">
                        </div>
                    </div>

                    {{-- Badge credential card --}}
                    <div class="mt-auto pt-8">
                        <div style="
                            background:
                                linear-gradient(140deg, rgba(26,18,8,0.90) 0%, rgba(46,30,12,0.86) 55%, rgba(30,21,8,0.92) 100%),
                                url('{{ asset('images/hero-port.jpg') }}') center 60% / cover no-repeat;
                            border-radius: 1.375rem;
                            padding: 1.625rem 1.875rem;
                            position: relative;
                            overflow: hidden;
                            box-shadow: 0 8px 32px rgba(26,18,8,0.22), 0 2px 8px rgba(26,18,8,0.14);
                        ">
                            {{-- Halo décoratif arrière-plan --}}
                            <div style="position:absolute; right:-3rem; top:-3rem; width:11rem; height:11rem; border-radius:50%; border:1.5px solid rgba(229,107,26,0.13); pointer-events:none;"></div>
                            <div style="position:absolute; right:-1.25rem; top:-1.25rem; width:6.5rem; height:6.5rem; border-radius:50%; border:1.5px solid rgba(229,107,26,0.09); pointer-events:none;"></div>
                            {{-- Grain subtil --}}
                            <div style="position:absolute; inset:0; background: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2264%22 height=%2264%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/><feColorMatrix type=%22saturate%22 values=%220%22/></filter><rect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22 opacity=%220.06%22/></svg>'); pointer-events:none; border-radius:1.375rem;"></div>

                            {{-- Header badge --}}
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; position:relative;">
                                <p style="font-family:'JetBrains Mono',monospace; font-size:8px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:hsl(var(--orange-ivoire));">Votre badge · Hub Import-Export 2026</p>
                                <div style="width:1.875rem; height:1.875rem; border-radius:7px; background:hsl(var(--orange-ivoire)); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <span style="font-family:'JetBrains Mono',monospace; font-size:7.5px; font-weight:700; color:#fff; letter-spacing:-0.02em;">HIE</span>
                                </div>
                            </div>

                            {{-- Séparateur --}}
                            <div style="height:1px; background:rgba(255,255,255,0.08); margin-bottom:1.375rem;"></div>

                            {{-- Stats --}}
                            <div style="display:grid; grid-template-columns: repeat(3,1fr); gap:1.25rem; position:relative;">
                                <div>
                                    <p style="font-family:'Fraunces',serif; font-size:2.25rem; font-weight:900; color:#FFFBF5; line-height:1; letter-spacing:-0.03em;">4</p>
                                    <p style="font-size:0.675rem; color:rgba(255,255,255,0.38); margin-top:0.4rem; line-height:1.45; text-transform:uppercase; letter-spacing:0.09em; font-weight:700;">Ateliers<br>spécialisés</p>
                                </div>
                                <div>
                                    <p style="font-family:'Fraunces',serif; font-size:2.25rem; font-weight:900; color:#FFFBF5; line-height:1; letter-spacing:-0.03em;">150</p>
                                    <p style="font-size:0.675rem; color:rgba(255,255,255,0.38); margin-top:0.4rem; line-height:1.45; text-transform:uppercase; letter-spacing:0.09em; font-weight:700;">Participants<br>sélectionnés</p>
                                </div>
                                <div>
                                    <p style="font-family:'Fraunces',serif; font-size:2.25rem; font-weight:900; color:#FFFBF5; line-height:1; letter-spacing:-0.03em;">4 j.</p>
                                    <p style="font-size:0.675rem; color:rgba(255,255,255,0.38); margin-top:0.4rem; line-height:1.45; text-transform:uppercase; letter-spacing:0.09em; font-weight:700;">22–25 juin<br>Abidjan</p>
                                </div>
                            </div>

                            {{-- Pied badge --}}
                            <div style="margin-top:1.375rem; padding-top:1rem; border-top:1px solid rgba(255,255,255,0.07); display:flex; align-items:center; gap:0.5rem; position:relative;">
                                <div style="width:5px; height:5px; border-radius:50%; background:hsl(var(--orange-ivoire)); flex-shrink:0;"></div>
                                <p style="font-family:'JetBrains Mono',monospace; font-size:0.625rem; color:rgba(255,255,255,0.22); letter-spacing:0.07em;">Ministère du Commerce · Côte d'Ivoire</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── Étape 3 : Profil Pro ───────── --}}
                <div x-show="step === 3" x-cloak class="flex flex-col flex-1">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-px w-7" style="background:hsl(var(--orange-ivoire));"></div>
                            <p class="text-[10px] font-mono font-bold uppercase tracking-[0.22em]"
                               style="color:hsl(var(--orange-ivoire));">Étape 03 — Profil professionnel</p>
                        </div>
                        <h1 style="font-family:'Fraunces',serif; font-size:clamp(2rem,3.5vw,3rem); font-weight:900; letter-spacing:-0.03em; line-height:1.05;" class="mb-4">
                            Votre <span style="color:hsl(var(--orange-ivoire));">entreprise.</span>
                        </h1>
                        <p class="text-base leading-relaxed mb-8" style="color:rgba(15,12,8,0.45); max-width:36rem;">
                            Ces informations permettent au comité de cibler les ateliers les plus pertinents pour votre profil.
                        </p>
                    </div>

                    <div class="flex flex-col gap-20">
                        <div>
                            <label class="f-label">Nom de l'entreprise <span style="color:#ef4444;">*</span></label>
                            <input x-model="form.entreprise" type="text" placeholder="Nom de votre entreprise"
                                   class="f-input text-base" :class="errors.entreprise ? 'err' : ''">
                            <p x-show="errors.entreprise" class="mt-1.5 text-xs" style="color:#ef4444;" x-text="errors.entreprise"></p>
                        </div>
                        <div style="margin-top: 2rem;">
                            <label class="f-label">Secteur d'activité <span style="color:#ef4444;">*</span></label>
                            <select x-model="form.secteur" class="f-input text-base" :class="errors.secteur ? 'err' : ''">
                                <option value="">Sélectionnez un secteur</option>
                                <option>Agriculture & agroalimentaire</option>
                                <option>Commerce & distribution</option>
                                <option>Industrie & manufacture</option>
                                <option>Services aux entreprises</option>
                                <option>Logistique & transport</option>
                                <option>Technologies & numérique</option>
                                <option>Finance & assurance</option>
                                <option>Artisanat & textile</option>
                                <option>Autre</option>
                            </select>
                            <p x-show="errors.secteur" class="mt-1.5 text-xs" style="color:#ef4444;" x-text="errors.secteur"></p>
                        </div>
                        <div style="margin-top: 2rem;">
                            <label class="f-label">Poste occupé <span class="font-normal normal-case" style="color:rgba(15,12,8,0.28); font-size:10px;">(optionnel)</span></label>
                            <input x-model="form.poste" type="text" placeholder="Directeur export, Responsable commercial…" class="f-input text-base">
                        </div>
                    </div>
                </div>

                {{-- ─── Étape 4 : Motivations ──────── --}}
                <div x-show="step === 4" x-cloak class="flex flex-col flex-1">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-px w-7" style="background:hsl(var(--orange-ivoire));"></div>
                            <p class="text-[10px] font-mono font-bold uppercase tracking-[0.22em]"
                               style="color:hsl(var(--orange-ivoire));">Étape 04 — Motivations</p>
                        </div>
                        <h1 style="font-family:'Fraunces',serif; font-size:clamp(2rem,3.5vw,3rem); font-weight:900; letter-spacing:-0.03em; line-height:1.05;" class="mb-4">
                            Votre projet <span style="color:hsl(var(--orange-ivoire));">d'exportation.</span>
                        </h1>
                        <p class="text-base leading-relaxed mb-8" style="color:rgba(15,12,8,0.45); max-width:36rem;">
                            Facultatif — ces réponses aident le comité à évaluer votre dossier avec précision.
                        </p>
                    </div>

                    <div class="flex flex-col flex-1 gap-6">
                        <div class="flex flex-col flex-1">
                            <label class="f-label mb-1">Votre projet export <span class="font-normal normal-case" style="color:rgba(15,12,8,0.28); font-size:10px;">(optionnel)</span></label>
                            <textarea x-model="form.motivation_projet" class="f-input flex-1"
                                      style="border: 1.5px solid rgba(15,12,8,0.12); border-radius: 0.75rem; padding: 1rem; resize: none; font-size: 0.9375rem; background: rgba(255,255,255,0.6);"
                                      placeholder="Quels produits ou services souhaitez-vous exporter ? Vers quels marchés ?"></textarea>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="f-label mb-1">Objectifs pour ce Hub <span class="font-normal normal-case" style="color:rgba(15,12,8,0.28); font-size:10px;">(optionnel)</span></label>
                            <textarea x-model="form.motivation_objectifs" class="f-input flex-1"
                                      style="border: 1.5px solid rgba(15,12,8,0.12); border-radius: 0.75rem; padding: 1rem; resize: none; font-size: 0.9375rem; background: rgba(255,255,255,0.6);"
                                      placeholder="Qu'espérez-vous acquérir ou accomplir grâce au Hub Import-Export 2026 ?"></textarea>
                        </div>
                    </div>
                </div>

                {{-- ─── Étape 5 : Récapitulatif ────── --}}
                <div x-show="step === 5" x-cloak>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-px w-7" style="background:hsl(var(--orange-ivoire));"></div>
                        <p class="text-[10px] font-mono font-bold uppercase tracking-[0.22em]"
                           style="color:hsl(var(--orange-ivoire));">Étape 05 — Récapitulatif</p>
                    </div>
                    <h1 style="font-family:'Fraunces',serif; font-size:clamp(1.8rem,3.2vw,2.625rem); font-weight:900; letter-spacing:-0.025em; line-height:1.08; color:#1A1208;" class="mb-2">Vérifiez votre</h1>
                    <h1 style="font-family:'Fraunces',serif; font-size:clamp(1.8rem,3.2vw,2.625rem); font-weight:900; letter-spacing:-0.025em; line-height:1.08;" class="mb-8">
                        <span style="color:hsl(var(--orange-ivoire));">candidature.</span>
                    </h1>

                    <div class="space-y-3">
                        <div class="recap-block">
                            <p class="text-[9px] font-mono font-bold uppercase tracking-[0.2em] mb-2" style="color:rgba(15,12,8,0.32);">Atelier sélectionné</p>
                            <p class="font-semibold text-sm capitalize" style="color:#1A1208;" x-text="form.atelier.replace(/-/g,' ') || '—'"></p>
                        </div>
                        <div class="recap-block">
                            <p class="text-[9px] font-mono font-bold uppercase tracking-[0.2em] mb-3" style="color:rgba(15,12,8,0.32);">Identité</p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                                <div><span style="color:rgba(15,12,8,0.40);">Nom :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.nom + ' ' + form.prenom"></span></div>
                                <div><span style="color:rgba(15,12,8,0.40);">E-mail :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.email"></span></div>
                                <div x-show="form.telephone"><span style="color:rgba(15,12,8,0.40);">Tél :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.telephone"></span></div>
                            </div>
                        </div>
                        <div class="recap-block">
                            <p class="text-[9px] font-mono font-bold uppercase tracking-[0.2em] mb-3" style="color:rgba(15,12,8,0.32);">Profil professionnel</p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                                <div class="col-span-2"><span style="color:rgba(15,12,8,0.40);">Entreprise :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.entreprise"></span></div>
                                <div><span style="color:rgba(15,12,8,0.40);">Secteur :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.secteur"></span></div>
                                <div x-show="form.poste"><span style="color:rgba(15,12,8,0.40);">Poste :</span> <span class="font-semibold" style="color:#1A1208;" x-text="form.poste"></span></div>
                            </div>
                        </div>
                        <div class="info-box text-sm leading-relaxed" style="border-left-color: hsl(var(--vert-ivoire)); background: hsl(var(--vert-ivoire)/0.05); color:rgba(15,12,8,0.60);">
                            En soumettant, vous acceptez que vos données soient traitées par la <strong>DGCE</strong> dans le cadre du Hub Import-Export 2026, conformément à la politique de confidentialité.
                        </div>
                    </div>
                </div>

            </div>{{-- /zone contenu --}}

            {{-- ─── Barre nav bas ──────────────────────── --}}
            <div class="w-nav">
                <button @click="prev()" :disabled="step === 1" class="btn-prev">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Précédent
                </button>

                <button x-show="step < total" @click="next()" class="btn-next">
                    Continuer
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button x-show="step === total" @click="submit()" :disabled="loading" class="btn-next" :class="loading ? 'cursor-wait' : ''">
                    <span x-text="loading ? 'Envoi…' : 'Soumettre ma candidature'"></span>
                    <svg x-show="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>{{-- /w-form-scroll --}}

    </div>{{-- /w-main --}}

</div>{{-- /w-layout --}}

<script>
function launchConfetti() {
    const canvas = document.getElementById('confetti-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;

    const COLORS = [
        '#E56B1A', '#B83A0A', '#C2850A',
        '#1A7A3C', '#F5C842', '#FFFFFF',
        '#F0974A', '#6DBE6D', '#FFF5E0',
    ];
    const N = 160;
    const particles = [];

    for (let i = 0; i < N; i++) {
        const cx = canvas.width  * (0.25 + Math.random() * 0.50);
        const cy = canvas.height * (0.30 + Math.random() * 0.15);
        const angle = Math.random() * Math.PI * 2;
        const speed = Math.random() * 18 + 6;
        particles.push({
            x:  cx,
            y:  cy,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed - 14,
            color:     COLORS[Math.floor(Math.random() * COLORS.length)],
            w:         Math.random() * 10 + 5,
            h:         Math.random() * 5  + 3,
            rotation:  Math.random() * Math.PI * 2,
            rotSpeed:  (Math.random() - 0.5) * 0.28,
            gravity:   0.50 + Math.random() * 0.20,
            drag:      0.992,
            alpha:     1,
            shape:     Math.random() < 0.45 ? 'rect' : (Math.random() < 0.6 ? 'circle' : 'strip'),
        });
    }

    let raf;
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        let alive = 0;

        for (const p of particles) {
            p.vy  += p.gravity;
            p.vx  *= p.drag;
            p.x   += p.vx;
            p.y   += p.vy;
            p.rotation += p.rotSpeed;

            if (p.y > canvas.height * 0.60) {
                p.alpha = Math.max(0, p.alpha - 0.020);
            }
            if (p.y < canvas.height + 40 && p.alpha > 0) alive++;

            ctx.save();
            ctx.globalAlpha = p.alpha;
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation);
            ctx.fillStyle = p.color;

            if (p.shape === 'circle') {
                ctx.beginPath();
                ctx.arc(0, 0, p.w * 0.48, 0, Math.PI * 2);
                ctx.fill();
            } else if (p.shape === 'strip') {
                ctx.fillRect(-p.w * 0.25, -p.h * 1.8, p.w * 0.5, p.h * 3.6);
            } else {
                ctx.fillRect(-p.w * 0.5, -p.h * 0.5, p.w, p.h);
            }
            ctx.restore();
        }

        if (alive > 0) {
            raf = requestAnimationFrame(draw);
        } else {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    raf = requestAnimationFrame(draw);
}
</script>

</body>
</html>
