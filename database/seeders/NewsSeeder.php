<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::whereHas('roles', fn ($q) => $q->where('name', 'super_admin'))->first();

        $articles = [
            [
                'slug'         => 'lancement-hub-import-export-2026',
                'title'        => 'Lancement officiel du Hub Import-Export 2026',
                'excerpt'      => 'Le Ministère du Commerce, de l\'Industrie et de l\'Artisanat annonce l\'ouverture des candidatures pour le Hub Import-Export 2026, qui se tiendra à Abidjan du 22 au 25 juin 2026.',
                'content'      => "Le Hub Import-Export 2026 est une initiative portée par la Direction Générale du Commerce Extérieur (DGCE) sous la tutelle du Ministère du Commerce, de l'Industrie et de l'Artisanat de Côte d'Ivoire.\n\nCet événement vise à renforcer les capacités des entreprises ivoiriennes dans le domaine du commerce extérieur, en leur offrant un accès à des formations pratiques, des mises en réseau avec des experts et des opportunités de partenariats commerciaux.\n\nLes candidatures sont ouvertes jusqu'au 31 mai 2026. Seuls 150 auditeurs seront sélectionnés parmi les candidats éligibles.",
                'is_featured'  => true,
                'published_at' => Carbon::parse('2026-04-15 09:00:00'),
            ],
            [
                'slug'         => 'ouverture-candidatures-hub-2026',
                'title'        => 'Ouverture des candidatures : mode d\'emploi',
                'excerpt'      => 'Guide pratique pour soumettre votre candidature au Hub Import-Export 2026 et maximiser vos chances de sélection.',
                'content'      => "Pour candidater au Hub Import-Export 2026, rendez-vous sur la plateforme en ligne et complétez le formulaire de candidature.\n\nLes critères de sélection prennent en compte :\n- L'expérience professionnelle dans le commerce extérieur\n- La motivation et le projet d'entreprise\n- La représentation équilibrée des secteurs d'activité\n\nLes candidats sélectionnés seront notifiés par email dans les deux semaines suivant la clôture des candidatures.",
                'is_featured'  => false,
                'published_at' => Carbon::parse('2026-04-20 10:00:00'),
            ],
            [
                'slug'         => 'programme-ateliers-hub-2026',
                'title'        => 'Le programme des ateliers thématiques dévoilé',
                'excerpt'      => 'Quatre ateliers intensifs ont été conçus pour outiller les professionnels ivoiriens du commerce extérieur : ZLECAf, Financement, E-commerce et Conformité.',
                'content'      => "Le programme du Hub Import-Export 2026 s'articule autour de quatre ateliers thématiques, chacun animé par des experts reconnus dans leur domaine.\n\n**Atelier 1 – ZLECAf & CEDEAO**\nComprendre les opportunités offertes par la Zone de Libre-Échange Continentale Africaine et les protocoles CEDEAO.\n\n**Atelier 2 – Financement du commerce international**\nMaîtriser les instruments financiers pour sécuriser et développer ses opérations d'export.\n\n**Atelier 3 – Commerce électronique**\nIntégrer les outils numériques dans sa stratégie d'internationalisation.\n\n**Atelier 4 – Conformité et qualité**\nRépondre aux exigences normatives des marchés cibles.",
                'is_featured'  => false,
                'published_at' => Carbon::parse('2026-05-05 11:00:00'),
            ],
        ];

        foreach ($articles as $data) {
            News::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['author_id' => $author?->id])
            );
        }
    }
}
