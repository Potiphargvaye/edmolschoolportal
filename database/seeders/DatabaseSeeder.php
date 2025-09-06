<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run()
{
    $this->call([
        GradeSeeder::class,
    ]);
}

public function run()
{
    // ... other seeders ...
    $this->call(SubjectSeeder::class);
}
    
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'registration_id' => '12345/1964',
            'role' => 'admin',
            'email' => 'edmolschoolportal@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('edmol123'), // You can change this
            'remember_token' => Str::random(10),
        ]);
    }
}
