<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
{
    $permissions = [

        // Students
        'view students',
        'view student details',
        'edit students',
        'delete students',
        'manage students', // status changes, lifecycle control

        // Fees
        'view fees',
        'manage fees',
        'generate receipts',

        // Academic
        'assign grades',

        // Users
        'manage users',

        // Announcements
        'manage announcements',

        // Reports (future)
        'view academic reports',
        'manage attendance',

        // System
        'access admin dashboard',
    ];

    foreach ($permissions as $permission) {
        \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => $permission
        ]);
    }
}
}