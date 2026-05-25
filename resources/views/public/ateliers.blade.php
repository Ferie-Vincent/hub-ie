<x-layouts.public :title="'Ateliers — Hub Import-Export 2026'">

<x-page-hero
    kicker="Ateliers thématiques"
    description="Des sessions pratiques en groupes de 60, animées par des experts du commerce extérieur."
>
    Quatre <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">ateliers</em> pour s'outiller.
</x-page-hero>

@php
$ateliers = [
    [
        'slug'    => 'zlecaf-cedeao',
        'num'     => '01',
        'titre'   => 'ZLECAf & CEDEAO',
        'tagline' => 'Conquérir les marchés régionaux',
        'desc'    => 'Maîtriser les règles d\'origine, les protocoles tarifaires et les opportunités ouvertes par la Zone de Libre-Échange Continentale Africaine.',
        'themes'  => ['Protocoles ZLECAf', 'TEC CEDEAO', 'PAPSS', 'Logistique intra-africaine'],
        'accent'  => '--orange-ivoire',
        'accentBg'=> 'hsl(var(--orange-ivoire) / 0.10)',
        'accentBorder' => 'hsl(var(--orange-ivoire) / 0.35)',
        'numColor'=> 'hsl(var(--orange-ivoire) / 0.08)',
        'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ],
    [
        'slug'    => 'financement-garanties',
        'num'     => '02',
        'titre'   => 'Financement & garanties',
        'tagline' => 'Sécuriser ses opérations',
        'desc'    => 'Découvrir les mécanismes de financement export, les garanties bancaires, l\'assurance-crédit et les dispositifs publics d\'appui à l\'international.',
        'themes'  => ['Crédoc', 'Assurance-crédit', 'Afreximbank', 'Risk management'],
        'accent'  => '--orange-brule',
        'accentBg'=> 'hsl(var(--orange-brule) / 0.10)',
        'accentBorder' => 'hsl(var(--orange-brule) / 0.35)',
        'numColor'=> 'hsl(var(--orange-brule) / 0.08)',
        'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    ],
    [
        'slug'    => 'commerce-electronique',
        'num'     => '03',
        'titre'   => 'Commerce électronique',
        'tagline' => 'Digitaliser ses échanges',
        'desc'    => 'Utiliser les plateformes B2B/B2C internationales, structurer sa présence digitale et exploiter les guichets uniques numériques pour exporter.',
        'themes'  => ['SEO export', 'Marketplaces B2B', 'GUCE-CI', 'E-commerce transfrontalier'],
        'accent'  => '--vert-ivoire',
        'accentBg'=> 'hsl(var(--vert-ivoire) / 0.10)',
        'accentBorder' => 'hsl(var(--vert-ivoire) / 0.35)',
        'numColor'=> 'hsl(var(--vert-ivoire) / 0.08)',
        'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    ],
    [
        'slug'    => 'conformite-qualite',
        'num'     => '04',
        'titre'   => 'Conformité & qualité',
        'tagline' => 'Maîtriser les normes',
        'desc'    => 'Comprendre les exigences normatives (sanitaires, environnementales, techniques) et structurer une démarche de certification pour accéder aux marchés cibles.',
        'themes'  => ['Normes ISO', 'HACCP', 'CODINORM', 'Étiquetage UE'],
        'accent'  => '--orange-brule',
        'accentBg'=> 'hsl(var(--orange-brule) / 0.08)',
        'accentBorder' => 'hsl(var(--orange-brule) / 0.25)',
        'numColor'=> 'hsl(var(--noir-profond) / 0.04)',
        'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
    ],
];
@endphp

{{-- Section principale : fond clair --}}
<section class="relative overflow-hidden py-24 bg-blanc-creme">

    <div class="max-w-hub mx-auto px-6">

        {{-- Chapeau de section --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-14">
            <div>
                <div class="kicker-orange rounded-full mb-4 w-fit">22–25 juin 2026 · Abidjan</div>
                <h2 class="font-serif font-bold text-noir-profond" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); line-height: 1.1;">
                    Choisissez votre<br>domaine d'expertise
                </h2>
            </div>
            <p class="text-noir-profond/50 text-sm max-w-xs leading-relaxed sm:text-right">
                Chaque atelier se déroule en groupe restreint de 60 participants pour maximiser les échanges avec les experts.
            </p>
        </div>

        {{-- Grille des 4 ateliers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($ateliers as $atelier)
            <a href="{{ route('ateliers.show', $atelier['slug']) }}"
               class="group relative overflow-hidden rounded-2xl block transition-all duration-300 hover:-translate-y-1 bg-blanc-pur"
               style="border: 1px solid hsl(var(--noir-profond) / 0.08); box-shadow: 0 2px 12px hsl(var(--noir-profond) / 0.06);">

                {{-- Numéro fantôme --}}
                <div class="absolute -top-4 -right-2 font-serif font-bold leading-none select-none pointer-events-none"
                     style="font-size: 9rem; color: hsl(var(--noir-profond) / 0.04); letter-spacing: -0.04em; line-height: 1;">
                    {{ $atelier['num'] }}
                </div>

                {{-- Filet coloré en haut --}}
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl transition-opacity duration-300"
                     style="background: linear-gradient(to right, hsl(var({{ $atelier['accent'] }})), transparent); opacity: 0.7;"></div>

                {{-- Contenu --}}
                <div class="relative z-10 p-8">

                    {{-- Icône + numéro --}}
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: {{ $atelier['accentBg'] }}; border: 1px solid {{ $atelier['accentBorder'] }};">
                            <svg class="w-5 h-5" fill="none" stroke="hsl(var({{ $atelier['accent'] }}))" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $atelier['icon'] !!}
                            </svg>
                        </div>
                        <span class="text-xs font-mono font-bold tracking-widest" style="color: hsl(var({{ $atelier['accent'] }}) / 0.60);">
                            ATELIER {{ $atelier['num'] }}
                        </span>
                    </div>

                    {{-- Titre + tagline --}}
                    <h2 class="font-serif font-bold text-noir-profond text-xl mb-1 leading-tight">
                        {{ $atelier['titre'] }}
                    </h2>
                    <p class="text-sm font-semibold mb-4" style="color: hsl(var({{ $atelier['accent'] }}));">
                        {{ $atelier['tagline'] }}
                    </p>

                    {{-- Description --}}
                    <p class="text-noir-profond/60 text-sm leading-relaxed mb-6">
                        {{ $atelier['desc'] }}
                    </p>

                    {{-- Thèmes pills --}}
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($atelier['themes'] as $theme)
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                              style="background: {{ $atelier['accentBg'] }}; color: hsl(var({{ $atelier['accent'] }}) / 0.85); border: 1px solid {{ $atelier['accentBorder'] }};">
                            {{ $theme }}
                        </span>
                        @endforeach
                    </div>

                    {{-- CTA --}}
                    <span class="inline-flex items-center gap-2 text-sm font-semibold transition-colors duration-200"
                          style="color: hsl(var({{ $atelier['accent'] }}));">
                        Découvrir l'atelier
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>

                {{-- Hover glow overlay --}}
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at 30% 50%, hsl(var({{ $atelier['accent'] }}) / 0.05) 0%, transparent 65%);"></div>

            </a>
            @endforeach
        </div>

        {{-- CTA bas de section --}}
        <div class="mt-14 text-center">
            <p class="text-noir-profond/40 text-sm mb-4">Accès conditionné à la sélection de votre candidature</p>
            <a href="{{ route('inscription') }}" class="btn-fill px-7 py-3"><span>S'inscrire au Hub 2026</span></a>
        </div>

    </div>
</section>

</x-layouts.public>
