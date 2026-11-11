<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use RealRashid\SweetAlert\Facades\Alert;
use App\DataTables\TahunAkademikDataTable;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TahunAkademikDataTable $dataTable)
    {
        return $dataTable->render('pages.tahunAkademik.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.tahunAkademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:100',
        ]);

        $isActive = $request->boolean('is_active');
        if ($isActive) {
            TahunAkademik::where('is_active', true)->update(['is_active' => false]);
        }

        TahunAkademik::create([
            'tahun_akademik' => $request->tahun_akademik,
            'is_active'      => $isActive,
        ]);

        Alert::success('Berhasil', 'Tahun akademik berhasil ditambahkan')->autoclose(2500)->toToast();
        return redirect()->route('tahun-akademik.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAkademik $tahunAkademik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAkademik $tahunAkademik)
    {
        return view('pages.tahunAkademik.edit', compact('tahunAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAkademik $tahunAkademik)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:100',
        ]);

        $isActive = $request->boolean('is_active');
        if ($isActive) {
            TahunAkademik::where('id', '!=', $tahunAkademik->id)->update(['is_active' => false]);
        }

        $tahunAkademik->update([
            'tahun_akademik' => $request->tahun_akademik,
            'is_active'      => $isActive,
        ]);

        Alert::success('Berhasil', 'Tahun akademik berhasil diperbarui')->autoclose(2500)->toToast();
        return redirect()->route('tahun-akademik.index');
    }

    /**
     * Set tahun akademik menjadi aktif.
     */
    public function setActive(TahunAkademik $tahunAkademik)
    {
        TahunAkademik::where('id', '!=', $tahunAkademik->id)->update(['is_active' => false]);
        $tahunAkademik->update(['is_active' => true]);

        Alert::success('Berhasil', "Tahun Akademik {$tahunAkademik->tahun_akademik} sekarang berstatus Aktif.")->autoclose(2500)->toToast();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->delete();
        Alert::success('Berhasil', 'Tahun akademik berhasil dihapus')->autoclose(2500)->toToast();
        return redirect()->route('tahun-akademik.index');
    }
}
