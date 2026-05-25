@props(['title' => null])
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' — Espace candidat · Hub IE 2026' : 'Espace candidat — Hub Import-Export 2026' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-blanc-creme text-noir-profond min-h-screen">

{{-- Barre de navigation candidate --}}
<header class="sticky top-0 z-40 glass-light shadow-sm border-b border-blanc-pur/60">
    <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between gap-6">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <svg width="36" height="36" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                <rect width="44" height="44" rx="10" fill="hsl(var(--orange-ivoire))"/>
                <text x="22" y="30" font-family="Inter,sans-serif" font-size="16" font-weight="700" fill="white" text-anchor="middle" letter-spacing="-0.5">HIE</text>
            </svg>
            <span class="font-manrope font-bold text-xs text-noir-profond leading-tight hidden sm:block">
                Hub Import-Export<br>
                <span class="font-normal opacity-60">Espace candidat</span>
            </span>
        </a>

        <nav class="hidden sm:flex items-center gap-1 text-sm">
            <a href="{{ route('candidate.dashboard') }}"
               class="px-3 py-1.5 rounded-lg font-medium transition-colors
                      {{ request()->routeIs('candidate.dashboard') ? 'bg-noir-profond text-blanc-pur' : 'text-gris-500 hover:text-noir-profond' }}">
                Tableau de bord
            </a>
            <a href="{{ route('candidature.index') }}"
               class="px-3 py-1.5 rounded-lg font-medium transition-colors
                      {{ request()->routeIs('candidature.*') ? 'bg-noir-profond text-blanc-pur' : 'text-gris-500 hover:text-noir-profond' }}">
                Ma candidature
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-xs text-gris-500 truncate max-w-[160px]">{{ auth()->user()?->email }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-gris-500 hover:text-orange-ivoire transition-colors link-underline">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    {{ $slot }}
</main>

<footer class="border-t border-sable mt-16 py-6">
    <div class="max-w-4xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-gris-500">
            © 2026 Hub Import-Export — Ministère du Commerce, de l'Industrie et de l'Artisanat
        </p>
        <a href="{{ route('home') }}" class="text-xs text-orange-ivoire link-underline">← Retour au site</a>
    </div>
</footer>

</body>
</html>
