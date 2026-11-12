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
        Schema::table('unit_kegiatans', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_kegiatans', 'kategori')) {
                $table->string('kategori')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_kegiatans', function (Blueprint $table) {
            if (Schema::hasColumn('unit_kegiatans', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};
