<?php

namespace Database\Factories;

use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory;

class FarmFactory extends Factory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('2025-01-01', 'now');
        return [
            'name' => $this->faker->company . ' Farm',
            'user_id' => 1, // You may want to set this dynamically in tests
            'city' => $this->faker->city,
            'location' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'capacity' => $this->faker->numberBetween(10, 1000),
            'animal_types' => $this->faker->randomElement(['Cattle', 'Sheep', 'Goat', 'Pig', 'Chicken']),
            'animal_breeds' => $this->faker->randomElement(['Holstein', 'Angus', 'Hereford', 'Jersey', 'Brahman']),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
