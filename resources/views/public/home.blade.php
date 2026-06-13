<x-layouts.public :darkHero="true">
@php
// ── Résolution des variables depuis l'édition active (avec fallbacks) ───────

$heroPatronText = $edition?->hero_patron_text
    ?? 'Sous le haut patronage de Monsieur le Ministre du Commerce, de l\'Industrie et de l\'Artisanat de la République de Côte d\'Ivoire.';

$heroSubtitle = $edition?->hero_subtitle
    ?? 'Le rendez-vous stratégique des acteurs du commerce extérieur ivoirien : se former, s\'outiller et conquérir les marchés régionaux et internationaux.';

$datesCles = $edition?->dates_cles ?? [
    ['lieu' => 'Abidjan',    'sublabel' => 'Ouverture officielle',            'date' => '22 juin'],
    ['lieu' => 'CGECI',      'sublabel' => 'Ateliers — Jour 1',               'date' => '23 juin'],
    ['lieu' => 'CCI-CI',     'sublabel' => 'Ateliers — Jour 2',               'date' => '24 juin'],
    ['lieu' => 'SEEN Hôtel', 'sublabel' => 'Clôture & remise des certificats','date' => '25 juin'],
];

$ministerName  = $edition?->minister_name  ?? 'Kalil KONATÉ';
$ministerTitle = $edition?->minister_title ?? 'Ministre du Commerce, de l\'Industrie et de l\'Artisanat';

$ministerSpeech = collect($edition?->minister_speech ?? [])->pluck('text')->filter()->values()->all();
if (empty($ministerSpeech)) {
    $ministerSpeech = [
        'L\'économie mondiale traverse une période de mutation structurelle marquée par des reconfigurations géopolitiques et commerciales sans précédent. Dans ce contexte, la résilience du commerce extérieur ivoirien repose plus que jamais sur la capacité de nos acteurs économiques à comprendre, anticiper et capter les opportunités offertes par les marchés stratégiques.',
        'L\'édition 2026 du Hub Import-Export, placée sous le thème « Résilience et compétitivité du commerce extérieur ivoirien : s\'outiller pour conquérir les marchés stratégiques dans un monde en pleine crise », traduit l\'engagement résolu de mon Ministère à outiller concrètement nos commerçants, agro-transformateurs, PME et grandes entreprises pour qu\'ils tirent pleinement parti des accords régionaux (CEDEAO, ZLECAf) et des marchés internationaux (Union européenne, Chine).',
        'Je formule le souhait que cette édition consolide une dynamique pérenne d\'accompagnement et de structuration de notre écosystème exportateur, et qu\'elle contribue à hisser la Côte d\'Ivoire au rang des nations africaines les plus compétitives en matière de commerce international.',
    ];
}

$statsCards = $edition?->stats_cards ?? [
    ['value' => '180',        'label' => 'Auditeurs sélectionnés', 'caption' => 'sur candidature, répartis en 3 groupes',         'color' => 'orange'],
    ['value' => '17 016 Mds', 'label' => 'FCFA d\'exports CI',    'caption' => 'valeur annuelle du commerce extérieur ivoirien',  'color' => 'vert'],
    ['value' => '4',          'label' => 'Ateliers thématiques',  'caption' => 'ZLECAf, Financement, E-commerce, Conformité',     'color' => 'orange'],
    ['value' => '+165 %',     'label' => 'Croissance exports',    'caption' => 'du commerce extérieur ivoirien en 10 ans',         'color' => 'vert'],
];

$capObjectifs = $edition?->cap_objectifs ?? [
    ['num' => '01', 'title' => 'Marchés régionaux',    'body' => 'Sensibiliser sur les opportunités CEDEAO, ZLECAf, UE et Chine.'],
    ['num' => '02', 'title' => 'Conformité export',    'body' => 'Accompagner la mise en conformité (normes qualité, certification, douane).'],
    ['num' => '03', 'title' => 'Outils numériques',    'body' => 'Encourager les outils numériques et la digitalisation des procédures.'],
    ['num' => '04', 'title' => 'Financement',          'body' => 'Promouvoir l\'accès au financement et aux garanties export.'],
    ['num' => '05', 'title' => 'Intelligence marché',  'body' => 'Favoriser l\'accès à l\'information stratégique sur les marchés mondiaux.'],
];

$capResultats = $edition?->cap_resultats ?? [
    ['num' => '01', 'title' => '180 acteurs formés',    'body' => '180 acteurs économiques outillés pour l\'export avec un plan d\'action validé.'],
    ['num' => '02', 'title' => 'Plans d\'action',       'body' => 'Plans individuels validés par les experts et institutions d\'appui.'],
    ['num' => '03', 'title' => 'Réseau structuré',      'body' => 'Réseau d\'entreprises ivoiriennes orientées vers les marchés régionaux.'],
    ['num' => '04', 'title' => 'Certification officielle', 'body' => 'Certificats officiels délivrés par le Ministère du Commerce.'],
    ['num' => '05', 'title' => 'Mise en relation',      'body' => 'Accès aux institutions d\'appui : ACIEx, CNE, GUCE-CI.'],
];

$capPourquoi = $edition?->cap_pourquoi ?? [
    ['num' => '01', 'title' => 'Gratuit & certifiant', 'body' => 'Formation de haut niveau, entièrement gratuite et certifiante.'],
    ['num' => '02', 'title' => 'Experts de terrain',   'body' => 'Rencontrez ACIEx, CNE, CODINORM, CI-PME, GUCE-CI en direct.'],
    ['num' => '03', 'title' => 'Réseau de 180 pairs',  'body' => 'Construisez votre réseau avec 180 pairs du commerce extérieur.'],
    ['num' => '04', 'title' => 'Certificat ministériel','body' => 'Certificat officiel signé par le Ministre du Commerce.'],
    ['num' => '05', 'title' => 'Impact national',      'body' => 'Représentez votre secteur et contribuez aux recommandations nationales.'],
];

