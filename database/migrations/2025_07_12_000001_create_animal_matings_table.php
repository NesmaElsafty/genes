<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animal_matings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('sir_id')->nullable()->constrained('animals')->onDelete('cascade');
            $table->foreignId('dam_id')->nullable()->constrained('animals')->onDelete('cascade');
            $table->enum('mating_type', ['artificial_ins', 'natural_mating']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_matings');
    }
}; 