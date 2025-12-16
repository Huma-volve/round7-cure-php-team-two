<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('settings')->insert([
            'phone' => '0107667558',
            'email' => 'doctor@gmail.com',
            'logo' => 'logo.png',
            'rate' => 20,
        ]);
    }
}
