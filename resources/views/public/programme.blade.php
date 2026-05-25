<x-layouts.public :title="'Programme — Hub Import-Export 2026'" :darkHero="true">

{{-- ── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative pt-32 pb-20 bg-noir-profond overflow-hidden">

    {{-- Drapeau CI --}}
    <div class="absolute inset-0" aria-hidden="true">
        <img src="{{ asset('images/flag-ivory-coast.jpg') }}" alt=""
             class="w-full h-full object-cover object-center" loading="eager">
    </div>
    {{-- Overlay sombre --}}
    <div class="absolute inset-0" aria-hidden="true"
         style="background: linear-gradient(135deg,
             hsl(var(--noir-profond) / 0.91) 0%,
             hsl(var(--noir-profond) / 0.80) 50%,
             hsl(var(--noir-profond) / 0.87) 100%);"></div>
    {{-- Filet tricolore en bas --}}
    <div class="absolute bottom-0 left-0 right-0 h-[3px] pointer-events-none" aria-hidden="true"
         style="background: linear-gradient(to right, hsl(var(--orange-ivoire)), hsl(var(--blanc-pur)/0.4) 50%, hsl(var(--vert-ivoire)));"></div>

    <div class="relative z-10 max-w-hub mx-auto px-6">
        <div class="kicker-orange rounded-full mb-5">Programme officiel</div>
        <h1 class="font-serif font-bold text-blanc-pur mb-5"
            style="font-size: clamp(2.8rem, 5.5vw, 4.5rem); letter-spacing: -0.02em; line-height: 1.05;">
            Programme <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">provisoire</em>
        </h1>
        <p class="text-blanc-pur/60 text-lg max-w-2xl mb-10">
            Quatre jours de formation intensive à Abidjan — du 22 au 25 juin 2026.
        </p>

        {{-- Méta-infos --}}
        <div class="flex flex-wrap gap-3">
            @foreach([
                ['M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', '22–25 juin 2026'],
                ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', '9h–17h chaque jour'],
                ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'Abidjan — 3 sites'],
                ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', '~150 auditeurs'],
            ] as [$path, $label])
            <span class="glass flex items-center gap-2 px-4 py-2 rounded-full text-sm text-blanc-pur/80">
                <svg class="w-4 h-4 flex-shrink-0 text-orange-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                </svg>
                {{ $label }}
            </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Programme ─────────────────────────────────────────────────────── --}}
@php
$jours = [
    1 => [
        'day'      => '22',
        'month'    => 'JUIN 2026',
        'titre'    => 'Ouverture & cadrage stratégique',
        'lieu'     => 'Abidjan — Ouverture officielle',
        'theme'    => 'Journée inaugurale',
        'sessions' => [
            ['Accueil et installation des participants',                                          'Accueil',     'Enregistrement des participants et distribution des kits pédagogiques.'],
            ['Cérémonie d\'ouverture officielle',                                                'Plénière',    'Discours officiels en présence des autorités et des partenaires institutionnels.'],
            ['Conférence inaugurale : enjeux du commerce extérieur ivoirien en 2026',            'Conférence',  'Analyse des enjeux et opportunités du commerce extérieur pour les opérateurs ivoiriens.'],
            ['Pause déjeuner',                                                                    'Pause',       'Repas servi sur site.'],
            ['Présentation des institutions d\'appui (ACIEx, CNE, GUCE-CI, CODINORM, CI-PME)',   'Networking',  'Stands d\'information et échanges avec les organismes d\'appui au commerce extérieur.'],
        ],
    ],
    2 => [
        'day'      => '23',
        'month'    => 'JUIN 2026',
        'titre'    => 'Ateliers — Journée 1',
        'lieu'     => 'CGECI',
        'theme'    => 'Sessions thématiques J1',
        'sessions' => [
            ['Atelier ZLECAf & CEDEAO — Groupe 1',                                              'Atelier',  'Conquérir les marchés régionaux et tirer parti des accords de libre-échange.'],
            ['Atelier Financement & garanties — Groupe 2',                                       'Atelier',  'Sécuriser ses opérations grâce aux mécanismes de garantie et de financement export.'],
            ['Atelier Commerce électronique — Groupe 3',                                         'Atelier',  'Digitaliser ses échanges et développer sa présence sur les plateformes internationales.'],
            ['Pause déjeuner',                                                                    'Pause',    'Repas servi sur site.'],
            ['Panel thématique : opérationnalisation de la ZLECAf en Côte d\'Ivoire',            'Panel',    'Table ronde avec des experts sur l\'état d\'avancement et les perspectives de la ZLECAf.'],
        ],
    ],
    3 => [
        'day'      => '24',
        'month'    => 'JUIN 2026',
        'titre'    => 'Ateliers — Journée 2 + Forums',
        'lieu'     => 'CCI-CI',
        'theme'    => 'Sessions thématiques J2 & B2B',
        'sessions' => [
            ['Rotation des groupes (Conformité, ZLECAf, Financement, E-commerce)',               'Atelier',  'Chaque groupe aborde les thématiques non traitées lors de la première journée.'],
            ['Forum B2B avec banques & assureurs partenaires',                                   'B2B',      'Rencontres bilatérales avec les établissements financiers et partenaires techniques.'],
            ['Pause déjeuner',                                                                    'Pause',    'Repas servi sur site.'],
            ['Échanges avec les journalistes — espace presse',                                   'Presse',   'Séquences d\'information pour les journalistes accrédités.'],
        ],
    ],
    4 => [
        'day'      => '25',
        'month'    => 'JUIN 2026',
        'titre'    => 'Synthèse & clôture',
        'lieu'     => 'SEEN Hôtel — Plateau',
        'theme'    => 'Restitution & cérémonie',
        'sessions' => [
            ['Restitution des travaux par les rapporteurs des ateliers',                         'Restitution', 'Synthèse des apprentissages présentée par les rapporteurs de chaque groupe.'],
            ['Recommandations consolidées',                                                       'Plénière',    'Adoption des recommandations issues des travaux en ateliers.'],
            ['Évaluation des acquis (post-test)',                                                'Évaluation',  'Questionnaire individuel de validation des acquis de la formation.'],
            ['Cérémonie de clôture & remise des certificats',                                    'Cérémonie',   'Remise solennelle des certificats de participation et mot de clôture.'],
        ],
    ],
];

