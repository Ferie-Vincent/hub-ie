<?php

namespace Database\Seeders;

use App\Enums\PartnerTier;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnersSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Ministère du Commerce, de l\'Industrie et de l\'Artisanat', 'tier' => PartnerTier::Organizer, 'show_in_footer' => true, 'display_order' => 1],
            ['name' => 'Direction Générale du Commerce Extérieur (DGCE)',            'tier' => PartnerTier::Organizer, 'show_in_footer' => true, 'display_order' => 2],
            ['name' => 'TradeMark Africa',                                           'tier' => PartnerTier::Strategic, 'show_in_footer' => true, 'display_order' => 3],
            ['name' => 'GIZ',                                                        'tier' => PartnerTier::Strategic, 'show_in_footer' => true, 'display_order' => 4],
            ['name' => 'ACIEx',                                                      'tier' => PartnerTier::Partner,  'display_order' => 5],
            ['name' => 'CNE',                                                        'tier' => PartnerTier::Partner,  'display_order' => 6],
            ['name' => 'GUCE-CI',                                                    'tier' => PartnerTier::Partner,  'display_order' => 7],
            ['name' => 'CODINORM',                                                   'tier' => PartnerTier::Partner,  'display_order' => 8],
            ['name' => 'CI-PME',                                                     'tier' => PartnerTier::Partner,  'display_order' => 9],
            ['name' => 'CGECI',                                                      'tier' => PartnerTier::Partner,  'display_order' => 10],
            ['name' => 'CCI-CI',                                                     'tier' => PartnerTier::Partner,  'display_order' => 11],
            ['name' => 'Administration des Douanes',                                 'tier' => PartnerTier::Partner,  'display_order' => 12],
        ];

        foreach ($partners as $data) {
            Partner::firstOrCreate(
                ['name' => $data['name']],
                array_merge(['show_in_marquee' => true, 'show_in_footer' => false], $data, ['tier' => $data['tier']->value])
            );
        }
    }
}
