<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call(CategorySeeder::class);

        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $mentorRole = Role::create(['name' => 'mentor']);
        $studentRole = Role::create(['name' => 'student']);

        // Create Permissions for Courses
        $coursePermissions = [
            'create course',
            'read course',
            'update course',
            'delete course'
        ];

        // Create Permissions for Categories
        $categoryPermissions = [
            'create category',
            'read category',
            'update category',
            'delete category'
        ];

        // Create Permissions for Tags
        $tagPermissions = [
            'create tag',
            'read tag',
            'update tag',
            'delete tag'
        ];

        // Create all permissions
        $allPermissions = array_merge(
            $coursePermissions,
            $categoryPermissions,
            $tagPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // Assign Permissions to Roles
        $adminRole->syncPermissions($allPermissions);

        $mentorRole->syncPermissions([
            'create course',
            'read course',
            'update course'
        ]);

        $studentRole->syncPermissions([
            'read course'
        ]);
    }
}
