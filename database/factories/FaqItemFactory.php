<?php

namespace Database\Factories;

use App\Models\FaqItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FaqItem> */
class FaqItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question'      => fake()->sentence() . '?',
            'answer'        => fake()->paragraph(2),
            'category'      => fake()->randomElement(['candidature', 'programme', 'pratique', 'autre']),
            'display_order' => fake()->numberBetween(0, 20),
            'is_published'  => true,
        ];
    }
}
