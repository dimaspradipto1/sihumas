@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan Kegiatan</a></li>
                <li class="breadcrumb-item active text-capitalize" aria-current="page">Detail Pengajuan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-3">
            <div>
                <h1 class="h4 text-capitalize fw-bold mb-1">
                    <i class="fa-solid fa-file-lines me-2 text-primary"></i> Detail Pengajuan Kegiatan
                </h1>
                <p class="text-muted small mb-0">Informasi lengkap rincian pengajuan liputan / publikasi kegiatan (Read Only).</p>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->is_admin)
                    <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="btn btn-warning text-white d-inline-flex align-items-center shadow-sm">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Verifikasi / Edit
                    </a>
                @endif
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('pengajuan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> KEMBALI
                </a>
            </div>
        </div>
    </div>

    <!-- Status Banner Alert -->
    @if ($pengajuan->status == 'diterima')
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check fa-2x me-3 text-success"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Pengajuan Telah Diterima &amp; Diverifikasi Humas</h6>
                <p class="mb-0 small">
                    Kegiatan ini telah disetujui. Petugas Humas (PIC) yang ditugaskan dapat dilihat pada rincian di bawah ini.
                    @if (!empty($pengajuan->alasan_ditolak))
                        <span class="d-block mt-1"><strong>Catatan Humas:</strong> {{ $pengajuan->alasan_ditolak }}</span>
                    @endif
                </p>
            </div>
        </div>
    @elseif ($pengajuan->status == 'ditolak')
        <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-xmark fa-2x me-3 text-danger"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Pengajuan Ditolak</h6>
                <p class="mb-0 small">
                    Pengajuan ini tidak dapat diproses.
                    @if (!empty($pengajuan->alasan_ditolak))
                        <span class="d-block mt-1"><strong>Alasan:</strong> {{ $pengajuan->alasan_ditolak }}</span>
                    @endif
                </p>
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-hourglass-half fa-2x me-3 text-warning"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Menunggu Verifikasi Tim Humas</h6>
                <p class="mb-0 small">Pengajuan kegiatan Anda telah tercatat dan sedang dalam antrean verifikasi serta penugasan PIC oleh Admin Humas.</p>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Kiri: Informasi Utama Kegiatan -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gray-100 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-gray-800 fw-bold">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i> Rincian Informasi Kegiatan
                    </h5>
                    <span class="badge bg-light text-dark border px-2 py-1 small">
                        <i class="fa-regular fa-clock me-1"></i> Diajukan: {{ $pengajuan->created_at ? $pengajuan->created_at->locale('id')->diffForHumans() : '-' }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 28%;" class="ps-3 text-muted small text-uppercase">Nama Kegiatan</th>
                                    <td class="pe-3 fw-bold text-gray-900 fs-6">
                                        {{ $pengajuan->nama_kegiatan }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Unit Pengusul</th>
                                    <td class="pe-3">
                                        <span class="badge bg-primary px-2 py-1"><i class="fa-solid fa-building me-1"></i> {{ $pengajuan->unit_kegiatan ?: ($pengajuan->user?->unitKegiatan?->unit_kegiatan ?? '-') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Pemohon / Pengusul</th>
                                    <td class="pe-3">
                                        <div class="fw-semibold text-gray-800">{{ $pengajuan->user?->name ?? '-' }}</div>
                                        @if ($pengajuan->user?->email)
                                            <div class="small text-muted"><i class="fa-regular fa-envelope me-1"></i> {{ $pengajuan->user->email }}</div>
                                        @endif
                                        @if ($pengajuan->user?->no_wa)
                                            <div class="small text-success">
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengajuan->user->no_wa) }}" target="_blank" class="text-success text-decoration-none">
                                                    <i class="fa-brands fa-whatsapp me-1"></i> {{ $pengajuan->user->no_wa }}
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Tanggal Kegiatan</th>
                                    <td class="pe-3">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                <i class="fa-regular fa-calendar-check text-primary me-1"></i> Mulai: {{ $pengajuan->tgl_awal ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                            </span>
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                <i class="fa-regular fa-calendar-xmark text-danger me-1"></i> Selesai: {{ $pengajuan->tgl_selesai ? \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Waktu Kegiatan</th>
                                    <td class="pe-3">
                                        <span class="badge bg-info text-white px-2 py-1">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            {{ $pengajuan->jam_kegiatan ? substr($pengajuan->jam_kegiatan, 0, 5) : '-' }} WIB
                                            @if ($pengajuan->waktu_selesai)
                                                - {{ substr($pengajuan->waktu_selesai, 0, 5) }} WIB
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Tempat Kegiatan</th>
                                    <td class="pe-3">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                        <span class="fw-semibold">{{ $pengajuan->tempat_kegiatan ?? '-' }}</span>
                                    </td>
                                </tr>
                                @if ($pengajuan->link_zoom)
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Link Zoom / Virtual</th>
                                    <td class="pe-3">
                                        <a href="{{ $pengajuan->link_zoom }}" target="_blank" class="btn btn-xs btn-outline-primary d-inline-flex align-items-center py-1 px-2 text-break">
                                            <i class="fa-solid fa-video me-1"></i> Buka Link Zoom
                                        </a>
                                        <small class="text-muted d-block mt-1 text-break">{{ $pengajuan->link_zoom }}</small>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Deskripsi Kegiatan</th>
                                    <td class="pe-3 text-break">
                                        @php
                                            $cleanDesk = trim(strip_tags($pengajuan->deskripsi_kegiatan ?? ''));
                                        @endphp
                                        @if ($cleanDesk)
                                            <div class="p-3 bg-light rounded border text-gray-800" style="white-space: pre-wrap; font-size: 0.875rem; line-height: 1.6;">{{ $cleanDesk }}</div>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Kebutuhan Perlengkapan</th>
                                    <td class="pe-3 text-break">
                                        @php
                                            $cleanPerl = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($pengajuan->perlengkapan ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                                        @endphp
                                        @if ($cleanPerl)
                                            <div class="p-2 bg-light rounded border small text-gray-800">{{ $cleanPerl }}</div>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($pengajuan->nama_kegiatan == 'Desain' || $pengajuan->nama_kegiatan == 'Desain dan Dokumentasi' || !empty($pengajuan->link_desain))
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase">Link Desain</th>
                                    <td class="pe-3">
                                        @if ($pengajuan->link_desain)
                                            <a href="{{ $pengajuan->link_desain }}" target="_blank" class="btn btn-xs btn-outline-info d-inline-flex align-items-center py-1 px-2">
                                                <i class="fa-solid fa-palette me-1"></i> Buka Link Desain
                                            </a>
                                            <small class="text-muted d-block mt-1 text-break">{{ $pengajuan->link_desain }}</small>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status, Petugas PIC, & Lampiran -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- Card Status & Verifikasi -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gray-100 py-3">
                        <h6 class="card-title mb-0 text-gray-800 fw-bold">
                            <i class="fa-solid fa-clipboard-check me-2 text-success"></i> Status &amp; Verifikasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center p-3 bg-light rounded border">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold">Status Saat Ini</span>
                            @if ($pengajuan->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">
                                    <i class="fa-solid fa-clock me-1"></i> Pending
                                </span>
                            @elseif ($pengajuan->status == 'diterima')
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-6">
                                    <i class="fa-solid fa-check me-1"></i> Diterima
                                </span>
                            @elseif ($pengajuan->status == 'ditolak')
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-6">
                                    <i class="fa-solid fa-xmark me-1"></i> Ditolak
                                </span>
                            @endif
                        </div>

                        @if (!empty($pengajuan->alasan_ditolak))
                            <div class="mb-2">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Catatan / Alasan:</label>
                                <div class="p-2 bg-light border rounded small text-gray-800">
                                    {{ $pengajuan->alasan_ditolak }}
                                </div>
                            </div>
                        @endif

                        @if ($pengajuan->status == 'diterima' && $pengajuan->publikasi)
                            <div class="mt-3 text-center">
                                <a href="{{ route('publikasi.show', $pengajuan->publikasi->slug ?? $pengajuan->publikasi->id) }}" class="btn btn-sm btn-outline-success w-100 d-inline-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-file-invoice me-1"></i> Lihat Rekap Pelaporan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Petugas PIC yang Ditugaskan -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gray-100 py-3">
                        <h6 class="card-title mb-0 text-gray-800 fw-bold">
                            <i class="fa-solid fa-user-check me-2 text-primary"></i> Petugas PIC Humas
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $picList = $pengajuan->getVisiblePicPetugas(Auth::user());
                            $picDesain = array_values(array_filter($picList, fn($p) => ($p['peran'] ?? 'desain') === 'desain'));
                            $picDokumentasi = array_values(array_filter($picList, fn($p) => ($p['peran'] ?? '') === 'dokumentasi'));
                        @endphp

                        @if (!empty($picDesain) || !empty($picDokumentasi))
                            @if (!empty($picDesain))
                                <div class="mb-3">
                                    <span class="badge bg-primary text-white mb-2"><i class="fa-solid fa-palette me-1"></i> PIC Desain</span>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($picDesain as $pd)
                                            @if (!empty($pd['nama']) || !empty($pd['no_wa']))
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong class="text-gray-900 small">{{ $pd['nama'] ?: '-' }}</strong>
                                                        @if (!empty($pd['no_wa']))
                                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pd['no_wa']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">
                                                                <i class="fa-brands fa-whatsapp me-1"></i> WA
                                                            </a>
                                                        @endif
                                                    </div>
                                                    @if (!empty($pd['catatan']))
                                                        <div class="text-muted small fst-italic mt-1 border-top pt-1">
                                                            <i class="fa-solid fa-comment-dots me-1 text-primary"></i> {{ $pd['catatan'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($picDokumentasi))
                                <div>
                                    <span class="badge bg-info text-white mb-2"><i class="fa-solid fa-camera me-1"></i> PIC Dokumentasi</span>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($picDokumentasi as $pdk)
                                            @if (!empty($pdk['nama']) || !empty($pdk['no_wa']))
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong class="text-gray-900 small">{{ $pdk['nama'] ?: '-' }}</strong>
                                                        @if (!empty($pdk['no_wa']))
                                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pdk['no_wa']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">
                                                                <i class="fa-brands fa-whatsapp me-1"></i> WA
                                                            </a>
                                                        @endif
                                                    </div>
                                                    @if (!empty($pdk['catatan']))
                                                        <div class="text-muted small fst-italic mt-1 border-top pt-1">
                                                            <i class="fa-solid fa-comment-dots me-1 text-info"></i> {{ $pdk['catatan'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @elseif ($pengajuan->petugas_ditugaskan)
                            <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small"><i class="fa-solid fa-user me-1 text-primary"></i> {{ $pengajuan->petugas_ditugaskan }}</span>
                                @if ($pengajuan->no_wa_tujuan)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengajuan->no_wa_tujuan) }}" target="_blank" class="badge bg-success text-white">
                                        <i class="fa-brands fa-whatsapp me-1"></i> WA
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-3 text-muted small fst-italic bg-light rounded border">
                                <i class="fa-solid fa-user-clock fa-2x mb-2 d-block text-gray-400"></i>
                                Belum ada petugas PIC yang ditugaskan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Dokumen Pendukung & Bahan Materi -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gray-100 py-3">
                        <h6 class="card-title mb-0 text-gray-800 fw-bold">
                            <i class="fa-solid fa-paperclip me-2 text-primary"></i> Lampiran &amp; Berkas
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Dokumen Pendukung -->
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-2">Dokumen Pendukung:</label>
                            @if ($pengajuan->upload_file)
                                @php
                                    $ext = strtoupper(pathinfo($pengajuan->upload_file, PATHINFO_EXTENSION));
                                @endphp
                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center w-100 justify-content-center">
                                    <i class="fa-solid fa-file-arrow-down me-2"></i> Unduh Dokumen ({{ $ext ?: 'FILE' }})
                                </a>
                                <small class="text-muted d-block text-truncate mt-1 text-center" style="font-size: 11px;">
                                    {{ basename($pengajuan->upload_file) }}
                                </small>
                            @else
                                <span class="text-muted small fst-italic d-block text-center py-2 bg-light rounded border">Tidak ada dokumen pendukung</span>
                            @endif
                        </div>

                        <!-- Bahan Materi -->
                        <div>
                            <label class="small text-muted fw-bold text-uppercase d-block mb-2">Bahan Materi (Gambar):</label>
                            @if ($pengajuan->upload_png)
                                <div class="text-center p-2 bg-light border rounded">
                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="d-block mb-2">
                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Bahan Materi" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: contain;">
                                    </a>
                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="btn btn-xs btn-outline-success d-inline-flex align-items-center">
                                        <i class="fa-solid fa-eye me-1"></i> Buka Gambar Penuh
                                    </a>
                                </div>
                            @else
                                <span class="text-muted small fst-italic d-block text-center py-2 bg-light rounded border">Tidak ada bahan materi gambar</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
