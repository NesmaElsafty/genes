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
            $table->date('birth_date')->nullable();
            $table->string('sir_id')->nullable();
            $table->string('dam_id')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->foreignId('farm_id')->nullable()->constrained('farms')->onDelete('cascade');
            $table->foreignId('animal_type_id')->nullable()->constrained('animal_types')->onDelete('cascade');
            $table->foreignId('breed_id')->nullable()->constrained('animal_breeds')->onDelete('cascade');
            $table->foreignId('event_type_id')->nullable()->constrained('event_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
}; 