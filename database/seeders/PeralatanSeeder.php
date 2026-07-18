<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeralatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        DB::table('alat')->truncate(); // Kosongkan tabel sebelum insert (opsional)

        $data = [
            'VCS 3020X',
            'Radio Link 720 - Tower',
            'Voice Recorder (FREQUENTIS)',
            'VHF - ADC Secondary Tower South',
            'VHF - ACC Primary UJKT',
            'VHF - APP Secondary Lower East (LE)',
            'VHF - APP Primary Terminal South (TS)',
            'VHF - APP Secondary Terminal South (TS)',
            'VHF - APP Primary Departure West (DW)',
            'VHF - APP Secondary Departure West (DW)',
            'VHF - APP Primary Departure East (DE)',
            'VHF - APP Secondary Departure East (DE)',
            'VHF - APP Primary Arrival East (AE)',
            'GP - Primary',
            'VHF - SECONDARY ARRIVAL EAST',
            'VHF - ACC Primary UPLB',
            'VHF - ADC Primary Tower South',
            'VHF - ACC Primary UBND',
            'HF-RDARA',
            'ATIS',
            'VCS (Frequentis)',
            'Voice Recorder (VERSADIAL)',
            'VHF - Emergency',
            'VHF - ADC Back Up Tower South',
            'VHF - Ground Control South Back Up',
            'VHF - ADC Back Up Tower North',
            'VHF - Ground Control North Back Up',
            'HF-MWARA FSS I',
            'VHF - APP Secondary Lower North (LN)',
            'VHF - Ground Control South Primary',
            'VHF - Ground Control South Secondary',
            'VHF - Ground Control 3',
            'VHF - ADC Primary Tower North',
            'VHF - ADC Secondary Tower North',
            'VHF - Ground Control North Primary',
            'VHF - Ground Control North Secondary',
            'VHF - Delivery North Primary',
            'VHF - Delivery North Secondary',
            'VHF - APP Secondary Terminal West (TW)',
            'VHF - APP Primary Terminal East (TE)',
            'VHF - APP Secondary Terminal East (TE)',
            'VHF - APP Primary Lower Center (LC)',
            'VHF - APP Secondary Lower Center (LC)',
            'VHF - APP Primary Arrival North (AN)',
            'VHF - APP Secondary Arrival North (AN)',
            'VHF - APP Primary Lower North (LN)',
            'VHF - APP Primary Lower East (LE)',
            'VHF - APP Primary Terminal West (TW)',
            'EJAATS',
            'JAATS',
            'PRISMA',
            'ARTAS'
        ];

        foreach ($data as $index => $item) {
            DB::table('alat')->insert([
                'name' => $item,
                'created_at' => $now,
                'updated_at' => null,
            ]);
        }
    }
}
