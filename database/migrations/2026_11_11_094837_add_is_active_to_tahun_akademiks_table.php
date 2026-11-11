<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tahun_akademiks', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('tahun_akademik');
        });

        // Set tahun akademik terakhir sebagai aktif secara default
        $latestId = DB::table('tahun_akademiks')->orderByDesc('id')->value('id');
        if ($latestId) {
            DB::table('tahun_akademiks')->where('id', $latestId)->update(['is_active' => true]);

            // Backfill semua publikasi yang belum memiliki tahun_akademik_id
            DB::table('publikasis')
                ->whereNull('tahun_akademik_id')
                ->update(['tahun_akademik_id' => $latestId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_akademiks', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
