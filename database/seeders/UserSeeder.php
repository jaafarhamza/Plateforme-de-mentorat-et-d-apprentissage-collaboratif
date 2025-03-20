<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $mentorRole = Role::firstOrCreate(['name' => 'mentor']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('AdminPassword123!'),
            'user_type' => 'admin'
        ]);
        $admin->assignRole($adminRole);

        // Create Mentor User
        $mentor = User::create([
            'name' => 'John Mentor',
            'email' => 'mentor@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('MentorPassword123!'),
            'user_type' => 'mentor'
        ]);
        $mentor->assignRole($mentorRole);
        
        // Create Student User
        $student = User::create([
            'name' => 'Jane Student',
            'email' => 'student@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('StudentPassword123!'),
            'user_type' => 'student'
        ]);
        $student->assignRole($studentRole);

        User::factory()->count(10)->create();
    }
}