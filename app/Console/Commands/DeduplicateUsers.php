<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DeduplicateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deduplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua pengguna duplikat di database dan rapikan relasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa pengguna duplikat...');

        $users = User::orderBy('id', 'asc')->get();
        $seen = [];
        $toDeleteIds = [];

        foreach ($users as $user) {
            $nameKey = strtolower(trim($user->name));
            $emailKey = strtolower(trim($user->email));

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

        $deletedCount = 0;
        foreach ($toDeleteIds as $dupId => $keepId) {
            DB::table('pengajuans')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('publikasis')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('laporan_publikasis')->where('user_id', $dupId)->update(['user_id' => $keepId]);
            DB::table('pegawais')->where('user_id', $dupId)->update(['user_id' => $keepId]);

            DB::table('users')->where('id', $dupId)->delete();
            $deletedCount++;
        }

        // Hapus akun test yang menduplikasi nomor WhatsApp akun resmi
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
                    $deletedCount++;
                }
            }
        }

        // Hapus akun dummy test (UJANG_*, BUDI_*) dari hasil running unit test
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
            $deletedCount++;
        }

        $this->info("Berhasil membersihkan {$deletedCount} pengguna duplikat/data uji.");
        return 0;
    }
}
