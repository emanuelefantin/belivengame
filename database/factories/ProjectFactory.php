<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $generation_progress = $this->faker->numberBetween(0, 10000);
        $development_progress = ($generation_progress == 10000) ? $this->faker->numberBetween(0, 10000) : 0;
        return [
            'game_id' => null,
            'name' => $this->faker->company() . ' Project',
            'budget' => $this->faker->randomFloat(2, 1000, 20000),
            'complexity' => $this->faker->numberBetween(1, 10000),
            'seller_id' => $this->faker->numberBetween(1, 3),
            'generation_progress' => $generation_progress,
            'generation_started_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'generation_completed_at' => ($generation_progress == 10000) ? $this->faker->date() : null,
            'developer_id' => $this->faker->numberBetween(1, 3),
            'development_progress' =>  $development_progress,
            'development_started_at' => ($generation_progress == 10000) ? $this->faker->optional()->dateTimeBetween('-30 days', 'now') : null,
            'development_completed_at' => ($development_progress == 10000) ? $this->faker->date() : null,
            'game_id' => null, // This will be set when creating the game
        ];
    }
}

