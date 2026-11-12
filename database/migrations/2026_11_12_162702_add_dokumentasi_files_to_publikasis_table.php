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
            $table->text('dokumentasi_files')->nullable()->after('link_dokumentasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropColumn('dokumentasi_files');
        });
    }
};
