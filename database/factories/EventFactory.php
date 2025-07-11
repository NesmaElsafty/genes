<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'event_type_id' => EventType::inRandomOrder()->first()?->id ?? EventType::factory(),
            'animal_id' => Animal::inRandomOrder()->first()?->id ?? Animal::factory(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
} 