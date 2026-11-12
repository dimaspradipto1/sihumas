<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitKegiatan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // BAAK
            ['kategori' => 'BAAK', 'unit_kegiatan' => 'BAAK - Kabid Akademik'],
            ['kategori' => 'BAAK', 'unit_kegiatan' => 'BAAK - Kabid Adm & Layanan Mahasiswa'],

            // Perpustakaan
            ['kategori' => 'Perpustakaan', 'unit_kegiatan' => 'Kepala Perpustakaan'],

            // BAUK
            ['kategori' => 'BAUK', 'unit_kegiatan' => 'BAUK - Kabid Kepegawaian'],
            ['kategori' => 'BAUK', 'unit_kegiatan' => 'BAUK - Kabid Keuangan'],
            ['kategori' => 'BAUK', 'unit_kegiatan' => 'BAUK - Kepala TU'],
            ['kategori' => 'BAUK', 'unit_kegiatan' => 'BAUK - Kabid Perencanaan & Pengembangan'],
            ['kategori' => 'BAUK', 'unit_kegiatan' => 'BAUK - Kabid Sarana Prasarana'],

            // BKAK
            ['kategori' => 'BKAK', 'unit_kegiatan' => 'BKAK - Kabid Humas & Publikasi'],
            ['kategori' => 'BKAK', 'unit_kegiatan' => 'BKAK - Kabid Kerjasama'],
            ['kategori' => 'BKAK', 'unit_kegiatan' => 'BKAK - Kabid Urusan Internasional'],
            ['kategori' => 'BKAK', 'unit_kegiatan' => 'BKAK - Kabid Pusat Karir, Alumni dan Kewirausahaan'],
            ['kategori' => 'BKAK', 'unit_kegiatan' => 'BKAK - Kabid Kemahasiswaan'],

            // LPTI
            ['kategori' => 'LPTI', 'unit_kegiatan' => 'Ka.LPTI'],

            // Ka.LPMM
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kepala Pusat Kajian FEB'],
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kepala Pusat Kajian FST'],
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kepala Pusat Kajian Fikes'],
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kabid Haki Dan Publikasi'],
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kabid Penelitian'],
            ['kategori' => 'Ka.LPMM', 'unit_kegiatan' => 'Ka.LPMM - Kabid Pengabdian kepada masyarakat'],

            // Ka.LPMI
            ['kategori' => 'Ka.LPMI', 'unit_kegiatan' => 'Ka.LPMI - Kabid Dokumentasi Mutu dan Akreditasi'],
            ['kategori' => 'Ka.LPMI', 'unit_kegiatan' => 'Ka.LPMI - Kabid Pengembangan SPMI dan Pegawai SPMI'],
            ['kategori' => 'Ka.LPMI', 'unit_kegiatan' => 'Ka.LPMI - Kabid Audit Mutu Internal'],
            ['kategori' => 'Ka.LPMI', 'unit_kegiatan' => 'Ka.LPMI - Kabid Monitoring dan Evaluasi'],

            // Rektorat
            ['kategori' => 'Rektorat', 'unit_kegiatan' => 'Rektorat'],

            // FAKULTAS EKONOMI DAN BISNIS (FEB)
            ['kategori' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)', 'unit_kegiatan' => 'S2-MAGISTER MANAJEMEN'],
            ['kategori' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)', 'unit_kegiatan' => 'S1-MANAJEMEN'],
            ['kategori' => 'FAKULTAS EKONOMI DAN BISNIS (FEB)', 'unit_kegiatan' => 'S1-AKUNTANSI'],

            // FAKULTAS SAINS DAN TEKNOLOGI (FST)
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'S1-TEKNIK INDUSTRI'],
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'S1-TEKNIK INFORMATIKA'],
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'S1-TEKNIK LOGISTIK'],
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'S1-SISTEM INFORMASI'],
            ['kategori' => 'FAKULTAS SAINS DAN TEKNOLOGI (FST)', 'unit_kegiatan' => 'S1-TEKNIK PERKAPALAN'],

            // FAKULTAS ILMU KESEHATAN (FIKes)
            ['kategori' => 'FAKULTAS ILMU KESEHATAN (FIKes)', 'unit_kegiatan' => 'S2-KESEHATAN MASYARAKAT'],
            ['kategori' => 'FAKULTAS ILMU KESEHATAN (FIKes)', 'unit_kegiatan' => 'S1-KESEHATAN DAN KESELAMATAN KERJA'],
            ['kategori' => 'FAKULTAS ILMU KESEHATAN (FIKes)', 'unit_kegiatan' => 'S1-KESEHATAN LINGKUNGAN'],
        ];

        $createdMap = [];
        foreach ($units as $item) {
            $unit = \App\Models\UnitKegiatan::updateOrCreate(
                ['unit_kegiatan' => $item['unit_kegiatan']],
                ['kategori' => $item['kategori']]
            );
            $createdMap[$item['unit_kegiatan']] = $unit->id;
        }

        // Migrasi relasi user dari nama unit lama ke nama unit baru sebelum menghapus unit lama
        $legacyMap = [
            'Prodi Feb' => 'S1-MANAJEMEN',
            'Feb' => 'S1-MANAJEMEN',
            'Fakultas Ekonomi dan Bisnis (FEB)' => 'S1-MANAJEMEN',
            'FEB - S1 Manajemen' => 'S1-MANAJEMEN',
            'FEB - S2 Manajemen' => 'S2-MAGISTER MANAJEMEN',
            'FEB - S1 Akuntansi' => 'S1-AKUNTANSI',
            'Prodi Fst' => 'S1-TEKNIK INFORMATIKA',
            'Fst' => 'S1-TEKNIK INFORMATIKA',
            'Fakultas Sains dan Teknologi (FST)' => 'S1-TEKNIK INFORMATIKA',
            'FST - Teknik Industri' => 'S1-TEKNIK INDUSTRI',
            'FST - Teknik Informatika' => 'S1-TEKNIK INFORMATIKA',
            'FST - Teknik Perkapalan' => 'S1-TEKNIK PERKAPALAN',
            'FST - Teknik Logistik' => 'S1-TEKNIK LOGISTIK',
            'FST - Sistem Informasi' => 'S1-SISTEM INFORMASI',
            'Prodi Fikes' => 'S1-KESEHATAN DAN KESELAMATAN KERJA',
            'Fikes' => 'S1-KESEHATAN DAN KESELAMATAN KERJA',
            'Fakultas Ilmu Kesehatan (FIKES)' => 'S1-KESEHATAN DAN KESELAMATAN KERJA',
            'FIKES - S1 K3' => 'S1-KESEHATAN DAN KESELAMATAN KERJA',
            'FIKES - S2 Kesmas' => 'S2-KESEHATAN MASYARAKAT',
            'FIKES - S1 Kesling' => 'S1-KESEHATAN LINGKUNGAN',
            'HUMAS' => 'BKAK - Kabid Humas & Publikasi',
            'BKAK' => 'BKAK - Kabid Humas & Publikasi',
            'LPTI' => 'Ka.LPTI',
            'Universitas Ibnu Sina' => 'Rektorat',
        ];

        foreach ($legacyMap as $oldName => $newName) {
            $oldUnits = \App\Models\UnitKegiatan::whereRaw('LOWER(unit_kegiatan) = ?', [strtolower($oldName)])->get();
            foreach ($oldUnits as $oldUnit) {
                if (isset($createdMap[$newName]) && $oldUnit->id !== $createdMap[$newName]) {
                    if (Schema::hasTable('users') && Schema::hasColumn('users', 'unit_kegiatan_id')) {
                        DB::table('users')
                            ->where('unit_kegiatan_id', $oldUnit->id)
                            ->update(['unit_kegiatan_id' => $createdMap[$newName]]);
                    }
                    $oldUnit->delete();
                }
            }
        }
    }
}
