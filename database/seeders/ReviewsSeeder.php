<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;


class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $doctors = Doctor::all();
        $patientIds = Patient::pluck('id')->toArray();

        foreach ($doctors as $doctor) {
            $num = rand(0, 8); // 0..8 reviews per doctor
            for ($i = 0; $i < $num; $i++) {
                Review::factory()->create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patientIds[array_rand($patientIds)],
                ]);
            }
        }
    }
}
