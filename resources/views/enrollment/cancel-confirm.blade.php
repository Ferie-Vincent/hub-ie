<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirmer la désinscription — Hub Import-Export 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700..900;1,9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            background: #0A1628;
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
        }

        .hub-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 2.75rem 2.5rem 2.25rem;
            backdrop-filter: blur(8px);
        }

        .hub-logo {
            display: block;
            margin: 0 auto 2.25rem;
        }

        .warning-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            border: 1px solid rgba(239, 153, 54, 0.35);
            background: rgba(239, 153, 54, 0.10);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #EF9936;
            margin-bottom: 1.75rem;
        }
        .warning-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #EF9936;
            flex-shrink: 0;
        }

        .hub-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.625rem, 3vw, 2rem);
            font-weight: 900;
            letter-spacing: -0.025em;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 1rem;
        }

        .hub-desc {
            font-size: 0.9rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.50);
            margin-bottom: 2rem;
        }

        .warning-box {
            display: flex;
            gap: 0.875rem;
            padding: 1rem 1.125rem;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .warning-box-icon {
            font-size: 1.125rem;
            flex-shrink: 0;
            margin-top: 0.05rem;
        }
        .warning-box-text {
            font-size: 0.8375rem;
            line-height: 1.65;
            color: rgba(255, 200, 200, 0.85);
        }
        .warning-box-text strong {
            color: #FCA5A5;
            font-weight: 600;
        }

        .deadline-info {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            margin-bottom: 2rem;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.45);
        }
        .deadline-info span {
            font-family: 'JetBrains Mono', monospace;
            color: rgba(255, 255, 255, 0.70);
            font-size: 0.8rem;
        }

        .btn-cancel-confirm {
            width: 100%;
            padding: 0.9375rem;
            background: #DC2626;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.15s;
            margin-bottom: 0.875rem;
        }
        .btn-cancel-confirm:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-cancel-confirm:active { transform: scale(0.98); }

        .btn-back {
            display: block;
            width: 100%;
            padding: 0.9375rem;
            background: transparent;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.80);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .hub-footer {
            margin-top: 2.5rem;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.20);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="hub-card">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="hub-logo" style="width: fit-content;">
            <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" style="height: 36px; width: auto; display: block; opacity: 0.85;">
        </a>

        {{-- Badge avertissement --}}
        <div class="warning-badge">Action irréversible</div>

        {{-- Titre --}}
        <h1 class="hub-title">
            Confirmer la désinscription
        </h1>

        <p class="hub-desc">
            Vous êtes sur le point d'annuler votre inscription à un atelier du Hub Import-Export 2026. Veuillez lire attentivement les informations ci-dessous avant de confirmer.
        </p>

        {{-- Bloc d'avertissement --}}
        <div class="warning-box">
            <div class="warning-box-icon">⚠️</div>
            <div class="warning-box-text">
                <strong>Cette action est définitive.</strong> Une fois confirmée, votre place sera libérée et attribuée à un autre participant. Vous ne pourrez pas réintégrer l'atelier si les places sont épuisées.
            </div>
        </div>

        {{-- Date limite --}}
        <div class="deadline-info">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.5; flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Délai de désinscription :&nbsp;<span>{{ $deadline instanceof \Carbon\Carbon ? $deadline->locale('fr')->isoFormat('D MMMM YYYY [à] HH[h]mm') : $deadline }}</span>
        </div>

        {{-- Formulaire de confirmation --}}
        <form method="POST" action="{{ route('enrollment.cancel.confirm', $token) }}">
            @csrf
            <button type="submit" class="btn-cancel-confirm">
                Confirmer l'annulation
            </button>
        </form>

        {{-- Bouton retour --}}
        <a href="javascript:history.back()" class="btn-back">
            ← Annuler — conserver mon inscription
        </a>

    </div>

    <p class="hub-footer">
        Hub Import-Export 2026 — Plateforme officielle du Ministère du Commerce, République de Côte d'Ivoire
    </p>

</body>
</html>
