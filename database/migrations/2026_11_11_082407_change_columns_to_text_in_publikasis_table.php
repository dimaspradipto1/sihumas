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
            $table->text('upload_laporan')->nullable()->change();
            $table->text('link_dokumentasi')->nullable()->change();
            $table->text('link_publikasi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            $table->string('upload_laporan')->nullable()->change();
            $table->string('link_dokumentasi')->nullable()->change();
            $table->string('link_publikasi')->nullable()->change();
        });
    }
};
