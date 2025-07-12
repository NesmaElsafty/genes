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
        $animals = Animal::all();
        foreach ($animals as $animal) {
            \App\Models\AnimalView::factory()->count(rand(1, 3))->create([
                'animal_id' => $animal->id,
            ]);
        }
    }
} 