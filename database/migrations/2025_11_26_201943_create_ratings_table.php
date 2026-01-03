<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating'); 
            $table->text('comment')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }

};
