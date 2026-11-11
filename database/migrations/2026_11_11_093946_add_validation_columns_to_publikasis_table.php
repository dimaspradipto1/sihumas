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
        Schema::table('publikasis', function (Blueprint $table) {
            if (!Schema::hasColumn('publikasis', 'status_validasi')) {
                $table->string('status_validasi')->default('belum_dilaporkan')->after('link_publikasi');
            }
            if (!Schema::hasColumn('publikasis', 'catatan_validasi')) {
                $table->text('catatan_validasi')->nullable()->after('status_validasi');
            }
            if (!Schema::hasColumn('publikasis', 'validated_by')) {
                $table->foreignId('validated_by')->nullable()->after('catatan_validasi')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('publikasis', 'validated_at')) {
                $table->timestamp('validated_at')->nullable()->after('validated_by');
            }
        });

        // Backfill data yang sudah ada: jika sudah pernah isi laporan, set sebagai 'disetujui'
        if (Schema::hasColumn('publikasis', 'status_validasi')) {
            DB::table('publikasis')
                ->where(function ($q) {
                    $q->whereNotNull('upload_laporan')
                      ->orWhereNotNull('link_dokumentasi')
                      ->orWhereNotNull('link_publikasi');
                })
                ->update([
                    'status_validasi' => 'disetujui',
                    'validated_at'    => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['status_validasi', 'catatan_validasi', 'validated_by', 'validated_at']);
        });
    }
};
