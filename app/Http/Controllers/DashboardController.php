<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Publikasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Identifikasi Role & Unit pengguna yang login
        $role = 'user';
        $roleLabel = 'Pengguna';
        $unitName = 'Umum';

        if (!empty($user->is_admin)) {
            $role = 'admin';
            $roleLabel = 'Administrator';
            $unitName = 'Humas & Publikasi Universitas';
        } elseif (!empty($user->is_rektorat)) {
            $role = 'rektorat';
            $roleLabel = 'Operator Rektorat';
            $unitName = 'Unit Rektorat';
        } elseif (!empty($user->is_feb)) {
            $role = 'feb';
            $roleLabel = 'Operator FEB';
            $unitName = 'Fakultas Ekonomi dan Bisnis (FEB)';
        } elseif (!empty($user->is_fst)) {
            $role = 'fst';
            $roleLabel = 'Operator FST';
            $unitName = 'Fakultas Sains dan Teknologi (FST)';
        } elseif (!empty($user->is_fikes)) {
            $role = 'fikes';
            $roleLabel = 'Operator FIKES';
            $unitName = 'Fakultas Ilmu Kesehatan (FIKES)';
        }

        // Data Statistik Global (Untuk Admin)
        $total_pengajuan = Pengajuan::count();
        $total_pending = Pengajuan::where('status', 'pending')->count();
        $total_diterima = Pengajuan::where('status', 'diterima')->count();
        $total_ditolak = Pengajuan::where('status', 'ditolak')->count();
        $total_publikasi = Publikasi::count();

        // Data Breakdown per Unit Fakultas & Rektorat
        $units = [
            'Rektorat' => [
                'name' => 'Rektorat',
                'fullName' => 'Unit Rektorat',
                'badgeClass' => 'bg-success',
                'color' => '#059669',
                'pengajuan' => Pengajuan::whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count(),
                'diterima' => Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count(),
                'pending' => Pengajuan::where('status', 'pending')->whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count(),
                'publikasi' => Publikasi::whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count(),
            ],
            'FEB' => [
                'name' => 'FEB',
                'fullName' => 'Fakultas Ekonomi dan Bisnis',
                'badgeClass' => 'bg-warning',
                'color' => '#d97706',
                'pengajuan' => Pengajuan::whereHas('user', fn($q) => $q->where('is_feb', 1))->count(),
                'diterima' => Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_feb', 1))->count(),
                'pending' => Pengajuan::where('status', 'pending')->whereHas('user', fn($q) => $q->where('is_feb', 1))->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->whereHas('user', fn($q) => $q->where('is_feb', 1))->count(),
                'publikasi' => Publikasi::whereHas('user', fn($q) => $q->where('is_feb', 1))->count(),
            ],
            'FST' => [
                'name' => 'FST',
                'fullName' => 'Fakultas Sains dan Teknologi',
                'badgeClass' => 'bg-danger',
                'color' => '#dc2626',
                'pengajuan' => Pengajuan::whereHas('user', fn($q) => $q->where('is_fst', 1))->count(),
                'diterima' => Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_fst', 1))->count(),
                'pending' => Pengajuan::where('status', 'pending')->whereHas('user', fn($q) => $q->where('is_fst', 1))->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->whereHas('user', fn($q) => $q->where('is_fst', 1))->count(),
                'publikasi' => Publikasi::whereHas('user', fn($q) => $q->where('is_fst', 1))->count(),
            ],
            'FIKES' => [
                'name' => 'FIKES',
                'fullName' => 'Fakultas Ilmu Kesehatan',
                'badgeClass' => 'bg-purple',
                'color' => '#9333ea',
                'pengajuan' => Pengajuan::whereHas('user', fn($q) => $q->where('is_fikes', 1))->count(),
                'diterima' => Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_fikes', 1))->count(),
                'pending' => Pengajuan::where('status', 'pending')->whereHas('user', fn($q) => $q->where('is_fikes', 1))->count(),
                'ditolak' => Pengajuan::where('status', 'ditolak')->whereHas('user', fn($q) => $q->where('is_fikes', 1))->count(),
                'publikasi' => Publikasi::whereHas('user', fn($q) => $q->where('is_fikes', 1))->count(),
            ],
        ];

        // Statistik Unit Spesifik (Untuk Fakultas/Rektorat/User)
        if ($role === 'rektorat') {
            $unit_stats = $units['Rektorat'];
        } elseif ($role === 'feb') {
            $unit_stats = $units['FEB'];
        } elseif ($role === 'fst') {
            $unit_stats = $units['FST'];
        } elseif ($role === 'fikes') {
            $unit_stats = $units['FIKES'];
        } else {
            $unit_stats = [
                'name' => 'Pribadi',
                'fullName' => 'Kegiatan Anda',
                'badgeClass' => 'bg-secondary',
                'color' => '#2563eb',
                'pengajuan' => Pengajuan::where('user_id', $user->id)->count(),
                'diterima' => Pengajuan::where('user_id', $user->id)->where('status', 'diterima')->count(),
                'pending' => Pengajuan::where('user_id', $user->id)->where('status', 'pending')->count(),
                'ditolak' => Pengajuan::where('user_id', $user->id)->where('status', 'ditolak')->count(),
                'publikasi' => Publikasi::where('user_id', $user->id)->count(),
            ];
        }

        // Pengajuan Terakhir Unit Ini (Khusus Fakultas / Rektorat / User)
        $my_recent_pengajuan = collect();
        if ($role !== 'admin') {
            $queryRecent = Pengajuan::with('user');
            if ($role === 'rektorat') {
                $queryRecent->whereHas('user', fn($q) => $q->where('is_rektorat', 1));
            } elseif ($role === 'feb') {
                $queryRecent->whereHas('user', fn($q) => $q->where('is_feb', 1));
            } elseif ($role === 'fst') {
                $queryRecent->whereHas('user', fn($q) => $q->where('is_fst', 1));
            } elseif ($role === 'fikes') {
                $queryRecent->whereHas('user', fn($q) => $q->where('is_fikes', 1));
            } else {
                $queryRecent->where('user_id', $user->id);
            }
            $my_recent_pengajuan = $queryRecent->latest('id')->take(6)->get();
        }

        // Events / Kegiatan Terkini yang Disetujui
        $latest_pengajuan = Pengajuan::with('user')
            ->where('status', 'diterima')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
            })
            ->latest('id')
            ->paginate(5)
            ->withQueryString();

        // Data Chart Mingguan untuk Line Chart Admin
        $days = [];
        $weekly_data = [
            'Rektorat' => [],
            'FEB' => [],
            'FST' => [],
            'FIKES' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->locale('id')->isoFormat('dddd');
            $days[] = $dayName;

            $weekly_data['Rektorat'][] = Pengajuan::where('status', 'diterima')->whereDate('tgl_awal', $date)->whereHas('user', fn($q) => $q->where('is_rektorat', 1))->count();
            $weekly_data['FEB'][] = Pengajuan::where('status', 'diterima')->whereDate('tgl_awal', $date)->whereHas('user', fn($q) => $q->where('is_feb', 1))->count();
            $weekly_data['FST'][] = Pengajuan::where('status', 'diterima')->whereDate('tgl_awal', $date)->whereHas('user', fn($q) => $q->where('is_fst', 1))->count();
            $weekly_data['FIKES'][] = Pengajuan::where('status', 'diterima')->whereDate('tgl_awal', $date)->whereHas('user', fn($q) => $q->where('is_fikes', 1))->count();
        }

        // Tooltip detail per unit
        $list_rektorat = Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_rektorat', 1))
            ->get()->map(fn($p) => [
                'nama' => $p->nama_kegiatan,
                'tgl' => \Carbon\Carbon::parse($p->tgl_awal)->locale('id')->translatedFormat('d M Y'),
                'tgl_akhir' => \Carbon\Carbon::parse($p->tgl_selesai)->locale('id')->translatedFormat('d M Y'),
                'total' => $units['Rektorat']['diterima']
            ])->toArray();

        $list_feb = Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_feb', 1))
            ->get()->map(fn($p) => [
                'nama' => $p->nama_kegiatan,
                'tgl' => \Carbon\Carbon::parse($p->tgl_awal)->locale('id')->translatedFormat('d M Y'),
                'tgl_akhir' => \Carbon\Carbon::parse($p->tgl_selesai)->locale('id')->translatedFormat('d M Y'),
                'total' => $units['FEB']['diterima']
            ])->toArray();

        $list_fst = Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_fst', 1))
            ->get()->map(fn($p) => [
                'nama' => $p->nama_kegiatan,
                'tgl' => \Carbon\Carbon::parse($p->tgl_awal)->locale('id')->translatedFormat('d M Y'),
                'tgl_akhir' => \Carbon\Carbon::parse($p->tgl_selesai)->locale('id')->translatedFormat('d M Y'),
                'total' => $units['FST']['diterima']
            ])->toArray();

        $list_fikes = Pengajuan::where('status', 'diterima')->whereHas('user', fn($q) => $q->where('is_fikes', 1))
            ->get()->map(fn($p) => [
                'nama' => $p->nama_kegiatan,
                'tgl' => \Carbon\Carbon::parse($p->tgl_awal)->locale('id')->translatedFormat('d M Y'),
                'tgl_akhir' => \Carbon\Carbon::parse($p->tgl_selesai)->locale('id')->translatedFormat('d M Y'),
                'total' => $units['FIKES']['diterima']
            ])->toArray();

        return view('layouts.dashboard.index', [
            'role' => $role,
            'roleLabel' => $roleLabel,
            'unitName' => $unitName,
            'total_pengajuan' => $total_pengajuan,
            'total_pending' => $total_pending,
            'total_diterima' => $total_diterima,
            'total_ditolak' => $total_ditolak,
            'total_publikasi' => $total_publikasi,
            'units' => $units,
            'unit_stats' => $unit_stats,
            'my_recent_pengajuan' => $my_recent_pengajuan,
            'pengajuan_rektorat' => $units['Rektorat']['diterima'],
            'pengajuan_feb' => $units['FEB']['diterima'],
            'pengajuan_fst' => $units['FST']['diterima'],
            'pengajuan_fikes' => $units['FIKES']['diterima'],
            'publikasi_rektorat' => $units['Rektorat']['publikasi'],
            'publikasi_feb' => $units['FEB']['publikasi'],
            'publikasi_fst' => $units['FST']['publikasi'],
            'publikasi_fikes' => $units['FIKES']['publikasi'],
            'latest_pengajuan' => $latest_pengajuan,
            'days' => $days,
            'weekly_data' => $weekly_data,
            'list_rektorat' => $list_rektorat,
            'list_feb' => $list_feb,
            'list_fst' => $list_fst,
            'list_fikes' => $list_fikes,
        ]);
    }
}
