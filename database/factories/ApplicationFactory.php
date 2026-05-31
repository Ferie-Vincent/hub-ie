<?php

namespace Database\Factories;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Application> */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(ApplicationStatus::cases());

        return [
            'reference_code' => 'HIE2026-'.strtoupper(Str::random(6)),
            'user_id' => User::factory(),
            'status' => $status->value,
            'current_step' => 4,
            'category' => fake()->randomElement(ApplicationCategory::cases())->value,
            'organization_name' => fake()->company(),
            'organization_type' => 'Société commerciale',
            'position' => fake()->jobTitle(),
            'sector' => fake()->randomElement(['Agroalimentaire', 'Commerce', 'Finance', 'Transport', 'TIC']),
            'experience_years' => fake()->numberBetween(1, 30),
            'motivation' => fake()->paragraphs(3, true),
            'chosen_workshops' => fake()->randomElements([1, 2, 3, 4], 2),
            'referral_source' => fake()->randomElement(['Web', 'Réseaux sociaux', 'Bouche-à-oreille', 'Partenaire']),
            'is_first_participation' => fake()->boolean(60),
            'rgpd_consent' => true,
            'communication_consent' => fake()->boolean(80),
            'submitted_at' => $status !== ApplicationStatus::Draft ? now()->subDays(fake()->numberBetween(1, 30)) : null,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn () => [
            'status' => ApplicationStatus::Accepted->value,
            'group_label' => fake()->randomElement(['G1', 'G2', 'G3']),
            'qr_token' => Str::random(48),
            'check_in_code' => (string) fake()->numberBetween(100000, 999999),
            'accepted_at' => now()->subDays(fake()->numberBetween(1, 10)),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => ApplicationStatus::Draft->value,
            'current_step' => fake()->numberBetween(1, 3),
            'submitted_at' => null,
        ]);
    }
}
