<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'doctor', 'patient'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);

        }
        $admin = Role::where('name', 'admin')->first();

        $admin->givePermissionTo([
            'delete users',
            'edit users',
            'create users',
        ]);

    }
}
