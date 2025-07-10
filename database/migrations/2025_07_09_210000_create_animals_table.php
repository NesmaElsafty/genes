<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('animal_id')->unique();
            $table->string('sir_id')->nullable();
            $table->string('dam_id')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('event_types')->onDelete('cascade')->nullable();
            $table->foreignId('breed_id')->constrained('animal_breeds')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
}; 