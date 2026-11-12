<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = \App\Models\UnitKegiatan::orderBy('unit_kegiatan')->get();
        return view('pages.users.create', compact('units'));
    }

    /**
     * Normalize WhatsApp number to 08 format.
     */
    private function formatNoWaTo08(?string $noWa): string
    {
        if (!$noWa) {
            return '';
        }
        $clean = preg_replace('/[^0-9]/', '', $noWa);
        if (str_starts_with($clean, '628')) {
            return '0' . substr($clean, 2);
        }
        if (str_starts_with($clean, '8')) {
            return '0' . $clean;
        }
        return $clean;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $unit = null;
        if ($request->filled('unit_kegiatan_id')) {
            $unit = \App\Models\UnitKegiatan::find($request->unit_kegiatan_id);
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['no_wa'] = $this->formatNoWaTo08($request->no_wa);
        $data['unit_kegiatan_id'] = $unit?->id;
        $data['is_admin'] = $request->has('is_admin');
        $data['is_humas'] = $request->has('is_humas');
        $data['is_rektorat'] = $request->has('is_rektorat');
        $data['is_feb'] = $request->has('is_feb');
        $data['is_fst'] = $request->has('is_fst');
        $data['is_fikes'] = $request->has('is_fikes');
        $data['is_users'] = $request->has('is_users');
        $data['password'] = bcrypt($request->filled('password') ? $request->password : 'password');
        $data['remember_token'] = Str::random(60);

        // Jika tidak ada role yang dicentang secara manual, tetapkan default berdasarkan unit
        if (!$data['is_admin'] && !$data['is_humas'] && !$data['is_rektorat'] && !$data['is_feb'] && !$data['is_fst'] && !$data['is_fikes']) {
            $unitNameUpper = strtoupper($unit?->unit_kegiatan ?? '');
            if (str_contains($unitNameUpper, 'IBNU SINA') || str_contains($unitNameUpper, 'HUMAS')) {
                $data['is_admin'] = true;
                $data['is_humas'] = true;
            } elseif (str_contains($unitNameUpper, 'FST') || str_contains($unitNameUpper, 'SAINS')) {
                $data['is_fst'] = true;
            } elseif (str_contains($unitNameUpper, 'FEB') || str_contains($unitNameUpper, 'EKONOMI')) {
                $data['is_feb'] = true;
            } elseif (str_contains($unitNameUpper, 'FIKES') || str_contains($unitNameUpper, 'KESEHATAN')) {
                $data['is_fikes'] = true;
            } elseif (str_contains($unitNameUpper, 'REKTORAT')) {
                $data['is_rektorat'] = true;
            } else {
                $data['is_users'] = true;
            }
        }

        User::create($data);
        Alert::success('success', 'data created successfully')->autoclose(2000)->toToast();
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $units = \App\Models\UnitKegiatan::orderBy('unit_kegiatan')->get();
        return view('pages.users.edit', compact('user', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'is_humas' => $request->has('is_humas') ? 1 : 0,
            'is_rektorat' => $request->has('is_rektorat') ? 1 : 0,
            'is_feb' => $request->has('is_feb') ? 1 : 0,
            'is_fst' => $request->has('is_fst') ? 1 : 0,
            'is_fikes' => $request->has('is_fikes') ? 1 : 0,
            'is_users' => $request->has('is_users') ? 1 : 0,
        ]);

        $updateData = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
            'no_wa' => $this->formatNoWaTo08($request->no_wa),
        ];

        if ($request->has('unit_kegiatan_id')) {
            $unit = \App\Models\UnitKegiatan::find($request->unit_kegiatan_id);
            $updateData['unit_kegiatan_id'] = $unit?->id;
        }

        if ($request->has('password') && !empty($request->password)) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);
        Alert::success('success', 'data updated successfully')->autoclose(2000)->toToast();
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrfail($id);
        $user->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(2000)->toToast();
        return redirect()->route('users.index');
    }

    public function showUpdatePasswordForm($id)
    {
        $users = User::findOrFail($id);
        return view('pages.users.updatePassword', compact('users'));
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->password = Hash::make($request->new_password);
        $user->save();
        Alert::success('success', 'data updated successfully')->autoclose(2000)->toToast();
        return redirect()->route('users.index');
    }

    public function import(Request $request)
    {
        $file = $request->file('excel_file');
        Excel::import(new UsersImport, $file);

        return back()->with('success', 'Data telah berhasil diimpor!');
    }
}
