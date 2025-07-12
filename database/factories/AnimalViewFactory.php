<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\AnimalView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnimalView>
 */
class AnimalViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => Animal::factory(),
            'external_chars' => $this->faker->randomElement(['black', 'white', 'brown', 'gray', 'mixed']) . ' color, ' . 
                               $this->faker->randomElement(['small', 'medium', 'large']) . ' size, ' . 
                               $this->faker->numberBetween(50, 500) . 'kg weight, ' . 
                               $this->faker->numberBetween(100, 200) . 'cm height',
            'value' => 'Health score: ' . $this->faker->numberBetween(1, 10) . '/10, ' .
                       'Performance rating: ' . $this->faker->numberBetween(1, 5) . '/5, ' .
                       'Market value: $' . $this->faker->numberBetween(1000, 10000) . ', ' .
                       'Breeding potential: ' . $this->faker->randomElement(['high', 'medium', 'low']),
            'expects' => 'Expected weight: ' . $this->faker->numberBetween(400, 800) . 'kg, ' .
                         'Expected height: ' . $this->faker->numberBetween(150, 250) . 'cm, ' .
                         'Expected health: ' . $this->faker->numberBetween(7, 10) . '/10, ' .
                         'Growth timeline: ' . $this->faker->numberBetween(6, 24) . ' months',
        ];
    }
} 