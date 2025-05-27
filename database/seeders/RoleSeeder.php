<?php

namespace Database\Seeders; // <-- Make sure this namespace is correct

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // <-- IMPORTANT: Make sure you import your Role model if you have one

class RoleSeeder extends Seeder // <-- Make sure the class name matches the filename
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Seed some roles
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Staff'],
            ['name' => 'Customer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}