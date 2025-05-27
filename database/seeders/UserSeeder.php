<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        

DB::table('user_statuses')->insert([
    ['status_name' => 'Active'],
    ['status_name' => 'Inactive'],
]);

    }
}
