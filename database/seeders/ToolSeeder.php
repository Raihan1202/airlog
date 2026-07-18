<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tool;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            'Laptop',
            'Multimeter',
            'Oscilloscope',
            'Soldering Iron',
            'Logic Analyzer',
            'Power Supply',
            'Function Generator',
            'Screwdriver Set',
            'Wire Strippers',
            'Breadboard',
            'Arduino',
            'Raspberry Pi',
            'Network Cable Tester',
            'Digital Caliper',
            'Heat Gun',
            'Anti-static Wrist Strap',
            'PCB Holder',
            'Desoldering Pump',
            'Cable Ties',
            'Electrical Tape'
        ];

        foreach ($tools as $tool) {
            Tool::firstOrCreate(['name' => $tool]);
        }
    }
}
