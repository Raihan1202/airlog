<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['no' => 1, 'name' => 'Manager'],
            ['no' => 2, 'name' => 'Supervisor'],
            ['no' => 3, 'name' => 'Teknisi Senior'],
            ['no' => 4, 'name' => 'Teknisi'],
            ['no' => 5, 'name' => 'Teknisi Junior'],
            ['no' => 6, 'name' => 'Admin'],
            ['no' => 7, 'name' => 'Staff'],
            ['no' => 8, 'name' => 'Operator'],
            ['no' => 9, 'name' => 'Maintenance'],
            ['no' => 10, 'name' => 'Quality Control'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
