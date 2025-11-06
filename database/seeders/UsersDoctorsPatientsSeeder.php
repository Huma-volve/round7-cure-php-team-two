<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;

class UsersDoctorsPatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        $specialtyIds = Specialty::pluck('id')->toArray();
        $numDoctors = 20;

        for ($i = 0; $i < $numDoctors; $i++) {
            $user = User::factory()->create([
                // doctors' clinic location should be near Cairo with small offset
                'latitude' => fake()->latitude(29.9, 30.1),
                'longitude' => fake()->longitude(31.0, 31.5),
            ]);

            Doctor::factory()->create([
                'user_id' => $user->id,
                'specialty_id' => $this->fakerOrRandom($specialtyIds),
            ]);
        }

        // 3) Create patients: each patient gets a user and a patient record
        $numPatients = 50;
        for ($i = 0; $i < $numPatients; $i++) {
            $user = User::factory()->create();
            Patient::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
    private function fakerOrRandom(array $arr)
    {
        // To avoid using $this->faker inside seeder, pick random
        return $arr[array_rand($arr)];
    }
}
