<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BiayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Biaya::create([
            'nama_biaya' => 'SPP',
            'nominal' => 50000,
            'nama_nominal' => 'SPP',
            'tahun' => 2022,
            'bulan' => 'Januari',
            'level' => 'X',
        ]);
        \App\Models\Biaya::create([
            'nama_biaya' => 'Uang Bangunan',
            'nominal' => 200000,
            'nama_nominal' => 'Uang Bangunan',
            'tahun' => 2022,
            'bulan' => 'Januari',
            'level' => 'X',
        ]);
        \App\Models\Biaya::create([
            'nama_biaya' => 'Uang Kegiatan',
            'nominal' => 100000,
            'nama_nominal' => 'Uang Kegiatan',
            'tahun' => 2022,
            'bulan' => 'Januari',
            'level' => 'X',
        ]);
        \App\Models\Biaya::create([
            'nama_biaya' => 'Uang Lain-Lain',
            'nominal' => 50000,
            'nama_nominal' => 'Uang Lain-Lain',
            'tahun' => 2022,
            'bulan' => 'Januari',
            'level' => 'X',
        ]);
    }
}
