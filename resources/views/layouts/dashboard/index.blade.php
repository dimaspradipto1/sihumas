@extends('layouts.dashboard.template')

@section('content')
<div class="py-3">
    {{-- 1. HERO WELCOME BANNER (PROFESSIONAL UIS GREEN #046B26 & YELLOW #FED802) --}}
    <div class="card border-0 shadow-sm mb-4 text-white overflow-hidden hero-welcome-card">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center position-relative" style="z-index: 2;">
                <div class="mb-3 mb-lg-0">
                    <div class="d-inline-flex align-items-center gap-2 mb-2 px-3 py-1 rounded-pill hero-role-badge">
                        <span class="badge hero-role-tag">
                            {{ $roleLabel }}
                        </span>
                        <span class="text-white small fw-semibold">{{ $unitName }}</span>
                    </div>
                    <h1 class="h3 fw-bold text-white mb-2">
                        Selamat Datang, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="hero-desc mb-0 font-small" style="max-width: 680px; line-height: 1.6;">
                        @if ($role === 'admin')
                            Sistem Informasi Humas & Publikasi Universitas Ibnu Sina. Pantau status pengajuan kegiatan, verifikasi permohonan dari seluruh fakultas, serta kelola dokumentasi publikasi secara terpadu.
                        @else
                            Portal layanan terpadu Humas & Publikasi untuk {{ $unitName }}. Ajukan kegiatan unit Anda, pantau persetujuan berkas, dan laporkan dokumentasi publikasi kampus dengan cepat dan transparan.
                        @endif
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2 flex-shrink-0">
                    @if ($role === 'admin')
                        <a href="{{ route('kotak-masuk-pengajuan.index') }}" class="btn btn-uis-yellow px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-inbox me-2"></i> Kotak Masuk
                            @if (($total_pending ?? 0) > 0)
                                <span class="badge bg-danger ms-2 px-2 py-1 rounded-pill text-white">{{ $total_pending }}</span>
                            @endif
                        </a>
                        <a href="{{ route('publikasi.index') }}" class="btn btn-uis-white px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-newspaper me-2"></i> Pelaporan Kegiatan
                        </a>
                    @else
                        <a href="{{ route('pengajuan.create') }}" class="btn btn-uis-yellow px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-circle-plus me-2"></i> Buat Pengajuan Baru
                        </a>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-uis-white px-3 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-list-check me-2"></i> Status Pengajuan
                        </a>
                    @endif
                </div>
            </div>
            <!-- Background Decorative Element (UIS Yellow Glow) -->
            <div class="hero-decorative-shape"></div>
        </div>
    </div>

    {{-- 2. JADWAL MINGGU INI (EDLINK STYLE SCHEDULE WIDGET - PLACED AT THE TOP) --}}
    <div class="card border-0 shadow-sm mb-4 edlink-schedule-card">
        <div class="card-body p-4">
            <!-- Header: Title & Today Date / Week Navigator -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3">
                <div class="d-flex align-items-center gap-2 mb-2 mb-sm-0">
                    <h4 class="fw-bold text-gray-900 mb-0" style="font-size: 1.25rem;">Jadwal Minggu Ini</h4>
                    <div class="btn-group btn-group-sm ms-2" role="group">
                        <a href="{{ route('dashboard', ['week_offset' => $weekOffset - 1]) }}" class="btn btn-outline-light border text-muted py-0 px-2" title="Minggu Sebelumnya">
                            <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i>
                        </a>
                        @if ($weekOffset !== 0)
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light border text-primary py-0 px-2 fw-semibold" style="font-size: 11px;">
                                Minggu Ini
                            </a>
                        @endif
                        <a href="{{ route('dashboard', ['week_offset' => $weekOffset + 1]) }}" class="btn btn-outline-light border text-muted py-0 px-2" title="Minggu Berikutnya">
                            <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>
                <div class="text-muted fw-normal" style="font-size: 0.85rem;">
                    {{ $todayFormatted }}
                </div>
            </div>

            <!-- Edlink Weekday Selector Row -->
            <div class="edlink-week-selector position-relative mt-2">
                <div class="edlink-days-row d-flex justify-content-between text-center position-relative">
                    @foreach ($weekDays as $day)
                        <div class="edlink-day-item {{ $day['date'] === $selectedDate ? 'active' : '' }}"
                             data-date="{{ $day['date'] }}"
                             data-full-date="{{ $day['fullDate'] }}"
                             onclick="selectEdlinkDay('{{ $day['date'] }}', '{{ $day['fullDate'] }}', this)">
                            <div class="edlink-day-name mb-2">{{ $day['dayName'] }}</div>
                            <div class="edlink-day-number-wrapper">
                                <span class="edlink-day-number">{{ $day['dayNumber'] }}</span>
                            </div>
                            <div class="edlink-caret-indicator {{ $day['date'] === $selectedDate ? '' : 'd-none' }}">
                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L7 7L13 1" stroke="#E2E8F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="#FFFFFF"/>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="edlink-divider-line"></div>
            </div>

            <!-- Selected Date Events Content Area -->
            <div class="edlink-content-area pt-3 pb-1" id="edlinkContentArea">
                <!-- Javascript will dynamically display events for selected day or empty state -->
                <div id="edlinkEventsListContainer"></div>
            </div>
        </div>
    </div>

    {{-- 3. METRIC / KPI STAT CARDS --}}
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

    {{-- 4. MIDDLE SECTION (BERBEDA SESUAI ROLE) --}}
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
</div>

{{-- DASHBOARD STYLING --}}
<style>
    /* Hero Card (UIS Brand Colors: Green #046B26 & Yellow #FED802) */
    .hero-welcome-card {
        background: linear-gradient(135deg, #024518 0%, #046B26 55%, #057d2d 100%) !important;
        border-radius: 1rem !important;
        border-left: 6px solid #FED802 !important;
        box-shadow: 0 6px 20px rgba(4, 107, 38, 0.28) !important;
    }
    .hero-role-badge {
        background: rgba(0, 0, 0, 0.24);
        border: 1px solid rgba(254, 216, 2, 0.5);
        backdrop-filter: blur(8px);
    }
    .hero-role-tag {
        background-color: #FED802 !important;
        color: #046B26 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        padding: 4px 8px;
        font-size: 10px;
        letter-spacing: 0.5px;
    }
    .hero-desc {
        color: #f8fafc !important;
        font-size: 0.875rem;
    }
    .hero-decorative-shape {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(254, 216, 2, 0.22) 0%, rgba(254, 216, 2, 0) 70%);
        pointer-events: none;
    }

    /* UIS Buttons (Guaranteed Contrast & Readability) */
    .btn-uis-yellow {
        background-color: #FED802 !important;
        color: #046B26 !important;
        font-weight: 700 !important;
        border: 1px solid #FED802 !important;
        transition: background-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }
    .btn-uis-yellow:hover {
        background-color: #ebd002 !important;
        color: #023814 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(254, 216, 2, 0.4) !important;
    }
    .btn-uis-yellow i {
        color: #046B26 !important;
    }

    .btn-uis-white {
        background-color: #ffffff !important;
        color: #046B26 !important;
        font-weight: 700 !important;
        border: 1px solid #ffffff !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        transition: background-color 0.2s ease, transform 0.15s ease;
    }
    .btn-uis-white:hover {
        background-color: #f1f5f9 !important;
        color: #023814 !important;
        transform: translateY(-1px);
    }
    .btn-uis-white i {
        color: #046B26 !important;
    }

    /* EDLINK SCHEDULE COMPONENT */
    .edlink-schedule-card {
        border-radius: 1rem !important;
        background: #ffffff;
    }
    .edlink-days-row {
        gap: 8px;
        z-index: 2;
    }
    .edlink-divider-line {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #E2E8F0;
        z-index: 1;
    }
    .edlink-day-item {
        flex: 1;
        cursor: pointer;
        padding-bottom: 12px;
        position: relative;
        user-select: none;
        transition: color 0.15s ease;
    }
    .edlink-day-name {
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
        transition: color 0.15s ease, font-weight 0.15s ease;
    }
    .edlink-day-number-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .edlink-day-number {
        font-size: 16px;
        font-weight: 700;
        color: #1E293B;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .edlink-day-item:hover .edlink-day-name {
        color: #046B26;
    }
    .edlink-day-item.active .edlink-day-name {
        color: #046B26;
        font-weight: 700;
    }
    .edlink-day-item.active .edlink-day-number {
        background-color: #046B26;
        color: #ffffff;
    }
    .edlink-caret-indicator {
        position: absolute;
        bottom: -7px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        line-height: 1;
    }

    /* Event Cards in Edlink */
    .edlink-event-card {
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        background: #ffffff;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .edlink-event-card:hover {
        transform: translateY(-2px);
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .edlink-time-pill {
        background: #ECFDF5;
        color: #046B26;
        font-weight: 700;
        font-size: 11.5px;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
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
    // Data Events Minggu Ini dari Server
    const edlinkEventsMap = @json($weekEvents);
    let currentSelectedDate = "{{ $selectedDate }}";

    function selectEdlinkDay(dateStr, fullDateStr, element) {
        currentSelectedDate = dateStr;

        // Update class active pada daftar hari
        document.querySelectorAll('.edlink-day-item').forEach(item => {
            item.classList.remove('active');
            const caret = item.querySelector('.edlink-caret-indicator');
            if (caret) caret.classList.add('d-none');
        });

        if (element) {
            element.classList.add('active');
            const caret = element.querySelector('.edlink-caret-indicator');
            if (caret) caret.classList.remove('d-none');
        }

        renderEdlinkDayEvents(dateStr);
    }

    function renderEdlinkDayEvents(dateStr) {
        const container = document.getElementById('edlinkEventsListContainer');
        if (!container) return;

        const events = edlinkEventsMap[dateStr] || [];

        if (events.length === 0) {
            // Tampilan Kosong persis seperti Edlink
            container.innerHTML = `
                <div class="text-center py-4 my-2">
                    <div class="mb-3">
                        <svg width="120" height="110" viewBox="0 0 140 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Desk calendar -->
                            <rect x="25" y="24" width="75" height="65" rx="10" fill="#F1F5F9" stroke="#94A3B8" stroke-width="2"/>
                            <!-- Calendar rings -->
                            <rect x="38" y="16" width="6" height="14" rx="3" fill="#64748B"/>
                            <rect x="58" y="16" width="6" height="14" rx="3" fill="#64748B"/>
                            <rect x="78" y="16" width="6" height="14" rx="3" fill="#64748B"/>
                            <!-- Calendar grid cells -->
                            <rect x="34" y="38" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="52" y="38" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="70" y="38" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="34" y="52" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="52" y="52" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="70" y="52" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="34" y="66" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <rect x="52" y="66" width="12" height="10" rx="2" fill="#CBD5E1"/>
                            <!-- Alarm clock -->
                            <circle cx="88" cy="74" r="24" fill="#FFFFFF" stroke="#94A3B8" stroke-width="2.5"/>
                            <circle cx="88" cy="74" r="19" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1.5"/>
                            <!-- Clock ears -->
                            <rect x="70" y="52" width="7" height="12" rx="3" transform="rotate(-30 70 52)" fill="#94A3B8"/>
                            <rect x="100" y="50" width="7" height="12" rx="3" transform="rotate(30 100 50)" fill="#94A3B8"/>
                            <!-- Clock feet -->
                            <rect x="73" y="93" width="6" height="8" rx="2" transform="rotate(25 73 93)" fill="#94A3B8"/>
                            <rect x="97" y="95" width="6" height="8" rx="2" transform="rotate(-25 97 95)" fill="#94A3B8"/>
                            <!-- Clock face happy lines -->
                            <path d="M82 72L85 75L82 78" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M94 72L91 75L94 78" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M85 80C86.5 81.5 89.5 81.5 91 80" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"/>
                            <!-- Sparkle stars -->
                            <path d="M72 10L73.5 14L77.5 15.5L73.5 17L72 21L70.5 17L66.5 15.5L70.5 14L72 10Z" fill="#94A3B8"/>
                            <path d="M38 88L39 91L42 92L39 93L38 96L37 93L34 92L37 91L38 88Z" fill="#CBD5E1"/>
                        </svg>
                    </div>
                    <p class="text-gray-700 fw-medium mb-0" style="font-size: 14px;">
                        Tidak ada jadwal kegiatan di tanggal ini
                    </p>
                </div>
            `;
        } else {
            let html = '<div class="row g-3">';
            events.forEach(ev => {
                html += `
                    <div class="col-12 col-md-6">
                        <div class="edlink-event-card p-3 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="edlink-time-pill">
                                        <i class="fa-solid fa-clock me-1" style="font-size: 11px;"></i>
                                        ${ev.jam_kegiatan} - ${ev.waktu_selesai} WIB
                                    </span>
                                    <span class="badge bg-success-soft text-success px-2 py-1 rounded-pill fw-semibold" style="font-size: 10.5px;">
                                        Disetujui
                                    </span>
                                </div>
                                <h5 class="fw-bold text-gray-900 mb-1" style="font-size: 14.5px;">
                                    ${ev.nama_kegiatan}
                                </h5>
                                <div class="text-muted small mb-2 d-flex align-items-center flex-wrap gap-2">
                                    <span>
                                        <i class="fa-solid fa-building me-1 text-primary"></i> ${ev.organizer}
                                    </span>
                                    <span>&bull;</span>
                                    <span>
                                        <i class="fa-solid fa-location-dot me-1 text-danger"></i> ${ev.tempat_kegiatan}
                                    </span>
                                </div>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-end">
                                <a href="${ev.url}" class="btn btn-outline-primary btn-sm py-1 px-3 fw-semibold" style="font-size: 11.5px;">
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }
    }

    // Inisialisasi tampilan hari aktif pertama kali
    document.addEventListener('DOMContentLoaded', function () {
        renderEdlinkDayEvents(currentSelectedDate);
    });

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
</script>
@endpush
