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

            // Fakultas & Rektorat
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'Rektorat'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'Fakultas Ekonomi dan Bisnis (FEB)'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FEB - S1 Manajemen'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FEB - S2 Manajemen'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FEB - S1 Akuntansi'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'Fakultas Sains dan Teknologi (FST)'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FST - Teknik Industri'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FST - Teknik Informatika'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FST - Teknik Perkapalan'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FST - Teknik Logistik'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FST - Sistem Informasi'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'Fakultas Ilmu Kesehatan (FIKES)'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FIKES - S1 K3'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FIKES - S2 Kesmas'],
            ['kategori' => 'Fakultas & Rektorat', 'unit_kegiatan' => 'FIKES - S1 Kesling'],
        ];

        foreach ($units as $item) {
            \App\Models\UnitKegiatan::updateOrCreate(
                ['unit_kegiatan' => $item['unit_kegiatan']],
                ['kategori' => $item['kategori']]
            );
        }
    }
}
