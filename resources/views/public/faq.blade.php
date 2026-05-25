<x-layouts.public :title="'FAQ — Hub Import-Export 2026'" :darkHero="true">

<x-page-hero kicker="Questions fréquentes" kickerColor="orange">
    Vos questions, nos <em class="font-fraunces italic text-vert-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">réponses.</em>
</x-page-hero>

<div class="pb-24 bg-blanc-creme" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-6 pt-14">
        <div class="mb-12">

        @php
        $sections = [
            'Candidature' => [
                ['Comment puis-je candidater au Hub Import-Export 2026 ?', 'Créez un compte sur la plateforme avec votre adresse email, puis remplissez le formulaire de candidature en 4 étapes. Votre dossier sera examiné par un comité de sélection.'],
                ['La participation est-elle payante ?', 'Non, la participation au Hub Import-Export 2026 est entièrement gratuite pour les auditeurs sélectionnés.'],
                ['Quand seront annoncées les sélections ?', 'Les résultats sont communiqués au plus tard 10 jours avant l\'événement, soit autour du 12 juin 2026.'],
                ['Puis-je modifier ma candidature après l\'avoir soumise ?', 'Tant que le comité n\'a pas commencé l\'évaluation, vous pouvez ajouter ou modifier des pièces depuis votre espace candidat.'],
                ['Combien d\'ateliers puis-je choisir ?', 'Vous pouvez sélectionner jusqu\'à 2 ateliers parmi les 4 proposés. L\'attribution finale tient compte de l\'équilibre des groupes.'],
            ],
            'Programme' => [
                ['Quelles sont les dates exactes ?', 'Le Hub se tient du lundi 22 au jeudi 25 juin 2026 inclus, de 9h à 17h chaque jour.'],
                ['Où se déroulent les sessions ?', 'Les sessions se tiennent dans trois lieux d\'Abidjan : la CGECI, la CCI-CI et le SEEN Hôtel au Plateau.'],
                ['Y aura-t-il des supports de formation distribués ?', 'Oui, chaque auditeur reçoit un kit pédagogique comprenant les supports de cours et les fiches techniques par atelier.'],
                ['Les sessions seront-elles enregistrées ?', 'Les sessions plénières sont enregistrées et publiées sur la plateforme à l\'issue de l\'événement. Les ateliers ne le sont pas.'],
            ],
            'Pratique' => [
                ['Comment se rendre sur les lieux ?', 'Les trois sites sont au Plateau d\'Abidjan, accessibles en taxi ou transport en commun. Aucun transport n\'est pris en charge par les organisateurs.'],
                ['La restauration est-elle assurée ?', 'Une pause-déjeuner est servie sur chaque site les jours d\'atelier.'],
                ['Recevrai-je un certificat de participation ?', 'Oui, à l\'issue du Hub et après validation du post-test, chaque auditeur reçoit un certificat officiel signé du Ministre.'],
                ['Que dois-je apporter le jour J ?', 'Votre badge (téléchargeable depuis votre espace candidat) ou votre code à 6 chiffres, et une pièce d\'identité valide.'],
            ],
            'Autre' => [
                ['Comment être tenu informé des actualités ?', 'Inscrivez-vous à la newsletter en bas de la page d\'accueil, ou suivez les communications officielles du Ministère du Commerce.'],
                ['Comment contacter les organisateurs ?', 'Par email à hub-import-export@commerce.gouv.ci ou via le formulaire de contact du site.'],
            ],
        ];
        $i = 0;
        @endphp

        <div class="space-y-10">
            @foreach($sections as $cat => $questions)
            <div>
                <h2 class="font-manrope font-bold text-sm uppercase tracking-widest text-gris-500 mb-4">{{ $cat }}</h2>
                <div class="space-y-2">
                    @foreach($questions as $q)
                    @php $idx = $i++; @endphp
                    <div class="relative bg-blanc-pur rounded-2xl overflow-hidden shadow-card group">
                        {{-- Barre latérale orange — effet programme --}}
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-orange-ivoire
                                    transition-all duration-300 opacity-0 group-hover:opacity-100"
                             style="height: 55%;"></div>
                        <button
                            @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                            class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-noir-profond transition-colors"
                            :class="open === {{ $idx }} ? 'text-orange-ivoire' : 'hover:text-orange-ivoire'"
                            :aria-expanded="open === {{ $idx }}"
                        >
                            <span>{{ $q[0] }}</span>
                            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300"
                                 :class="open === {{ $idx }} ? 'rotate-45 text-orange-ivoire' : 'text-gris-500'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                            </svg>
                        </button>
                        <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-5 text-gris-500 leading-relaxed border-t border-sable/40" style="display:none;">
                            <p class="pt-4">{{ $q[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <p class="text-gris-500 mb-4">Votre question n'est pas ici ?</p>
            <a href="{{ route('contact') }}" class="btn-fill px-6 py-3"><span>Nous contacter</span></a>
        </div>
    </div>
</div>

</x-layouts.public>
