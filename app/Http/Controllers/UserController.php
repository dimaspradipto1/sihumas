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
        return view('pages.users.create');
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
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['no_wa'] = $this->formatNoWaTo08($request->no_wa);
        $data['fakultas'] = $request->fakultas;
        $data['is_admin'] = $request->has('is_admin');
        $data['is_humas'] = $request->has('is_humas');
        $data['is_rektorat'] = $request->has('is_rektorat');
        $data['is_feb'] = $request->has('is_feb');
        $data['is_fst'] = $request->has('is_fst');
        $data['is_fikes'] = $request->has('is_fikes');
        $data['is_users'] = $request->has('is_users');
        $data['password'] = bcrypt($request->password);
        $data['remember_token'] = Str::random(60);

        // Jika tidak ada role yang dicentang secara manual, tetapkan default berdasarkan unit
        if (!$data['is_admin'] && !$data['is_humas'] && !$data['is_rektorat'] && !$data['is_feb'] && !$data['is_fst'] && !$data['is_fikes']) {
            switch ($request->fakultas) {
                case 'UNIVERSITAS IBNU SINA':
                case 'UNIVERISTAS IBNU SINA':
                    $data['is_admin'] = true;
                    $data['is_humas'] = true;
                    break;
                case 'FAKULTAS SAINS DAN TEKNOLOGI':
                    $data['is_fst'] = true;
                    break;
                case 'FAKULTAS EKONOMI DAN BISNIS':
                    $data['is_feb'] = true; // Assuming Fakultas Ilmu Komputer is in FST
                    break;
                case 'FAKULTAS ILMU KESEHATAN':
                    $data['is_fikes'] = true; // Assuming Sastra is for rectorate
                    break;
                case 'REKTORAT':
                    $data['is_rektorat'] = true; // Assuming Hukum is for rectorate
                    break;
                default:
                    $data['is_users'] = true; // Set to default if no match
                    break;
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
        return view('pages.users.edit', compact('user'));
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

        if ($request->has('fakultas')) {
            $updateData['fakultas'] = $request->fakultas;
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
