<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logbook>
 */
class LogbookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(), 
            'date' => now(),
            'judul' => $this->faker->sentence(),
            'shift' => $this->faker->randomElement(['1', '2', '3']),
            'created_by' => User::factory(),
            'is_approved' => 0,
        ];
    }
}