<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'General Medicine',
            'Dermatology',
            'Cardiology',
            'Orthopedics',
            'Pediatrics',
            'Gynecology',
            'ENT',
            'Neurology',
            'Psychiatry',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($names),
        ];
    }
}
