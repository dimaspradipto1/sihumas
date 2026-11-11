<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Publikasi;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\UnitKegiatan;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Mail\PengajuanSubmitted;
use App\Models\LaporanPublikasi;
use App\Mail\PengajuanUpdatedMail;
use App\Models\KotakMasukPengajuan;
use Illuminate\Support\Facades\Mail;
use App\DataTables\PengajuanDataTable;
use App\Http\Requests\PengajuanRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PengajuanDataTable $dataTable)
{
    $user = auth()->user();

    // Inisialisasi variabel untuk menyimpan jumlah pengajuan berdasarkan status dan fakultas pengguna
    $pendingPengajuanFST = $pendingPengajuanFEB = $pendingPengajuanFIKES = $pendingPengajuanRektorat = 0;
    $diterimaPengajuanFST = $diterimaPengajuanFEB = $diterimaPengajuanFIKES = $diterimaPengajuanRektorat = 0;
    $ditolakPengajuanFST = $ditolakPengajuanFEB = $ditolakPengajuanFIKES = $ditolakPengajuanRektorat = 0;

    // Hitung jumlah pengajuan berdasarkan status dan fakultas pengguna
    if ($user->is_feb) {
        $pendingPengajuanFEB = Pengajuan::where('status', 'pending')->whereHas('user', fn($query) => $query->where('is_feb', true))->count();
        $ditolakPengajuanFEB = Pengajuan::where('status', 'ditolak')->whereHas('user', fn($query) => $query->where('is_feb', true))->count();
        $diterimaPengajuanFEB = Pengajuan::where('status', 'diterima')->whereHas('user', fn($query) => $query->where('is_feb', true))->count();
        session(['notifikasi_pengajuan_feb' => $pendingPengajuanFEB]);
        session(['notifikasi_pengajuan_feb_diterima' => $diterimaPengajuanFEB]);
        session(['notifikasi_pengajuan_feb_ditolak' => $ditolakPengajuanFEB]);
    }

    if ($user->is_fikes) {
        $pendingPengajuanFIKES = Pengajuan::where('status', 'pending')->whereHas('user', fn($query) => $query->where('is_fikes', true))->count();
        $ditolakPengajuanFIKES = Pengajuan::where('status', 'ditolak')->whereHas('user', fn($query) => $query->where('is_fikes', true))->count();
        $diterimaPengajuanFIKES = Pengajuan::where('status', 'diterima')->whereHas('user', fn($query) => $query->where('is_fikes', true))->count();
        session(['notifikasi_pengajuan_fikes' => $pendingPengajuanFIKES]);
        session(['notifikasi_pengajuan_fikes_diterima' => $diterimaPengajuanFIKES]);
        session(['notifikasi_pengajuan_fikes_ditolak' => $ditolakPengajuanFIKES]);
    }

    if ($user->is_fst) {
        $pendingPengajuanFST = Pengajuan::where('status', 'pending')->whereHas('user', fn($query) => $query->where('is_fst', true))->count();
        $ditolakPengajuanFST = Pengajuan::where('status', 'ditolak')->whereHas('user', fn($query) => $query->where('is_fst', true))->count();
        $diterimaPengajuanFST = Pengajuan::where('status', 'diterima')->whereHas('user', fn($query) => $query->where('is_fst', true))->count();
        session(['notifikasi_pengajuan_fst' => $pendingPengajuanFST]);
        session(['notifikasi_pengajuan_fst_diterima' => $diterimaPengajuanFST]);
        session(['notifikasi_pengajuan_fst_ditolak' => $ditolakPengajuanFST]);
    }

    if ($user->is_rektorat) {
        $pendingPengajuanRektorat = Pengajuan::where('status', 'pending')->whereHas('user', fn($query) => $query->where('is_rektorat', true))->count();
        $ditolakPengajuanRektorat = Pengajuan::where('status', 'ditolak')->whereHas('user', fn($query) => $query->where('is_rektorat', true))->count();
        $diterimaPengajuanRektorat = Pengajuan::where('status', 'diterima')->whereHas('user', fn($query) => $query->where('is_rektorat', true))->count();
        session(['notifikasi_pengajuan_rektorat' => $pendingPengajuanRektorat]);
        session(['notifikasi_pengajuan_rektorat_diterima' => $diterimaPengajuanRektorat]);
        session(['notifikasi_pengajuan_rektorat_ditolak' => $ditolakPengajuanRektorat]);
    }

    // Kirimkan data ke view
    return $dataTable->render('pages.pengajuan.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKegiatan = UnitKegiatan::all();
        $tahunAkademik = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();
        return view('pages.pengajuan.create', compact('tahunAkademik', 'unitKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengajuanRequest $request)
    {
        $data = $request->validated();

        $uploadFilePath = null;
        if ($request->hasFile('upload_file')) {
            $uploadFilePath = $request->file('upload_file')->store('pengajuan/files', 'public');
        }

        $uploadPngPath = null;
        if ($request->hasFile('upload_png')) {
            $uploadPngPath = $request->file('upload_png')->store('pengajuan/images', 'public');
        }

        $pengajuan = Pengajuan::create([
            'user_id'           => auth()->id(),
            'nama_kegiatan'     => $data['nama_kegiatan'],
            'tgl_awal'          => $data['tgl_awal'],
            'tgl_selesai'       => $data['tgl_selesai'],
            'jam_kegiatan'      => $data['jam_kegiatan'],
            'waktu_selesai'     => $data['waktu_selesai'],
            'deskripsi_kegiatan' => isset($data['deskripsi_kegiatan']) ? trim(strip_tags($data['deskripsi_kegiatan'])) : null,
            'perlengkapan'      => isset($data['perlengkapan']) ? trim(strip_tags($data['perlengkapan'])) : null,
            'link_zoom'         => $data['link_zoom'] ?? null,
            'unit_kegiatan'     => $data['unit_kegiatan'],
            'tempat_kegiatan'   => $data['tempat_kegiatan'],
            'alasan_ditolak'    => $data['alasan_ditolak'] ?? null,
            'email_tujuan'      => $data['email_tujuan'] ?? null,
            'upload_file'       => $uploadFilePath,
            'upload_png'        => $uploadPngPath,
            'status'            => 'pending',
        ]);

        // Buat Publikasi terkait pengajuan yang baru
        $pengajuan->publikasi()->create([
            'user_id'           => auth()->id(),
            'tahun_akademik_id' => \App\Models\TahunAkademik::getActive()?->id,
            'upload_laporan'    => null,
            'link_dokumentasi'  => null,
            'link_publikasi'    => null,
        ]);

        // Buat LaporanPublikasi terkait publikasi yang baru
        LaporanPublikasi::create([
            'user_id'       => auth()->id(),
            'publikasi_id'  => $pengajuan->publikasi->id,
            'pengajuan_id'  => $pengajuan->id,
            'tgl_awal'      => $data['tgl_awal'],
            'tgl_selesai'   => $data['tgl_selesai'],
        ]);

        // Buat KotakMasukPengajuan terkait pengajuan yang baru
        KotakMasukPengajuan::create([
            'pengajuan_id'  => $pengajuan->id,
        ]);

        if ((auth()->user()->is_admin || auth()->user()->is_humas) && isset($data['email_tujuan'])) {
            $emailTujuan = $data['email_tujuan'];  // Gunakan email_tujuan jika diisi
        } else {
            $emailTujuan = 'humasuis@gmail.com';  // Jika bukan admin/humas atau email_tujuan tidak diisi, kirim ke email default
        }

        // Kirim email setelah pengajuan berhasil
        Mail::to($emailTujuan)
            ->send(new PengajuanSubmitted($pengajuan));


        // Kirim notifikasi WhatsApp (Manual Redirect) jika nomor WA admin terisi
        $waAdmin = Setting::getValue('admin_whatsapp');
        if ($waAdmin) {
            $waTemplate = Setting::getValue('wa_message_template', '');
            
            // Format pesan
            $waMessage = strtr($waTemplate, [
                '{nama_kegiatan}' => $pengajuan->nama_kegiatan,
                '{tgl_awal}' => Carbon::parse($pengajuan->tgl_awal)->translatedFormat('d F Y'),
                '{tgl_selesai}' => Carbon::parse($pengajuan->tgl_selesai)->translatedFormat('d F Y'),
                '{tempat_kegiatan}' => $pengajuan->tempat_kegiatan,
                '{nama_user}' => auth()->user()->name,
                '{no_wa_user}' => auth()->user()->no_wa ?? '-',
            ]);

            $waUrl = 'https://wa.me/' . $waAdmin . '?text=' . urlencode($waMessage);
            session()->flash('wa_redirect_url', $waUrl);
        } else {
            Alert::success('Success', 'Data berhasil ditambahkan dan email telah dikirim.')->toToast()->autoclose(3000)->timerProgressBar();
        }

        return redirect()->route('pengajuan.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Pengajuan $pengajuan)
    {
        $tahunAkademik = TahunAkademik::all();
        return view('pages.pengajuan.show', compact('pengajuan', 'tahunAkademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengajuan $pengajuan)
    {
        $tahunAkademik = TahunAkademik::all();
        $petugasHumas = User::where('is_humas', true)
            ->orderBy('name')
            ->get(['id', 'name', 'no_wa', 'is_humas'])
            ->unique('name')
            ->values();
        return view('pages.pengajuan.edit', compact('pengajuan', 'tahunAkademik', 'petugasHumas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        // Cek jika status pengajuan adalah 'ditolak', simpan alasan ditolak
        if ($request->status == 'ditolak') {
            $pengajuan->alasan_ditolak = $request->alasan_ditolak;
        } elseif ($request->status == 'diterima') {
            $pengajuan->alasan_ditolak = null; // Hapus alasan jika diterima
        } else {
            $pengajuan->alasan_ditolak = null; // Jika status bukan 'ditolak' atau 'diterima'
        }

        // Mengambil email tujuan dari input admin, jika tidak diisi maka ambil dari email user yang login
        $emailTujuan = $request->email_tujuan ?? $pengajuan->user->email;  // Mengambil email pengguna yang mengajukan

        // Cek jika email tujuan valid
        if ($emailTujuan && filter_var($emailTujuan, FILTER_VALIDATE_EMAIL)) {
            $isEmailValid = true;
        } else {
            $isEmailValid = false;
        }

        // Cek jika pengguna adalah admin atau petugas humas
        if (auth()->user()->is_admin || auth()->user()->is_humas) {
            // Mengupdate data pengajuan
            $data = $request->all();

            if ($request->hasFile('upload_file')) {
                if ($pengajuan->upload_file && Storage::disk('public')->exists($pengajuan->upload_file)) {
                    Storage::disk('public')->delete($pengajuan->upload_file);
                }
                $data['upload_file'] = $request->file('upload_file')->store('pengajuan/files', 'public');
            }

            if ($request->hasFile('upload_png')) {
                if ($pengajuan->upload_png && Storage::disk('public')->exists($pengajuan->upload_png)) {
                    Storage::disk('public')->delete($pengajuan->upload_png);
                }
                $data['upload_png'] = $request->file('upload_png')->store('pengajuan/images', 'public');
            }

            if (isset($data['deskripsi_kegiatan'])) {
                $data['deskripsi_kegiatan'] = trim(strip_tags($data['deskripsi_kegiatan']));
            }
            if (isset($data['perlengkapan'])) {
                $data['perlengkapan'] = trim(strip_tags($data['perlengkapan']));
            }

            // Memproses daftar Petugas / PIC yang ditugaskan (bisa lebih dari 1)
            $pics = [];
            if ($request->has('pic_nama') && is_array($request->pic_nama)) {
                foreach ($request->pic_nama as $index => $nama) {
                    $nama = trim($nama ?? '');
                    $noWa = trim($request->pic_no_wa[$index] ?? '');
                    if ($nama !== '' || $noWa !== '') {
                        $pics[] = [
                            'nama' => $nama,
                            'no_wa' => $noWa,
                        ];
                    }
                }
            } elseif ($request->has('pic_petugas') && is_array($request->pic_petugas)) {
                foreach ($request->pic_petugas as $item) {
                    $nama = trim($item['nama'] ?? '');
                    $noWa = trim($item['no_wa'] ?? '');
                    if ($nama !== '' || $noWa !== '') {
                        $pics[] = [
                            'nama' => $nama,
                            'no_wa' => $noWa,
                        ];
                    }
                }
            } elseif ($request->filled('petugas_ditugaskan') || $request->filled('no_wa_tujuan')) {
                $pics[] = [
                    'nama' => trim($request->petugas_ditugaskan ?? ''),
                    'no_wa' => trim($request->no_wa_tujuan ?? ''),
                ];
            }

            if (!empty($pics)) {
                $data['pic_petugas'] = $pics;
                $data['petugas_ditugaskan'] = implode(', ', array_filter(array_column($pics, 'nama')));
                $data['no_wa_tujuan'] = implode(', ', array_filter(array_column($pics, 'no_wa')));
            } else {
                $data['pic_petugas'] = null;
                $data['petugas_ditugaskan'] = null;
                $data['no_wa_tujuan'] = null;
            }

            unset($data['pic_nama'], $data['pic_no_wa']);
            $pengajuan->update($data);

            // Generate link WhatsApp untuk setiap PIC yang memiliki nomor WA
            $waPicList = [];
            $kegiatan = $pengajuan->nama_kegiatan;
            $unit = $pengajuan->unit_kegiatan;
            $status = strtoupper($pengajuan->status);
            $tglAwal = Carbon::parse($pengajuan->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM Y');
            $tglSelesai = Carbon::parse($pengajuan->tgl_selesai)->locale('id')->isoFormat('dddd, D MMMM Y');
            $waktu = "{$pengajuan->jam_kegiatan} - {$pengajuan->waktu_selesai} WIB";
            $tempat = $pengajuan->tempat_kegiatan ?? '-';
            $catatan = $pengajuan->alasan_ditolak ?: '-';
            $pemohon = $pengajuan->user?->name ?? '-';
            $pemohonWa = $pengajuan->user?->no_wa ?? '-';

            foreach ($pics as $pic) {
                $rawWa = preg_replace('/[^0-9]/', '', $pic['no_wa'] ?? '');
                if (!$rawWa) {
                    continue;
                }
                if (str_starts_with($rawWa, '0')) {
                    $cleanPhone = '62' . substr($rawWa, 1);
                } elseif (str_starts_with($rawWa, '8')) {
                    $cleanPhone = '62' . $rawWa;
                } else {
                    $cleanPhone = $rawWa;
                }

                $namaPic = $pic['nama'] ?: 'Petugas Humas';

                $message = "*PENUGASAN KEGIATAN HUMAS*\n\n" .
                    "Halo *{$namaPic}*,\n" .
                    "Anda telah ditugaskan untuk kegiatan berikut:\n\n" .
                    "- *Nama Kegiatan:* {$kegiatan}\n" .
                    "- *Unit:* {$unit}\n" .
                    "- *Tanggal:* {$tglAwal} s/d {$tglSelesai}\n" .
                    "- *Waktu:* {$waktu}\n" .
                    "- *Tempat:* {$tempat}\n" .
                    "- *Pemohon:* {$pemohon} ({$pemohonWa})\n" .
                    "- *Status / Catatan:* {$status} (" . ($catatan !== '-' ? $catatan : 'Telah diverifikasi') . ")\n\n" .
                    "Mohon untuk dikoordinasikan dan dipersiapkan dengan baik. Terima kasih.\n\n" .
                    "_Sistem Informasi Humas (SIHUMAS)_";

                $waPicList[] = [
                    'nama' => $namaPic,
                    'no_wa' => $pic['no_wa'],
                    'phone' => $cleanPhone,
                    'url' => 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message),
                ];
            }

            if (!empty($waPicList)) {
                session()->flash('wa_pic_list', $waPicList);
            }

            // Cek apakah laporanPublikasi ada dan update jika ada
            if ($pengajuan->laporanPublikasi) {
                // Jika tgl_awal atau tgl_selesai diupdate, update juga pada laporan_publikasi
                if ($request->has('tgl_awal') || $request->has('tgl_selesai')) {
                    $pengajuan->laporanPublikasi->update([
                        'tgl_awal'    => $request->tgl_awal ?? $pengajuan->tgl_awal,
                        'tgl_selesai' => $request->tgl_selesai ?? $pengajuan->tgl_selesai,
                    ]);
                }
            } elseif ($pengajuan->publikasi && ($request->has('tgl_awal') || $request->has('tgl_selesai'))) {
                // Jika laporanPublikasi tidak ada dan publikasi ada, buat data laporanPublikasi baru
                $pengajuan->laporanPublikasi()->create([
                    'user_id'      => $pengajuan->user_id ?? auth()->id(),
                    'publikasi_id' => $pengajuan->publikasi->id,
                    'tgl_awal'     => $request->tgl_awal ?? $pengajuan->tgl_awal,
                    'tgl_selesai'  => $request->tgl_selesai ?? $pengajuan->tgl_selesai,
                ]);
            }

            // Mengirimkan email jika email valid
            if ($isEmailValid) {
                try {
                    Mail::to($emailTujuan)->send(new PengajuanUpdatedMail($pengajuan));  // Kirim email ke tujuan yang sudah dipilih
                    if (empty($waPicList)) {
                        Alert::success('SUCCESS', 'Data berhasil diperbarui dan email telah dikirim.')->autoclose(2000)->toToast();
                    }
                } catch (\Exception $e) {
                    if (empty($waPicList)) {
                        Alert::error('Error', 'Email gagal dikirim, tetapi data berhasil diperbarui.')->toToast()->autoclose(3000)->timerProgressBar();
                    }
                }
            } else {
                // Jika email tidak valid atau kosong, beri tahu pengguna
                if (empty($waPicList)) {
                    Alert::info('Info', 'Email tidak valid atau kosong. Data berhasil diperbarui, tetapi email tidak dikirim.')->toToast()->autoclose(3000)->timerProgressBar();
                }
            }

            if (!empty($waPicList)) {
                session()->forget('alert.config');
            }

            return redirect()->route('kotak-masuk-pengajuan.index');
        }

        // Cek jika pengguna adalah is_feb, is_fst, is_fikes, atau is_rektorat
        elseif (auth()->user()->is_feb || auth()->user()->is_fst || auth()->user()->is_fikes || auth()->user()->is_rektorat) {
            // Jika status bukan 'ditolak', ubah status menjadi 'pending'
            if ($request->status != 'ditolak') {
                $pengajuan->status = 'pending';
            }

            $data = $request->all();

            if ($request->hasFile('upload_file')) {
                if ($pengajuan->upload_file && Storage::disk('public')->exists($pengajuan->upload_file)) {
                    Storage::disk('public')->delete($pengajuan->upload_file);
                }
                $data['upload_file'] = $request->file('upload_file')->store('pengajuan/files', 'public');
            }

            if ($request->hasFile('upload_png')) {
                if ($pengajuan->upload_png && Storage::disk('public')->exists($pengajuan->upload_png)) {
                    Storage::disk('public')->delete($pengajuan->upload_png);
                }
                $data['upload_png'] = $request->file('upload_png')->store('pengajuan/images', 'public');
            }

            if (isset($data['deskripsi_kegiatan'])) {
                $data['deskripsi_kegiatan'] = trim(strip_tags($data['deskripsi_kegiatan']));
            }
            if (isset($data['perlengkapan'])) {
                $data['perlengkapan'] = trim(strip_tags($data['perlengkapan']));
            }

            $pengajuan->update($data);

            // Cek apakah laporanPublikasi ada dan update jika ada
            if ($pengajuan->laporanPublikasi) {
                // Jika tgl_awal atau tgl_selesai diupdate, update juga pada laporan_publikasi
                if ($request->has('tgl_awal') || $request->has('tgl_selesai')) {
                    $pengajuan->laporanPublikasi->update([
                        'tgl_awal'    => $request->tgl_awal ?? $pengajuan->tgl_awal,
                        'tgl_selesai' => $request->tgl_selesai ?? $pengajuan->tgl_selesai,
                    ]);
                }
            } elseif ($pengajuan->publikasi && ($request->has('tgl_awal') || $request->has('tgl_selesai'))) {
                // Jika laporanPublikasi tidak ada dan publikasi ada, buat data laporanPublikasi baru
                $pengajuan->laporanPublikasi()->create([
                    'user_id'      => $pengajuan->user_id ?? auth()->id(),
                    'publikasi_id' => $pengajuan->publikasi->id,
                    'tgl_awal'     => $request->tgl_awal ?? $pengajuan->tgl_awal,
                    'tgl_selesai'  => $request->tgl_selesai ?? $pengajuan->tgl_selesai,
                ]);
            }

            // Mengirimkan email jika email valid
            if ($isEmailValid) {
                try {
                    Mail::to($emailTujuan)->send(new PengajuanUpdatedMail($pengajuan));  // Kirim email ke tujuan yang sudah dipilih
                    Alert::success('SUCCESS', 'Data berhasil diperbarui dan email telah dikirim.')->autoclose(2000)->toToast();
                } catch (\Exception $e) {
                    Alert::error('Error', 'Email gagal dikirim, tetapi data berhasil diperbarui.')->toToast()->autoclose(3000)->timerProgressBar();
                }
            } else {
                // Jika email tidak valid atau kosong, beri tahu pengguna
                Alert::info('Info', 'Email kosong. Data berhasil diperbarui, tetapi email tidak dikirim.')->toToast()->autoclose(3000)->timerProgressBar();
            }

            return redirect()->route('pengajuan.index');
        }

        // Jika tidak ada role yang cocok
        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        Alert::success('SUSSCESS', 'data deleted successfully')->autoclose(2000)->toToast()->timerProgressBar();
        return redirect()->route('pengajuan.index');
    }
}
