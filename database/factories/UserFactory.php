<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassInfo;
use App\Models\Unit;

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

        $classList = ClassInfo::orderBy('school_year', 'desc')->limit(200)->get();
        $classArray = [];
        if ($classList) {
            foreach ($classList as $class) {
                array_push($classArray, $class['id']);
            }
        }
        $unitList = Unit::all();
        $unitArray = [];
        if ($unitList) {
            foreach ($unitList as $unit) {
                array_push($unitArray, $unit['id']);
            }
        }

        return [
            'fullname' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '123456',
            'class_id' => fake()->randomElement($classArray),
            'unit_id' => fake()->randomElement($unitArray),
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