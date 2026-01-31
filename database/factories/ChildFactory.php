<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birth_date' => $this->faker->dateTimeBetween('-5 years', '-6 months'),
            'birth_weight' => $this->faker->randomFloat(1, 2.5, 4.5),
            'birth_height' => $this->faker->randomFloat(1, 45, 55),
        ];
    }

    /**
     * Indicate that the child is male.
     */
    public function male(): static
    {
        return $this->state(fn(array $attributes) => [
            'gender' => 'male',
        ]);
    }

    /**
     * Indicate that the child is female.
     */
    public function female(): static
    {
        return $this->state(fn(array $attributes) => [
            'gender' => 'female',
        ]);
    }

    /**
     * Create a newborn (0-3 months).
     */
    public function newborn(): static
    {
        return $this->state(fn(array $attributes) => [
            'birth_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Create a toddler (12-36 months).
     */
    public function toddler(): static
    {
        return $this->state(fn(array $attributes) => [
            'birth_date' => $this->faker->dateTimeBetween('-36 months', '-12 months'),
        ]);
    }
}
