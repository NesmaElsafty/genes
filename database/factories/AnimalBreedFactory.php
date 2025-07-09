<?php

namespace Database\Factories;

use App\Models\AnimalBreed;
use App\Models\AnimalType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalBreedFactory extends Factory
{
    protected $model = AnimalBreed::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' Breed',
            'description' => $this->faker->sentence,
            'animal_type_id' => AnimalType::inRandomOrder()->first()->id ?? AnimalType::factory(),
        ];
    }
}
