<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            'Karyawan',
            'Junior Manager',
            'Manager',
            'Deputy General Manager',
            'General Manager',
        ];

        foreach ($positions as $index => $name) {
            DB::table('positions')->insert([
				'no' => $index + 1,
                'name'       => $name,
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }
    }
}
