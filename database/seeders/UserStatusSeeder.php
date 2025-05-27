<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserStatus; // IMPORTANT: Make sure this line is present

class UserStatusSeeder extends Seeder // <-- Make sure this class name is 'UserStatusSeeder'
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active'],
            ['name' => 'Inactive'],
            ['name' => 'Pending'],
        ];

        foreach ($statuses as $status) {
            UserStatus::create($status);
        }
    }
}