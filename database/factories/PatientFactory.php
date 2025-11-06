<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
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
            'birth_date' => $this->faker->optional()->date('Y-m-d', '2005-01-01'),
            'medical_history' => $this->faker->optional(0.6)->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
