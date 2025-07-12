<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use Illuminate\Support\Facades\Storage;
use App\Models\Farm;
use App\Models\AnimalType;
use App\Models\AnimalBreed;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $farms = Farm::all();
        $animalTypeIds = AnimalType::pluck('id')->toArray();
        $breedIds = AnimalBreed::pluck('id')->toArray();
        $genders = ['male', 'female'];
        $start = strtotime(date('Y-01-01'));
        $end = time();
        foreach ($farms as $farm) {
            $count = rand(20, 50); // Generate 20-50 animals per farm
            for ($i = 0; $i < $count; $i++) {
                $createdAt = date('Y-m-d H:i:s', rand($start, $end));
                $birthDate = date('Y-m-d', rand($start, $end));
                \App\Models\Animal::create([
                    'animal_id' => uniqid('AN'),
                    'birth_date' => $birthDate,
                    'sir_id' => null,
                    'dam_id' => null,
                    'gender' => $genders[array_rand($genders)],
                    'farm_id' => $farm->id,
                    'animal_type_id' => $animalTypeIds[array_rand($animalTypeIds)],
                    'breed_id' => $breedIds[array_rand($breedIds)],
                    'event_type_id' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
} 