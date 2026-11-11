<?php

use App\Models\Aktivitas;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\UnitKegiatanController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\LaporanPublikasiController;
use App\Http\Controllers\KotakMasukPengajuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BackupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses');
    Route::get('/logout', 'logout')->name('logout');
});


Route::middleware(['auth', 'checkrole'])->group(function () { 
    Route::post('/notifications/mark-as-read', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();
        $type = $request->input('type', 'all');

        if ($type === 'pengajuan' || $type === 'all') {
            $user->update(['pengajuan_read_at' => now()]);
            session(['pengajuan_read_at' => now()]);
        }

        if ($type === 'kotak_masuk' || $type === 'all') {
            $user->update(['kotak_masuk_read_at' => now()]);
            session(['kotak_masuk_read_at' => now()]);
        }

        return response()->json(['success' => true]);
    })->name('notifications.mark-as-read');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/publikasi/{publikasi}/validasi', [PublikasiController::class, 'validasi'])->name('publikasi.validasi');
    Route::resource('/publikasi', PublikasiController::class);
    Route::resource('/pengajuan', PengajuanController::class);
    Route::resource('kotak-masuk-pengajuan', KotakMasukPengajuanController::class);
    Route::resource('/users', UserController::class);
    Route::get('/laporan-publikasi', [LaporanPublikasiController::class, 'index'])->name('laporan-publikasi.index');
    Route::get('/laporan-publikasi/show', [LaporanPublikasiController::class, 'show'])->name('laporan-publikasi.show');
    Route::post('/tahun-akademik/{tahunAkademik}/set-active', [TahunAkademikController::class, 'setActive'])->name('tahun-akademik.set-active');
    Route::resource('/tahun-akademik', TahunAkademikController::class);
    Route::get('/user/{id}/update-password',[UserController::class, 'showUpdatePasswordForm'])->name('users.showUpdatePasswordForm');
    Route::put('/user/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::resource('/unit-kegiatan', UnitKegiatanController::class);
    Route::resource('/pegawai', PegawaiController::class);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup/database', [BackupController::class, 'downloadDatabase'])->name('backup.database.download');
    Route::post('/backup/database/restore', [BackupController::class, 'restoreDatabase'])->name('backup.database.restore');
    Route::get('/backup/storage', [BackupController::class, 'downloadStorage'])->name('backup.storage.download');
    Route::post('/backup/storage/restore', [BackupController::class, 'restoreStorage'])->name('backup.storage.restore');
});
