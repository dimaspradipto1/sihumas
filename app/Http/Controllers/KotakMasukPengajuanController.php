<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\KotakMasukPengajuan;
use App\DataTables\KotakMasukPengajuanDataTable;

class KotakMasukPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(KotakMasukPengajuanDataTable $dataTable)
    {
        $user = auth()->user();
        if ($user) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'kotak_masuk_read_at')) {
                    $user->update(['kotak_masuk_read_at' => now()]);
                }
            } catch (\Throwable $e) {}
            session(['kotak_masuk_read_at' => now()]);
            session()->forget('notifikasi_pengajuan_admin');
        }

        $isHumasOnly = !empty($user->is_humas) && empty($user->is_admin);

        if ($isHumasOnly) {
            // Untuk Petugas Humas (PIC), hanya hitung data yang ditugaskan kepada dirinya
            $baseQuery = Pengajuan::assignedToUser($user);
            $pendingPengajuan = (clone $baseQuery)->where('status', 'pending')->count();
            $ditolakPengajuan = (clone $baseQuery)->where('status', 'ditolak')->count();
            $diterimaPengajuan = (clone $baseQuery)->where('status', 'diterima')->count();

            // Notifikasi tugas yang ditugaskan kepada Petugas Humas
            $totalTugas = (clone $baseQuery)->whereIn('status', ['pending', 'diterima'])->count();
            if ($totalTugas > 0) {
                session(['notifikasi_pengajuan_admin' => $totalTugas]);
            } else {
                session()->forget('notifikasi_pengajuan_admin');
            }
        } else {
            // Untuk Super Admin, hitung seluruh data pengajuan
            $pendingPengajuan = Pengajuan::where('status', 'pending')->count();
            $ditolakPengajuan = Pengajuan::where('status', 'ditolak')->count();
            $diterimaPengajuan = Pengajuan::where('status', 'diterima')->count();

            if (auth()->user()->is_admin) {
                if ($pendingPengajuan > 0) {
                    session(['notifikasi_pengajuan_admin' => $pendingPengajuan]);
                } else {
                    session()->forget('notifikasi_pengajuan_admin');
                }
            }
        }

        // Hitung ringkasan filter tugas & deadline untuk Kotak Masuk
        $kotakMasukBase = Pengajuan::whereHas('kotakMasukPengajuan');
        if ($isHumasOnly) {
            $kotakMasukBase->assignedToUser($user);
        }

        $today = now()->toDateString();
        $belumSelesaiQuery = (clone $kotakMasukBase)
            ->where('status', '!=', 'ditolak')
            ->where(function ($q) {
                $q->whereDoesntHave('publikasi')
                  ->orWhereHas('publikasi', fn($pq) => $pq->whereNull('status_validasi')->orWhere('status_validasi', '!=', 'disetujui'));
            });

        $filterCounts = [
            'all' => (clone $kotakMasukBase)->count(),
            'belum_selesai' => (clone $belumSelesaiQuery)->count(),
            'lewat_deadline' => (clone $belumSelesaiQuery)->whereRaw('COALESCE(tgl_selesai, tgl_awal) < ?', [$today])->count(),
            'selesai' => (clone $kotakMasukBase)->where(function ($q) {
                $q->where('status', 'ditolak')
                  ->orWhereHas('publikasi', fn($pq) => $pq->where('status_validasi', 'disetujui'));
            })->count(),
        ];

        // Kirimkan jumlah pengajuan yang 'pending', 'ditolak', dan 'diterima' ke view
        return $dataTable->render('pages.kotakMasukPengajuan.index', compact('pendingPengajuan', 'ditolakPengajuan', 'diterimaPengajuan', 'filterCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KotakMasukPengajuan $kotakMasukPengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KotakMasukPengajuan $kotakMasukPengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KotakMasukPengajuan $kotakMasukPengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KotakMasukPengajuan $kotakMasukPengajuan)
    {
        //
    }
}
