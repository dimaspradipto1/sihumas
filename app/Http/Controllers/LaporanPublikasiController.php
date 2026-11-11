<?php

namespace App\Http\Controllers;

use App\DataTables\LaporanPublikasiDataTable;
use App\Models\LaporanPublikasi;
use App\Models\Pengajuan;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class LaporanPublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LaporanPublikasiDataTable $dataTable)
    {
        // $tahunAkademik = TahunAkademik::all();
        // return $dataTable->render('pages.laporanPublikasi.index', compact('tahunAkademik'));
        $tahunAkademik = TahunAkademik::orderByDesc('id')->get();
        $selectedPeriode = request('tahun_akademik'); // Jangan auto default

        return $dataTable
            ->with(['tahun_akademik' => $selectedPeriode])
            ->render('pages.laporanPublikasi.index', compact('tahunAkademik', 'selectedPeriode'));
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
    // public function show(LaporanPublikasiDataTable $dataTable)
    // {
    //     $tahunAkademik = TahunAkademik::all();
    //     $tahun_akademik = request('tahun_akademik');
    //     return $dataTable->with('tahun_akademik', $tahun_akademik)
    //         ->render('pages.laporanPublikasi.show', compact('tahunAkademik'));
    // }

    // public function show(Request $request)
    // {
    //     $tahunAkademik = TahunAkademik::all();
    //     $selectedPeriode = $request->get('tahun_akademik'); // Ambil tahun akademik yang dipilih

    //     // Mengambil data berdasarkan tahun yang dipilih
    //     $laporanPublikasi = LaporanPublikasi::with([
    //         'publikasi.tahunAkademik',
    //         'publikasi.pengajuan'
    //     ])
    //     ->whereHas('publikasi.tahunAkademik', function($query) use ($selectedPeriode) {
    //         $query->where('tahun_akademik', $selectedPeriode);
    //     })
    //     ->get();

    //     return view('pages.laporanPublikasi.show', compact('laporanPublikasi', 'tahunAkademik', 'selectedPeriode'));
    // }

    //     public function show(Request $request)
    // {
    //     $tahunAkademik = TahunAkademik::all();
    //     $selectedPeriode = $request->get('tahun_akademik'); // Ambil tahun akademik yang dipilih
    //     $tglAwal = $request->get('tgl_awal'); // Ambil tanggal awal
    //     $tglSelesai = $request->get('tgl_selesai'); // Ambil tanggal selesai (tgl_selesai)

    //     // Query dasar untuk mengambil data
    //     $laporanPublikasi = LaporanPublikasi::with([
    //         'publikasi.tahunAkademik',  // Pastikan relasi ini ada
    //         'publikasi.pengajuan'
    //     ]);

    //     // Filter berdasarkan tahun akademik jika ada
    //     if ($selectedPeriode) {
    //         $laporanPublikasi = $laporanPublikasi->whereHas('publikasi', function($query) use ($selectedPeriode) {
    //             $query->where('tahun_akademik', $selectedPeriode);
    //         });
    //     }

    //     // Filter berdasarkan tgl_awal jika ada
    //     if ($tglAwal && !$tglSelesai) {
    //         // Jika hanya tgl_awal yang diisi, ambil data setelah atau pada tgl_awal
    //         $laporanPublikasi = $laporanPublikasi->whereDate('tgl_awal', '>=', $tglAwal);
    //     }

    //     // Jika tgl_awal dan tgl_selesai keduanya ada, filter berdasarkan rentang tanggal
    //     if ($tglAwal && $tglSelesai) {
    //         $laporanPublikasi = $laporanPublikasi->whereDate('tgl_awal', '>=', $tglAwal)
    //                                              ->whereDate('tgl_awal', '<=', $tglSelesai);
    //     }

    //     // Ambil data laporan publikasi sesuai dengan filter
    //     $laporanPublikasi = $laporanPublikasi->get();

    //     return view('pages.laporanPublikasi.show', compact('laporanPublikasi', 'tahunAkademik', 'selectedPeriode'));
    // }

    public function show(Request $request)
    {
        $tahunAkademikList = TahunAkademik::orderByDesc('id')->get();
        $selectedPeriode = $request->get('tahun_akademik');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $tglAwal = $request->get('tgl_awal');
        $tglSelesai = $request->get('tgl_selesai');
        $filterType = $request->get('filter_type', 'all');

        // Query dasar untuk mengambil data
        $query = LaporanPublikasi::with([
            'user',
            'publikasi.tahunAkademik',
            'publikasi.pengajuan',
            'pengajuan',
        ]);

        $filterLabel = null;

        // 1. Filter Tahun Akademik
        if ($filterType === 'tahun_akademik' || (!empty($selectedPeriode) && $filterType !== 'all')) {
            if (!empty($selectedPeriode)) {
                $query->whereHas('publikasi', function ($q) use ($selectedPeriode) {
                    $q->where('tahun_akademik_id', $selectedPeriode)
                      ->orWhereHas('tahunAkademik', fn($tq) => $tq->where('tahun_akademik', $selectedPeriode));
                });
                $ta = TahunAkademik::find($selectedPeriode) ?? TahunAkademik::where('tahun_akademik', $selectedPeriode)->first();
                $filterLabel = 'Tahun Akademik: ' . ($ta->tahun_akademik ?? $selectedPeriode);
            }
        }
        // 2. Filter Bulan (dan opsional Tahun)
        elseif ($filterType === 'bulan' || !empty($bulan)) {
            if (!empty($bulan)) {
                $query->where(function ($q) use ($bulan) {
                    $q->whereMonth('tgl_awal', $bulan)
                      ->orWhereHas('pengajuan', fn($pq) => $pq->whereMonth('tgl_awal', $bulan));
                });
            }
            if (!empty($tahun)) {
                $query->where(function ($q) use ($tahun) {
                    $q->whereYear('tgl_awal', $tahun)
                      ->orWhereHas('pengajuan', fn($pq) => $pq->whereYear('tgl_awal', $tahun));
                });
            }

            if (!empty($bulan) && !empty($tahun)) {
                $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
                $filterLabel = "Bulan {$namaBulan} {$tahun}";
            } elseif (!empty($bulan)) {
                $namaBulan = \Carbon\Carbon::createFromDate(2026, $bulan, 1)->locale('id')->translatedFormat('F');
                $filterLabel = "Bulan {$namaBulan}";
            }
        }
        // 3. Filter Tahun Kalender
        elseif ($filterType === 'tahun' || !empty($tahun)) {
            if (!empty($tahun)) {
                $query->where(function ($q) use ($tahun) {
                    $q->whereYear('tgl_awal', $tahun)
                      ->orWhereHas('pengajuan', fn($pq) => $pq->whereYear('tgl_awal', $tahun));
                });
                $filterLabel = "Tahun {$tahun}";
            }
        }
        // 4. Filter Rentang Tanggal
        elseif ($filterType === 'tanggal' || !empty($tglAwal) || !empty($tglSelesai)) {
            if ($tglAwal && $tglSelesai) {
                $query->whereDate('tgl_awal', '>=', $tglAwal)
                      ->whereDate('tgl_selesai', '<=', $tglSelesai);
                $filterLabel = 'Periode ' . \Carbon\Carbon::parse($tglAwal)->locale('id')->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($tglSelesai)->locale('id')->translatedFormat('d M Y');
            } elseif ($tglAwal) {
                $query->whereDate('tgl_awal', '>=', $tglAwal);
                $filterLabel = 'Mulai ' . \Carbon\Carbon::parse($tglAwal)->locale('id')->translatedFormat('d M Y');
            } elseif ($tglSelesai) {
                $query->whereDate('tgl_selesai', '<=', $tglSelesai);
                $filterLabel = 'Sampai ' . \Carbon\Carbon::parse($tglSelesai)->locale('id')->translatedFormat('d M Y');
            }
        }

        // Ambil data laporan publikasi sesuai dengan filter
        $laporanPublikasi = $query->orderBy('tgl_awal', 'asc')->get();

        return view('pages.laporanPublikasi.show', compact('laporanPublikasi', 'tahunAkademikList', 'selectedPeriode', 'filterLabel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanPublikasi $laporanPublikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanPublikasi $laporanPublikasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanPublikasi $laporanPublikasi)
    {
        //
    }
}
