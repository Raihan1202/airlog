<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil PositionSeeder untuk menambah data posisi
        // $this->call([
        //     PositionSeeder::class, // Menambahkan PositionSeeder
        // ]);
        // Memanggil UnitsSeeder untuk menambah data unit
        $this->call([
            UnitsSeeder::class, // Menambahkan UnitsSeeder
        ]);
        // Memanggil UserSeeder untuk menambah data user
        $this->call([
            UserSeeder::class, // Menambahkan UserSeeder
        ]);
		//Memanggil PeralatanSeeder
		$this->call([
			PeralatanSeeder::class,
		]);
		$this->call([
			PosisiSeeder::class,
		]);
    }
}
