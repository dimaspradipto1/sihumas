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
                'unit_kegiatan' => 'LPTI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Feb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Fst',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_kegiatan' => 'Fikes',
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
