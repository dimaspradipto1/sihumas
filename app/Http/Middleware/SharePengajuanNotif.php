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
            } elseif (!empty($user->is_humas)) {
                // Petugas Humas: HANYA jumlah tugas yang ditugaskan kepada dirinya sebagai PIC yang belum dibaca
                $query = Pengajuan::assignedToUser($user)
                    ->whereIn('status', ['pending', 'diterima']);
                if ($kotakMasukReadAt) {
                    $query->where('updated_at', '>', $kotakMasukReadAt);
                }
                $pendingPengajuan = $query->count();
            }
        }

        // dibagikan ke semua view, jadi sidebar di halaman manapun bisa akses
        view()->share('notifikasi_pengajuan_admin', $pendingPengajuan);

        return $next($request);
    }
}
