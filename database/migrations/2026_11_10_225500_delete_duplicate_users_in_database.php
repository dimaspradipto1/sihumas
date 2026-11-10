<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cari duplikat berdasarkan nama (case-insensitive) atau kombinasi nama & no_wa
        $users = User::orderBy('id', 'asc')->get();
        $seen = [];
        $toDeleteIds = [];

        foreach ($users as $user) {
            $nameKey = strtolower(trim($user->name));
            $emailKey = strtolower(trim($user->email));

            // Jika sudah ada user dengan nama yang sama persis
            if (isset($seen[$nameKey])) {
                $primaryId = $seen[$nameKey];
                $toDeleteIds[$user->id] = $primaryId;
            } elseif (isset($seen[$emailKey])) {
                $primaryId = $seen[$emailKey];
                $toDeleteIds[$user->id] = $primaryId;
            } else {
                $seen[$nameKey] = $user->id;
                $seen[$emailKey] = $user->id;
            }
        }

        foreach ($toDeleteIds as $dupId => $keepId) {
            // Reassign foreign key references to the kept user
            DB::table('pengajuans')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('publikasis')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('laporan_publikasis')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('pegawais')->where('user_id', $dupId)->update(['user_id' => $keepId]);

            // Hapus duplicate user
            DB::table('users')->where('id', $dupId)->delete();
        }

        // 2. Jika ada akun test (@example) yang nomor WA-nya menduplikasi akun resmi (@uis.ac.id), bersihkan akun test
        $realUsers = User::where('email', 'not like', '%@example.%')->get();
        foreach ($realUsers as $ru) {
            if ($ru->no_wa) {
                $testDups = User::where('id', '!=', $ru->id)
                    ->where('no_wa', $ru->no_wa)
                    ->where(function ($q) {
                        $q->where('email', 'like', '%@example.com')
                            ->orWhere('email', 'like', '%@example.net')
                            ->orWhere('email', 'like', '%@example.org');
                    })->get();
                foreach ($testDups as $td) {
                    DB::table('pengajuans')->where('user_id', $td->id)->update(['user_id' => $ru->id]);
                    DB::table('publikasis')->where('user_id', $td->id)->update(['user_id' => $ru->id]);
                    DB::table('laporan_publikasis')->where('user_id', $td->id)->update(['user_id' => $ru->id]);
                    DB::table('pegawais')->where('user_id', $td->id)->update(['user_id' => $ru->id]);
                    DB::table('users')->where('id', $td->id)->delete();
                }
            }
        }

        // 3. Hapus akun dummy test (UJANG_*, BUDI_*) dari hasil running unit test
        $testPrefixUsers = User::where(function ($q) {
            $q->where('name', 'like', 'UJANG_%')
              ->orWhere('name', 'like', 'BUDI_%')
              ->orWhere('name', 'like', 'ADMIN_NON_HUMAS_%')
              ->orWhere('name', 'like', 'PETUGAS_HUMAS_%');
        })->get();

        foreach ($testPrefixUsers as $tu) {
            DB::table('pengajuans')->where('user_id', $tu->id)->delete();
            DB::table('publikasis')->where('user_id', $tu->id)->delete();
            DB::table('laporan_publikasis')->where('user_id', $tu->id)->delete();
            DB::table('pegawais')->where('user_id', $tu->id)->delete();
            DB::table('users')->where('id', $tu->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data cleaning cannot be reversed
    }
};
