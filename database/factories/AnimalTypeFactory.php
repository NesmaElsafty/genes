<?php

namespace Database\Factories;

use App\Models\AnimalType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnimalType>
 */
class AnimalTypeFactory extends Factory
{
    protected $model = AnimalType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' Type',
            'description' => $this->faker->sentence,
        ];
    }
}
