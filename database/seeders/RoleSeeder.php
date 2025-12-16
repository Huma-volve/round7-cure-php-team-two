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
    $roles = ['admin','doctor','patient','helper'];

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
        'make booking',
        'create_helper',
        'edit_helper',
        'delete_helper',
        'reschedule booking',
        'cancel booking',
        'view bookings',
        'show-booking-details',
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
    $helper=Role::where('name','helper')->first();
    $helper->givePermissionTo([  'delete users', 'edit users', 'create users',]);
    // Assign Permissions to Patient and Doctor
    $patient=Role::where('name','patient')->first();
    $patient->givePermissionTo(['view bookings','make booking','reschedule booking','cancel booking']);
    $doctor=Role::where('name','doctor')->first();
    $doctor->givePermissionTo(['view bookings','cancel booking','show-booking-details']);
}
}
