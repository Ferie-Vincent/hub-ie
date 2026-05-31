<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name'         => fake()->firstName(),
            'last_name'          => fake()->lastName(),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => static::$password ??= Hash::make('password'),
            'phone'              => '+225 0' . fake()->numerify('#########'),
            'birth_date'         => fake()->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'gender'             => fake()->randomElement(Gender::cases())->value,
            'nationality'        => 'Ivoirienne',
            'city'               => fake()->randomElement(['Abidjan', 'Bouaké', 'Daloa', 'Yamoussoukro', 'San-Pédro']),
            'country'            => 'Côte d\'Ivoire',
            'is_active'          => true,
            'remember_token'     => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function candidate(): static
    {
        return $this->state(fn () => [])->afterCreating(function (User $user) {
            $user->assignRole('candidate');
        });
    }
}
