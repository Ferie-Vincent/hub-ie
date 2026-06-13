@props(['title' => null, 'description' => null, 'darkHero' => false])
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Hub Import-Export 2026 — Abidjan, 22–25 juin' }}</title>
    <meta name="description" content="{{ $description ?? 'Plateforme officielle du Hub Import-Export 2026 organisé par le Ministère du Commerce de Côte d\'Ivoire, du 22 au 25 juin 2026 à Abidjan. Candidatures ouvertes.' }}">

    {{-- OG / Twitter Card (BRIEF §III.11) --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_CI">
    <meta property="og:site_name" content="Hub Import-Export 2026">
    <meta property="og:title" content="{{ $title ?? 'Hub Import-Export 2026 — Abidjan, 22–25 juin' }}">
    <meta property="og:description" content="{{ $description ?? 'Plateforme officielle du Hub Import-Export 2026 organisé par le Ministère du Commerce de Côte d\'Ivoire, du 22 au 25 juin 2026 à Abidjan.' }}">
    <meta property="og:image" content="{{ asset('images/og-hub-2026.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Hub Import-Export 2026 — Abidjan, 22–25 juin' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Plateforme officielle du Hub Import-Export 2026 organisé par le Ministère du Commerce de Côte d\'Ivoire.' }}">
    <meta name="twitter:image" content="{{ asset('images/og-hub-2026.jpg') }}">

    @if(request()->routeIs('home'))
    {{-- Schema.org Event (BRIEF §III.11) --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Event",
        "name": "Hub Import-Export 2026",
        "startDate": "2026-06-22T09:00:00+00:00",
        "endDate": "2026-06-25T18:00:00+00:00",
        "eventStatus": "https://schema.org/EventScheduled",
        "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
        "location": [
            {"@@type": "Place", "name": "CGECI", "address": {"@@type": "PostalAddress", "addressLocality": "Abidjan-Plateau", "addressCountry": "CI"}},
            {"@@type": "Place", "name": "CCI-CI", "address": {"@@type": "PostalAddress", "addressLocality": "Abidjan-Plateau", "addressCountry": "CI"}},
            {"@@type": "Place", "name": "SEEN Hôtel", "address": {"@@type": "PostalAddress", "addressLocality": "Abidjan-Plateau", "addressCountry": "CI"}}
        ],
        "description": "Le rendez-vous stratégique des acteurs du commerce extérieur ivoirien. Sous le haut patronage du Ministre du Commerce, de l'Industrie et de l'Artisanat.",
        "organizer": {
            "@@type": "Organization",
            "name": "Direction Générale du Commerce Extérieur (DGCE) — Ministère du Commerce, de l'Industrie et de l'Artisanat",
            "url": "https://hubimportexport.ci"
        },
        "url": "https://hubimportexport.ci",
        "image": "{{ asset('images/og-hub-2026.jpg') }}",
        "isAccessibleForFree": true
    }
    </script>
    @endif

    {{-- Google Fonts — 5 polices (BRIEF §II.2) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&family=Manrope:wght@200;400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nav-active {
            position: relative;
        }
        .nav-active-dot {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 2.5px;
            border-radius: 9999px;
            background: hsl(var(--vert-ivoire));
        }
    </style>

    {{ $head ?? '' }}
</head>
<body class="antialiased font-sans bg-noir-profond text-blanc-pur">

{{-- ── Motif bogolan global — défini une fois, référencé partout ─────────── --}}
<svg width="0" height="0" class="absolute overflow-hidden" aria-hidden="true" focusable="false">
    <defs>
        <pattern id="bogolan-pattern" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
            <line x1="0" y1="0" x2="36" y2="0" stroke="white" stroke-width="0.4"/>
            <line x1="0" y1="0" x2="0" y2="36" stroke="white" stroke-width="0.4"/>
            <polygon points="18,2 34,18 18,34 2,18" fill="none" stroke="white" stroke-width="1.4"/>
            <polygon points="18,10 26,18 18,26 10,18" fill="none" stroke="white" stroke-width="0.8"/>
            <line x1="14" y1="18" x2="22" y2="18" stroke="white" stroke-width="2"/>
            <line x1="18" y1="14" x2="18" y2="22" stroke="white" stroke-width="2"/>
            <circle cx="2" cy="2" r="1.8" fill="white"/>
            <circle cx="34" cy="2" r="1.8" fill="white"/>
            <circle cx="2" cy="34" r="1.8" fill="white"/>
            <circle cx="34" cy="34" r="1.8" fill="white"/>
            <line x1="16" y1="0" x2="20" y2="0" stroke="white" stroke-width="1.5"/>
            <line x1="0" y1="16" x2="0" y2="20" stroke="white" stroke-width="1.5"/>
        </pattern>
    </defs>
</svg>

{{-- ── Header sticky (BRIEF §II.6) ─────────────────────────────────────── --}}
{{-- darkHero=true → hero sombre → texte blanc au départ (home)           --}}
{{-- darkHero=false → page claire → glass-light + texte sombre par défaut --}}
<header
    x-data="{ scrolled: {{ $darkHero ? 'false' : 'true' }}, open: false }"
    @scroll.window="scrolled = {{ $darkHero ? 'window.scrollY > 10' : 'true' }}"
    @keydown.escape.window="open = false"
    :class="scrolled ? 'glass-light shadow-sm' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    role="banner"
>
    <div class="max-w-hub mx-auto px-6 h-20 flex items-center justify-between gap-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
            <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" class="h-11 w-auto object-contain">
            <span
                :class="scrolled ? 'text-noir-profond' : 'text-blanc-pur'"
                class="font-manrope font-bold text-sm leading-tight transition-colors duration-300 hidden sm:block"
            >
                Hub Import-Export<br>
                <span class="font-normal text-xs opacity-70">2026 · Abidjan</span>
            </span>
        </a>

        {{-- Nav principale --}}
        <nav class="hidden md:flex items-center gap-1" aria-label="Navigation principale">
            @foreach([
                ['Accueil',     route('home'),             'home'],
                ['Programme',   route('programme'),        'programme'],
                ['Ateliers',    route('ateliers.index'),   'ateliers.*'],
                ['Actualités',  route('actualites.index'), 'actualites.*'],
                ['FAQ',         route('faq'),              'faq'],
                ['Portfolio',   route('portfolio'),        'portfolio'],
                ['Partenaires', route('partenaires'),      'partenaires'],
                ['Contact',     route('contact'),          'contact'],
            ] as [$label, $href, $routeName])
            @php $isActive = request()->routeIs($routeName); @endphp
            <a
                href="{{ $href }}"
                @if($isActive) aria-current="page" @endif
                :class="scrolled
                    ? '{{ $isActive ? 'text-vert-ivoire font-bold' : 'text-noir-profond hover:text-vert-ivoire' }}'
                    : '{{ $isActive ? 'text-blanc-pur font-bold' : 'text-blanc-pur/80 hover:text-blanc-pur' }}'"
                class="hub-tab-hover px-4 py-2 text-sm transition-colors duration-200 relative {{ $isActive ? 'nav-active' : '' }}"
            >{{ $label }}
                @if($isActive)
                <span class="nav-active-dot" aria-hidden="true"></span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- CTAs droite --}}
        <div class="flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}"
                    :class="scrolled ? 'text-noir-profond' : 'text-blanc-pur/80'"
                    class="hidden sm:inline text-sm font-medium link-underline transition-colors duration-200">
                    Se connecter
                </a>
                <a href="{{ route('inscription') }}" class="btn-fill text-sm px-5 py-2.5">
                    <span>S'inscrire</span>
                </a>
            @else
                @if(auth()->user()?->hasRole('candidate'))
                    <a href="{{ route('candidate.dashboard') }}" class="btn-fill text-sm px-5 py-2.5">
                        <span>Mon espace</span>
                    </a>
                @else
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-fill text-sm px-5 py-2.5">
                        <span>Administration</span>
                    </a>
                @endif
            @endguest

            {{-- Mobile menu button --}}
            <button
                @click="open = !open"
                :aria-expanded="open"
                :class="scrolled ? 'text-noir-profond' : 'text-blanc-pur'"
                class="md:hidden p-2 transition-colors"
                aria-label="Menu"
            >
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile nav panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="open = false"
        class="md:hidden border-t"
        :class="scrolled ? 'glass-light border-noir-profond/10' : 'bg-noir-profond/95 border-blanc-pur/10'"
        x-cloak
    >
        <nav class="max-w-hub mx-auto px-6 py-4 flex flex-col gap-1" aria-label="Navigation mobile">
            @foreach([
                ['Accueil',     route('home'),             'home'],
                ['Programme',   route('programme'),        'programme'],
                ['Ateliers',    route('ateliers.index'),   'ateliers.*'],
                ['Actualités',  route('actualites.index'), 'actualites.*'],
                ['FAQ',         route('faq'),              'faq'],
                ['Portfolio',   route('portfolio'),        'portfolio'],
                ['Partenaires', route('partenaires'),      'partenaires'],
                ['Contact',     route('contact'),          'contact'],
            ] as [$label, $href, $routeName])
            @php $isActive = request()->routeIs($routeName); @endphp
            <a href="{{ $href }}"
               @click="open = false"
               :class="scrolled ? '{{ $isActive ? 'text-vert-ivoire font-bold' : 'text-noir-profond' }}' : '{{ $isActive ? 'text-vert-ivoire font-bold' : 'text-blanc-pur/90' }}'"
               class="px-3 py-3 text-sm rounded-lg hover:bg-blanc-pur/10 transition-colors">
                {{ $label }}
            </a>
            @endforeach

            <div class="mt-3 pt-3 border-t flex flex-col gap-2" :class="scrolled ? 'border-noir-profond/10' : 'border-blanc-pur/10'">
                @guest
                    <a href="{{ route('login') }}"
                       :class="scrolled ? 'text-noir-profond' : 'text-blanc-pur/80'"
                       class="px-3 py-3 text-sm font-medium transition-colors"
                       @click="open = false">
                        Se connecter
                    </a>
                    <a href="{{ route('inscription') }}" class="btn-fill text-sm text-center py-3" @click="open = false">
                        <span>S'inscrire</span>
                    </a>
                @else
                    @if(auth()->user()?->hasRole('candidate'))
                        <a href="{{ route('candidate.dashboard') }}" class="btn-fill text-sm text-center py-3" @click="open = false">
                            <span>Mon espace</span>
                        </a>
                    @else
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-fill text-sm text-center py-3" @click="open = false">
                            <span>Administration</span>
                        </a>
                    @endif
                @endguest
            </div>
        </nav>
    </div>
</header>

{{-- ── Contenu principal ─────────────────────────────────────────────────── --}}
<main id="main-content">
    {{ $slot }}
</main>

{{-- ── Footer (BRIEF §III.1, A.6) ──────────────────────────────────────── --}}
<footer class="relative bg-noir-profond border-t border-blanc-pur/8 overflow-hidden" role="contentinfo">
    {{-- Motif bogolan footer --}}
    <svg class="absolute inset-0 w-full h-full pointer-events-none" style="opacity: 0.05;" aria-hidden="true">
        <rect width="100%" height="100%" fill="url(#bogolan-pattern)"/>
    </svg>
    <div class="relative z-10 max-w-hub mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            {{-- Colonne Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-5">
                    <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" class="h-9 w-auto object-contain">
                </div>
                <p class="text-sm text-blanc-pur/60 leading-relaxed mb-4">
                    Le rendez-vous stratégique des acteurs du commerce extérieur ivoirien. Sous le haut patronage du Ministre du Commerce, de l'Industrie et de l'Artisanat.
                </p>
                <p class="text-xs text-orange-soft font-mono">22–25 juin · Abidjan</p>
            </div>

            {{-- Colonne Le Hub --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-blanc-pur/40 mb-5">Le Hub</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['Ateliers',      route('ateliers.index')],
                        ['Programme',     route('programme')],
                        ['FAQ',           route('faq')],
                        ['Partenaires',   route('partenaires')],
                        ['Espace presse', route('presse')],
                    ] as [$label, $href])
                    <li><a href="{{ $href }}" class="hub-footer-link text-sm text-blanc-pur/60">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Colonne Informations légales --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-blanc-pur/40 mb-5">Informations légales</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['Mentions légales',              route('mentions-legales')],
                        ['Politique de confidentialité',  route('politique-confidentialite')],
                        ['Conditions d\'utilisation',     route('conditions-utilisation')],
                    ] as [$label, $href])
                    <li><a href="{{ $href }}" class="hub-footer-link text-sm text-blanc-pur/60">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Colonne Contact --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-blanc-pur/40 mb-5">Contact</h3>
                <address class="not-italic space-y-3 text-sm text-blanc-pur/70">
                    <p class="font-medium text-blanc-pur">DGCE — Ministère du Commerce</p>
                    <p>
                        <a href="mailto:hub-import-export@commerce.gouv.ci" class="link-underline hover:text-orange-soft transition-colors">
                            hub-import-export@commerce.gouv.ci
                        </a>
                    </p>
                    <p>Plateau, Abidjan<br>République de Côte d'Ivoire</p>
                </address>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="pt-8 border-t border-blanc-pur/8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-blanc-pur/40 text-center sm:text-left">
                © 2026 Hub Import-Export — Ministère du Commerce, de l'Industrie et de l'Artisanat de la République de Côte d'Ivoire. Tous droits réservés.
            </p>
            <p class="text-xs text-orange-soft/60 font-mono flex-shrink-0">
                Édition 2026 · Du 22 au 25 juin · Abidjan
            </p>
        </div>
    </div>
</footer>

</body>
</html>
