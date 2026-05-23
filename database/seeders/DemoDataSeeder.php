<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ApplicationStatus::Received->value    => 10,
            ApplicationStatus::Incomplete->value  => 5,
            ApplicationStatus::Eligible->value    => 8,
            ApplicationStatus::UnderReview->value => 10,
            ApplicationStatus::Shortlisted->value => 8,
            ApplicationStatus::Accepted->value    => 5,
            ApplicationStatus::Waitlisted->value  => 4,
        ];

        foreach ($statuses as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $user = User::factory()->create();
                $user->assignRole('candidate');

                $data = Application::factory()->make([
                    'user_id'        => $user->id,
                    'status'         => $status,
                    'reference_code' => 'HIE2026-' . strtoupper(Str::random(6)),
                    'submitted_at'   => now()->subDays(rand(1, 45)),
                ])->toArray();

                if ($status === ApplicationStatus::Accepted->value) {
                    $data['group_label']   = ['G1', 'G2', 'G3'][array_rand(['G1', 'G2', 'G3'])];
                    $data['qr_token']      = Str::random(48);
                    $data['check_in_code'] = (string) rand(100000, 999999);
                    $data['accepted_at']   = now()->subDays(rand(1, 5));
                }

                Application::create($data);
            }
        }
    }
}
