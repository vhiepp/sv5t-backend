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
        $class = [
            'da21tta',
            'da21ttb',
            'da21ttc',
            'da21da',
            'da21db',
            'da21dc',
            'da20tta',
            'da20ttb',
        ];
        return [
            'fullname' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '123456', // password
            'role' => 'user',
            'class' => fake()->randomElement($class),
            'avatar' => env('APP_URL') . '/assets/images/avatars/avatar_' . rand(1, 24) . '.jpg',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}