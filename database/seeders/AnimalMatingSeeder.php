<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\AnimalMating;
use Illuminate\Database\Seeder;

class AnimalMatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing animals or create some if none exist
        $animals = Animal::all();
        
        if ($animals->isEmpty()) {
            $animals = Animal::factory(10)->create();
        }

        // Create animal matings
        AnimalMating::factory()->count(20)->create();
    }
} 