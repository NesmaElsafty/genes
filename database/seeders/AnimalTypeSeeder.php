<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnimalType;

class AnimalTypeSeeder extends Seeder
{
    public function run(): void
    {    
        $animalTypes = [
            'أبقار', // Cattle
            'أغنام', // Sheep
            'ماعز',  // Goat
            'خنازير', // Pig
            'دجاج',  // Chicken
        ];
        foreach ($animalTypes as $animalType) { 
            AnimalType::create([
                'name' => $animalType,
                'description' => 'وصف تجريبي',
            ]);
        }
    }
}
