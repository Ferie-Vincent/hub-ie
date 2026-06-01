<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'workshop_id' => Workshop::factory(),
            'participant_user_id' => User::factory(),
            'trainer_user_id' => null,
            'subject' => $this->faker->optional()->sentence(5),
            'is_closed' => false,
            'last_message_at' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(['is_closed' => true]);
    }
}
