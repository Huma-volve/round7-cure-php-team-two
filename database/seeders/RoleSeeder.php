<?php
namespace database\seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
public function run()
{
    // 1️⃣ Create Roles
    $roles = ['admin','doctor','patient'];

    foreach ($roles as $role) {
        Role::firstOrCreate([
            'name' => $role,
            'guard_name' => 'web',
        ]);
    }

    // 2️⃣ Create Permissions
    $permissions = [
        'delete users',
        'edit users',
        'create users',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]);
    }

    // 3️⃣ Assign Permissions to Admin
    $admin = Role::where('name', 'admin')->first();
    $admin->givePermissionTo($permissions);
}
}