$tagColors = [
    'Accueil'     => 'bg-orange-soft-bg text-orange-brule',
    'Plénière'    => 'bg-vert-soft-bg text-vert-ivoire',
    'Conférence'  => 'bg-vert-soft-bg text-vert-ivoire',
    'Atelier'     => 'bg-vert-soft-bg text-vert-ivoire',
    'Networking'  => 'bg-orange-soft-bg text-orange-brule',
    'Pause'       => 'bg-sable/60 text-gris-500',
    'Panel'       => 'bg-vert-soft-bg text-vert-ivoire',
    'B2B'         => 'bg-purple-100 text-purple-700',
    'Presse'      => 'bg-orange-soft-bg text-orange-brule',
    'Restitution' => 'bg-vert-soft-bg text-vert-ivoire',
    'Évaluation'  => 'bg-vert-soft-bg text-vert-ivoire',
    'Cérémonie'   => 'bg-orange-soft-bg text-orange-brule',
];
@endphp

<section class="py-16 bg-blanc-creme" x-data="{ jour: 1 }">
    <div class="max-w-hub mx-auto px-6">

        {{-- Sélecteur de jours --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-10">
            @foreach($jours as $num => $jour)
            <button
                @click="jour = {{ $num }}"
                :class="jour === {{ $num }}
                    ? 'bg-noir-profond text-blanc-pur shadow-lg border-transparent'
                    : 'bg-blanc-pur text-noir-profond border-sable hover:border-gris-500/30 hover:shadow-sm'"
                class="text-left px-5 py-4 rounded-2xl border transition-all duration-300 flex items-center gap-4"
            >
                <div class="flex-shrink-0">
                    <div class="font-mono font-bold leading-none"
                         :class="jour === {{ $num }} ? 'text-orange-soft' : 'text-orange-ivoire'"
                         style="font-size: 1.75rem; line-height: 1;">{{ $jour['day'] }}</div>
                    <div class="text-[0.58rem] font-bold uppercase tracking-widest mt-0.5"
                         :class="jour === {{ $num }} ? 'text-blanc-pur/40' : 'text-gris-500/50'">{{ $jour['month'] }}</div>
                </div>
                <div class="w-px h-7 flex-shrink-0"
                     :class="jour === {{ $num }} ? 'bg-blanc-pur/15' : 'bg-sable'"></div>
                <div class="flex-1 min-w-0">
                    <div class="text-[0.6rem] font-bold uppercase tracking-widest mb-0.5"
                         :class="jour === {{ $num }} ? 'text-blanc-pur/40' : 'text-gris-500/50'">Jour {{ $num }}</div>
                    <div class="font-semibold text-xs leading-tight truncate">{{ $jour['theme'] }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Contenu par jour --}}
        @foreach($jours as $num => $jour)
        <div
            x-show="jour === {{ $num }}"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            style="{{ $num > 1 ? 'display:none;' : '' }}"
        >
            {{-- En-tête du jour avec motif bogolan --}}
            <div class="relative bg-noir-profond rounded-3xl px-10 lg:px-14 py-8 mb-5 flex flex-col sm:flex-row items-start sm:items-center gap-8 overflow-hidden">

                {{-- Motif bogolan en fond (droite) --}}
                <svg class="absolute right-0 top-0 h-full pointer-events-none" style="width: 55%; opacity: 0.14;" aria-hidden="true">
                    <rect width="100%" height="100%" fill="url(#bogolan-pattern)"/>
                </svg>
                {{-- Fondu orange → transparent sur le motif --}}
                <div class="absolute inset-0 pointer-events-none"
                     style="background: linear-gradient(to right, hsl(var(--noir-profond)) 20%, transparent 60%, hsl(var(--noir-profond)/0.3) 100%);"></div>
                {{-- Filet orange en bas --}}
                <div class="absolute bottom-0 left-10 right-10 lg:left-14 lg:right-14 h-[2px] rounded-full"
                     style="background: linear-gradient(to right, hsl(var(--orange-ivoire)/0.6), transparent);"></div>

                <div class="relative z-10 flex-shrink-0 text-center sm:text-left ml-2 sm:ml-6">
                    <div class="font-mono font-bold text-orange-soft leading-none" style="font-size: 3.5rem; line-height: 1;">{{ $jour['day'] }}</div>
                    <div class="text-[0.6rem] font-bold uppercase tracking-widest text-blanc-pur/40 mt-1">{{ $jour['month'] }}</div>
                </div>
                <div class="relative z-10 w-px h-14 bg-blanc-pur/10 hidden sm:block flex-shrink-0"></div>
                <div class="relative z-10 flex-1">
                    <h2 class="font-serif font-bold text-blanc-pur text-2xl leading-tight mb-1">{{ $jour['titre'] }}</h2>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-blanc-pur/50">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-orange-soft flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $jour['lieu'] }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-orange-soft flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            9h00 – 17h00
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-orange-soft flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ count($jour['sessions']) }} sessions
                        </span>
                    </div>
                </div>
            </div>

            {{-- Liste des sessions --}}
            <div class="bg-blanc-pur rounded-3xl overflow-hidden border border-sable/60 shadow-sm mb-8">
                @foreach($jour['sessions'] as $i => [$label, $tag, $desc])
                <div class="relative flex items-start gap-5 px-6 py-6 border-b border-sable/40 last:border-b-0
                            group hover:bg-orange-soft-bg/20 transition-colors duration-200
                            {{ $tag === 'Pause' ? 'opacity-60' : '' }}">
                    {{-- Accent latéral --}}
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-orange-ivoire
                                transition-all duration-300 opacity-0 group-hover:opacity-100"
                         style="height: 55%;"></div>
                    {{-- Numéro --}}
                    <span class="font-mono font-bold text-base w-8 flex-shrink-0 text-gris-500/25
                                 group-hover:text-orange-ivoire transition-colors duration-200 select-none mt-0.5">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-noir-profond text-sm leading-snug mb-1">{{ $label }}</div>
                        @if($tag !== 'Pause')
                        <div class="text-xs text-gris-500 leading-relaxed">{{ $desc }}</div>
                        @endif
                    </div>
                    {{-- Tag --}}
                    <span class="flex-shrink-0 text-[0.6rem] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mt-0.5 {{ $tagColors[$tag] ?? 'bg-sable/50 text-gris-500' }}">
                        {{ $tag }}
                    </span>
                </div>
                @endforeach
            </div>

        </div>
        @endforeach

        {{-- Note provisoire --}}
        <div class="flex items-start gap-3 p-5 rounded-2xl bg-orange-soft-bg border border-orange-ivoire/20">
            <svg class="w-5 h-5 text-orange-brule flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <p class="text-sm text-orange-brule leading-relaxed">
                <strong>Programme provisoire.</strong> Les horaires précis, les intervenants et les affectations de salles seront communiqués au plus tard 10 jours avant l'événement aux auditeurs sélectionnés.
            </p>
        </div>

    </div>
</section>

{{-- ── CTA candidature ──────────────────────────────────────────────── --}}
<section class="py-16 bg-noir-profond">
    <div class="max-w-hub mx-auto px-6 text-center">
        <p class="text-blanc-pur/50 text-sm uppercase tracking-widest font-mono mb-4">Participez au Hub</p>
        <h2 class="font-serif font-bold text-blanc-pur mb-6"
            style="font-size: clamp(1.8rem, 3.5vw, 2.8rem); letter-spacing: -0.02em; line-height: 1.1;">
            Rejoignez les <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144;">150 auditeurs</em> sélectionnés.
        </h2>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('inscription') }}" class="btn-fill px-7 py-3.5">
                <span>Déposer ma candidature</span>
            </a>
            <a href="{{ route('ateliers.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-blanc-pur/70
                      border border-blanc-pur/20 hover:border-blanc-pur/40 hover:text-blanc-pur transition-all">
                Découvrir les ateliers
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

</x-layouts.public>
