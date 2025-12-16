<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class adminSeeder extends Seeder
{
    public function run(): void
    {
        // افراغ جداول الصلاحيات (احذر إنك على بيئة تطوير)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Roles الأساسية
        $roles = [
            'admin' => Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']),
            'doctor' => Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']),
            'patient' => Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']),
        ];

        // Permissions مفصّلة حسب controllers / methods
        $perms = [

            // ---- Admin area ----
            'admin' => [
                'dashboard' => ['view', 'export'],
                'users' => ['index', 'view', 'create', 'edit', 'delete', 'impersonate'],
                'roles' => ['index', 'view', 'create', 'edit', 'delete', 'assign'],
                'doctors' => ['index', 'view', 'approve', 'suspend', 'edit', 'delete'],
                'patients' => ['index', 'view', 'edit', 'delete'],
                'bookings' => ['index', 'view', 'update', 'cancel', 'refund', 'assign', 'export'],
                'payments' => ['index', 'view', 'refund', 'export'],
                'notifications' => ['view', 'broadcast', 'send', 'delete'],
                'reports' => ['view', 'export'],
                'settings' => ['view', 'update'],
                'specialties' => ['index', 'create', 'edit', 'delete'],
                'clinics' => ['index', 'create', 'edit', 'delete'],
            ],

            // ---- Doctor area ----
            'doctor' => [
                'dashboard' => ['view'],
                'patients' => ['index', 'show', 'notes', 'export'],
                'bookings' => ['index', 'show', 'update', 'cancel', 'reschedule', 'status'],
                'reviews' => ['index', 'reply', 'delete'],
                'chats' => ['index', 'view', 'reply', 'close'],
                'payments' => ['index', 'view'],
                'profile' => ['view', 'update'],
                'schedule' => ['view', 'update', 'slots'],
                'notifications' => ['view', 'read'],
            ],

            // ---- Patient area ----
            'patient' => [
                'dashboard' => ['view'],
                'profile' => ['view', 'update'],
                'bookings' => ['create', 'index', 'show', 'cancel', 'reschedule', 'review'],
                'payments' => ['create', 'index', 'view'],
                'favorites' => ['index', 'add', 'remove'],
                'reviews' => ['create', 'index'],
                'chats' => ['create', 'index', 'view'],
                'notifications' => ['view', 'read'],
                'search' => ['doctors', 'specialties'],
            ],

            // ---- API / Public endpoints (optional perms, mapped to roles as needed) ----
            'api' => [
                'home' => ['nearby', 'search'],
                'auth' => ['login', 'register', 'logout', 'forgot-password', 'verify-otp'],
                'doctor' => ['show', 'list'],
            ],
        ];

        // Create permissions and attach to corresponding role
        foreach ($perms as $roleName => $controllers) {
            foreach ($controllers as $controller => $methods) {
                foreach ($methods as $method) {
                    // permission name pattern: {role}.{controller}.{method}
                    $permName = "{$roleName}.{$controller}.{$method}";
                    Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                }
            }
        }

        // Map which permissions each role actually receives.
        // Strategy:
        // - admin: gets all 'admin.*' AND many management permissions across other areas
        // - doctor: gets doctor.* + relevant patient/bookings permissions
        // - patient: gets patient.* + api.auth/doctor.home minimal
        $allPermissions = Permission::all()->pluck('name')->toArray();

        // Admin: give everything that starts with 'admin.' plus management rights
        $adminPerms = array_filter($allPermissions, fn($p) => Str::startsWith($p, 'admin.'));
        // also give admin some cross perms (doctors/patients/bookings/payments)
        $crossPrefixes = ['doctors.', 'patients.', 'bookings.', 'payments.', 'specialties.', 'clinics.', 'notifications.', 'reports.', 'settings.'];
        foreach ($allPermissions as $p) {
            foreach ($crossPrefixes as $pref) {
                if (Str::contains($p, ".{$pref}") || Str::startsWith($p, "admin.")) {
                    // handled by adminPerms already
                }
            }
        }
        // Simpler: give admin all permissions
        $roles['admin']->givePermissionTo(Permission::all());

        // Doctor: give only permissions that start with 'doctor.' OR are necessary (e.g., patient.bookings interaction)
        $doctorPermNames = array_filter($allPermissions, function ($p) {
            return Str::startsWith($p, 'doctor.') ||
                Str::startsWith($p, 'patient.bookings.') === false && // safety
                Str::startsWith($p, 'patient.') === false ? false : true;
        });

        // More explicitly: grant doctor.* and booking/payment/notifications related to doctor
        $doctorGrant = [];
        foreach ($allPermissions as $p) {
            if (Str::startsWith($p, 'doctor.')) {
                $doctorGrant[] = $p;
            }
            if (Str::startsWith($p, 'bookings.') || Str::startsWith($p, 'payments.') || Str::startsWith($p, 'notifications.') || Str::startsWith($p, 'patients.')) {
                // give only doctor-relevant methods (index, show, update, cancel, reschedule, view, read)
                if (preg_match('/\.(index|show|update|cancel|reschedule|view|read|status|export)$/', $p)) {
                    $doctorGrant[] = $p;
                }
            }
        }
        $roles['doctor']->givePermissionTo(array_unique($doctorGrant));

        // Patient: give patient.* + basic API/auth + favorites/reviews/bookings create/index/view/cancel
        $patientGrant = [];
        foreach ($allPermissions as $p) {
            if (Str::startsWith($p, 'patient.')) {
                $patientGrant[] = $p;
            }
            if (Str::startsWith($p, 'api.') && preg_match('/\.(auth|doctor|home)\./', $p)) {
                // keep minimal api perms if exist
                $patientGrant[] = $p;
            }
            if (Str::startsWith($p, 'bookings.') || Str::startsWith($p, 'favorites.') || Str::startsWith($p, 'reviews.') || Str::startsWith($p, 'payments.') || Str::startsWith($p, 'chats.')) {
                if (preg_match('/\.(create|index|show|cancel|reschedule|add|remove|view|read|review)$/', $p)) {
                    $patientGrant[] = $p;
                }
            }
        }
        $roles['patient']->givePermissionTo(array_unique($patientGrant));

        // Create example users and attach roles
        $usersData = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone_number' => '01000000001',
                'password' => bcrypt('password#123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Dr. Ahmed',
                'email' => 'doctor@example.com',
                'phone_number' => '01000000002',
                'password' => bcrypt('password#123'),
                'role' => 'doctor',
            ],
            [
                'name' => 'Patient User',
                'email' => 'patient@example.com',
                'phone_number' => '01000000003',
                'password' => bcrypt('password#123'),
                'role' => 'patient',
            ],
        ];

        foreach ($usersData as $ud) {
            $user = User::firstOrCreate(
                ['email' => $ud['email']],
                [
                    'name' => $ud['name'],
                    'phone_number' => $ud['phone_number'],
                    'password' => $ud['password'],
                ]
            );
            $user->syncRoles([$ud['role']]);
        }

        $this->command->info('✅ Roles and expanded permissions seeded — follow naming: {role}.{controller}.{method}');
    }
}
