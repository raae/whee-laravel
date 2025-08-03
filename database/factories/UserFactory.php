<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'airtable_id' => fake()->uuid(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's phone should be verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the model's phone should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone_verified_at' => null,
        ]);
    }
}
