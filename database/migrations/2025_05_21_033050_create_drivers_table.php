<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('drivers', function (Blueprint $table) {
        $table->id(); // id bigint unsigned auto-increment primary key
        $table->string('first_name');
        $table->string('last_name');
        $table->string('license_number')->unique();
        $table->string('phone')->nullable();
        $table->timestamps(); // created_at and updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::dropIfExists('drivers');
}

};
