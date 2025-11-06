<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Favorite;

class FavoritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $doctors = Doctor::all()->pluck('id')->toArray();

        // For each user, add 0..6 favorites randomly
        foreach ($users as $user) {
            $count = rand(0, 6);
            if ($count === 0)
                continue;
            $chosen = (array) array_rand(array_flip($doctors), min($count, count($doctors)));

            foreach ($chosen as $docId) {
                // prevent duplicates due to random
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'favoritable_type' => \App\Models\Doctor::class,
                    'favoritable_id' => $docId,
                ]);
            }
        }
    }
}
