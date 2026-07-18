<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'link' => '#',
        ];
    }
}