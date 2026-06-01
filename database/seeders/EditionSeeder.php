<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Edition;
use Illuminate\Database\Seeder;

class EditionSeeder extends Seeder
{
    public function run(): void
    {
        $edition = Edition::updateOrCreate(
            ['year' => 2026],
            [
                'title' => 'Hub Import-Export 2026',
                'theme' => "L'export au cœur du développement",
                'location' => 'Abidjan, Côte d\'Ivoire',
                'application_opens_at' => '2026-04-01 00:00:00',
                'application_closes_at' => '2026-05-15 23:59:59',
                'event_starts_at' => '2026-06-22 09:00:00',
                'event_ends_at' => '2026-06-25 18:00:00',
                'max_participants' => 180,
                'quota_women_min_pct' => 50,
                'quota_youth_min_pct' => 40,
                'quota_youth_max_age' => 35,
                'registration_open' => true,
                'is_active' => true,
                'launched_at' => now(),
            ]
        );

        Application::whereNull('edition_id')->update(['edition_id' => $edition->id]);
    }
}
