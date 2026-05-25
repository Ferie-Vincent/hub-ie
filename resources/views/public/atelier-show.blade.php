<x-layouts.public>

@php
$ateliers = [
    'zlecaf-cedeao' => [
        'titre'       => 'ZLECAf & CEDEAO : conquérir les marchés régionaux',
        'tagline'     => 'Conquérir les marchés régionaux',
        'description' => 'Maîtriser les règles d\'origine, les protocoles tarifaires et les opportunités opérationnelles ouvertes par la Zone de Libre-Échange Continentale Africaine.',
        'objectifs'   => [
            'Comprendre le cadre juridique de la ZLECAf et de l\'union douanière CEDEAO.',
            'Identifier les produits éligibles et les règles d\'origine applicables.',
            'Cartographier les marchés cibles prioritaires (Nigéria, Sénégal, Ghana, Afrique du Sud, Kenya).',
            'Activer les dispositifs d\'accompagnement nationaux (ACIEx, CNE) pour exporter.',
        ],
        'themes'      => ['Protocoles ZLECAf', 'TEC CEDEAO', 'Régimes douaniers préférentiels', 'Études de marché régionales', 'Plateforme PAPSS', 'Logistique intra-africaine'],
    ],
    'financement-garanties' => [
        'titre'       => 'Financement & garanties : sécuriser ses opérations',
        'tagline'     => 'Sécuriser ses opérations',
        'description' => 'Découvrir les mécanismes de financement export, les garanties bancaires, l\'assurance-crédit et les dispositifs publics d\'appui.',
        'objectifs'   => [
            'Comprendre les instruments de financement à court et moyen terme (crédit documentaire, crédit acheteur, affacturage international).',
            'Identifier les acteurs nationaux et internationaux (banques, BAD, Afreximbank, fonds de garantie).',
            'Construire un dossier bancable pour un projet d\'exportation.',
            'Maîtriser les couvertures de risque (assurance-crédit, garantie de change).',
        ],
        'themes'      => ['Crédoc', 'Garantie bancaire', 'Assurance-crédit', 'Afreximbank', 'Fonds de garantie ivoiriens', 'Risk management'],
    ],
    'commerce-electronique' => [
        'titre'       => 'Commerce électronique : digitaliser ses échanges',
        'tagline'     => 'Digitaliser ses échanges',
        'description' => 'Utiliser les plateformes B2B/B2C internationales, structurer sa présence digitale et exploiter les guichets uniques numériques.',
        'objectifs'   => [
            'Construire une stratégie digitale export adaptée à son secteur.',
            'Référencer son entreprise sur les principales plateformes B2B internationales (Alibaba, Amazon Business, Africa Trade Portal).',
            'Utiliser le Guichet Unique du Commerce Extérieur (GUCE-CI) et autres outils dématérialisés.',
            'Sécuriser ses transactions électroniques transfrontalières.',
        ],
        'themes'      => ['SEO export', 'Marketplaces B2B', 'GUCE-CI', 'Paiements internationaux', 'Logistique e-commerce', 'Conformité RGPD pour exporter vers l\'UE'],
    ],
    'conformite-qualite' => [
        'titre'       => 'Conformité, qualité & certification : maîtriser les normes',
        'tagline'     => 'Maîtriser les normes',
        'description' => 'Comprendre les exigences normatives (sanitaires, environnementales, techniques) et structurer une démarche de certification.',
        'objectifs'   => [
            'Cartographier les normes obligatoires et volontaires des marchés cibles.',
            'Maîtriser les exigences SPS (sanitaires et phytosanitaires) et OTC (obstacles techniques).',
            'Engager une démarche de certification ISO / HACCP / FairTrade / Bio adaptée au produit.',
            'Mobiliser l\'accompagnement de CODINORM et des laboratoires nationaux.',
        ],
        'themes'      => ['Normes ISO', 'HACCP', 'Certifications volontaires', 'Étiquetage UE', 'Traçabilité', 'CODINORM', 'Laboratoires d\'analyse'],
    ],
];

$data = $ateliers[$slug] ?? null;
@endphp

@if(!$data)
    <div class="pt-32 pb-24 min-h-screen bg-blanc-creme flex items-center justify-center">
        <div class="text-center">
            <p class="text-gris-500 mb-4">Atelier introuvable.</p>
            <a href="{{ route('ateliers.index') }}" class="btn-fill px-5 py-2.5"><span>Retour aux ateliers</span></a>
        </div>
    </div>
@else
<x-page-hero kicker="Atelier thématique" :description="$data['description']">
    {{ $data['titre'] }}
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-3xl mx-auto px-6 pt-14">
        <a href="{{ route('ateliers.index') }}" class="inline-flex items-center gap-2 text-sm text-gris-500 hover:text-orange-ivoire transition-colors mb-10 link-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Tous les ateliers
        </a>

        <div class="space-y-8">
            <div>
                <h2 class="font-serif font-bold text-noir-profond text-xl mb-4">Objectifs pédagogiques</h2>
                <ul class="space-y-3">
                    @foreach($data['objectifs'] as $obj)
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                              style="background: hsl(var(--vert-soft-bg));">
                            <svg class="w-3 h-3" fill="none" stroke="hsl(var(--vert-ivoire))" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-noir-profond/80">{{ $obj }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="font-serif font-bold text-noir-profond text-xl mb-4">Thèmes couverts</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($data['themes'] as $theme)
                    <span class="kicker-vert rounded-full">{{ $theme }}</span>
                    @endforeach
                </div>
            </div>

            <div class="bg-orange-soft-bg rounded-2xl p-6">
                <p class="text-orange-brule font-semibold mb-3">Participer à cet atelier</p>
                <p class="text-sm text-noir-profond/70 mb-4">L'accès à cet atelier est conditionné à la sélection de votre candidature. Candidatez dès maintenant pour rejoindre le Hub Import-Export 2026.</p>
                <a href="{{ route('inscription') }}" class="btn-fill px-5 py-2.5 text-sm"><span>S'inscrire au Hub</span></a>
            </div>
        </div>
    </div>
</div>
@endif

</x-layouts.public>
