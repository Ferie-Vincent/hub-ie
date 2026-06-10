<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Désinscription — Délai dépassé — Hub Import-Export 2026</title>
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

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: rgba(148, 163, 184, 0.08);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(148, 163, 184, 0.80);
            margin-bottom: 1.75rem;
        }
        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.60);
            flex-shrink: 0;
        }

        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.10);
            border: 1px solid rgba(148, 163, 184, 0.20);
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

        .info-block {
            padding: 1.125rem 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            margin-bottom: 1.875rem;
        }
        .info-block-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.30);
            margin-bottom: 0.375rem;
        }
        .info-block-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.65);
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.07);
            margin: 0 0 1.875rem;
        }

        .help-text {
            font-size: 0.825rem;
            line-height: 1.68;
            color: rgba(255, 255, 255, 0.35);
            margin-bottom: 2rem;
        }
        .help-text a {
            color: hsl(var(--vert-ivoire));
            text-decoration: none;
            border-bottom: 1px solid hsl(var(--vert-ivoire) / 0.35);
            transition: border-color 0.15s;
        }
        .help-text a:hover {
            border-color: hsl(var(--vert-ivoire));
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

        .btn-contact {
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
        .btn-contact:hover {
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

        {{-- Badge statut --}}
        <div class="status-badge">Délai dépassé</div>

        {{-- Icône --}}
        <div class="icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(148,163,184,0.70)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        {{-- Titre --}}
        <h1 class="hub-title">
            Délai de désinscription dépassé
        </h1>

        <p class="hub-desc">
            La période pendant laquelle il était possible de se désinscrire de cet atelier est désormais close. Votre inscription reste confirmée.
        </p>

        {{-- Date limite affichée --}}
        <div class="info-block">
            <p class="info-block-label">Date limite de désinscription</p>
            <p class="info-block-value">
                {{ $deadline instanceof \Carbon\Carbon ? $deadline->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH[h]mm') : $deadline }}
            </p>
        </div>

        <div class="divider"></div>

        {{-- Texte d'aide --}}
        <p class="help-text">
            Si vous vous trouvez dans une situation exceptionnelle (urgence médicale, contrainte professionnelle), nous vous invitons à contacter directement l'organisation du Hub Import-Export 2026 via la page
            <a href="{{ route('contact') }}">contact</a>.
            Votre demande sera examinée par le comité dans les meilleurs délais.
        </p>

        {{-- Actions --}}
        <a href="{{ url('/') }}" class="btn-home">
            Retour à l'accueil
        </a>
        <a href="{{ route('contact') }}" class="btn-contact">
            Contacter l'organisation
        </a>

    </div>

    <p class="hub-footer">
        Hub Import-Export 2026 — Plateforme officielle du Ministère du Commerce, République de Côte d'Ivoire
    </p>

</body>
</html>
