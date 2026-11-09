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
        Schema::table('publikasis', function (Blueprint $table) {
            if (!Schema::hasColumn('publikasis', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('publikasis', 'tahun_akademik_id')) {
                $table->foreignId('tahun_akademik_id')->nullable()->after('user_id')->constrained('tahun_akademiks')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('publikasis', 'slug')) {
            \App\Models\Publikasi::with('pengajuan')->whereNull('slug')->orWhere('slug', '')->get()->each(function ($p) {
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
        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropForeign(['tahun_akademik_id']);
            $table->dropColumn(['tahun_akademik_id', 'slug']);
        });
    }
};
