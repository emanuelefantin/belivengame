<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{

    protected $casts = [
        'date_start' => 'datetime',
        'date_current' => 'datetime',
        'cash_start' => 'decimal:2',
        'cash_current' => 'decimal:2',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'slug' => $this->faker->unique()->slug(),
            'date_start' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'date_current' => $this->faker->dateTimeBetween('now', '+1 year'),
            'cash_start' => 5000,
            'cash_current' => $this->faker->randomFloat(2, 1000, 10000),
            'user_id' => null,
        ];
    }
}
