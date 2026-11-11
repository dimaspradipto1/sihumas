@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publikasi.index') }}">Pelaporan Kegiatan</a></li>
                <li class="breadcrumb-item active text-capitalize" aria-current="page">Detail Pelaporan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">Detail Pelaporan Kegiatan</h1>
            </div>
            <div>
                <a href="{{ route('publikasi.edit', $publikasi->slug ?? $publikasi->id) }}" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Banner Status Validasi -->
    @if ($publikasi->status_validasi === 'perlu_revisi' || $publikasi->status_validasi === 'ditolak')
        <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation fa-2x me-3 text-danger"></i>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1">{{ $publikasi->status_validasi === 'ditolak' ? 'Laporan Ditolak oleh Admin' : 'Perlu Revisi dari Admin' }}</h6>
                <p class="mb-0 text-danger fw-semibold">
                    {{ $publikasi->catatan_validasi ?? 'Mohon periksa dan perbaiki kelengkapan dokumentasi atau rilis laporan kegiatan.' }}
                </p>
            </div>
            <div class="ms-3">
                <a href="{{ route('publikasi.edit', $publikasi->slug ?? $publikasi->id) }}" class="btn btn-sm btn-danger text-white">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Perbaiki Sekarang
                </a>
            </div>
        </div>
    @elseif ($publikasi->status_validasi === 'menunggu_validasi')
        <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-clock fa-2x me-3 text-warning"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Menunggu Validasi Admin</h6>
                <p class="mb-0">Laporan kegiatan ini telah dikirim oleh PIC Humas dan sedang menunggu peninjauan / validasi oleh Super Admin.</p>
            </div>
        </div>
    @elseif ($publikasi->status_validasi === 'disetujui')
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check fa-2x me-3 text-success"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Laporan Tervalidasi &amp; Disetujui</h6>
                <p class="mb-0">
                    Laporan kegiatan ini telah divalidasi oleh {{ $publikasi->validator?->name ?? 'Super Admin' }}
                    @if ($publikasi->validated_at)
                        pada {{ $publikasi->validated_at->locale('id')->translatedFormat('l, d F Y H:i') }}.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Data Pelaporan -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-header bg-gray-100 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-gray-800">
                        <i class="fa-solid fa-file-lines me-2 text-primary"></i> Rincian Pelaporan Kegiatan
                    </h5>
                    <div>
                        @if ($publikasi->status_validasi === 'disetujui')
                            <span class="badge bg-success text-white px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Disetujui</span>
                        @elseif ($publikasi->status_validasi === 'menunggu_validasi')
                            <span class="badge bg-warning text-dark px-3 py-2"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi</span>
                        @elseif ($publikasi->status_validasi === 'ditolak')
                            <span class="badge bg-danger text-white px-3 py-2"><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak</span>
                        @elseif ($publikasi->status_validasi === 'perlu_revisi')
                            <span class="badge bg-danger text-white px-3 py-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Revisi</span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-2"><i class="fa-solid fa-hourglass-start me-1"></i> Belum Dilaporkan</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7" style="width: 20%;">Status Validasi</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1" style="width: 2%;">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7" style="width: 78%;">
                                        @if ($publikasi->status_validasi === 'disetujui')
                                            <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Disetujui / Valid</span>
                                        @elseif ($publikasi->status_validasi === 'menunggu_validasi')
                                            <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi Admin</span>
                                        @elseif ($publikasi->status_validasi === 'perlu_revisi')
                                            <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Revisi</span>
                                        @else
                                            <span class="badge bg-secondary text-white px-2 py-1"><i class="fa-solid fa-hourglass-start me-1"></i> Belum Dilaporkan</span>
                                        @endif
                                    </td>
                                </tr>

                                @if ($publikasi->catatan_validasi)
                                <tr>
                                    <td class="text-uppercase text-danger text-xxs font-weight-bolder">Catatan Validasi Admin</td>
                                    <td class="text-danger text-xxs font-weight-bolder px-1">:</td>
                                    <td class="text-danger font-weight-bold">
                                        <div class="p-2 bg-danger-subtle border border-danger rounded text-danger" style="font-size: 0.875rem;">
                                            <i class="fa-solid fa-comment-dots me-1"></i> {{ $publikasi->catatan_validasi }}
                                        </div>
                                    </td>
                                </tr>
                                @endif

                                @if ($publikasi->validated_by)
                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Divalidasi Oleh</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        <span class="badge bg-primary text-white px-2 py-1">
                                            <i class="fa-solid fa-user-shield me-1"></i> {{ $publikasi->validator?->name ?? 'Admin' }}
                                        </span>
                                        @if ($publikasi->validated_at)
                                            <small class="text-muted ms-2">pada {{ $publikasi->validated_at->locale('id')->translatedFormat('l, d F Y H:i') }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Tahun Akademik</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize">
                                        {{ $publikasi->tahunAkademik?->tahun_akademik ?? (\App\Models\TahunAkademik::getActive()?->tahun_akademik ?? '-') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Nama Kegiatan</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 fw-bold text-primary">
                                        {{ $publikasi->pengajuan?->nama_kegiatan ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Tanggal Kegiatan</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        {{ $publikasi->pengajuan?->tgl_awal ? \Carbon\Carbon::parse($publikasi->pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Tanggal Selesai</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        {{ $publikasi->pengajuan?->tgl_selesai ? \Carbon\Carbon::parse($publikasi->pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Laporan Kegiatan</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        @if ($publikasi->upload_laporan)
                                            @if (filter_var(trim(strip_tags($publikasi->upload_laporan)), FILTER_VALIDATE_URL))
                                                <a href="{{ trim(strip_tags($publikasi->upload_laporan)) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-success text-capitalize">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Link Laporan
                                                </a>
                                            @else
                                                <div class="p-3 bg-gray-50 rounded border text-gray-800" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.6;">{!! nl2br(e(strip_tags($publikasi->upload_laporan))) !!}</div>
                                            @endif
                                        @else
                                            <span class="text-muted fst-italic">Belum ada laporan kegiatan diisi</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Link Dokumentasi</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        @if ($publikasi->link_dokumentasi)
                                            <a href="{{ $publikasi->link_dokumentasi }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center py-1 px-3 text-capitalize">
                                                <i class="fa-solid fa-folder-open me-2"></i> Buka Google Drive Dokumentasi
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Belum ada link dokumentasi</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Link Publikasi</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7 px-1">:</td>
                                    <td class="text-dark text-xxs font-weight-bolder opacity-7">
                                        @php
                                            $links = $publikasi->link_publikasi_array;
                                        @endphp
                                        @if (!empty($links))
                                            <div class="d-flex flex-column gap-2">
                                                @foreach ($links as $idx => $link)
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <a href="{{ $link }}" target="_blank"
                                                            class="btn btn-sm btn-outline-success d-inline-flex align-items-center py-1 px-3 text-capitalize">
                                                            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Buka Publikasi {{ count($links) > 1 ? '#' . ($idx + 1) : '' }}
                                                        </a>
                                                        <small class="text-muted text-break">({{ $link }})</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Belum ada link publikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if (Auth::user()->is_admin)
                <!-- Panel Validasi Khusus Super Admin -->
                <div class="card-footer bg-light border-top p-4">
                    <h6 class="fw-bold text-gray-800 mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-clipboard-check text-success me-2"></i> Form Validasi Pelaporan Kegiatan (Super Admin)
                    </h6>
                    <form action="{{ route('publikasi.validasi', $publikasi->slug ?? $publikasi->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="status_validasi_select" class="form-label fw-bold">Status Validasi <span class="text-danger">*</span></label>
                                <select name="status_validasi" id="status_validasi_select" class="form-select" required>
                                    <option value="disetujui" {{ old('status_validasi', $publikasi->status_validasi) == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui / Valid
                                    </option>
                                    <option value="ditolak" {{ old('status_validasi', $publikasi->status_validasi) == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak (Kembalikan ke PIC)
                                    </option>
                                    <option value="perlu_revisi" {{ old('status_validasi', $publikasi->status_validasi) == 'perlu_revisi' ? 'selected' : '' }}>
                                        Perlu Revisi (Kembalikan ke PIC)
                                    </option>
                                    <option value="menunggu_validasi" {{ old('status_validasi', $publikasi->status_validasi) == 'menunggu_validasi' ? 'selected' : '' }}>
                                        Menunggu Validasi
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 col-md-8">
                                <label for="catatan_validasi_input" class="form-label fw-bold">Catatan Validasi / Catatan Revisi</label>
                                <textarea name="catatan_validasi" id="catatan_validasi_input" rows="2" class="form-control"
                                    placeholder="Tuliskan catatan untuk PIC Humas jika ada revisi atau apresiasi...">{{ old('catatan_validasi', $publikasi->catatan_validasi) }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-success text-white d-inline-flex align-items-center px-4">
                                    <i class="fa-solid fa-circle-check me-2"></i> Simpan Validasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <div class="card-footer bg-white border-top py-3 d-flex justify-content-start align-items-center">
                    <a href="{{ route('publikasi.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center px-3 py-2">
                        <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
