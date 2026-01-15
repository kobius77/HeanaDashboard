<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyLog>
 */
class DailyLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'log_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'egg_count' => $this->faker->numberBetween(0, 12),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
