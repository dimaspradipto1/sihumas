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
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->date('tgl_selesai')->nullable()->change();
            $table->string('jam_kegiatan')->nullable()->change();
            $table->string('waktu_selesai')->nullable()->change();
            $table->string('tempat_kegiatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->date('tgl_selesai')->nullable(false)->change();
            $table->string('jam_kegiatan')->nullable(false)->change();
            $table->string('waktu_selesai')->nullable(false)->change();
            $table->string('tempat_kegiatan')->nullable(false)->change();
        });
    }
};
