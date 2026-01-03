<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');

            $table->string('title');              
            $table->text('description');          
            $table->string('governorate');       
            $table->string('city');               
            $table->decimal('price_per_night', 10, 2); 

            $table->json('features')->nullable(); 
            $table->enum('status', ['available', 'unavailable'])->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};