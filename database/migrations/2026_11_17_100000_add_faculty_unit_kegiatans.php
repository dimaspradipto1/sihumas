<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('unit_kegiatans')) {
            return;
        }

        $faculties = [
            ['kategori' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)', 'unit_kegiatan' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)'],
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)'],
            ['kategori' => 'FAKULTAS ILMU KESEHATAN (FIKes)', 'unit_kegiatan' => 'FAKULTAS ILMU KESEHATAN (FIKes)'],
        ];

        $now = now();
        foreach ($faculties as $faculty) {
            $exists = DB::table('unit_kegiatans')
                ->whereRaw('LOWER(unit_kegiatan) = ?', [strtolower($faculty['unit_kegiatan'])])
                ->first();

            if ($exists) {
                DB::table('unit_kegiatans')
                    ->where('id', $exists->id)
                    ->update([
                        'kategori' => $faculty['kategori'],
                        'unit_kegiatan' => $faculty['unit_kegiatan'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('unit_kegiatans')->insert([
                    'kategori' => $faculty['kategori'],
                    'unit_kegiatan' => $faculty['unit_kegiatan'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('unit_kegiatans')) {
            return;
        }

        DB::table('unit_kegiatans')->whereIn('unit_kegiatan', [
            'FAKULTAS EKONOMI DAN BISNIS (FEB)',
            'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
            'FAKULTAS ILMU KESEHATAN (FIKes)',
        ])->delete();
    }
};
