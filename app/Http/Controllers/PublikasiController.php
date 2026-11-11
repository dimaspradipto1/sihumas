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

        // Bersihkan tag HTML dari laporan (TinyMCE)
        $rawLaporan = (string) $request->input('upload_laporan', '');
        $cleanLaporan = preg_replace('/<br\s*\/?>/i', "\n", $rawLaporan);
        $cleanLaporan = preg_replace('/<\/(p|div|h[1-6]|li)>/i', "\n", $cleanLaporan);
        $cleanLaporan = html_entity_decode(strip_tags($cleanLaporan), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleanLaporan = preg_replace("/[\r\n]{3,}/", "\n\n", $cleanLaporan);
        $data['upload_laporan'] = trim($cleanLaporan) !== '' ? trim($cleanLaporan) : null;

        $data['link_dokumentasi'] = !empty(trim($request->input('link_dokumentasi', ''))) ? trim($request->input('link_dokumentasi')) : null;

        // Simpan multiple link publikasi sebagai JSON
        $links = $request->input('link_publikasi');
        if (is_array($links)) {
            $filteredLinks = array_values(array_filter(array_map('trim', $links)));
            $data['link_publikasi'] = !empty($filteredLinks) ? json_encode($filteredLinks) : null;
        } elseif (is_string($links)) {
            $trimmed = trim($links);
            $data['link_publikasi'] = $trimmed !== '' ? $trimmed : null;
        } else {
            $data['link_publikasi'] = null;
        }

        // Membuat Publikasi terlebih dahulu
        $publikasi = Publikasi::create($data);

        // Kemudian buat LaporanPublikasi dengan menggunakan ID dari Publikasi yang sudah dibuat
        $pengajuan = Pengajuan::find($request->pengajuan_id);
        LaporanPublikasi::create([
            'publikasi_id' => $publikasi->id,
            'user_id' => auth()->user()->id,
            'pengajuan_id' => $request->pengajuan_id,
            'tgl_awal' => $pengajuan?->tgl_awal,
            'tgl_selesai' => $pengajuan?->tgl_selesai,
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
        ]);

        // Bersihkan tag HTML dari laporan (TinyMCE)
        if ($request->has('upload_laporan')) {
            $rawLaporan = (string) $request->input('upload_laporan', '');
            $cleanLaporan = preg_replace('/<br\s*\/?>/i', "\n", $rawLaporan);
            $cleanLaporan = preg_replace('/<\/(p|div|h[1-6]|li)>/i', "\n", $cleanLaporan);
            $cleanLaporan = html_entity_decode(strip_tags($cleanLaporan), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanLaporan = preg_replace("/[\r\n]{3,}/", "\n\n", $cleanLaporan);
            $data['upload_laporan'] = trim($cleanLaporan) !== '' ? trim($cleanLaporan) : null;
        }

        if ($request->has('link_dokumentasi')) {
            $data['link_dokumentasi'] = !empty(trim($request->input('link_dokumentasi', ''))) ? trim($request->input('link_dokumentasi')) : null;
        }

        // Simpan multiple link publikasi sebagai JSON
        if ($request->has('link_publikasi')) {
            $links = $request->input('link_publikasi');
            if (is_array($links)) {
                $filteredLinks = array_values(array_filter(array_map('trim', $links)));
                $data['link_publikasi'] = !empty($filteredLinks) ? json_encode($filteredLinks) : null;
            } elseif (is_string($links)) {
                $trimmed = trim($links);
                $data['link_publikasi'] = $trimmed !== '' ? $trimmed : null;
            } else {
                $data['link_publikasi'] = null;
            }
        }

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
