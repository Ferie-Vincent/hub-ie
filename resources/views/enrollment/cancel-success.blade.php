<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Désinscription confirmée — Hub Import-Export 2026</title>
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
            width: fit-content;
        }

        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            border: 1px solid rgba(43, 158, 94, 0.35);
            background: rgba(43, 158, 94, 0.10);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #2B9E5E;
            margin-bottom: 1.75rem;
        }
        .success-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #2B9E5E;
            flex-shrink: 0;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(43, 158, 94, 0.10);
            border: 1px solid rgba(43, 158, 94, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .hub-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.5rem, 3vw, 1.875rem);
            font-weight: 900;
            letter-spacing: -0.025em;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 1rem;
        }

        .hub-desc {
            font-size: 0.9rem;
            line-height: 1.72;
            color: rgba(255, 255, 255, 0.48);
            margin-bottom: 1.875rem;
        }

        .workshop-block {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 1.125rem 1.25rem;
            background: rgba(43, 158, 94, 0.06);
            border: 1px solid rgba(43, 158, 94, 0.20);
            border-radius: 10px;
            margin-bottom: 1.875rem;
        }
        .workshop-block-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(43, 158, 94, 0.14);
            border: 1px solid rgba(43, 158, 94, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .workshop-block-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(43, 158, 94, 0.70);
            margin-bottom: 0.3rem;
        }
        .workshop-block-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.80);
            line-height: 1.35;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.07);
            margin: 0 0 1.875rem;
        }

        .notice-text {
            font-size: 0.8125rem;
            line-height: 1.68;
            color: rgba(255, 255, 255, 0.32);
            margin-bottom: 2rem;
        }
        .notice-text strong {
            color: rgba(255, 255, 255, 0.52);
            font-weight: 600;
        }

        .btn-home {
            display: block;
            width: 100%;
            padding: 0.9375rem;
            background: hsl(var(--vert-ivoire));
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            text-align: center;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.15s, transform 0.15s;
            margin-bottom: 0.875rem;
        }
        .btn-home:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-home:active { transform: scale(0.98); }

        .btn-dashboard {
            display: block;
            width: 100%;
            padding: 0.9375rem;
            background: transparent;
            color: rgba(255, 255, 255, 0.50);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }
        .btn-dashboard:hover {
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.75);
            border-color: rgba(255, 255, 255, 0.22);
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
        <a href="{{ url('/') }}" class="hub-logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" style="height: 36px; width: auto; display: block; opacity: 0.85;">
        </a>

        {{-- Badge succès --}}
        <div class="success-badge">Désinscription enregistrée</div>

        {{-- Icône --}}
        <div class="icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2B9E5E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        {{-- Titre --}}
        <h1 class="hub-title">
            Désinscription confirmée
        </h1>

        <p class="hub-desc">
            Votre désinscription a bien été prise en compte. Votre place a été libérée et sera proposée à un autre participant.
        </p>

        {{-- Atelier concerné --}}
        <div class="workshop-block">
            <div class="workshop-block-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2B9E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <p class="workshop-block-label">Atelier concerné</p>
                <p class="workshop-block-name">{{ $workshop->title ?? $workshop }}</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Note informative --}}
        <p class="notice-text">
            <strong>Un email de confirmation</strong> vous a été envoyé à l'adresse associée à votre compte. Si vous ne le recevez pas dans les prochaines minutes, vérifiez votre dossier de courriers indésirables.
        </p>

        {{-- Actions --}}
        <a href="{{ url('/') }}" class="btn-home">
            Retour à l'accueil
        </a>

        @auth
        <a href="{{ route('candidate.dashboard') }}" class="btn-dashboard">
            Accéder à mon espace
        </a>
        @endauth

    </div>

    <p class="hub-footer">
        Hub Import-Export 2026 — Plateforme officielle du Ministère du Commerce, République de Côte d'Ivoire
    </p>

</body>
</html>
