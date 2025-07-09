<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnimalType;

class AnimalTypeSeeder extends Seeder
{
    public function run(): void
    {    
        $animalTypes = [
            'Cattle',
            'Sheep',
            'Goat',
            'Pig',
            'Chicken',
        ];
        foreach ($animalTypes as $animalType) { 
            AnimalType::create([
                'name' => $animalType,
                'description' => 'test description',
            ]);
        }
    }
}
