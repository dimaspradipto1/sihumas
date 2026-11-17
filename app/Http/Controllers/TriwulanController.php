<?php

namespace App\Http\Controllers;

use App\DataTables\TriwulanDataTable;
use App\Models\Triwulan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TriwulanController extends Controller
{
    public function index(TriwulanDataTable $dataTable)
    {
        if (!session('triwulan_manual_mode')) {
            $activeTw = Triwulan::syncActiveByCurrentMonth();
        } else {
            $activeTw = Triwulan::where('is_active', true)->first();
        }

        return $dataTable->render('pages.triwulan.index', compact('activeTw'));
    }

    public function create()
    {
        return view('pages.triwulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:triwulans,kode',
            'nama' => 'required|string|max:100',
            'bulan_mulai' => 'required|integer|min:1|max:12',
            'bulan_selesai' => 'required|integer|min:1|max:12',
        ]);

        $isActive = $request->boolean('is_active');
        if ($isActive) {
            Triwulan::where('is_active', true)->update(['is_active' => false]);
        }

        Triwulan::create([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => trim($request->nama),
            'bulan_mulai' => (int) $request->bulan_mulai,
            'bulan_selesai' => (int) $request->bulan_selesai,
            'is_active' => $isActive,
        ]);

        Alert::success('Berhasil', 'Data Triwulan berhasil ditambahkan')->autoclose(2500)->toToast();
        return redirect()->route('triwulan.index');
    }

    public function edit(Triwulan $triwulan)
    {
        return view('pages.triwulan.edit', compact('triwulan'));
    }

    public function update(Request $request, Triwulan $triwulan)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:triwulans,kode,' . $triwulan->id,
            'nama' => 'required|string|max:100',
            'bulan_mulai' => 'required|integer|min:1|max:12',
            'bulan_selesai' => 'required|integer|min:1|max:12',
        ]);

        $isActive = $request->boolean('is_active');
        if ($isActive) {
            Triwulan::where('id', '!=', $triwulan->id)->update(['is_active' => false]);
        }

        $triwulan->update([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => trim($request->nama),
            'bulan_mulai' => (int) $request->bulan_mulai,
            'bulan_selesai' => (int) $request->bulan_selesai,
            'is_active' => $isActive,
        ]);

        Alert::success('Berhasil', 'Data Triwulan berhasil diperbarui')->autoclose(2500)->toToast();
        return redirect()->route('triwulan.index');
    }

    public function setActive(Request $request, Triwulan $triwulan)
    {
        session(['triwulan_manual_mode' => true]);

        if ($triwulan->is_active) {
            $triwulan->update(['is_active' => false]);
            $message = "{$triwulan->nama} dinonaktifkan.";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'is_active' => false,
                    'active_name' => null,
                    'message' => $message,
                ]);
            }
            Alert::success('Berhasil', $message)->autoclose(2500)->toToast();
            return back();
        }

        Triwulan::where('id', '!=', $triwulan->id)->update(['is_active' => false]);
        $triwulan->update(['is_active' => true]);

        $message = "{$triwulan->nama} sekarang berstatus Aktif.";
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => true,
                'active_name' => $triwulan->nama,
                'message' => $message,
            ]);
        }

        Alert::success('Berhasil', $message)->autoclose(2500)->toToast();
        return back();
    }

    public function syncAuto(Request $request)
    {
        session()->forget('triwulan_manual_mode');
        $activeTw = Triwulan::syncActiveByCurrentMonth();
        $nama = $activeTw?->nama ?? '-';
        $message = "Triwulan otomatis disinkronkan ke {$nama} sesuai bulan berjalan.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'active_name' => $activeTw?->nama,
                'message' => $message,
            ]);
        }

        Alert::success('Berhasil', $message)->autoclose(2500)->toToast();
        return back();
    }

    public function destroy(Triwulan $triwulan)
    {
        $triwulan->delete();
        Alert::success('Berhasil', 'Data Triwulan berhasil dihapus')->autoclose(2500)->toToast();
        return redirect()->route('triwulan.index');
    }
}
