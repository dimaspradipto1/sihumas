<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\LaporanPublikasi;
use App\DataTables\PublikasiDataTable;
use App\Models\Pengajuan;
use RealRashid\SweetAlert\Facades\Alert;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PublikasiDataTable $dataTable)
    {
        return $dataTable->render('pages.publikasi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAkademik = TahunAkademik::all();
        return view('pages.publikasi.create', compact('tahunAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Data untuk tabel pengajuan
        $data['tahun_akademik_id'] = $request->tahun_akademik_id;
        $data['user_id'] = auth()->user()->id;
        $data['pengajuan_id'] = $request->pengajuan_id;
        $data['upload_laporan'] = $request->upload_laporan;
        $data['link_dokumentasi'] = $request->link_dokumentasi;
        $data['link_publikasi'] = $request->link_publikasi;

        // Membuat Publikasi terlebih dahulu
        $publikasi = Publikasi::create($data);

        // Kemudian buat LaporanPublikasi dengan menggunakan ID dari Publikasi yang sudah dibuat
        LaporanPublikasi::create([
            'publikasi_id' => $publikasi->id,
            'user_id' => auth()->user()->id,  // Mendapatkan ID user yang login
            'nama_kegiatan' => $request->nama_kegiatan,
            'link_dokumentasi' => $request->link_dokumentasi,
        ]);

        Alert::success('Success, Data Successfully added')->toToast()->autoclose();
        return redirect()->route('publikasi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publikasi $publikasi)
    {
        $publikasi->loadMissing(['tahunAkademik', 'pengajuan', 'user']);
        $tahunAkademik = TahunAkademik::all();

        return view('pages.publikasi.show', compact('publikasi', 'tahunAkademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publikasi $publikasi)
    {
        $publikasi->loadMissing('pengajuan');
        $pengajuan = $publikasi->pengajuan;  
        return view('pages.publikasi.edit', compact('publikasi', 'pengajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publikasi $publikasi)
    {
        $data = $request->only([
            'tahun_akademik_id',
            'upload_laporan',
            'link_dokumentasi',
            'link_publikasi',
        ]);

        $publikasi->update($data);
        Alert::success('Success, Data Successfully Updated')->toToast()->autoclose(3000);
        return redirect()->route('publikasi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publikasi $publikasi)
    {
        $publikasi->delete();
        Alert::success('Success, Data Successfully Deleted')->toToast()->autoclose(3000);
        return redirect()->route('publikasi.index');
    }
}
