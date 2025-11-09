<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'google_id' => null,
            'password' => static::$password ??= Hash::make('Password#123'),
            'phone_number' => $this->faker->unique()->numerify('01#########'),
            'profile_photo' => null,
            'latitude' => $this->faker->latitude(29.8, 30.2), // around Cairo
            'longitude' => $this->faker->longitude(31.0, 31.6),
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'otp_code' => null,
            'otp_expires_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
