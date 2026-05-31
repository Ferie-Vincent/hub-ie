<x-layouts.public :title="'Politique de confidentialité — Hub Import-Export 2026'">
<x-page-hero kicker="Protection des données">
    Politique de confidentialité
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-3xl mx-auto px-6 pt-14">
        <div class="mb-10">

        <div class="space-y-8 text-gris-500 leading-relaxed">
            <p>Conformément à la <strong class="text-noir-profond">loi n°2013-450 du 19 juin 2013 relative à la protection des données à caractère personnel</strong> en République de Côte d'Ivoire, et sous le contrôle de l'<strong class="text-noir-profond">Autorité de Régulation des Télécommunications/TIC de Côte d'Ivoire (ARTCI)</strong>, le présent traitement de données est mis en œuvre dans les conditions suivantes.</p>

            @foreach([
                ['Responsable du traitement', 'Ministère du Commerce, de l\'Industrie et de l\'Artisanat, représenté par le DGCE.'],
                ['Finalités', 'Organisation du Hub Import-Export 2026 (instruction des candidatures, évaluation, attribution des places, communication, suivi des présences, certification de participation, communication post-événement).'],
                ['Données collectées', 'Identité, coordonnées, données professionnelles, motivation, photo, pièces justificatives, données de navigation (cookies techniques uniquement).'],
                ['Bases légales', 'Consentement explicite du candidat (recueilli à l\'étape 4 du formulaire) et exécution d\'une mission d\'intérêt public confiée au responsable du traitement.'],
                ['Destinataires', 'Personnel habilité de la DGCE, membres du comité de sélection, prestataire d\'hébergement, prestataire d\'envoi d\'emails. Aucune cession à des tiers à des fins commerciales.'],
                ['Durée de conservation', 'Les données de candidature sont conservées 3 ans après la clôture de l\'événement, à des fins de suivi d\'impact et d\'historique institutionnel. Les comptes utilisateurs peuvent être supprimés à tout moment sur demande.'],
                ['Droits du candidat', 'Droit d\'accès, de rectification, d\'effacement, de limitation, d\'opposition et de portabilité. Ces droits peuvent être exercés par email à hub-import-export@commerce.gouv.ci ou directement depuis votre espace candidat.'],
                ['Cookies', 'Le site utilise exclusivement des cookies techniques nécessaires au bon fonctionnement (session, authentification, préférences). Aucun cookie publicitaire ou de mesure d\'audience tiers n\'est utilisé.'],
            ] as [$titre, $contenu])
            <div>
                <h2 class="font-serif font-bold text-noir-profond text-lg mb-2">{{ $titre }}</h2>
                <p>{{ $contenu }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
</x-layouts.public>
