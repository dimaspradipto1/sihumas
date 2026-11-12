<?php

namespace App\Http\Controllers;

use App\Models\UnitKegiatan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\DataTables\UnitKegiatanDataTable;

class UnitKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UnitKegiatanDataTable $unitKegiatanDataTable)
    {
        return $unitKegiatanDataTable->render('pages.unitKegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.unitKegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'unit_kegiatan' => 'required|string|max:255',
        ]);

        UnitKegiatan::create($validated);
        Alert::success('success','Data berhasil ditambahkan')->autoClose(3000)->toToast()->timerProgressBar();
        return redirect()->route('unit-kegiatan.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(UnitKegiatan $unitKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKegiatan $unitKegiatan)
    {
        return view('pages.unitKegiatan.edit', compact('unitKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitKegiatan $unitKegiatan)
    {
        $validated = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'unit_kegiatan' => 'required|string|max:255',
        ]);

        $unitKegiatan->update($validated);
        Alert::success('success','Data berhasil diupdate')->autoClose(3000)->toToast()->timerProgressBar();
        return redirect()->route('unit-kegiatan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKegiatan $unitKegiatan)
    {
        $unitKegiatan->delete();
        Alert::success('success','Data berhasil dihapus')->autoClose(3000)->toToast()->timerProgressBar();
        return redirect()->route('unit-kegiatan.index');
    }
}
