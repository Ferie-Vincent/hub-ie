<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;

class SpeakersSeeder extends Seeder
{
    public function run(): void
    {
        $speakers = [
            [
                'first_name'    => 'Koné',
                'last_name'     => 'Adjoua',
                'title'         => 'Directrice Générale',
                'organization'  => 'ACIEx — Agence Ivoirienne pour la Compétitivité à l\'Export',
                'bio'           => 'Experte en stratégies d\'exportation pour les PME africaines, avec plus de 15 ans d\'expérience dans l\'accompagnement à l\'internationalisation.',
                'display_order' => 1,
                'is_featured'   => true,
                'is_published'  => true,
            ],
            [
                'first_name'    => 'Traoré',
                'last_name'     => 'Ibrahim',
                'title'         => 'Directeur des Opérations',
                'organization'  => 'GUCE-CI — Guichet Unique du Commerce Extérieur',
                'bio'           => 'Spécialiste de la digitalisation des procédures douanières et du commerce électronique transfrontalier en Afrique de l\'Ouest.',
                'display_order' => 2,
                'is_featured'   => true,
                'is_published'  => true,
            ],
            [
                'first_name'    => 'Coulibaly',
                'last_name'     => 'Mariam',
                'title'         => 'Responsable Financement Export',
                'organization'  => 'CGECI — Confédération Générale des Entreprises de Côte d\'Ivoire',
                'bio'           => 'Experte en financement du commerce international et instruments de garantie pour les entreprises exportatrices.',
                'display_order' => 3,
                'is_featured'   => false,
                'is_published'  => true,
            ],
            [
                'first_name'    => 'N\'Guessan',
                'last_name'     => 'Patrice',
                'title'         => 'Chef de Division Normes et Qualité',
                'organization'  => 'CODINORM — Côte d\'Ivoire Normalisation',
                'bio'           => 'Ingénieur qualité certifié, il accompagne les entreprises ivoiriennes dans leurs démarches de certification ISO, HACCP et conformité réglementaire.',
                'display_order' => 4,
                'is_featured'   => false,
                'is_published'  => true,
            ],
        ];

        foreach ($speakers as $data) {
            Speaker::updateOrCreate(
                ['first_name' => $data['first_name'], 'last_name' => $data['last_name']],
                $data
            );
        }
    }
}
