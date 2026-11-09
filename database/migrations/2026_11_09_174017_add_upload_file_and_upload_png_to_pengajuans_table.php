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
            if (!Schema::hasColumn('pengajuans', 'upload_file')) {
                $table->string('upload_file')->nullable()->after('link_desain');
            }
            if (!Schema::hasColumn('pengajuans', 'upload_png')) {
                $table->string('upload_png')->nullable()->after('upload_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn(['upload_file', 'upload_png']);
        });
    }
};
