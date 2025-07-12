<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\EventType;
use App\Models\AnimalBreed;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            // 'animal_id' => $this->faker->unique()->uuid,
            // 'sir_id' => $this->faker->optional()->uuid,
            // 'dam_id' => $this->faker->optional()->uuid,
            // 'gender' => $this->faker->randomElement(['male', 'female']),
            // 'farm_id' => Farm::inRandomOrder()->first()?->id ?? Farm::factory(),
            // 'event_type_id' => EventType::inRandomOrder()->first()?->id ?? EventType::factory(),
            // 'breed_id' => AnimalBreed::inRandomOrder()->first()?->id ?? AnimalBreed::factory(),
            // 'animal_type_id' => AnimalType::inRandomOrder()->first()?->id ?? AnimalType::factory(),
            // 'birth_date' => $this->faker->date(),
            // 'created_at' => $this->faker->dateTime(),
            // 'updated_at' => $this->faker->dateTime(),
        ];
    }
} 