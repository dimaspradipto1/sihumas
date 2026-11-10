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

        // Kirimkan jumlah pengajuan yang 'pending', 'ditolak', dan 'diterima' ke view
        return $dataTable->render('pages.kotakMasukPengajuan.index', compact('pendingPengajuan', 'ditolakPengajuan', 'diterimaPengajuan'));
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
