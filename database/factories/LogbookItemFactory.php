<?php

namespace Database\Factories;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogbookItem>
 */
class LogbookItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logbook_id' => Logbook::factory(),
            'catatan' => $this->faker->sentence(10),
            'tanggal_kegiatan' => now()->toDateString(),
            'tools' => $this->faker->word(),
            'teknisi' => User::factory(),
            'mulai' => now()->format('Y-m-d H:i:s'),
            'selesai' => now()->addHours(1)->format('Y-m-d H:i:s'),
        ];
    }
}