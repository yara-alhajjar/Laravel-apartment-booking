<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('phone')->unique();
            $table->string('password');

            $table->enum('role', ['tenant', 'landlord', 'admin'])->default('tenant');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');

        
            $table->string('personal_image');
            $table->string('identity_image');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};