<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitKegiatan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKegiatan = [
            [
                'unit_kegiatan' => 'Universitas Ibnu Sina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Prodi Feb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Prodi Fst',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Prodi Fikes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Rektorat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($unitKegiatan as $unitKegiatan) {
            DB::table('unit_kegiatans')->insert($unitKegiatan);
        }
    }
}
