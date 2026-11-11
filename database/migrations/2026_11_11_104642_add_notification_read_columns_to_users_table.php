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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pengajuan_read_at')) {
                $table->timestamp('pengajuan_read_at')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'kotak_masuk_read_at')) {
                $table->timestamp('kotak_masuk_read_at')->nullable()->after('pengajuan_read_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pengajuan_read_at', 'kotak_masuk_read_at']);
        });
    }
};
