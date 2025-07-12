<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnimalBreed;

class AnimalBreedSeeder extends Seeder
{
    public function run(): void
    {
        // create 10 animal breeds without factory get example from animal type
        $animalBreeds = [
            'هولشتاين',
            'أنغوس',
            'هيرفورد',
            'جيرسي',
            'براهمان',
        ];
        foreach ($animalBreeds as $animalBreed) {
            AnimalBreed::create([
                'name' => $animalBreed,
                'description' => 'وصف تجريبي',
            ]);
        }
    }
}