$defaultProgramme = [
    1 => [
        ['label' => 'Accueil et installation des participants',                                        'tag' => 'Accueil'],
        ['label' => 'Cérémonie d\'ouverture officielle',                                              'tag' => 'Plénière'],
        ['label' => 'Conférence inaugurale : enjeux du commerce extérieur ivoirien en 2026',          'tag' => 'Conférence'],
        ['label' => 'Pause déjeuner',                                                                  'tag' => 'Pause'],
        ['label' => 'Présentation des institutions d\'appui (ACIEx, CNE, GUCE-CI, CODINORM, CI-PME)','tag' => 'Networking'],
    ],
    2 => [
        ['label' => 'Atelier ZLECAf & CEDEAO — Groupe 1',                                            'tag' => 'Atelier'],
        ['label' => 'Atelier Financement & garanties — Groupe 2',                                     'tag' => 'Atelier'],
        ['label' => 'Atelier Commerce électronique — Groupe 3',                                       'tag' => 'Atelier'],
        ['label' => 'Pause déjeuner',                                                                  'tag' => 'Pause'],
        ['label' => 'Panel thématique : opérationnalisation de la ZLECAf en Côte d\'Ivoire',          'tag' => 'Panel'],
    ],
    3 => [
        ['label' => 'Rotation des groupes (Conformité, ZLECAf, Financement, E-commerce)',             'tag' => 'Atelier'],
        ['label' => 'Forum B2B avec banques & assureurs partenaires',                                 'tag' => 'B2B'],
        ['label' => 'Pause déjeuner',                                                                  'tag' => 'Pause'],
        ['label' => 'Échanges avec les journalistes — espace presse',                                 'tag' => 'Presse'],
    ],
    4 => [
        ['label' => 'Restitution des travaux par les rapporteurs des ateliers',                       'tag' => 'Restitution'],
        ['label' => 'Recommandations consolidées',                                                     'tag' => 'Plénière'],
        ['label' => 'Évaluation des acquis (post-test)',                                              'tag' => 'Évaluation'],
        ['label' => 'Cérémonie de clôture & remise des certificats',                                  'tag' => 'Cérémonie'],
    ],
];

$programme = [
    1 => $edition?->programme_j1 ?? $defaultProgramme[1],
    2 => $edition?->programme_j2 ?? $defaultProgramme[2],
    3 => $edition?->programme_j3 ?? $defaultProgramme[3],
    4 => $edition?->programme_j4 ?? $defaultProgramme[4],
];

$institutions = $edition?->institutions ?? [
    [
        'kicker' => 'Haut Patronage',
        'type'   => 'orange',
        'title'  => 'Ministère du Commerce,<br>de l\'Industrie et de l\'Artisanat',
        'text'   => 'Sous le haut patronage de Monsieur le Ministre du Commerce, de l\'Industrie et de l\'Artisanat de la République de Côte d\'Ivoire.',
        'footer' => 'MCIA — République de Côte d\'Ivoire',
    ],
    [
        'kicker' => 'Parrainage stratégique',
        'type'   => 'vert',
        'title'  => 'TradeMark Africa &amp; GIZ',
        'text'   => 'Avec l\'appui technique et financier de TradeMark Africa et de la Deutsche Gesellschaft für Internationale Zusammenarbeit (GIZ).',
        'footer' => 'Partenaires techniques &amp; financiers',
    ],
];

$formatsEchange = $edition?->formats_echange ?? [
    ['num' => '01', 'kicker' => 'Plénière',  'title' => 'Cérémonie d\'ouverture',  'description' => 'Ouverture officielle sous le haut patronage du Ministre, avec allocutions institutionnelles et cadrage stratégique.'],
    ['num' => '02', 'kicker' => 'Atelier',   'title' => 'Ateliers thématiques',    'description' => 'Sessions pratiques en groupes de 60 animées par des experts. ZLECAf, finance, digital, normes & certification.'],
    ['num' => '03', 'kicker' => 'Panel',     'title' => 'Panels thématiques',      'description' => 'Débats d\'experts sur l\'opérationnalisation des accords commerciaux et les bonnes pratiques sectorielles.'],
    ['num' => '04', 'kicker' => 'B2B',       'title' => 'Forums & partenaires',    'description' => 'Rencontres B2B avec banques, assureurs, agences d\'appui et partenaires techniques pour nouer des contacts opérationnels.'],
];

