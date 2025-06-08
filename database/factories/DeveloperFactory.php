<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $xp = $this->faker->numberBetween(500, 10000);
        $salary = $xp / 2;
        return [
            'name' => $this->faker->firstName().' ' . $this->faker->lastName(),
            'xp' => $xp,
            'salary' => $salary,
            'hired' => $this->faker->boolean(),
            'hired_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'fired_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'game_id' => null, // This will be set when creating the game
        ];
    }
}
