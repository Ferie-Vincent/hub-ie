<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Workshop> */
class WorkshopFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'slug'              => Str::slug($title),
            'title'             => $title,
            'short_description' => fake()->sentence(),
            'full_description'  => fake()->paragraph(4),
            'objectives'        => fake()->sentences(4),
            'themes'            => fake()->sentences(3),
            'capacity'          => 60,
            'display_order'     => 0,
            'is_published'      => true,
        ];
    }
}
