<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // Ensure DB facade is imported

return new class extends Migration {
    public function up(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Driver', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('user_statuses')->insert([
            ['id' => 1, 'name' => 'Active', 'created_at' => now(), 'updated_at' => now()], // <-- CHANGE 'status_name' to 'name' here
            ['id' => 2, 'name' => 'Inactive', 'created_at' => now(), 'updated_at' => now()], // <-- CHANGE 'status_name' to 'name' here
        ]);
    }

    public function down(): void
    {
        DB::table('roles')->whereIn('id', [1, 2, 3])->delete();
        DB::table('user_statuses')->whereIn('id', [1, 2])->delete();
    }
};