$newsletterTitle    = $edition?->newsletter_title    ?? 'Rejoignez le Hub.';
$newsletterSubtitle = $edition?->newsletter_subtitle ?? 'Recevez le programme officiel détaillé, les annonces des intervenants et les modalités d\'inscription dans votre boîte mail.';
@endphp

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 2 — HERO (BRIEF §III.1 #2)                                    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section
    class="relative min-h-screen flex items-center overflow-hidden bg-noir-profond"
    aria-label="Présentation du Hub Import-Export 2026"
>
    {{-- Photo de fond — Port Autonome d'Abidjan (vue aérienne) --}}
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <img
            src="{{ asset('images/hero-port.jpg') }}"
            alt=""
            class="w-full h-full object-cover object-center"
            loading="eager"
            fetchpriority="high"
        >
    </div>

    {{-- Overlay sombre multi-couche pour lisibilité du texte --}}
    <div class="absolute inset-0 z-[1]" aria-hidden="true"
         style="background: linear-gradient(135deg,
             hsl(var(--noir-profond) / 0.88) 0%,
             hsl(var(--noir-profond) / 0.75) 50%,
             hsl(var(--noir-profond) / 0.60) 100%);"></div>

    {{-- Vignette bords --}}
    <div class="absolute inset-0 z-[1] pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse 110% 100% at 50% 50%, transparent 40%, hsl(var(--noir-profond) / 0.6) 100%);"></div>

    {{-- Fondu gauche pour ne pas couvrir le texte --}}
    <div class="absolute inset-0 pointer-events-none z-[2]" aria-hidden="true"
         style="background: linear-gradient(to right, hsl(var(--noir-profond)/0.9) 0%, transparent 50%);"></div>

    {{-- Accent radial orange haut-droite --}}
    <div class="absolute inset-0 pointer-events-none z-[3]" aria-hidden="true">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full opacity-20"
             style="background: radial-gradient(circle, hsl(var(--vert-ivoire)), transparent 65%);"></div>
    </div>

    <div class="relative z-10 max-w-hub mx-auto px-6 w-full pt-32 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Colonne gauche --}}
            <div class="space-y-8">

                {{-- Bandeau patronage glassmorphique --}}
                <div class="glass-dark inline-flex items-center gap-3 rounded-full px-5 py-2.5 reveal">
                    <svg class="w-4 h-4 text-orange-soft flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 14.5a6.5 6.5 0 110-13 6.5 6.5 0 010 13z"/>
                    </svg>
                    <p class="text-xs text-blanc-pur/80 leading-snug">{{ $heroPatronText }}</p>
                </div>

                {{-- Logo SVG animé + H1 --}}
                <div class="space-y-4 reveal">
                    {{-- Logo --}}
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo Hub Import-Export 2026" style="height:64px; width:auto; object-fit:contain;">
                        <div>
                            <p class="text-xs font-mono text-orange-soft uppercase tracking-widest">Ministère du Commerce · CI</p>
                            <p class="text-sm text-blanc-pur/60">Édition 2026</p>
                        </div>
                    </div>

                    {{-- H1 --}}
                    <h1
                        class="font-serif font-bold text-blanc-pur leading-tight"
                        style="font-size: clamp(2.5rem, 6vw, 5rem); letter-spacing: -0.02em; line-height: 1.05;"
                    >
                        Hub <em class="font-fraunces not-italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 50;">Import-Export</em> 2026
                    </h1>
                </div>

                {{-- Sous-titre --}}
                <p class="text-lg text-blanc-pur/70 leading-relaxed max-w-xl reveal">{{ $heroSubtitle }}</p>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 reveal">
                    <a href="{{ route('inscription') }}" class="btn-fill px-7 py-3.5 text-base">
                        <span>S'inscrire au Hub</span>
                    </a>
                    <a href="{{ route('programme') }}"
                       class="btn-secondary-hub inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-semibold text-base text-blanc-pur border border-blanc-pur/25 hover:border-blanc-pur/50 hover:bg-blanc-pur/8">
                        Voir le programme
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Colonne droite — carte dates clés glassmorphique + countdown --}}
            <div class="flex flex-col gap-6 reveal">

                {{-- Carte DATES CLÉS — flottement 3D ambiant --}}
                <div class="glass rounded-3xl p-7 hub-float hub-float-d0">
                    <div class="kicker-orange rounded-full mb-6">DATES CLÉS</div>

                    <ul class="space-y-0 divide-y divide-blanc-pur/10">
                        @foreach($datesCles as $dc)
                        <li class="flex items-center gap-4 py-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background: hsl(var(--vert-ivoire)/0.2);">
                                <span class="w-2 h-2 rounded-full animate-v10-green-pulse"
                                      style="background: hsl(var(--vert-soft));"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-blanc-pur/50 uppercase tracking-wider font-medium">{{ $dc['lieu'] }}</p>
                                <p class="text-sm text-blanc-pur/85 font-medium truncate">{{ $dc['sublabel'] }}</p>
                            </div>
                            <span class="font-mono font-bold text-orange-soft text-sm flex-shrink-0">{{ $dc['date'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Countdown — flottement 3D ambiant décalé --}}
                <div class="glass-dark rounded-2xl p-6 hub-float hub-float-d2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 rounded-full animate-pulse-dot" style="background: hsl(var(--vert-ivoire));"></span>
                        <p class="text-xs text-blanc-pur/60 uppercase tracking-widest font-medium">Rendez-vous à Abidjan dans</p>
                    </div>
                    <div class="grid grid-cols-4 gap-3 text-center">
                        @foreach([
                            ['cd-days',    'Jours'],
                            ['cd-hours',   'Heures'],
                            ['cd-minutes', 'Min'],
                            ['cd-seconds', 'Sec'],
                        ] as [$id, $label])
                        <div>
                            <div class="font-mono font-bold text-3xl text-blanc-pur" id="{{ $id }}">--</div>
                            <div class="text-xs text-blanc-pur/40 uppercase tracking-wider mt-1">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Indicateur scroll --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 opacity-50" aria-hidden="true">
        <span class="text-xs text-blanc-pur uppercase tracking-widest">Défiler</span>
        <div class="w-px h-10 bg-gradient-to-b from-blanc-pur to-transparent"></div>
    </div>
</section>




{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 3 — SOUS L'AUTORITÉ DES PLUS HAUTES INSTITUTIONS (BRIEF §III.1 #3) --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-noir-doux" aria-labelledby="patronage-title">
    <div class="max-w-hub mx-auto px-6">
        <p id="patronage-title"
           class="text-center text-xs font-bold uppercase tracking-widest text-blanc-pur/30 mb-10 reveal">
            Sous l'autorité des plus hautes institutions
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl mx-auto">
            @foreach($institutions as $inst)
            @php $isOrange = ($inst['type'] ?? 'orange') === 'orange'; @endphp
            <div class="glass rounded-3xl p-7 reveal hub-card-lift">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                         style="background: hsl(var(--vert-ivoire) / 0.15); border: 1px solid hsl(var(--vert-ivoire) / 0.25);">
                        <svg class="w-6 h-6" fill="none"
                             stroke="{{ $isOrange ? 'hsl(var(--orange-soft))' : 'hsl(var(--vert-soft))' }}"
                             viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                            @if($isOrange)
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                            @endif
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="{{ $isOrange ? 'kicker-orange' : 'kicker-vert' }} rounded-full mb-3 text-[0.65rem]">{{ $inst['kicker'] }}</p>
                        <h3 class="font-serif font-bold text-blanc-pur text-base leading-tight mb-2">{!! $inst['title'] !!}</h3>
                        <p class="text-xs text-blanc-pur/50 leading-relaxed">{{ $inst['text'] }}</p>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-blanc-pur/10 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isOrange ? 'animate-v10-pulse' : 'animate-v10-green-pulse' }}"
                          style="background: hsl(var(--vert-ivoire));"></span>
                    <span class="text-[0.6rem] font-mono {{ $isOrange ? 'text-orange-soft/70' : 'text-vert-soft/70' }} uppercase tracking-widest">{!! $inst['footer'] !!}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 4 — MOT DU MINISTRE (BRIEF §III.1 #4, CONTENT §A.2)           --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="py-24 bg-blanc-creme" aria-labelledby="ministre-title">
    <div class="max-w-hub mx-auto px-6 space-y-12">

        {{-- Ligne 1 : Portrait + Discours --}}
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,_1fr)_minmax(0,_2.5fr)] gap-10 items-stretch">

            {{-- Portrait Ministre --}}
            <div class="reveal" style="perspective: 1200px;">
                <div class="hub-float hub-float-d1 rounded-3xl shadow-2xl h-full">
                    <div class="relative w-full rounded-3xl overflow-hidden h-full" style="min-height: 400px;">
                        <img src="{{ asset('images/ministre.png') }}"
                             alt="Monsieur le Ministre du Commerce, de l'Industrie et de l'Artisanat"
                             class="w-full h-full object-cover object-top">
                        <div class="absolute bottom-0 inset-x-0 h-1.5 bg-vert-ivoire z-10"></div>
                        <div class="absolute inset-0"
                             style="background: linear-gradient(to top, hsl(var(--noir-profond)/0.80) 0%, transparent 45%);"></div>
                        <div class="absolute bottom-6 inset-x-0 text-center z-10">
                            <p class="font-serif font-bold text-blanc-pur text-base tracking-wide">{{ $ministerName }}</p>
                            <p class="font-serif italic text-blanc-pur/70 text-xs mt-0.5">{{ $ministerTitle }}</p>
                            <p class="text-xs text-orange-soft font-medium mt-1">République de Côte d'Ivoire</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Texte Ministre --}}
            <div class="flex flex-col gap-6">
                <div class="reveal">
                    <div class="kicker-orange rounded-full mb-4">Mot du Ministre</div>
                    <h2 id="ministre-title"
                        class="font-serif font-bold text-noir-profond leading-tight"
                        style="font-size: clamp(1.75rem, 3.5vw, 2.8rem); letter-spacing: -0.02em; line-height: 1.1;">
                        D'une <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">ambition</em> partagée<br>
                        à des <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">résultats concrets.</em>
                    </h2>
                </div>

                <blockquote class="border-l-2 border-vert-ivoire pl-5 space-y-4 text-noir-profond/80 leading-relaxed reveal">
                    @foreach($ministerSpeech as $para)
                    <p>{{ $para }}</p>
                    @endforeach
                </blockquote>

                <div class="flex items-center gap-3 reveal">
                    <div class="h-px flex-1 bg-sable"></div>
                    <p class="font-serif italic text-gris-500 text-sm">
                        — {{ $ministerName }}, {{ $ministerTitle }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Ligne 2 : 4 Stat cards full-width (BRIEF §III.1 #4) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 reveal">
            @foreach($statsCards as $stat)
            <x-stat-card :value="$stat['value']" :label="$stat['label']" :caption="$stat['caption'] ?? ''" :color="$stat['color'] ?? 'orange'"/>
            @endforeach
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 5 — CAP STRATÉGIQUE (BRIEF §III.1 #5)                         --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="relative py-24 overflow-hidden bg-noir-profond" aria-labelledby="cap-title"
    x-data="{ tab: 'objectifs' }">

    {{-- Couche 1 : photo marché Abidjan (blur léger) --}}
    <div class="absolute pointer-events-none" aria-hidden="true"
         style="inset: -8px;">
        <img src="{{ asset('images/cap-marche.jpg') }}" alt=""
             class="w-full h-full object-cover object-top"
             style="filter: blur(2px) brightness(0.6);">
    </div>

    {{-- Couche 2 : overlay sombre pour lisibilité --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background: linear-gradient(135deg,
             hsl(var(--noir-profond) / 0.88) 0%,
             hsl(var(--noir-profond) / 0.78) 60%,
             hsl(var(--noir-profond) / 0.85) 100%);"></div>

    {{-- Couche 3 : accent vert subtil bas-gauche --}}
    <div class="absolute left-0 bottom-0 w-[500px] h-[500px] pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(circle at 0% 100%, hsl(var(--vert-ivoire)/0.15), transparent 65%);"></div>

    <div class="relative z-10 max-w-hub mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

            {{-- Colonne gauche — éditoriale, sticky --}}
            <div class="lg:sticky lg:top-28 space-y-10 reveal">
                <div>
                    <div class="kicker-vert rounded-full mb-5">Cap stratégique</div>
                    <h2 id="cap-title"
                        class="font-serif font-bold text-blanc-pur leading-tight mb-5"
                        style="font-size: clamp(2.2rem, 4vw, 3.5rem); letter-spacing: -0.025em; line-height: 1.05;">
                        Résilience et<br>
                        <em class="font-fraunces italic text-vert-soft v10-accent-word" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">compétitivité</em><br>
                        pour <span class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 50;">conquérir.</span>
                    </h2>
                    <p class="text-blanc-pur/50 text-base leading-relaxed">
                        Un programme structuré autour des grands enjeux du commerce extérieur ivoirien — formation, financement, digital, certification.
                    </p>
                </div>

                {{-- Tabs verticaux --}}
                <div class="flex flex-col gap-1.5" role="tablist" aria-label="Onglets du cap stratégique">
                    @foreach([
                        ['objectifs', 'Objectifs',          'Ce que le Hub veut accomplir'],
                        ['resultats', 'Résultats attendus', 'Ce que vous en repartez avec'],
                        ['pourquoi',  'Pourquoi participer','Les raisons de candidater'],
                    ] as [$key, $label, $desc])
                    <button
                        @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'bg-noir-doux border-vert-ivoire text-blanc-pur'
                            : 'border-blanc-pur/8 text-blanc-pur/40 hover:border-blanc-pur/20 hover:text-blanc-pur/70'"
                        class="group w-full text-left flex items-center gap-4 px-5 py-4 rounded-2xl border transition-all duration-200"
                        role="tab"
                        :aria-selected="tab === '{{ $key }}'"
                    >
                        <span
                            :class="tab === '{{ $key }}' ? 'bg-vert-ivoire' : 'bg-blanc-pur/10 group-hover:bg-blanc-pur/20'"
                            class="w-1.5 h-8 rounded-full flex-shrink-0 transition-all duration-200"
                        ></span>
                        <span class="flex-1 min-w-0">
                            <span class="block font-semibold text-sm">{{ $label }}</span>
                            <span class="block text-xs text-blanc-pur/40 mt-0.5">{{ $desc }}</span>
                        </span>
                        <svg
                            :class="tab === '{{ $key }}' ? 'text-vert-soft opacity-100' : 'opacity-0'"
                            class="w-4 h-4 flex-shrink-0 transition-opacity"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Colonne droite — contenu dynamique --}}
            <div class="reveal">

                @php
                $capTabs = [
                    'objectifs' => ['color' => 'vert',   'items' => $capObjectifs],
                    'resultats' => ['color' => 'orange',  'items' => $capResultats],
                    'pourquoi'  => ['color' => 'vert',   'items' => $capPourquoi],
                ];
                @endphp

                @foreach($capTabs as $key => $tab)
                <div x-show="tab === '{{ $key }}'" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" {{ $key !== 'objectifs' ? "style=display:none" : '' }}>
                    <ul class="divide-y divide-blanc-pur/8">
                        @foreach($tab['items'] as $item)
                        <li class="group flex gap-6 py-7 hover:bg-blanc-pur/3 -mx-4 px-4 rounded-xl transition-colors duration-200">
                            <span class="font-mono text-sm font-bold flex-shrink-0 mt-0.5 w-7 text-right text-vert-ivoire/70">
                                {{ $item['num'] }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-blanc-pur text-base mb-1.5">{{ $item['title'] }}</p>
                                <p class="text-blanc-pur/60 text-sm leading-loose">{{ $item['body'] }}</p>
                            </div>
                            <svg class="w-4 h-4 flex-shrink-0 mt-1 opacity-0 group-hover:opacity-100 transition-opacity
                                {{ $tab['color'] === 'orange' ? 'text-orange-soft' : 'text-vert-soft' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 6 — QUATRE ATELIERS (BRIEF §III.1 #6, CONTENT §A.3)           --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="py-24 bg-blanc-creme" aria-labelledby="ateliers-title"
    x-data="{ active: 0 }">
    <div class="max-w-hub mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

            {{-- Colonne gauche — éditoriale --}}
            <div class="lg:sticky lg:top-28 space-y-8 reveal">
                <div>
                    <div class="kicker-orange rounded-full mb-5">Programme</div>
                    <h2 id="ateliers-title"
                        class="font-serif font-bold text-noir-profond leading-tight mb-5"
                        style="font-size: clamp(2.2rem, 4vw, 3.5rem); letter-spacing: -0.025em; line-height: 1.05;">
                        Quatre <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">ateliers,</em><br>
                        des panels et des forums.
                    </h2>
                    <p class="text-gris-500 text-base leading-relaxed max-w-sm">
                        Une approche pédagogique innovante axée sur l'andragogie&nbsp;: apports théoriques et pratiques, réflexions de groupe, mises en situation, et évaluation des acquis.
                    </p>
                </div>

                {{-- Carte logistique --}}
                <div class="bg-blanc-pur rounded-2xl p-6 shadow-sm border border-sable/60">
                    <ul class="space-y-4">
                        <li class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: hsl(var(--orange-soft-bg));">
                                <svg class="w-5 h-5" fill="none" stroke="hsl(var(--vert-fonce))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                            </div>
                            <span class="text-sm text-noir-profond font-medium">Lundi 22 au Jeudi 25 Juin 2026</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: hsl(var(--orange-soft-bg));">
                                <svg class="w-5 h-5" fill="none" stroke="hsl(var(--vert-fonce))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-noir-profond font-medium">CGECI · CCI CI · SEEN Hôtel — Abidjan-Plateau</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: hsl(var(--orange-soft-bg));">
                                <svg class="w-5 h-5" fill="none" stroke="hsl(var(--vert-fonce))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-noir-profond font-medium">180 auditeurs · 3 groupes</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('ateliers.index') }}" class="btn-fill inline-flex px-6 py-3 text-sm">
                    <span>Voir tous les ateliers</span>
                </a>
            </div>

            {{-- Colonne droite — accordéon des 4 ateliers --}}
            <div class="space-y-3 reveal">
                @foreach($workshops as $i => $atelier)
                <div
                    @click="active = active === {{ $i }} ? -1 : {{ $i }}"
                    :class="active === {{ $i }}
                        ? 'border-vert-ivoire shadow-md bg-blanc-pur'
                        : 'border-sable/80 bg-blanc-pur hover:border-vert-ivoire/40 hover:shadow-sm'"
                    class="rounded-2xl border-2 cursor-pointer transition-all duration-300 overflow-hidden"
                    role="button"
                    :aria-expanded="active === {{ $i }}"
                >
                    {{-- En-tête de la ligne --}}
                    <div class="flex items-center gap-5 px-6 py-5">
                        {{-- Numéro --}}
                        <span
                            :class="active === {{ $i }} ? 'text-vert-ivoire' : 'text-gris-500/50'"
                            class="font-mono font-bold text-3xl w-10 flex-shrink-0 transition-colors duration-300"
                            style="font-size: 2rem; line-height: 1; letter-spacing: -0.04em;"
                        >{{ $atelier['num'] }}</span>

                        {{-- Titre + sous-titre --}}
                        <div class="flex-1 min-w-0">
                            <p
                                :class="active === {{ $i }} ? 'text-noir-profond' : 'text-noir-profond/80'"
                                class="font-serif font-bold text-lg leading-tight transition-colors duration-300"
                            >{{ $atelier['titre'] }}</p>
                            <p
                                x-show="active !== {{ $i }}"
                                class="text-sm text-gris-500 mt-0.5 truncate"
                            >{{ $atelier['tagline'] }}</p>
                        </div>

                        {{-- Icône thématique --}}
                        <div
                            :class="active === {{ $i }}
                                ? 'bg-vert-ivoire'
                                : 'bg-blanc-creme group-hover:bg-vert-soft-bg'"
                            class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300"
                        >
                            <svg
                                :class="active === {{ $i }} ? 'text-white' : 'text-gris-500'"
                                class="w-5 h-5 transition-colors duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                                {!! $atelier['icon'] !!}
                            </svg>
                        </div>

                        {{-- Chevron --}}
                        <svg
                            :class="active === {{ $i }} ? 'rotate-180 text-vert-ivoire' : 'text-gris-500/40'"
                            class="w-5 h-5 flex-shrink-0 transition-all duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    {{-- Corps accordéon --}}
                    <div x-show="active === {{ $i }}" x-collapse class="px-6 pb-6">
                        <div class="pt-1 border-t border-sable/50">
                            <p class="text-sm text-gris-500 leading-relaxed mt-4 mb-5">{{ $atelier['desc'] }}</p>
                            <a
                                href="{{ route('ateliers.show', $atelier['slug']) }}"
                                class="inline-flex items-center gap-2 text-vert-ivoire text-sm font-semibold hover:gap-3 transition-all"
                                @click.stop
                            >
                                Découvrir cet atelier
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 7 — QUATRE FORMATS D'ÉCHANGE (BRIEF §III.1 #7)                --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="relative py-24 bg-noir-profond overflow-hidden" aria-labelledby="formats-title">

    {{-- Décor fond --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        {{-- Motif bogolan pleine section --}}
        <svg class="absolute inset-0 w-full h-full" style="opacity: 0.06;">
            <rect width="100%" height="100%" fill="url(#bogolan-pattern)"/>
        </svg>
        {{-- Blob orange haut-droit --}}
        <div class="absolute -top-40 -right-40 w-[700px] h-[700px] rounded-full opacity-[0.10]"
             style="background: radial-gradient(circle, hsl(var(--vert-ivoire)), transparent 60%);"></div>
        {{-- Blob vert bas-gauche --}}
        <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] rounded-full opacity-[0.08]"
             style="background: radial-gradient(circle, hsl(var(--vert-ivoire)), transparent 60%);"></div>
    </div>

    <div class="relative z-10 max-w-hub mx-auto px-6">

        {{-- En-tête aligné à gauche pour varier des sections centrées --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end mb-16 reveal">
            <div>
                <div class="kicker-orange rounded-full mb-4">Formats d'échange</div>
                <h2 id="formats-title"
                    class="font-serif font-bold text-blanc-pur"
                    style="font-size: clamp(2rem, 4vw, 3.2rem); letter-spacing: -0.02em; line-height: 1.1;">
                    Quatre formats,<br>
                    <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">une trajectoire d'outillage.</em>
                </h2>
            </div>
            <p class="text-blanc-pur/50 text-base leading-relaxed lg:pb-1">
                Du cadrage stratégique en plénière aux rencontres B2B opérationnelles, chaque format est conçu pour maximiser l'impact de votre participation sur quatre jours.
            </p>
        </div>

        {{-- Grille de cartes glass --}}
        @php
        $formatVisual = [
            0 => ['accent' => '--vert-ivoire', 'icon_path' => 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6'],
            1 => ['accent' => '--vert-fonce',  'icon_path' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5'],
            2 => ['accent' => '--vert-ivoire', 'icon_path' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z'],
            3 => ['accent' => '--orange-soft', 'icon_path' => 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z'],
        ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($formatsEchange as $i => $format)
            @php $fv = $formatVisual[$i] ?? $formatVisual[0]; @endphp
            <div class="relative rounded-3xl p-6 overflow-hidden reveal hub-card-lift"
                 style="background: hsla(0,0%,100%,0.10); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid hsla(0,0%,100%,0.14);">

                {{-- Filet accent coloré en haut --}}
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-3xl"
                     style="background: linear-gradient(to right, hsl(var({{ $fv['accent'] }})), transparent 80%);"></div>

                {{-- Numéro fantôme en fond haut-droit --}}
                <div class="absolute top-3 right-4 font-mono font-bold leading-none pointer-events-none select-none"
                     style="font-size: 6rem; color: hsla(0,0%,100%,0.06); line-height: 1;">{{ $format['num'] }}</div>

                {{-- Numéro visible petit --}}
                <div class="font-mono text-xs font-bold mb-5 relative z-10"
                     style="color: hsl(var({{ $fv['accent'] }}));">{{ $format['num'] }}</div>

                {{-- Icône --}}
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5"
                     style="background: hsl(var({{ $fv['accent'] }}) / 0.15); border: 1px solid hsl(var({{ $fv['accent'] }}) / 0.25);">
                    <svg class="w-6 h-6" fill="none" stroke="hsl(var({{ $fv['accent'] }}))"
                         viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $fv['icon_path'] }}"/>
                    </svg>
                </div>

                {{-- Kicker + titre + desc --}}
                <p class="text-xs font-bold uppercase tracking-widest mb-2"
                   style="color: hsl(var({{ $fv['accent'] }}) / 0.8);">{{ $format['kicker'] }}</p>
                <h3 class="font-serif font-bold text-blanc-pur text-xl leading-tight mb-3">{{ $format['title'] }}</h3>
                <p class="text-sm leading-relaxed" style="color: hsla(0,0%,100%,0.55);">{{ $format['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION INTERVENANTS                                                    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@if($speakers->isNotEmpty())
<section class="py-24 bg-blanc-creme" aria-labelledby="speakers-title">
    <div class="max-w-hub mx-auto px-6">

        {{-- En-tête --}}
        <div class="text-center mb-14 reveal">
            <div class="kicker-orange rounded-full mb-4 inline-block">Intervenants</div>
            <h2 id="speakers-title"
                class="font-serif font-bold text-noir-profond"
                style="font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -0.02em; line-height: 1.1;">
                Ils animent<br>
                <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">le Hub 2026.</em>
            </h2>
        </div>

        {{-- Grille speakers — v10-format-card (5 effets hover BRIEF §II.5.11) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
            @foreach($speakers as $speaker)
            <div class="v10-format-card flex flex-col">

                {{-- Coin décoratif --}}
                <div class="v10-corner" aria-hidden="true"></div>

                {{-- Photo full-width — portrait crop, top of card --}}
                <div class="-mx-6 -mt-6 mb-5 overflow-hidden flex-shrink-0" style="height: 220px;">
                    @if($speaker->photo_path)
                    <img
                        src="{{ asset('storage/'.$speaker->photo_path) }}"
                        alt="{{ $speaker->full_name }}"
                        class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                    >
                    @else
                    <div class="w-full h-full flex items-center justify-center text-4xl font-serif font-bold text-blanc-pur select-none"
                         style="background: linear-gradient(135deg, hsl(var(--vert-fonce)) 0%, hsl(var(--vert-ivoire)/0.85) 100%);">
                        {{ mb_strtoupper(mb_substr($speaker->first_name, 0, 1)).mb_strtoupper(mb_substr($speaker->last_name, 0, 1)) }}
                    </div>
                    @endif
                </div>

                {{-- Badge intervenant clé --}}
                @if($speaker->is_featured)
                <span class="inline-block text-[0.6rem] font-bold uppercase tracking-widest text-orange-soft mb-2">── Intervenant clé</span>
                @endif

                {{-- Nom --}}
                <h3 class="font-serif font-bold text-noir-profond text-base leading-tight mb-1">{{ $speaker->full_name }}</h3>
                <p class="text-xs text-gris-500 leading-snug mb-3">{{ $speaker->title }}</p>

                {{-- Spacer --}}
                <div class="flex-1"></div>

                {{-- Ligne basse : icône (v10-float au hover) + orga + LinkedIn --}}
                <div class="flex items-center gap-3 pt-3 border-t border-sable/60">
                    <div class="v10-format-icon w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: hsl(var(--vert-soft-bg)); border: 1px solid hsl(var(--vert-ivoire)/0.2);">
                        <svg class="w-4 h-4" fill="none" stroke="hsl(var(--vert-ivoire))" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <p class="text-[0.6rem] font-mono text-gris-500 uppercase tracking-wide flex-1 min-w-0 leading-tight">{{ $speaker->organization }}</p>
                    @if($speaker->linkedin)
                    <a href="{{ $speaker->linkedin }}"
                       target="_blank" rel="noopener noreferrer"
                       class="text-gris-500/40 hover:text-vert-ivoire transition-colors flex-shrink-0"
                       aria-label="LinkedIn de {{ $speaker->full_name }}"
                       @click.stop>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 8 — PROGRAMME (BRIEF §III.1 #8, CONTENT §A.4)                 --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@php
$tagColors = [
    'Accueil'     => 'bg-vert-soft-bg text-vert-fonce',
    'Plénière'    => 'bg-vert-soft-bg text-vert-ivoire',
    'Conférence'  => 'bg-vert-soft-bg text-vert-ivoire',
    'Atelier'     => 'bg-vert-soft-bg text-vert-ivoire',
    'Networking'  => 'bg-vert-soft-bg text-vert-fonce',
    'Pause'       => 'bg-sable/50 text-gris-500',
    'Panel'       => 'bg-vert-soft-bg text-vert-ivoire',
    'B2B'         => 'bg-purple-100 text-purple-700',
    'Presse'      => 'bg-vert-soft-bg text-vert-fonce',
    'Restitution' => 'bg-vert-soft-bg text-vert-ivoire',
    'Évaluation'  => 'bg-vert-soft-bg text-vert-ivoire',
    'Cérémonie'   => 'bg-vert-soft-bg text-vert-fonce',
];
@endphp

<section class="py-24 bg-blanc-creme" aria-labelledby="programme-title"
    x-data="{ jour: 1 }">
    <div class="max-w-hub mx-auto px-6">

        {{-- En-tête pleine largeur --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-end mb-12 reveal">
            <div>
                <div class="kicker-orange rounded-full mb-4">Programme provisoire</div>
                <h2 id="programme-title"
                    class="font-serif font-bold text-noir-profond"
                    style="font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -0.02em; line-height: 1.1;">
                    Quatre jours,<br>
                    <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">une trajectoire d'outillage.</em>
                </h2>
            </div>
            <p class="text-gris-500 text-base leading-relaxed lg:pb-1">
                Du 22 au 25 juin 2026 à Abidjan, quatre journées denses articulant plénières, ateliers thématiques, panels d'experts et forums B2B.
            </p>
        </div>

        {{-- Sélecteur de jours horizontal --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8 reveal">
            @foreach([
                [1, '22', 'Juin', 'Ouverture'],
                [2, '23', 'Juin', 'Ateliers J1'],
                [3, '24', 'Juin', 'Ateliers J2'],
                [4, '25', 'Juin', 'Clôture'],
            ] as [$num, $day, $month, $label])
            <button
                @click="jour = {{ $num }}"
                :class="jour === {{ $num }}
                    ? 'bg-noir-profond text-blanc-pur shadow-lg border-transparent'
                    : 'bg-blanc-pur text-noir-profond border-sable hover:border-gris-500/30 hover:shadow-sm'"
                class="text-left px-5 py-4 rounded-2xl border transition-all duration-300 flex items-center gap-4"
            >
                {{-- Date --}}
                <div class="flex-shrink-0">
                    <div class="font-mono font-bold leading-none"
                         :class="jour === {{ $num }} ? 'text-orange-soft' : 'text-vert-ivoire'"
                         style="font-size: 1.75rem; line-height: 1;">{{ $day }}</div>
                    <div class="text-[0.58rem] font-bold uppercase tracking-widest mt-0.5"
                         :class="jour === {{ $num }} ? 'text-blanc-pur/40' : 'text-gris-500/50'">{{ $month }} 2026</div>
                </div>
                {{-- Séparateur --}}
                <div class="w-px h-7 flex-shrink-0"
                     :class="jour === {{ $num }} ? 'bg-blanc-pur/15' : 'bg-sable'"></div>
                {{-- Label --}}
                <div class="flex-1 min-w-0">
                    <div class="text-[0.6rem] font-bold uppercase tracking-widest mb-0.5"
                         :class="jour === {{ $num }} ? 'text-blanc-pur/40' : 'text-gris-500/50'">Jour {{ $num }}</div>
                    <div class="font-semibold text-xs leading-tight truncate">{{ $label }}</div>
                </div>
            </button>
            @endforeach
        </div>

        {{-- Panneau programme --}}
        @foreach($programme as $num => $items)
        <div
            x-show="jour === {{ $num }}"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="{{ $num > 1 ? 'display:none;' : '' }}"
            class="reveal"
        >
            <div class="bg-blanc-pur rounded-3xl overflow-hidden border border-sable/60 shadow-sm">
                @foreach($items as $i => $item)
                @php $label = $item['label'] ?? ''; $tag = $item['tag'] ?? ''; @endphp
                <div class="relative flex items-center gap-4 px-6 py-5 border-b border-sable/40 last:border-b-0 group
                            hover:bg-vert-soft-bg/20 transition-colors duration-200">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-vert-ivoire
                                transition-all duration-300 opacity-0 group-hover:opacity-100"
                         style="height: 55%;"></div>
                    <span class="font-mono font-bold text-base w-8 flex-shrink-0 text-gris-500/25
                                 group-hover:text-vert-ivoire transition-colors duration-200 select-none">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="flex-1 text-noir-profond font-medium text-sm leading-snug">{{ $label }}</span>
                    <span class="flex-shrink-0 text-[0.6rem] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full {{ $tagColors[$tag] ?? 'bg-sable/50 text-gris-500' }}">
                        {{ $tag }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex items-center gap-5">
                <a href="{{ route('programme') }}" class="btn-fill px-6 py-3">
                    <span>Voir le programme complet</span>
                </a>
                <span class="text-sm text-gris-500 font-mono">4 jours · 22–25 juin · Abidjan</span>
            </div>
        </div>
        @endforeach

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION FAQ — QUESTIONS FRÉQUENTES                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@if($faqItems->isNotEmpty())
<section class="py-24 bg-blanc-creme" aria-labelledby="faq-home-title">
    <div class="max-w-hub mx-auto px-6">

        <div class="max-w-3xl mx-auto">
            {{-- En-tête --}}
            <div class="text-center mb-12 reveal">
                <div class="kicker-vert rounded-full mb-4 inline-block">Questions fréquentes</div>
                <h2 id="faq-home-title"
                    class="font-serif font-bold text-noir-profond"
                    style="font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -0.02em; line-height: 1.1;">
                    Tout ce que vous voulez<br>
                    <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">savoir sur le Hub.</em>
                </h2>
            </div>

            {{-- Accordéon FAQ --}}
            <div class="space-y-3" x-data="{ open: null }">
                @foreach($faqItems as $idx => $faq)
                <div
                    class="rounded-2xl border-2 overflow-hidden transition-all duration-300 reveal"
                    :class="open === {{ $idx }} ? 'border-vert-ivoire bg-blanc-pur shadow-md' : 'border-sable/80 bg-blanc-pur hover:border-vert-ivoire/40'"
                >
                    <button
                        type="button"
                        class="w-full text-left flex items-center gap-4 px-6 py-5"
                        @click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        :aria-expanded="open === {{ $idx }}"
                    >
                        <span class="flex-1 font-serif font-bold text-base text-noir-profond leading-snug">{{ $faq->question }}</span>
                        <svg
                            :class="open === {{ $idx }} ? 'rotate-180 text-vert-ivoire' : 'text-gris-500/40'"
                            class="w-5 h-5 flex-shrink-0 transition-all duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-6">
                        <p class="text-sm text-gris-500 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Lien vers page FAQ complète --}}
            <div class="text-center mt-10 reveal">
                <a href="{{ route('faq') }}" class="inline-flex items-center gap-2 text-vert-ivoire font-semibold hover:gap-3 transition-all">
                    Voir toutes les questions
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 9 — REJOIGNEZ LE HUB / NEWSLETTER (BRIEF §III.1 #9, A.7)     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="relative py-24 bg-noir-profond overflow-hidden" aria-labelledby="newsletter-title">
    {{-- Motif bogolan centré --}}
    <svg class="absolute inset-0 w-full h-full pointer-events-none" style="opacity: 0.055;" aria-hidden="true">
        <rect width="100%" height="100%" fill="url(#bogolan-pattern)"/>
    </svg>
    <div class="relative z-10 max-w-hub mx-auto px-6">
        <div class="max-w-2xl mx-auto text-center">
            <h2 id="newsletter-title"
                class="font-serif font-bold text-blanc-pur mb-4 reveal"
                style="font-size: clamp(2.5rem, 5vw, 4rem); letter-spacing: -0.02em; line-height: 1.05;">
                {{ $newsletterTitle }}
            </h2>
            <p class="text-blanc-pur/60 text-lg mb-10 reveal">
                {{ $newsletterSubtitle }}
            </p>

            <div x-data="{ sent: false, email: '', loading: false }" class="reveal">
                <form
                    x-show="!sent"
                    @submit.prevent="loading = true; fetch('/newsletter', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: JSON.stringify({ email }) }).then(r => r.json()).then(() => { sent = true; loading = false; }).catch(() => { loading = false; })"
                    class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto"
                    aria-label="Formulaire d'inscription à la newsletter"
                >
                    <label for="newsletter-email" class="sr-only">Votre adresse e-mail</label>
                    <input
                        id="newsletter-email"
                        x-model="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="votre@email.fr"
                        class="flex-1 px-5 py-3.5 rounded-xl bg-blanc-pur/10 border border-blanc-pur/20 text-blanc-pur placeholder-blanc-pur/30 focus:outline-none focus:border-vert-ivoire focus:ring-1 focus:ring-vert-ivoire transition"
                    >
                    <button
                        type="submit"
                        :disabled="loading"
                        class="btn-fill px-6 py-3.5 flex-shrink-0"
                    >
                        <span x-text="loading ? '...' : 'Recevoir'"></span>
                    </button>
                </form>

                <div x-show="sent" x-transition class="glass rounded-2xl p-6 text-blanc-pur/90 text-sm" style="display:none;">
                    <svg class="w-6 h-6 text-vert-soft mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Un email de confirmation vient de vous être envoyé. Cliquez sur le lien qu'il contient pour finaliser votre inscription.
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 10 — MARQUEE PARTENAIRES (BRIEF §III.1 #10)                   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-noir-doux overflow-hidden" aria-label="Nos partenaires">
    <div class="max-w-hub mx-auto px-6 mb-8 reveal">
        <p class="text-center text-xs font-bold uppercase tracking-widest text-blanc-pur/30">Ils nous accompagnent</p>
    </div>

    <div class="relative overflow-hidden">
        <div class="marquee-track">
            @foreach(array_fill(0, 4, ['TradeMark Africa', 'GIZ', 'ACIEx', 'CNE', 'GUCE-CI', 'CODINORM', 'CI-PME', 'Afreximbank', 'BAD']) as $group)
            @foreach($group as $partner)
            <div class="flex-shrink-0 mx-10 flex items-center hub-partner-item">
                <span class="font-manrope font-bold text-blanc-pur text-xl tracking-wide">
                    {{ $partner }}
                </span>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>
</section>

</x-layouts.public>
