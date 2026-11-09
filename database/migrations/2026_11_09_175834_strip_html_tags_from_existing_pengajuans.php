<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pengajuans')) {
            \App\Models\Pengajuan::all()->each(function ($pengajuan) {
                $changed = false;
                if ($pengajuan->deskripsi_kegiatan && str_contains($pengajuan->deskripsi_kegiatan, '<')) {
                    $pengajuan->deskripsi_kegiatan = trim(strip_tags($pengajuan->deskripsi_kegiatan));
                    $changed = true;
                }
                if ($pengajuan->perlengkapan && str_contains($pengajuan->perlengkapan, '<')) {
                    $pengajuan->perlengkapan = trim(strip_tags($pengajuan->perlengkapan));
                    $changed = true;
                }
                if ($changed) {
                    $pengajuan->saveQuietly();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
