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

        if (auth()->check() && (auth()->user()->is_admin || auth()->user()->is_humas)) {
            $pendingPengajuan = Pengajuan::where('status', 'pending')->count();
        }

        // dibagikan ke semua view, jadi sidebar di halaman manapun bisa akses
        view()->share('notifikasi_pengajuan_admin', $pendingPengajuan);

        return $next($request);
    }
}
