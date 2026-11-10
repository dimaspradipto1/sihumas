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

        // helper: count status by user flag
        $countByFlag = function (string $flag) {
            return [
                'pending'  => Pengajuan::where('status', 'pending')
                    ->whereHas('user', fn($q) => $q->where($flag, true))->count(),
                'diterima' => Pengajuan::where('status', 'diterima')
                    ->whereHas('user', fn($q) => $q->where($flag, true))->count(),
                'ditolak'  => Pengajuan::where('status', 'ditolak')
                    ->whereHas('user', fn($q) => $q->where($flag, true))->count(),
            ];
        };

        // structure session konsisten
        $notif = [
            'admin'    => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'feb'      => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'fst'      => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'fikes'    => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
            'rektorat' => ['pending' => 0, 'diterima' => 0, 'ditolak' => 0],
        ];

        // admin & humas global (total semua pengajuan)
        if (!empty($user->is_admin) || !empty($user->is_humas)) {
            $notif['admin'] = [
                'pending'  => Pengajuan::where('status', 'pending')->count(),
                'diterima' => Pengajuan::where('status', 'diterima')->count(),
                'ditolak'  => Pengajuan::where('status', 'ditolak')->count(),
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
