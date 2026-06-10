<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}Hub Import-Export 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700..900;1,9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }

        .auth-layout {
            display: grid;
            grid-template-columns: clamp(360px, 42vw, 540px) 1fr;
            min-height: 100vh;
        }
        @media (max-width: 900px) {
            .auth-layout { grid-template-columns: 1fr; }
            .auth-panel-right { display: none; }
        }

        /* ── Champs underline ── */
        .auth-input {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid rgba(15,12,8,0.16);
            border-radius: 0;
            background: transparent;
            padding: 0.875rem 0;
            font-size: 0.9375rem;
            font-family: 'Inter', sans-serif;
            color: #0F0C08;
            outline: none;
            transition: border-color 0.18s;
        }
        .auth-input:focus { border-bottom-color: hsl(var(--vert-ivoire)); outline: none !important; box-shadow: none !important; }
        .auth-input:focus-visible { outline: none !important; outline-offset: 0 !important; }
        .auth-input::placeholder { color: rgba(15,12,8,0.25); font-size: 0.875rem; }

        .auth-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(15,12,8,0.38);
            margin-bottom: 0.2rem;
        }

        .auth-btn {
            width: 100%;
            padding: 0.9375rem;
            background: hsl(var(--vert-ivoire));
            color: #fff;
            font-weight: 700;
            font-size: 0.875rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.15s;
        }
        .auth-btn:hover { opacity: 0.88; transform: translateY(-1px); }
        .auth-btn:active { transform: scale(0.98); }

        /* ── Panneau droit ── */
        .auth-panel-right {
            position: relative;
            overflow: hidden;
        }
        .auth-panel-right img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .auth-panel-right-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(155deg,
                rgba(10,30,20,0.93) 0%,
                rgba(15,42,28,0.88) 40%,
                rgba(8,22,15,0.92) 100%);
        }
        .auth-panel-right-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 3.5rem;
        }

        /* ── Badge pill ── */
        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.07);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.75);
            width: fit-content;
            margin-bottom: 2.5rem;
        }
        .auth-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: hsl(var(--vert-soft));
            flex-shrink: 0;
        }

        /* ── Séparateur stats ── */
        .auth-stat-sep {
            width: 1px;
            height: 2.5rem;
            background: rgba(255,255,255,0.15);
            flex-shrink: 0;
        }

        /* ── Erreur Laravel ── */
        .auth-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.375rem;
        }
    </style>
</head>
<body class="antialiased font-sans">

<div class="auth-layout">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- PANNEAU GAUCHE — blanc, formulaire                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div style="background:#FAFAF7; display:flex; flex-direction:column; min-height:100vh;">

        {{-- En-tête brand --}}
        <div style="padding: 2.25rem 3rem 0;">
            <a href="{{ url('/') }}" style="display:inline-flex; align-items:center; gap:0.75rem; text-decoration:none;">
                <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" style="height:40px; width:auto; object-fit:contain;">
            </a>
        </div>

        {{-- Corps formulaire --}}
        <div style="flex:1; display:flex; flex-direction:column; justify-content:center; padding: 2.5rem 3rem 3rem;">

            {{-- Titre --}}
            <div style="margin-bottom: 2.5rem;">
                <p style="font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:700; letter-spacing:0.24em; text-transform:uppercase; color:hsl(var(--vert-ivoire)); margin-bottom:0.625rem;">
                    Espace sécurisé
                </p>
                <h1 style="font-family:'Fraunces',serif; font-size:clamp(1.75rem,2.8vw,2.25rem); font-weight:900; letter-spacing:-0.025em; line-height:1.1; color:#1A1208; margin-bottom:0.625rem;">
                    Bon retour.
                </h1>
                <p style="font-size:0.9rem; color:rgba(15,12,8,0.45); line-height:1.65; max-width:24rem;">
                    Saisissez vos identifiants pour accéder à votre espace Hub Import-Export 2026.
                </p>
            </div>

            {{-- Slot formulaire --}}
            {{ $slot }}

        </div>

        {{-- Pied --}}
        <div style="padding: 1.5rem 3rem; border-top: 1px solid rgba(15,12,8,0.07);">
            <a href="{{ url('/') }}"
               style="font-size:0.8rem; color:rgba(15,12,8,0.35); text-decoration:none; transition:color 0.15s;"
               onmouseover="this.style.color='hsl(var(--vert-ivoire))'"
               onmouseout="this.style.color='rgba(15,12,8,0.35)'">
                ← Retour à l'accueil
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- PANNEAU DROIT — image sombre, branding                            --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="auth-panel-right">
        <img src="{{ asset('images/cap-marche.jpg') }}" alt="" aria-hidden="true">
        <div class="auth-panel-right-overlay"></div>

        <div class="auth-panel-right-content">

            {{-- Badge --}}
            <div class="auth-badge">Plateforme officielle 2026</div>

            {{-- Titre principal --}}
            <h2 style="font-family:'Fraunces',serif; font-size:clamp(2rem,3.5vw,3.125rem); font-weight:900; letter-spacing:-0.025em; line-height:1.1; color:#fff; margin-bottom:1.375rem;">
                Le commerce extérieur<br>
                <em style="font-style:italic; color:hsl(var(--orange-soft)); font-variation-settings:'opsz' 144,'SOFT' 100;">ivoirien en action.</em>
            </h2>

            {{-- Description --}}
            <p style="font-size:1rem; line-height:1.72; color:rgba(255,255,255,0.55); max-width:28rem; margin-bottom:3rem;">
                Accédez aux outils de gestion et de pilotage du Hub Import-Export 2026 — formation, candidatures et pointage en un seul espace.
            </p>

            {{-- Stats --}}
            <div style="display:flex; align-items:center; gap:2rem; margin-bottom:3.5rem;">
                <div>
                    <p style="font-family:'Fraunces',serif; font-size:2rem; font-weight:900; color:#fff; line-height:1; margin-bottom:0.25rem;">150</p>
                    <p style="font-family:'JetBrains Mono',monospace; font-size:0.6rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(255,255,255,0.38);">Auditeurs</p>
                </div>
                <div class="auth-stat-sep"></div>
                <div>
                    <p style="font-family:'Fraunces',serif; font-size:2rem; font-weight:900; color:#fff; line-height:1; margin-bottom:0.25rem;">4</p>
                    <p style="font-family:'JetBrains Mono',monospace; font-size:0.6rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(255,255,255,0.38);">Ateliers</p>
                </div>
                <div class="auth-stat-sep"></div>
                <div>
                    <p style="font-family:'Fraunces',serif; font-size:2rem; font-weight:900; color:#fff; line-height:1; margin-bottom:0.25rem;">22–25</p>
                    <p style="font-family:'JetBrains Mono',monospace; font-size:0.6rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(255,255,255,0.38);">Juin 2026</p>
                </div>
            </div>

            {{-- Patrocinage --}}
            <div style="display:flex; align-items:center; gap:1rem; padding-top:2rem; border-top:1px solid rgba(255,255,255,0.10);">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:700; color:rgba(255,255,255,0.7);">MCIA</span>
                </div>
                <div>
                    <p style="font-size:0.75rem; font-weight:600; color:rgba(255,255,255,0.85); margin-bottom:0.125rem;">
                        Sous le haut patronage du Ministre du Commerce
                    </p>
                    <p style="font-size:0.6875rem; color:rgba(255,255,255,0.38);">République de Côte d'Ivoire</p>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
