<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialty;


class SpecialtiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $specialties = [
            'General Medicine',
            'Dermatology',
            'Cardiology',
            'Orthopedics',
            'Pediatrics',
            'Gynecology',
            'ENT',
            'Neurology',
            'Psychiatry',
            'Endocrinology',
        ];

        foreach ($specialties as $name) {
            Specialty::firstOrCreate(['name' => $name]);
        }
    }
}
