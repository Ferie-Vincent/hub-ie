<?php

namespace Database\Factories;

use App\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Speaker> */
class SpeakerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'title' => fake()->jobTitle(),
            'organization' => fake()->company(),
            'bio' => fake()->paragraph(2),
            'display_order' => fake()->numberBetween(0, 20),
            'is_featured' => false,
            'is_published' => true,
        ];
    }
}
