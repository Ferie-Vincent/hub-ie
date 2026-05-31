<?php

namespace Database\Factories;

use App\Enums\PartnerTier;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Partner> */
class PartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'website' => fake()->url(),
            'tier' => fake()->randomElement(PartnerTier::cases())->value,
            'display_order' => fake()->numberBetween(0, 10),
            'show_in_marquee' => true,
            'show_in_footer' => false,
        ];
    }
}
