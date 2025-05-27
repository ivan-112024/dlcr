<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // IMPORTANT: Ensure this line is present
use Illuminate\Support\Facades\Hash; // IMPORTANT: Ensure this line is present

class UserSeeder extends Seeder // <-- Make sure this class name is 'UserSeeder'
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'user_status_id' => 1,
            ],
            // Add any other users you want to seed here
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}