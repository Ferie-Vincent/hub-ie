<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkshopCourseFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'workshop_id' => Workshop::factory(),
            'uploaded_by' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'file_path' => 'workshop-courses/'.$this->faker->uuid().'.pdf',
            'original_filename' => $this->faker->word().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size_bytes' => $this->faker->numberBetween(102400, 10485760),
            'is_published' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }
}
