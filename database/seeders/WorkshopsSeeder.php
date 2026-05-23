<?php

namespace Database\Seeders;

use App\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopsSeeder extends Seeder
{
    public function run(): void
    {
        $workshops = [
            [
                'slug'              => 'zlecaf-cedeao',
                'title'             => 'ZLECAf & CEDEAO : opportunités et stratégies d\'accès aux marchés',
                'short_description' => 'Comprendre et exploiter les accords commerciaux régionaux et continentaux.',
                'objectives'        => [
                    'Maîtriser les mécanismes de la ZLECAf et de la CEDEAO',
                    'Identifier les marchés porteurs pour les entreprises ivoiriennes',
                    'Élaborer une stratégie d\'internationalisation adaptée',
                    'Connaître les procédures d\'accès aux marchés régionaux',
                ],
                'themes'            => [
                    'Accords préférentiels et zones de libre-échange',
                    'Opportunités sectorielles CEDEAO',
                    'Stratégies export pour PME',
                    'Accompagnement institutionnel disponible',
                ],
                'capacity'     => 60,
                'display_order'=> 1,
                'is_published' => true,
            ],
            [
                'slug'              => 'financement-international',
                'title'             => 'Financement du commerce international et garanties',
                'short_description' => 'Accéder aux instruments financiers adaptés au commerce extérieur.',
                'objectives'        => [
                    'Identifier les instruments de financement du commerce international',
                    'Comprendre les garanties bancaires et assurance-crédit',
                    'Accéder aux lignes de crédit export disponibles en Côte d\'Ivoire',
                    'Maîtriser les crédits documentaires et lettres de crédit',
                ],
                'themes'            => [
                    'Crédits documentaires (CREDOC) et remises documentaires',
                    'Assurance-crédit et garanties COFACE / Afreximbank',
                    'Financement des PME exportatrices',
                    'Rôle des banques de développement régionales',
                ],
                'capacity'     => 60,
                'display_order'=> 2,
                'is_published' => true,
            ],
            [
                'slug'              => 'commerce-electronique',
                'title'             => 'Commerce électronique et digitalisation des procédures',
                'short_description' => 'Intégrer le numérique dans sa stratégie d\'export.',
                'objectives'        => [
                    'Maîtriser les plateformes de e-commerce international',
                    'Digitaliser ses procédures douanières et documentaires',
                    'Comprendre la réglementation du commerce numérique transfrontalier',
                    'Développer sa présence en ligne sur les marchés cibles',
                ],
                'themes'            => [
                    'Plateformes B2B et B2C internationales',
                    'Dédouanement électronique et GUCE-CI',
                    'Marketing digital à l\'international',
                    'Cybersécurité pour les transactions commerciales',
                ],
                'capacity'     => 60,
                'display_order'=> 3,
                'is_published' => true,
            ],
            [
                'slug'              => 'conformite-qualite',
                'title'             => 'Conformité, qualité et certification pour l\'export',
                'short_description' => 'Répondre aux exigences qualité et réglementaires des marchés d\'export.',
                'objectives'        => [
                    'Comprendre les normes qualité exigées sur les marchés cibles',
                    'Maîtriser les procédures de certification et labellisation',
                    'Connaître le rôle de CODINORM et des organismes de certification',
                    'Mettre en place un système qualité adapté à l\'export',
                ],
                'themes'            => [
                    'Normes ISO, HACCP, BRC pour l\'agro-industrie',
                    'Certification et labellisation produits',
                    'Barrières techniques au commerce (BTC)',
                    'Conformité réglementaire UE, USA, Chine',
                ],
                'capacity'     => 60,
                'display_order'=> 4,
                'is_published' => true,
            ],
        ];

        foreach ($workshops as $data) {
            Workshop::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
