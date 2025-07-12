<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\AnimalView;
use Illuminate\Database\Seeder;

class AnimalViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing animals or create some if none exist
        // $animals = Animal::all();
        
        // if ($animals->isEmpty()) {
        //     $animals = Animal::factory(10)->create();
        // }

        // // Create animal views for each animal
        // foreach ($animals as $animal) {
        //     AnimalView::factory()->count(rand(1, 3))->create([
        //         'animal_id' => $animal->id,
        //     ]);
        // }
    }
} 