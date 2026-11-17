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
        Schema::create('triwulans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->unsignedTinyInteger('bulan_mulai');
            $table->unsignedTinyInteger('bulan_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        $currentMonth = (int) date('n');
        $activeKode = match (true) {
            in_array($currentMonth, [9, 10, 11], true) => 'TW1',
            in_array($currentMonth, [12, 1, 2], true) => 'TW2',
            in_array($currentMonth, [3, 4, 5], true) => 'TW3',
            default => 'TW4',
        };

        $now = now();
        DB::table('triwulans')->insert([
            [
                'kode' => 'TW1',
                'nama' => 'TW1 (Sep–Nov)',
                'bulan_mulai' => 9,
                'bulan_selesai' => 11,
                'is_active' => $activeKode === 'TW1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'TW2',
                'nama' => 'TW2 (Des–Feb)',
                'bulan_mulai' => 12,
                'bulan_selesai' => 2,
                'is_active' => $activeKode === 'TW2',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'TW3',
                'nama' => 'TW3 (Mar–Mei)',
                'bulan_mulai' => 3,
                'bulan_selesai' => 5,
                'is_active' => $activeKode === 'TW3',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'TW4',
                'nama' => 'TW4 (Jun–Agu)',
                'bulan_mulai' => 6,
                'bulan_selesai' => 8,
                'is_active' => $activeKode === 'TW4',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        Schema::table('publikasis', function (Blueprint $table) {
            if (!Schema::hasColumn('publikasis', 'triwulan_id')) {
                $table->foreignId('triwulan_id')
                    ->nullable()
                    ->after('tahun_akademik_id')
                    ->constrained('triwulans')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            if (Schema::hasColumn('publikasis', 'triwulan_id')) {
                $table->dropConstrainedForeignId('triwulan_id');
            }
        });

        Schema::dropIfExists('triwulans');
    }
};
