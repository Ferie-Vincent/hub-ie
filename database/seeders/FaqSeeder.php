<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Candidature
            ['category' => 'candidature', 'display_order' => 1,  'question' => 'Qui peut participer au Hub Import-Export 2026 ?',                     'answer' => 'Le Hub est ouvert aux acteurs économiques ivoiriens : PME exportatrices et importatrices, agro-transformateurs, transitaires, banquiers, agents douaniers, universitaires, journalistes et représentants de la société civile. Les candidatures sont examinées par un comité de sélection.'],
            ['category' => 'candidature', 'display_order' => 2,  'question' => 'Comment soumettre ma candidature ?',                                   'answer' => 'Créez un compte sur la plateforme hubimportexport.ci, puis complétez le formulaire de candidature en 4 étapes : identité, profil professionnel, motivation et pièces justificatives. La soumission est entièrement en ligne.'],
            ['category' => 'candidature', 'display_order' => 3,  'question' => 'Quels documents dois-je fournir ?',                                    'answer' => 'Le CV est obligatoire. L\'attestation RCCM est requise pour les PME exportatrices et les agro-transformateurs. Une pièce d\'identité est facultative. Tous les documents doivent être en format PDF, JPG ou PNG, avec une taille maximale de 5 Mo chacun.'],
            ['category' => 'candidature', 'display_order' => 4,  'question' => 'Quelle est la date limite de candidature ?',                           'answer' => 'Les candidatures sont ouvertes du 1er avril 2026 au 15 mai 2026. Passé cette date, le formulaire sera fermé. Il est recommandé de candidater tôt pour permettre le traitement de votre dossier dans les meilleures conditions.'],
            ['category' => 'candidature', 'display_order' => 5,  'question' => 'Puis-je modifier ma candidature après soumission ?',                   'answer' => 'Si votre dossier est marqué "Incomplet" par l\'équipe DGCE, un lien vous sera envoyé par email pour compléter les informations manquantes. En dehors de ce cas, la candidature soumise ne peut pas être modifiée.'],
            ['category' => 'candidature', 'display_order' => 6,  'question' => 'Comment puis-je connaître l\'état de ma candidature ?',                 'answer' => 'Connectez-vous à votre espace personnel sur hubimportexport.ci. Vous y trouverez l\'état en temps réel de votre dossier. Chaque changement de statut vous est également notifié par email.'],

            // Programme
            ['category' => 'programme',   'display_order' => 7,  'question' => 'Quels sont les 4 ateliers du Hub ?',                                   'answer' => 'Les quatre ateliers sont : (1) ZLECAf & CEDEAO, (2) Financement du commerce international, (3) Commerce électronique et digitalisation, (4) Conformité, qualité et certification. Vous pouvez choisir jusqu\'à 2 ateliers dans votre candidature.'],
            ['category' => 'programme',   'display_order' => 8,  'question' => 'Où se déroule l\'événement ?',                                         'answer' => 'Le Hub Import-Export 2026 se tient du 22 au 25 juin 2026 dans trois lieux à Abidjan-Plateau : le CGECI, le CCI-CI et le SEEN Hôtel. Le programme détaillé par lieu sera communiqué aux auditeurs retenus.'],
            ['category' => 'programme',   'display_order' => 9,  'question' => 'La participation est-elle payante ?',                                   'answer' => 'La participation au Hub Import-Export 2026 est entièrement gratuite pour les auditeurs sélectionnés. Les frais de déplacement et d\'hébergement restent à la charge des participants.'],
            ['category' => 'programme',   'display_order' => 10, 'question' => 'Peut-on participer à plusieurs ateliers ?',                             'answer' => 'Chaque auditeur est affecté à un groupe (G1, G2 ou G3) et peut assister à 2 ateliers sur les 4. La répartition vise à équilibrer les profils dans chaque atelier.'],

            // Pratique
            ['category' => 'pratique',    'display_order' => 11, 'question' => 'Comment accéder à ma convocation et mon badge ?',                      'answer' => 'Si votre candidature est retenue, une convocation officielle avec votre badge (QR code et code à 6 chiffres) vous sera envoyée par email. Vous pouvez également les télécharger depuis votre espace personnel.'],
            ['category' => 'pratique',    'display_order' => 12, 'question' => 'Que faire si je ne reçois pas l\'email de confirmation ?',              'answer' => 'Vérifiez vos spams et courriers indésirables. Si vous ne trouvez pas l\'email, connectez-vous à votre espace personnel pour vérifier le statut de votre candidature. Pour toute assistance, contactez contact@dgce.ci.'],
            ['category' => 'pratique',    'display_order' => 13, 'question' => 'Comment fonctionne le pointage à l\'entrée ?',                         'answer' => 'À votre arrivée chaque jour, présentez votre badge (QR code) ou communiquez votre code à 6 chiffres à l\'agent d\'accueil. Le pointage est individuel et requis chaque jour de présence.'],
            ['category' => 'pratique',    'display_order' => 14, 'question' => 'Mes données personnelles sont-elles protégées ?',                      'answer' => 'Vos données sont traitées conformément à la loi ivoirienne n°2013-450 sur la protection des données à caractère personnel. Elles ne sont utilisées qu\'aux fins d\'organisation du Hub et ne sont pas transmises à des tiers non autorisés.'],
            ['category' => 'pratique',    'display_order' => 15, 'question' => 'Puis-je retirer ma candidature ?',                                     'answer' => 'Oui, vous pouvez retirer votre candidature depuis votre espace personnel tant que la délibération finale n\'a pas eu lieu. En cas de retrait après acceptation, votre place sera attribuée au premier candidat sur liste d\'attente.'],
        ];

        foreach ($items as $item) {
            FaqItem::updateOrCreate(
                ['question' => $item['question']],
                $item
            );
        }
    }
}
