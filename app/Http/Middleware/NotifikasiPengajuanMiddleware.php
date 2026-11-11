<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class NotifikasiPengajuanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Jika user sedang membuka halaman Pengajuan Kegiatan, tandai notifikasi pengajuan telah dibaca
        if ($request->routeIs('pengajuan.*')) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'pengajuan_read_at')) {
                    $user->update(['pengajuan_read_at' => now()]);
                }
            } catch (\Throwable $e) {}
            session(['pengajuan_read_at' => now()]);
        }

        $pengajuanReadAt = $user->pengajuan_read_at ?? session('pengajuan_read_at');

        // helper: count status by user flag yang belum dibaca
        $countByFlag = function (string $flag) use ($pengajuanReadAt) {
            $base = Pengajuan::whereHas('user', fn($q) => $q->where($flag, true));
            if ($pengajuanReadAt) {
                $base->where('updated_at', '>', $pengajuanReadAt);
            }
            return [
                'pending'  => (clone $base)->where('status', 'pending')->count(),
                'diterima' => (clone $base)->where('status', 'diterima')->count(),
                'ditolak'  => (clone $base)->where('status', 'ditolak')->count(),
            ];
        };

        // structure session konsisten
        $notif = [
            'admin'    => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'humas'    => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'feb'      => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'fst'      => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'fikes'    => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'rektorat' => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
        ];

        // admin global (total semua pengajuan yang belum dibaca)
        if (!empty($user->is_admin)) {
            $baseAdmin = Pengajuan::query();
            if ($pengajuanReadAt) {
                $baseAdmin->where('updated_at', '>', $pengajuanReadAt);
            }
            $notif['admin'] = [
                'pending'  => (clone $baseAdmin)->where('status', 'pending')->count(),
                'diterima' => (clone $baseAdmin)->where('status', 'diterima')->count(),
                'ditolak'  => (clone $baseAdmin)->where('status', 'ditolak')->count(),
            ];
        }

        // humas (PIC): HANYA tugas yang ditugaskan kepada dirinya
        if (!empty($user->is_humas) && empty($user->is_admin)) {
            $baseHumas = Pengajuan::assignedToUser($user);
            if ($pengajuanReadAt) {
                $baseHumas->where('updated_at', '>', $pengajuanReadAt);
            }
            $notif['humas'] = [
                'pending'  => (clone $baseHumas)->where('status', 'pending')->count(),
                'diterima' => (clone $baseHumas)->where('status', 'diterima')->count(),
                'ditolak'  => (clone $baseHumas)->where('status', 'ditolak')->count(),
            ];
        }

        // fakultas sesuai role yang login
        if (!empty($user->is_feb))      $notif['feb']      = $countByFlag('is_feb');
        if (!empty($user->is_fst))      $notif['fst']      = $countByFlag('is_fst');
        if (!empty($user->is_fikes))    $notif['fikes']    = $countByFlag('is_fikes');
        if (!empty($user->is_rektorat)) $notif['rektorat'] = $countByFlag('is_rektorat');

        // overwrite session biar notif lama otomatis hilang
        session(['pengajuan_notif' => $notif]);

        return $next($request);
    }
}
