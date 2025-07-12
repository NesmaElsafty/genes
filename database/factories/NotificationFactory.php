<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['alert', 'notify']),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'role' => $this->faker->randomElement(['admin', 'user', 'manager']),
            'status' => $this->faker->randomElement(['pending', 'sent', 'unsent']),
        ];
    }
} 