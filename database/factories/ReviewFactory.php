<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional(0.6)->sentence(8),
            'created_at' => $this->faker->dateTimeBetween('-120 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
