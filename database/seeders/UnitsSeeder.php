<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')->insert([
            ['nama' => 'FDPS-RDPS', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'AMSS-ADPS', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'SURVEILLANCE', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'NAVIGATION', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'RADIO KOMUNIKASI', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'SSJJ', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'LISTRIK', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'BANGUNAN', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
