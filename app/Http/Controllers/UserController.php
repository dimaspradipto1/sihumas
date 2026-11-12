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
        $units = \App\Models\UnitKegiatan::getOrderedList();
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
        $data['password'] = bcrypt($request->filled('password') ? $request->password : 'password');
        $data['remember_token'] = Str::random(60);

        // Tentukan 3 role fungsional utama (Opsi 1): admin, humas, operator
        $selectedRole = $request->input('role');
        $isAdmin = false;
        $isHumas = false;
        $isUsers = false;

        if ($selectedRole === 'admin' || $request->has('is_admin')) {
            $isAdmin = true;
            $isHumas = true;
        } elseif ($selectedRole === 'humas' || $request->has('is_humas')) {
            $isHumas = true;
        } elseif ($selectedRole === 'operator' || $request->has('is_users')) {
            $isUsers = true;
        } else {
            // Default jika tidak dipilih secara manual berdasarkan nama unit
            $unitNameUpper = strtoupper($unit?->unit_kegiatan ?? '');
            if (str_contains($unitNameUpper, 'HUMAS')) {
                $isHumas = true;
            } else {
                $isUsers = true;
            }
        }

        $data['is_admin'] = $isAdmin;
        $data['is_humas'] = $isHumas;
        $data['is_users'] = $isUsers;

        // Sinkronisasi otomatis flag fakultas/rektorat sesuai nama unit kegiatan
        $unitNameUpper = strtoupper($unit?->unit_kegiatan ?? '');
        $data['is_rektorat'] = $request->has('is_rektorat') || str_contains($unitNameUpper, 'REKTORAT');
        $data['is_feb'] = $request->has('is_feb') || str_contains($unitNameUpper, 'FEB') || str_contains($unitNameUpper, 'EKONOMI') || str_contains($unitNameUpper, 'MANAJEMEN') || str_contains($unitNameUpper, 'AKUNTANSI');
        $data['is_fst'] = $request->has('is_fst') || str_contains($unitNameUpper, 'FST') || str_contains($unitNameUpper, 'SAINS') || str_contains($unitNameUpper, 'INDUSTRI') || str_contains($unitNameUpper, 'INFORMATIKA') || str_contains($unitNameUpper, 'PERKAPALAN') || str_contains($unitNameUpper, 'LOGISTIK') || str_contains($unitNameUpper, 'SISTEM INFORMASI');
        $data['is_fikes'] = $request->has('is_fikes') || str_contains($unitNameUpper, 'FIKES') || str_contains($unitNameUpper, 'KESEHATAN') || str_contains($unitNameUpper, 'K3') || str_contains($unitNameUpper, 'KESMAS') || str_contains($unitNameUpper, 'KESLING');

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
        $units = \App\Models\UnitKegiatan::getOrderedList();
        return view('pages.users.edit', compact('user', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $unit = null;
        if ($request->has('unit_kegiatan_id')) {
            $unit = \App\Models\UnitKegiatan::find($request->unit_kegiatan_id);
        } else {
            $unit = $user->unitKegiatan;
        }

        // Tentukan 3 role fungsional utama (Opsi 1): admin, humas, operator
        $selectedRole = $request->input('role');
        $isAdmin = false;
        $isHumas = false;
        $isUsers = false;

        if ($selectedRole === 'admin' || $request->has('is_admin')) {
            $isAdmin = true;
            $isHumas = true;
        } elseif ($selectedRole === 'humas' || $request->has('is_humas')) {
            $isHumas = true;
        } elseif ($selectedRole === 'operator' || $request->has('is_users')) {
            $isUsers = true;
        } else {
            $isAdmin = $user->is_admin;
            $isHumas = $user->is_humas;
            $isUsers = $user->is_users;
        }

        $unitNameUpper = strtoupper($unit?->unit_kegiatan ?? '');
        $isRektorat = $request->has('is_rektorat') || str_contains($unitNameUpper, 'REKTORAT');
        $isFeb = $request->has('is_feb') || str_contains($unitNameUpper, 'FEB') || str_contains($unitNameUpper, 'EKONOMI') || str_contains($unitNameUpper, 'MANAJEMEN') || str_contains($unitNameUpper, 'AKUNTANSI');
        $isFst = $request->has('is_fst') || str_contains($unitNameUpper, 'FST') || str_contains($unitNameUpper, 'SAINS') || str_contains($unitNameUpper, 'INDUSTRI') || str_contains($unitNameUpper, 'INFORMATIKA') || str_contains($unitNameUpper, 'PERKAPALAN') || str_contains($unitNameUpper, 'LOGISTIK') || str_contains($unitNameUpper, 'SISTEM INFORMASI');
        $isFikes = $request->has('is_fikes') || str_contains($unitNameUpper, 'FIKES') || str_contains($unitNameUpper, 'KESEHATAN') || str_contains($unitNameUpper, 'K3') || str_contains($unitNameUpper, 'KESMAS') || str_contains($unitNameUpper, 'KESLING');

        $user->update([
            'is_admin' => $isAdmin ? 1 : 0,
            'is_humas' => $isHumas ? 1 : 0,
            'is_rektorat' => $isRektorat ? 1 : 0,
            'is_feb' => $isFeb ? 1 : 0,
            'is_fst' => $isFst ? 1 : 0,
            'is_fikes' => $isFikes ? 1 : 0,
            'is_users' => $isUsers ? 1 : 0,
        ]);

        $updateData = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
            'no_wa' => $this->formatNoWaTo08($request->no_wa),
        ];

        if ($request->has('unit_kegiatan_id')) {
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
