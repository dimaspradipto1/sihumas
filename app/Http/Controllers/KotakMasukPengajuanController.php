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
        // Hitung jumlah pengajuan dengan status 'pending'
        $pendingPengajuan = Pengajuan::where('status', 'pending')->count();

        // Hitung jumlah pengajuan dengan status 'ditolak'
        $ditolakPengajuan = Pengajuan::where('status', 'ditolak')->count();

        // Hitung jumlah pengajuan dengan status 'diterima'
        $diterimaPengajuan = Pengajuan::where('status', 'diterima')->count();

        // Jika user adalah admin atau humas, simpan notifikasi di session hanya jika ada pengajuan pending
        if (auth()->user()->is_admin || auth()->user()->is_humas) {
            // Total notifikasi untuk admin (jumlah pengajuan pending)
            if ($pendingPengajuan > 0) {
                session(['notifikasi_pengajuan_admin' => $pendingPengajuan]);
            } else {
                // Hapus session jika tidak ada pengajuan pending
                session()->forget('notifikasi_pengajuan_admin');
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
