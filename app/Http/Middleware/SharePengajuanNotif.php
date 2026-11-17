<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SharePengajuanNotif
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $pendingPengajuan = 0;
        $bellNotif = [
            'total' => 0,
            'pending' => 0,
            'diterima' => 0,
            'ditolak' => 0,
            'recent' => collect(),
        ];

        if (auth()->check()) {
            $user = auth()->user();

            // Jika sedang membuka halaman Kotak Masuk, tandai telah dibaca
            if ($request->routeIs('kotak-masuk-pengajuan.*')) {
                try {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'kotak_masuk_read_at')) {
                        $user->update(['kotak_masuk_read_at' => now()]);
                    }
                } catch (\Throwable $e) {}
                session(['kotak_masuk_read_at' => now()]);
            }

            $kotakMasukReadAt = $user->kotak_masuk_read_at ?? session('kotak_masuk_read_at');

            if (!empty($user->is_admin)) {
                // Admin: jumlah pengajuan pending yang menunggu verifikasi yang belum dibaca
                $query = Pengajuan::where('status', 'pending');
                if ($kotakMasukReadAt) {
                    $query->where('updated_at', '>', $kotakMasukReadAt);
                }
                $pendingPengajuan = $query->count();

                $bellNotif['pending'] = Pengajuan::where('status', 'pending')->count();
                $bellNotif['diterima'] = Pengajuan::where('status', 'diterima')->whereDate('updated_at', '>=', now()->subDays(7))->count();
                $bellNotif['ditolak'] = Pengajuan::where('status', 'ditolak')->whereDate('updated_at', '>=', now()->subDays(7))->count();
                $bellNotif['total'] = $bellNotif['pending'];
                $bellNotif['recent'] = Pengajuan::with('user')
                    ->orderByDesc('updated_at')
                    ->limit(5)
                    ->get();
            } elseif (!empty($user->is_humas)) {
                // Petugas Humas: HANYA jumlah tugas yang ditugaskan kepada dirinya sebagai PIC yang belum dibaca
                $query = Pengajuan::assignedToUser($user)
                    ->whereIn('status', ['pending', 'diterima']);
                if ($kotakMasukReadAt) {
                    $query->where('updated_at', '>', $kotakMasukReadAt);
                }
                $pendingPengajuan = $query->count();

                $bellNotif['pending'] = Pengajuan::assignedToUser($user)->where('status', 'pending')->count();
                $bellNotif['diterima'] = Pengajuan::assignedToUser($user)->where('status', 'diterima')->count();
                $bellNotif['ditolak'] = Pengajuan::assignedToUser($user)->where('status', 'ditolak')->count();
                $bellNotif['total'] = $pendingPengajuan > 0 ? $pendingPengajuan : $bellNotif['pending'];
                $bellNotif['recent'] = Pengajuan::with('user')
                    ->assignedToUser($user)
                    ->orderByDesc('updated_at')
                    ->limit(5)
                    ->get();
            } else {
                // Pengusul / Fakultas / Rektorat: informasi pengajuan milik user tersebut
                $pengajuanReadAt = $user->pengajuan_read_at ?? session('pengajuan_read_at');
                $ownQuery = Pengajuan::where('user_id', $user->id);
                $bellNotif['pending'] = (clone $ownQuery)->where('status', 'pending')->count();
                $bellNotif['diterima'] = (clone $ownQuery)->where('status', 'diterima')->count();
                $bellNotif['ditolak'] = (clone $ownQuery)->where('status', 'ditolak')->count();

                $unreadQuery = (clone $ownQuery)->whereIn('status', ['diterima', 'ditolak']);
                if ($pengajuanReadAt) {
                    $unreadQuery->where('updated_at', '>', $pengajuanReadAt);
                }
                $unreadUpdates = $unreadQuery->count();
                $bellNotif['total'] = $unreadUpdates > 0 ? $unreadUpdates : $bellNotif['pending'];
                $bellNotif['recent'] = (clone $ownQuery)
                    ->orderByDesc('updated_at')
                    ->limit(5)
                    ->get();
            }
        }

        // dibagikan ke semua view, jadi sidebar dan bell di halaman manapun bisa akses
        view()->share('notifikasi_pengajuan_admin', $pendingPengajuan);
        view()->share('bellNotif', $bellNotif);

        return $next($request);
    }
}
