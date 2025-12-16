<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slots = [];
for ($i = 0; $i < rand(3, 8); $i++) {
    $dt = $this->faker->dateTimeBetween('now', '+14 days');
    $hour = $this->faker->numberBetween(9, 17);
    $dt->setTime($hour, [0, 30][array_rand([0, 1])], 0);
    $slots[] = $dt->format('Y-m-d\TH:i:00');
}

return [
    'license_number' => 'LIC' . $this->faker->unique()->numerify('#######'),
    'session_price' => $this->faker->randomFloat(2, 50, 800),
    'available_slots' => json_encode($slots), // صح
    'created_at' => now(),
    'updated_at' => now(),
];

    }
}
