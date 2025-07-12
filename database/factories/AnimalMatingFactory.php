<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\AnimalMating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnimalMating>
 */
class AnimalMatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get male animals for sir_id
        $maleAnimals = Animal::where('gender', 'male')->pluck('id')->toArray();
        $sirId = !empty($maleAnimals) ? $this->faker->randomElement($maleAnimals) : Animal::factory()->create(['gender' => 'male'])->id;
        
        // Get female animals for dam_id
        $femaleAnimals = Animal::where('gender', 'female')->pluck('id')->toArray();
        $damId = !empty($femaleAnimals) ? $this->faker->randomElement($femaleAnimals) : Animal::factory()->create(['gender' => 'female'])->id;
        
        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'sir_id' => $sirId,
            'dam_id' => $damId,
            'mating_type' => $this->faker->randomElement(['artificial_ins', 'natural_mating']),
        ];
    }
} 