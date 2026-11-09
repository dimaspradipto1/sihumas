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
        if (Schema::hasTable('publikasis') && Schema::hasColumn('publikasis', 'slug')) {
            \App\Models\Publikasi::with('pengajuan')
                ->whereNull('slug')
                ->orWhere('slug', '')
                ->get()
                ->each(function ($p) {
                    $title = $p->pengajuan?->nama_kegiatan;
                    $p->slug = \App\Models\Publikasi::generateUniqueSlug($title, $p->id);
                    $p->saveQuietly();
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
