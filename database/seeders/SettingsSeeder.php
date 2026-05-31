<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'application_opens_at',  'value' => '2026-04-01 00:00:00', 'type' => 'date',   'group' => 'candidature', 'label' => 'Ouverture des candidatures'],
            ['key' => 'application_closes_at', 'value' => '2026-05-15 23:59:59', 'type' => 'date',   'group' => 'candidature', 'label' => 'Clôture des candidatures'],
            ['key' => 'target_auditors',       'value' => '180',                 'type' => 'int',    'group' => 'candidature', 'label' => 'Nombre d\'auditeurs cibles'],
            ['key' => 'quota_women_min_pct',   'value' => '50',                  'type' => 'int',    'group' => 'candidature', 'label' => 'Quota femmes minimum (%)'],
            ['key' => 'quota_youth_min_pct',   'value' => '40',                  'type' => 'int',    'group' => 'candidature', 'label' => 'Quota jeunes minimum (%)'],
            ['key' => 'quota_youth_max_age',   'value' => '35',                  'type' => 'int',    'group' => 'candidature', 'label' => 'Âge maximum jeune (ans)'],
            ['key' => 'event_starts_at',       'value' => '2026-06-22 09:00:00', 'type' => 'date',   'group' => 'event',       'label' => 'Début de l\'événement'],
            ['key' => 'event_ends_at',         'value' => '2026-06-25 18:00:00', 'type' => 'date',   'group' => 'event',       'label' => 'Fin de l\'événement'],
            ['key' => 'registration_open',     'value' => '1',                   'type' => 'bool',   'group' => 'candidature', 'label' => 'Inscriptions ouvertes'],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
