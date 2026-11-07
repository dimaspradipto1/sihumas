@extends('layouts.dashboard.template')

@section('content')
<div class="py-3">
    {{-- 1. HERO WELCOME BANNER (PROFESSIONAL & ROLE-TAILORED) --}}
    <div class="card border-0 shadow-sm mb-4 text-white overflow-hidden hero-welcome-card">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center position-relative" style="z-index: 2;">
                <div class="mb-3 mb-lg-0">
                    <div class="d-inline-flex align-items-center gap-2 mb-2 px-3 py-1 rounded-pill hero-role-badge">
                        <span class="badge bg-warning text-dark fw-bold text-uppercase px-2 py-1" style="font-size: 10px; letter-spacing: 0.5px;">
                            {{ $roleLabel }}
                        </span>
                        <span class="text-white small fw-medium">{{ $unitName }}</span>
                    </div>
                    <h1 class="h3 fw-bold text-white mb-2">
                        Selamat Datang, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-white-50 mb-0 font-small" style="max-width: 680px; line-height: 1.6;">
                        @if ($role === 'admin')
                            Sistem Informasi Humas & Publikasi Universitas Ibnu Sina. Pantau status pengajuan kegiatan, verifikasi permohonan dari seluruh fakultas, serta kelola dokumentasi publikasi secara terpadu.
                        @else
                            Portal layanan terpadu Humas & Publikasi untuk {{ $unitName }}. Ajukan kegiatan unit Anda, pantau persetujuan berkas, dan laporkan dokumentasi publikasi kampus dengan cepat dan transparan.
                        @endif
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2 flex-shrink-0">
                    @if ($role === 'admin')
                        <a href="{{ route('kotak-masuk-pengajuan.index') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-inbox me-2"></i> Kotak Masuk
                            @if (($total_pending ?? 0) > 0)
                                <span class="badge bg-danger ms-2 px-2 py-1 rounded-pill">{{ $total_pending }}</span>
                            @endif
                        </a>
                        <a href="{{ route('publikasi.index') }}" class="btn btn-light text-dark fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-newspaper me-2 text-primary"></i> Pelaporan Kegiatan
                        </a>
                    @else
                        <a href="{{ route('pengajuan.create') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-circle-plus me-2"></i> Buat Pengajuan Baru
                        </a>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-light text-dark fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-list-check me-2 text-primary"></i> Status Pengajuan
                        </a>
                    @endif
                </div>
            </div>
            <!-- Background Decorative Element -->
            <div class="hero-decorative-shape"></div>
        </div>
    </div>

    {{-- 2. METRIC / KPI STAT CARDS --}}
    @if ($role === 'admin')
        {{-- Stat Cards Khusus Administrator (Overview Seluruh Universitas) --}}
        <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Total Pengajuan
                                </h6>
                                <h3 class="fw-bold mb-0 text-gray-900">{{ $total_pengajuan ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Semua Fakultas & Rektorat</small>
                            </div>
                            <div class="stat-icon-wrapper bg-primary-soft text-primary">
                                <i class="fa-solid fa-file-lines fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card {{ ($total_pending ?? 0) > 0 ? 'border-warning-subtle' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Menunggu Verifikasi
                                </h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $total_pending ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Perlu review admin</small>
                            </div>
                            <div class="stat-icon-wrapper bg-warning-soft text-warning">
                                <i class="fa-solid fa-hourglass-half fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Pengajuan Disetujui
                                </h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $total_diterima ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Diterima & dijadwalkan</small>
                            </div>
                            <div class="stat-icon-wrapper bg-success-soft text-success">
                                <i class="fa-solid fa-circle-check fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Pelaporan Publikasi
                                </h6>
                                <h3 class="fw-bold mb-0 text-info">{{ $total_publikasi ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Dokumentasi & rilis</small>
                            </div>
                            <div class="stat-icon-wrapper bg-info-soft text-info">
                                <i class="fa-solid fa-newspaper fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Stat Cards Khusus Fakultas / Rektorat / Pengguna --}}
        <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Total Pengajuan Anda
                                </h6>
                                <h3 class="fw-bold mb-0 text-gray-900">{{ $unit_stats['pengajuan'] ?? $unit_stats['total'] ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Permohonan diajukan</small>
                            </div>
                            <div class="stat-icon-wrapper bg-primary-soft text-primary">
                                <i class="fa-solid fa-paper-plane fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Menunggu Persetujuan
                                </h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $unit_stats['pending'] ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Sedang ditinjau Humas</small>
                            </div>
                            <div class="stat-icon-wrapper bg-warning-soft text-warning">
                                <i class="fa-solid fa-clock fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Pengajuan Disetujui
                                </h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $unit_stats['diterima'] ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Siap publikasi & dokumentasi</small>
                            </div>
                            <div class="stat-icon-wrapper bg-success-soft text-success">
                                <i class="fa-solid fa-check-double fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    Laporan Selesai
                                </h6>
                                <h3 class="fw-bold mb-0 text-info">{{ $unit_stats['publikasi'] ?? 0 }}</h3>
                                <small class="text-muted" style="font-size: 11px;">Publikasi telah dirilis</small>
                            </div>
                            <div class="stat-icon-wrapper bg-info-soft text-info">
                                <i class="fa-solid fa-bullhorn fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. MIDDLE SECTION (BERBEDA SESUAI ROLE) --}}
    @if ($role === 'admin')
        {{-- Tampilan Middle Khusus Administrator --}}
        <div class="row mb-4 g-4">
            <!-- Monitoring Rekapitulasi per Unit Fakultas & Rektorat -->
            <div class="col-12 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fs-6 fw-bold mb-0 text-gray-900">
                                <i class="fa-solid fa-chart-pie text-primary me-2"></i> Rekapitulasi Kegiatan per Unit
                            </h2>
                            <small class="text-muted">Aktivitas pengajuan dan pelaporan per fakultas & rektorat</small>
                        </div>
                        <a href="{{ route('laporan-publikasi.index') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                            Lihat Rekap
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-hover mb-0">
                            <thead class="bg-gray-50 text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-3 py-3">Unit / Fakultas</th>
                                    <th class="text-center py-3">Pengajuan</th>
                                    <th class="text-center py-3">Disetujui</th>
                                    <th class="text-center py-3">Pending</th>
                                    <th class="text-center py-3">Pelaporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $key => $unit)
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $unit['badgeClass'] }} me-2" style="width: 10px; height: 10px; border-radius: 50%; padding: 0;"></span>
                                                <div>
                                                    <span class="fw-semibold text-gray-900 d-block" style="font-size: 13px;">{{ $unit['fullName'] }}</span>
                                                    <small class="text-muted" style="font-size: 11px;">Kode: {{ $unit['name'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-gray-100 text-gray-800 fw-bold px-2 py-1">
                                                {{ $unit['pengajuan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-success-soft text-success fw-bold px-2 py-1">
                                                {{ $unit['diterima'] }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3">
                                            @if ($unit['pending'] > 0)
                                                <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                                                    {{ $unit['pending'] }}
                                                </span>
                                            @else
                                                <span class="text-muted small">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-info-soft text-info fw-bold px-2 py-1">
                                                {{ $unit['publikasi'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Monitoring Aktivitas (Clean White Card) -->
            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fs-6 fw-bold mb-0 text-gray-900">
                                <i class="fa-solid fa-chart-column text-success me-2"></i> Grafik Aktivitas Diterima
                            </h2>
                            <small class="text-muted">Perbandingan kegiatan disetujui per unit</small>
                        </div>
                        <span class="badge bg-light text-muted fw-normal" style="font-size: 11px;">Terkini</span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="line-chart ct-major-tenth" style="height: 230px;"></div>
                        <div class="d-flex justify-content-center flex-wrap gap-3 pt-3 border-top mt-2" style="font-size: 11.5px;">
                            <span class="d-inline-flex align-items-center text-muted">
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #007F3B;"></span> Rektorat
                            </span>
                            <span class="d-inline-flex align-items-center text-muted">
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #fe9a20;"></span> FEB
                            </span>
                            <span class="d-inline-flex align-items-center text-muted">
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #810000;"></span> FST
                            </span>
                            <span class="d-inline-flex align-items-center text-muted">
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #B33791;"></span> FIKES
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Tampilan Middle Khusus Fakultas / Rektorat / Pengguna --}}
        <div class="row mb-4 g-4">
            <!-- Riwayat Pengajuan Terkini Unit Saya -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fs-6 fw-bold mb-0 text-gray-900">
                                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Pengajuan Terkini Unit Anda
                            </h2>
                            <small class="text-muted">Daftar kegiatan yang baru saja diajukan oleh {{ $unitName }}</small>
                        </div>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($my_recent_pengajuan->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead class="bg-gray-50 text-muted" style="font-size: 11px; text-transform: uppercase;">
                                        <tr>
                                            <th class="ps-3 py-3">Nama Kegiatan</th>
                                            <th class="py-3">Jadwal Pelaksanaan</th>
                                            <th class="text-center py-3">Status</th>
                                            <th class="text-end pe-3 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($my_recent_pengajuan as $item)
                                            <tr>
                                                <td class="ps-3 py-3">
                                                    <span class="fw-bold text-gray-900 d-block" style="font-size: 13px;">
                                                        {{ $item->nama_kegiatan }}
                                                    </span>
                                                    <small class="text-muted" style="font-size: 11px;">
                                                        <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $item->tempat_kegiatan }}
                                                    </small>
                                                </td>
                                                <td class="py-3" style="font-size: 12px;">
                                                    <span class="d-block text-gray-800">
                                                        {{ \Carbon\Carbon::parse($item->tgl_awal)->locale('id')->translatedFormat('d M Y') }}
                                                    </span>
                                                    <small class="text-muted">{{ $item->jam_kegiatan }} WIB</small>
                                                </td>
                                                <td class="text-center py-3">
                                                    @if ($item->status === 'diterima')
                                                        <span class="badge bg-success-soft text-success px-2 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                                                            <i class="fa-solid fa-check me-1"></i> Disetujui
                                                        </span>
                                                    @elseif ($item->status === 'ditolak')
                                                        <span class="badge bg-danger-soft text-danger px-2 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                                                            <i class="fa-solid fa-xmark me-1"></i> Ditolak
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-soft text-warning px-2 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                                                            <i class="fa-solid fa-clock me-1"></i> Menunggu
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-3 py-3">
                                                    <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-sm btn-light border py-1 px-2" title="Lihat Rincian">
                                                        <i class="fa-solid fa-eye text-primary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted">
                                    <i class="fa-solid fa-file-circle-question fa-3x" style="opacity: 0.3;"></i>
                                </div>
                                <h6 class="fw-bold text-gray-700 mb-1">Belum Ada Pengajuan Kegiatan</h6>
                                <p class="text-muted small mb-3">Unit Anda belum mengajukan kegiatan humas & publikasi.</p>
                                <a href="{{ route('pengajuan.create') }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                                    <i class="fa-solid fa-plus me-1"></i> Ajukan Kegiatan Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Petunjuk & Bantuan Humas -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h2 class="fs-6 fw-bold mb-0 text-gray-900">
                            <i class="fa-solid fa-circle-info text-info me-2"></i> Alur Layanan Humas
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline-workflow">
                            <div class="d-flex align-items-start mb-3">
                                <div class="step-badge bg-primary text-white me-3">1</div>
                                <div>
                                    <h6 class="fw-bold text-gray-900 mb-0" style="font-size: 13px;">Input Formulir Pengajuan</h6>
                                    <small class="text-muted" style="font-size: 11.5px;">Isi nama kegiatan, tanggal, waktu, tempat, dan kebutuhan publikasi.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="step-badge bg-warning text-dark me-3">2</div>
                                <div>
                                    <h6 class="fw-bold text-gray-900 mb-0" style="font-size: 13px;">Verifikasi Administrator</h6>
                                    <small class="text-muted" style="font-size: 11.5px;">Admin Humas & Publikasi meninjau permohonan dan menyetujui jadwal.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="step-badge bg-success text-white me-3">3</div>
                                <div>
                                    <h6 class="fw-bold text-gray-900 mb-0" style="font-size: 13px;">Pelaksanaan & Publikasi</h6>
                                    <small class="text-muted" style="font-size: 11.5px;">Kegiatan terlaksana, liputan dirilis, dan berkas diarsipkan.</small>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded mt-4 bg-light border">
                            <div class="d-flex align-items-center">
                                <i class="fa-brands fa-whatsapp text-success fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-gray-900" style="font-size: 12.5px;">Butuh Bantuan Koordinasi?</h6>
                                    <small class="text-muted d-block" style="font-size: 11px;">Hubungi Admin Humas UIS via WhatsApp</small>
                                    <a href="https://wa.me/6289523712219" target="_blank" class="fw-bold text-success text-decoration-none mt-1 d-inline-block" style="font-size: 11.5px;">
                                        Chat Admin &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 4. BOTTOM SECTION: AGENDA & KEGIATAN UNIVERSITAS (EVENTS LIST) --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <div>
                        <h2 class="fs-6 fw-bold mb-0 text-gray-900">
                            <i class="fa-solid fa-calendar-days text-danger me-2"></i> Agenda Kegiatan Universitas Terdekat
                        </h2>
                        <small class="text-muted">Jadwal kegiatan resmi yang telah disetujui oleh Humas & Publikasi</small>
                    </div>
                    <form action="{{ url()->current() }}" method="GET" class="m-0" style="min-width: 260px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-gray-100 border-end-0 text-muted">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" id="eventSearchInput"
                                class="form-control bg-gray-100 border-start-0 text-gray-800 shadow-none ps-0"
                                placeholder="Cari agenda kegiatan..." value="{{ request('search') }}" aria-label="Search">
                        </div>
                    </form>
                </div>

                <div class="card-body p-4" id="eventsListContainer">
                    @forelse ($latest_pengajuan as $item)
                        <div class="event-item-card d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3 mb-3 border rounded-3 bg-white">
                            <div class="d-flex align-items-center mb-3 mb-sm-0">
                                <!-- Calendar Icon Box -->
                                <div class="calendar-badge me-3 flex-shrink-0">
                                    <div class="calendar-badge-month">
                                        {{ \Carbon\Carbon::parse($item->tgl_awal)->locale('id')->translatedFormat('M') }}
                                    </div>
                                    <div class="calendar-badge-day">
                                        {{ \Carbon\Carbon::parse($item->tgl_awal)->format('d') }}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-gray-900 mb-1" style="font-size: 14px;">
                                        {{ $item->nama_kegiatan }}
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center gap-2 text-muted" style="font-size: 12px;">
                                        <span>
                                            <i class="fa-solid fa-building me-1 text-primary"></i>
                                            {{ $item->user->name ?? 'Penyelenggara' }}
                                        </span>
                                        <span>&bull;</span>
                                        <span>
                                            <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                                            {{ $item->tempat_kegiatan }}
                                        </span>
                                        <span>&bull;</span>
                                        <span>
                                            <i class="fa-solid fa-clock me-1 text-warning"></i>
                                            {{ $item->jam_kegiatan }} - {{ $item->waktu_selesai }} WIB
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-soft text-success px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                                    Disetujui
                                </span>
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-outline-secondary btn-sm px-2 py-1" title="Lihat Agenda">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-3x" style="opacity: 0.35;"></i>
                            </div>
                            <h6 class="fw-bold text-gray-800 mb-1">Belum Ada Agenda Mendatang</h6>
                            <p class="text-muted small mb-0">
                                Kegiatan yang telah disetujui oleh Humas akan otomatis ditampilkan dalam jadwal kalender ini.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($latest_pengajuan->hasPages())
                    <div class="card-footer bg-white border-top py-3 d-flex justify-content-center" id="eventsPaginationContainer">
                        {{ $latest_pengajuan->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- DASHBOARD STYLING --}}
<style>
    /* Hero Card */
    .hero-welcome-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #047857 100%) !important;
        border-radius: 1rem !important;
    }
    .hero-role-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
    }
    .hero-decorative-shape {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(52, 211, 153, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Soft Colors */
    .bg-primary-soft { background-color: #EFF6FF !important; color: #2563EB !important; }
    .bg-success-soft { background-color: #ECFDF5 !important; color: #059669 !important; }
    .bg-warning-soft { background-color: #FFFBEB !important; color: #D97706 !important; }
    .bg-danger-soft  { background-color: #FEF2F2 !important; color: #DC2626 !important; }
    .bg-info-soft    { background-color: #F0F9FF !important; color: #0284C7 !important; }
    .bg-purple       { background-color: #9333ea !important; }

    /* Calendar Badge */
    .calendar-badge {
        width: 52px;
        height: 54px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid #E2E8F0;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .calendar-badge-month {
        background: #DC2626;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 0;
        line-height: 1.2;
    }
    .calendar-badge-day {
        background: #ffffff;
        color: #1E293B;
        font-size: 18px;
        font-weight: 800;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    /* Workflow Timeline */
    .step-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .event-item-card {
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }
    .event-item-card:hover {
        border-color: #CBD5E1 !important;
        background-color: #F8FAFC !important;
    }

    /* Chartist Styling */
    .line-chart .ct-bar:nth-of-type(1) { stroke: #007F3B !important; }
    .line-chart .ct-bar:nth-of-type(2) { stroke: #fe9a20 !important; }
    .line-chart .ct-bar:nth-of-type(3) { stroke: #810000 !important; }
    .line-chart .ct-bar:nth-of-type(4) { stroke: #B33791 !important; }
    .line-chart .ct-bar { stroke-width: 18px; }
</style>
@endsection

@push('scripts')
<script>
    @if ($role === 'admin')
        // Bar Chart: Pemantauan Kegiatan Per Fakultas
        new Chartist.Bar('.line-chart', {
            labels: ['Rektorat', 'FEB', 'FST', 'FIKES'],
            series: [
                [
                    {
                        meta: JSON.stringify({
                            unit: "Rektorat",
                            total: {{ $pengajuan_rektorat }},
                            items: @json($list_rektorat)
                        }),
                        value: {{ $pengajuan_rektorat }}
                    },
                    {
                        meta: JSON.stringify({
                            unit: "FEB",
                            total: {{ $pengajuan_feb }},
                            items: @json($list_feb)
                        }),
                        value: {{ $pengajuan_feb }}
                    },
                    {
                        meta: JSON.stringify({
                            unit: "FST",
                            total: {{ $pengajuan_fst }},
                            items: @json($list_fst)
                        }),
                        value: {{ $pengajuan_fst }}
                    },
                    {
                        meta: JSON.stringify({
                            unit: "FIKES",
                            total: {{ $pengajuan_fikes }},
                            items: @json($list_fikes)
                        }),
                        value: {{ $pengajuan_fikes }}
                    }
                ]
            ]
        }, {
            low: 0,
            showArea: true,
            plugins: [
                Chartist.plugins.tooltip({
                    class: 'chartist-tooltip',
                    appendToBody: true,
                    transformTooltipTextFnc: function(meta) {
                        return meta;
                    }
                })
            ],
            axisY: {
                onlyInteger: true,
                offset: 30
            },
            axisX: {
                offset: 40
            }
        }, [
            ['screen and (max-width: 640px)', {
                axisX: {
                    labelInterpolationFnc: function(value) {
                        if (value === 'Rektorat') return 'Rekt';
                        if (value === 'FEB') return 'FEB';
                        if (value === 'FST') return 'FST';
                        if (value === 'FIKES') return 'FIKES';
                        return value;
                    }
                }
            }]
        ]);

        function renderTooltipTable(data) {
            if (!data) return '';
            let unitName = data.unit || 'Fakultas';
            let totalCount = typeof data.total !== 'undefined' ? data.total : (data.items ? data.items.length : 0);

            let html = `
                <div class="tooltip-table-wrapper" style="padding: 10px; font-family: inherit;">
                    <div style="font-weight: 700; font-size: 12px; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 4px; color: #34d399;">
                        ${unitName} — Total: ${totalCount} Disetujui
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr style="color: #94a3b8; text-transform: uppercase;">
                                <th style="text-align: left; padding: 4px 6px;">Kegiatan</th>
                                <th style="text-align: right; padding: 4px 6px;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            if (data.items && data.items.length > 0) {
                data.items.slice(0, 5).forEach(item => {
                    html += `
                        <tr style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <td style="padding: 4px 6px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${item.nama}</td>
                            <td style="padding: 4px 6px; text-align: right; color: #cbd5e1;">${item.tgl}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="2" style="text-align:center; padding: 6px; color: #94a3b8;">Belum ada kegiatan</td></tr>`;
            }

            html += `
                        </tbody>
                    </table>
                </div>
            `;
            return html;
        }

        function updateTooltipContent(node) {
            if (node.querySelector('.tooltip-table-wrapper')) return;
            const rawContent = node.textContent || "";
            if (rawContent.includes('{"') && rawContent.includes('}')) {
                const startIdx = rawContent.indexOf('{');
                const endIdx = rawContent.lastIndexOf('}');
                if (startIdx !== -1 && endIdx !== -1 && endIdx > startIdx) {
                    try {
                        const jsonStr = rawContent.substring(startIdx, endIdx + 1);
                        const data = JSON.parse(jsonStr);
                        node.innerHTML = renderTooltipTable(data);
                    } catch (e) {}
                }
            }
        }

        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.classList && node.classList.contains('chartist-tooltip')) {
                            updateTooltipContent(node);
                        }
                    });
                }
                if (mutation.target.classList && mutation.target.classList.contains('chartist-tooltip')) {
                    updateTooltipContent(mutation.target);
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true, characterData: true });
    @endif

    // Live Search for Events
    const searchInput = document.getElementById('eventSearchInput');
    const eventsContainer = document.getElementById('eventsListContainer');
    const paginationContainer = document.getElementById('eventsPaginationContainer');
    let typingTimer;
    const doneTypingInterval = 400;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                clearTimeout(typingTimer);
                performSearch();
            }
        });
    }

    function performSearch() {
        const query = searchInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', query);
        url.searchParams.set('page', 1);

        window.history.pushState({}, '', url);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newEvents = doc.getElementById('eventsListContainer');
            const newPagination = doc.getElementById('eventsPaginationContainer');

            if (newEvents && eventsContainer) {
                eventsContainer.innerHTML = newEvents.innerHTML;
            }
            if (paginationContainer) {
                if (newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                    paginationContainer.style.display = '';
                } else {
                    paginationContainer.innerHTML = '';
                    paginationContainer.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error fetching events:', error));
    }
</script>
@endpush
