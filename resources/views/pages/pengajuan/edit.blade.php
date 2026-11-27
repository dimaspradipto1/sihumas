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
            <li class="breadcrumb-item active text-capitalize" aria-current="page">data pengajuan berita
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4 text-capitalize">
                {{ (Auth::user()->is_admin || !Auth::user()->is_humas) ? 'form edit pengajuan berita' : 'detail pengajuan tugas kegiatan' }}
            </h1>
            <div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <form id="formEditPengajuan" action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    @method('PUT')

                    @if (!Auth::user()->is_admin && !Auth::user()->is_humas)
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">

                                @php
                                    $daftarJenisPengajuan = [
                                        'Desain' => 'Desain',
                                        'Dokumentasi' => 'Dokumentasi',
                                        'Desain dan Dokumentasi' => 'Desain dan Dokumentasi',
                                        'Publikasi' => 'Publikasi',
                                        'Desain dan Publikasi' => 'Desain dan Publikasi',
                                        'Tamu Undangan' => 'Tamu',
                                    ];
                                    $valJenis = old('nama_kegiatan', $pengajuan->nama_kegiatan ?? '');
                                    $isCustomJenis = !empty($valJenis) && !array_key_exists($valJenis, $daftarJenisPengajuan);
                                @endphp
                                <div class="mb-4">
                                    <label for="nama_kegiatan" class="text-capitalize">jenis pengajuan</label>
                                    <select name="{{ $isCustomJenis ? '' : 'nama_kegiatan' }}" id="nama_kegiatan" class="form-select single" {{ $isCustomJenis ? '' : 'required' }}>
                                        <option value="">Pilih Jenis Pengajuan atau Ketik Custom</option>
                                        @foreach ($daftarJenisPengajuan as $val => $label)
                                            <option value="{{ $val }}" {{ $valJenis === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                        @if ($isCustomJenis)
                                            <option value="{{ $valJenis }}" selected>{{ $valJenis }}</option>
                                        @endif
                                        <option value="__custom__" {{ $isCustomJenis ? 'selected' : '' }}>➕ Lainnya (Ketik Manual)...</option>
                                    </select>
                                    <div id="wrapper_custom_jenis" class="mt-2 {{ $isCustomJenis ? '' : 'd-none' }}">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-pen"></i></span>
                                            <input type="text" id="input_custom_jenis" class="form-control"
                                                name="{{ $isCustomJenis ? 'nama_kegiatan' : '' }}"
                                                value="{{ $isCustomJenis ? $valJenis : '' }}"
                                                placeholder="Tuliskan jenis pengajuan custom / lainnya..."
                                                {{ $isCustomJenis ? 'required' : 'disabled' }}>
                                        </div>
                                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i>Pilih opsi di atas atau ketik langsung jenis pengajuan baru.</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6" id="col_tgl_awal">
                                        <div class="mb-4 mb-md-0">
                                            <label for="tgl_awal" id="label_tgl_awal" class="text-capitalize fw-bold">tanggal mulai kegiatan</label>
                                            <input type="date" name="tgl_awal"
                                                value="{{ old('tgl_awal', $pengajuan->tgl_awal) }}" class="form-control"
                                                id="tgl_awal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-none" id="col_target_publikasi">
                                        <div class="mb-4 mb-md-0">
                                            <label for="target_publikasi_tgl" class="text-capitalize fw-bold">Target Publikasi <span class="text-danger">*</span></label>
                                            <input type="date" name="tgl_selesai" id="target_publikasi_tgl" class="form-control"
                                                value="{{ old('tgl_selesai', $pengajuan->tgl_selesai ?? $pengajuan->tgl_awal) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="wrapper_jam_kegiatan">
                                        <div class="mb-4 mb-md-0">
                                            <label for="jam_kegiatan" class="text-capitalize">jam kegiatan</label>
                                            <input type="time" name="jam_kegiatan" id="jam_kegiatan" class="form-control"
                                                value="{{ old('jam_kegiatan', $pengajuan->jam_kegiatan ? substr($pengajuan->jam_kegiatan, 0, 5) : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4" id="wrapper_tgl_selesai_waktu">
                                    <div class="col-md-6">
                                        <div class="mb-4 mb-md-0">
                                            <label for="tgl_selesai" class="text-capitalize">tanggal Selesai kegiatan</label>
                                            <input type="date" name="tgl_selesai"
                                                value="{{ old('tgl_selesai', $pengajuan->tgl_selesai) }}" class="form-control"
                                                id="tgl_selesai">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4 mb-md-0">
                                            <label for="waktu_selesai" class="text-capitalize">waktu selesai</label>
                                            <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control"
                                                value="{{ old('waktu_selesai', $pengajuan->waktu_selesai ? substr($pengajuan->waktu_selesai, 0, 5) : '') }}">
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $daftarTempat = [
                                        'Ruang Rapat Rektorat',
                                        'Ruang Rapat FEB',
                                        'Ruang Rapat FST',
                                        'Ruang Rapat FIKES',
                                        'Ruang Rapat Gedung B LT 4',
                                        'Ruang Rapat Gedung F Lt 5',
                                        'Aula Universitas Ibnu Sina',
                                    ];
                                    $valTempat = old('tempat_kegiatan', $pengajuan->tempat_kegiatan ?? '');
                                    $isCustomTempat = !empty($valTempat) && !in_array($valTempat, $daftarTempat);
                                @endphp

                                <div class="mb-4" id="wrapper_tempat_kegiatan">
                                    <label for="tempat_kegiatan_select" class="form-label text-capitalize fw-bold">Tempat Kegiatan</label>
                                    <select id="tempat_kegiatan_select" class="form-select select2-tempat" name="{{ $isCustomTempat ? '' : 'tempat_kegiatan' }}" {{ $isCustomTempat ? '' : 'required' }}>
                                        <option value="">-- Pilih Tempat Kegiatan atau Ketik Custom --</option>
                                        @foreach ($daftarTempat as $tempat)
                                            <option value="{{ $tempat }}" {{ $valTempat === $tempat ? 'selected' : '' }}>
                                                {{ $tempat }}
                                            </option>
                                        @endforeach
                                        @if ($isCustomTempat)
                                            <option value="{{ $valTempat }}" selected>{{ $valTempat }}</option>
                                        @endif
                                        <option value="__custom__" {{ $isCustomTempat ? 'selected' : '' }}>➕ Lainnya (Ketik Manual)...</option>
                                    </select>

                                    <div id="wrapper_custom_tempat" class="mt-2 {{ $isCustomTempat ? '' : 'd-none' }}">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-location-dot"></i></span>
                                            <input type="text" id="input_custom_tempat" class="form-control"
                                                name="{{ $isCustomTempat ? 'tempat_kegiatan' : '' }}"
                                                value="{{ $isCustomTempat ? $valTempat : '' }}"
                                                placeholder="Tuliskan nama tempat kegiatan custom / lainnya..."
                                                {{ $isCustomTempat ? 'required' : 'disabled' }}>
                                        </div>
                                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i>Pilih opsi di atas atau ketik langsung nama tempat baru.</small>
                                    </div>
                                </div>

                                <input type="hidden" name="unit_kegiatan" value="{{ old('unit_kegiatan', $pengajuan->unit_kegiatan) }}">

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="judul_kegiatan" class="text-capitalize fw-bold">nama kegiatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan"
                                            value="{{ old('judul_kegiatan', $pengajuan->judul_kegiatan) }}" placeholder="Masukkan Nama Kegiatan" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="deskripsi_kegiatan" id="label_deskripsi_kegiatan" class="text-capitalize fw-bold">deskripsi
                                            kegiatan</label>
                                        <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan"
                                            rows="3" placeholder="Masukkan Deskripsi Kegiatan">{{ old('deskripsi_kegiatan', $pengajuan->formatted_deskripsi) }}</textarea>
                                        <small id="hint_brief_desain" class="text-muted d-none mt-1">
                                            <i class="fa-solid fa-palette me-1 text-primary"></i> Jelaskan brief desain: jenis media (Flyer IG / Spanduk / Backdrop / Sertifikat), ukuran, teks/judul acara, tema warna, dan detail narasumber.
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-4" id="wrapper_perlengkapan">
                                    <div class="form-group">
                                        <label for="perlengkapan" class="text-capitalize">Kebutuhan
                                            perlengkapan</label>
                                        <textarea class="form-control" id="perlengkapan" name="perlengkapan"
                                            rows="3" placeholder="Masukkan Kebutuhan Perlengkapan">{{ old('perlengkapan', trim(strip_tags($pengajuan->perlengkapan ?? ''))) }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-4" id="wrapper_link_zoom">
                                    <div class="form-group">
                                        <label for="link_zoom" class="text-capitalize">link zoom <span
                                                class="text-danger">(jika ada)</span></label>
                                        <textarea class="form-control" name="link_zoom" id="link_zoom"
                                            rows="3" placeholder="contoh: https://zoom.us/j/123456789">{{ old('link_zoom', $pengajuan->link_zoom) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <!-- Upload Dokumen Pendukung -->
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="upload_file" class="text-capitalize fw-bold">Upload Dokumen Pendukung <span
                                                class="text-muted small fw-normal">(PDF, DOC, DOCX, XLS, ZIP - Maks. 10MB)</span></label>
                                        @if ($pengajuan->upload_file)
                                            <div class="mb-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                                <span class="small text-truncate me-2"><i class="fa-solid fa-file-lines text-primary me-1"></i> File saat ini: <strong>Dokumen Pendukung</strong></span>
                                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2">
                                                    <i class="fa-solid fa-download"></i> Unduh
                                                </a>
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-file-arrow-up"></i></span>
                                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" onchange="previewDokumenEdit(this)">
                                        </div>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah dokumen pendukung.</small>

                                        <div id="filePreviewContainerEdit" class="mt-3 d-none">
                                            <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Dokumen Pendukung Baru:</p>
                                            <div class="border rounded p-2 bg-light shadow-sm">
                                                <div class="d-flex align-items-center justify-content-between gap-2">
                                                    <div class="d-flex align-items-center gap-2 text-truncate">
                                                        <i id="filePreviewIconEdit" class="fa-solid fa-file-pdf fa-2x text-danger"></i>
                                                        <div class="text-truncate">
                                                            <div id="filePreviewNameEdit" class="small fw-bold text-dark text-truncate"></div>
                                                            <div id="filePreviewSizeEdit" class="text-muted" style="font-size: 0.75rem;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-1 flex-shrink-0">
                                                        <a id="filePreviewLinkEdit" href="#" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" title="Buka Pratinjau">
                                                            <i class="fa-solid fa-eye me-1"></i> Lihat
                                                        </a>
                                                        <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2" onclick="resetDokumenPreviewEdit()" title="Hapus Pilihan">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="pdfEmbedWrapperEdit" class="mt-2 d-none">
                                                    <iframe id="pdfEmbedPreviewEdit" src="" class="w-100 border rounded bg-white" style="height: 220px;"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upload Bahan Materi -->
                                    <div class="col-md-6">
                                        <label for="upload_png" class="text-capitalize fw-bold">Upload Bahan Materi <span
                                                class="text-muted small fw-normal">(Format PNG, JPG, JPEG, WEBP - Maks. 5MB)</span></label>
                                        @if ($pengajuan->upload_png)
                                            <div class="mb-2">
                                                <p class="text-xs text-muted mb-1">Bahan materi saat ini:</p>
                                                <div class="border rounded p-1 bg-light d-inline-block shadow-sm">
                                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Bahan Materi"
                                                            class="img-fluid rounded" style="max-height: 120px; object-fit: contain;">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                            <input type="file" name="upload_png" id="upload_png" class="form-control"
                                                accept=".png,.jpg,.jpeg,.webp" onchange="previewImageEdit(this)">
                                        </div>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah bahan materi.</small>
                                        <div id="imagePreviewContainerEdit" class="mt-3 d-none">
                                            <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Bahan Materi Baru:</p>
                                            <div class="border rounded p-2 bg-light d-inline-block shadow-sm">
                                                <img id="imagePreviewEdit" src="#" alt="Preview"
                                                    class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (Auth::user()->is_admin || Auth::user()->is_humas)
                                <div class="mb-4">
                                    <label for="status" class="text-capitalize">status</label>
                                    <div class="form-group">
                                        <select class="form-control form-control-sm" name="status" id="status">
                                            <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : ''
                                                }}>
                                                pending</option>
                                            <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' :
                                                '' }}>
                                                diterima</option>
                                        </select>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center me-2" aria-haspopup="true"
                            aria-expanded="false">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                </path>
                            </svg>
                            SUBMIT
                        </button>

                        <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-danger px-2 py-2">BACK <i
                                class="fa-solid fa-right-from-bracket"></i></a>
                    </div>
                    @endif

                    @if (Auth::user()->is_admin || Auth::user()->is_humas)
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Bagian Atas: Detail Pengajuan / Review (Full Width) -->
                            <div class="col-12 mb-4">
                                <div class="card border border-light shadow-sm">
                                    <div class="card-header bg-gray-100 py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 text-gray-800">
                                            <i class="fa-solid fa-file-lines me-2 text-primary"></i> Detail Pengajuan (Review)
                                        </h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0" style="table-layout: fixed; width: 100%;">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 24%; white-space: nowrap;" class="ps-3">Pemohon</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            <strong>{{ $pengajuan->user?->name ?? '-' }}</strong>
                                                            @if ($pengajuan->user?->email)
                                                                <br><small class="text-muted"><i class="fa-regular fa-envelope me-1"></i>{{ $pengajuan->user->email }}</small>
                                                            @endif
                                                            @if ($pengajuan->user?->no_wa)
                                                                <br><small class="text-success"><i class="fa-brands fa-whatsapp me-1"></i>{{ $pengajuan->user->no_wa }}</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Jenis Pengajuan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;"><span class="badge bg-primary text-uppercase text-wrap" style="white-space: normal !important;">{{ $pengajuan->nama_kegiatan }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Nama Kegiatan</th>
                                                        <td class="text-break fw-semibold text-gray-800" style="white-space: normal !important; word-break: break-word !important;">{{ $pengajuan->nama_kegiatan_display }}</td>
                                                    </tr>
                                                    @php
                                                        $isDesainOnly = ($pengajuan->nama_kegiatan === 'Desain');
                                                        $isPublikasiOnly = ($pengajuan->nama_kegiatan === 'Publikasi');
                                                        $isDesainDanPublikasi = ($pengajuan->nama_kegiatan === 'Desain dan Publikasi');
                                                        $isFlexibleSchedule = $isDesainOnly || $isPublikasiOnly || $isDesainDanPublikasi;
                                                    @endphp
                                                    <tr>
                                                        <th class="ps-3">{{ $isDesainOnly ? 'Deadline Desain' : ($isDesainDanPublikasi ? 'Deadline & Target Publikasi' : ($isPublikasiOnly ? 'Target Publikasi' : 'Tanggal Kegiatan')) }}</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @if (Auth::user()->is_admin)
                                                                <div class="p-2 bg-light border rounded">
                                                                    <div class="row g-2">
                                                                        <div class="col-12 {{ ($isFlexibleSchedule && !$isDesainDanPublikasi) ? '' : 'col-sm-6' }}">
                                                                            <label for="tgl_awal" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-calendar-check me-1 text-primary"></i> {{ $isDesainOnly || $isDesainDanPublikasi ? 'Deadline Desain' : ($isPublikasiOnly ? 'Target Tayang / Publikasi' : 'Mulai') }}
                                                                            </label>
                                                                            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control form-control-sm" value="{{ $pengajuan->tgl_awal }}">
                                                                        </div>
                                                                        @if (!$isFlexibleSchedule || $isDesainDanPublikasi)
                                                                        <div class="col-12 col-sm-6">
                                                                            <label for="tgl_selesai" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-calendar-check me-1 text-success"></i> {{ $isDesainDanPublikasi ? 'Target Publikasi' : 'Selesai' }}
                                                                            </label>
                                                                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-sm" value="{{ $pengajuan->tgl_selesai ?? $pengajuan->tgl_awal }}">
                                                                        </div>
                                                                        @else
                                                                            <input type="hidden" name="tgl_selesai" value="{{ $pengajuan->tgl_selesai ?? $pengajuan->tgl_awal }}">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="fw-semibold text-gray-800">
                                                                    @if ($isDesainDanPublikasi)
                                                                        Deadline Desain: {{ $pengajuan->tgl_awal ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                                                        <br><small class="text-muted">Target Publikasi: {{ ($pengajuan->tgl_selesai ?? $pengajuan->tgl_awal) ? \Carbon\Carbon::parse($pengajuan->tgl_selesai ?? $pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}</small>
                                                                    @else
                                                                        {{ $pengajuan->tgl_awal ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                                                        @if(!$isFlexibleSchedule && $pengajuan->tgl_selesai && $pengajuan->tgl_selesai !== $pengajuan->tgl_awal)
                                                                            <br><small class="text-muted">s/d {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') }}</small>
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!$isFlexibleSchedule || !empty($pengajuan->jam_kegiatan))
                                                    <tr>
                                                        <th class="ps-3">Waktu Kegiatan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @if (Auth::user()->is_admin)
                                                                <div class="p-2 bg-light border rounded">
                                                                    <div class="row g-2">
                                                                        <div class="col-12 col-sm-6">
                                                                            <label for="jam_kegiatan" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-clock me-1 text-primary"></i> Jam Mulai (WIB)
                                                                            </label>
                                                                            <input type="time" name="jam_kegiatan" id="jam_kegiatan" class="form-control form-control-sm" value="{{ $pengajuan->jam_kegiatan ? substr($pengajuan->jam_kegiatan, 0, 5) : '' }}">
                                                                        </div>
                                                                        <div class="col-12 col-sm-6">
                                                                            <label for="waktu_selesai" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-clock me-1 text-success"></i> Waktu Selesai (WIB)
                                                                            </label>
                                                                            <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control form-control-sm" value="{{ $pengajuan->waktu_selesai ? substr($pengajuan->waktu_selesai, 0, 5) : '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                {{ $pengajuan->jam_kegiatan ? substr($pengajuan->jam_kegiatan, 0, 5) : '-' }} WIB - {{ $pengajuan->waktu_selesai ? substr($pengajuan->waktu_selesai, 0, 5) : '-' }} WIB
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="ps-3">Unit Kegiatan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">{{ $pengajuan->unit_kegiatan }}</td>
                                                    </tr>
                                                    @if (!$isFlexibleSchedule || !empty($pengajuan->tempat_kegiatan))
                                                    <tr>
                                                        <th class="ps-3">Tempat Kegiatan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">{{ $pengajuan->tempat_kegiatan ?? '-' }}</td>
                                                    </tr>
                                                    @endif
                                                    @if ($pengajuan->link_zoom)
                                                    <tr>
                                                        <th class="ps-3">Link Zoom</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            <a href="{{ $pengajuan->link_zoom }}" target="_blank" class="text-decoration-underline text-break" style="white-space: normal !important; word-break: break-all !important;">
                                                                {{ $pengajuan->link_zoom }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="ps-3">{{ $isDesainOnly ? 'Brief Desain' : ($isDesainDanPublikasi ? 'Brief Desain & Publikasi' : ($isPublikasiOnly ? 'Brief Publikasi' : 'Deskripsi')) }}</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @php
                                                                $cleanDeskEdit = $pengajuan->formatted_deskripsi;
                                                            @endphp
                                                            @if ($cleanDeskEdit !== '')
                                                                <div class="p-3 bg-light rounded border text-gray-800" style="white-space: pre-wrap; font-size: 0.875rem; line-height: 1.6;">{{ $cleanDeskEdit }}</div>
                                                            @else
                                                                <span class="text-muted fst-italic">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cleanPerlengkapan = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($pengajuan->perlengkapan ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                                                    @endphp
                                                    @if (!$isFlexibleSchedule || $cleanPerlengkapan !== '')
                                                    <tr>
                                                        <th class="ps-3">Kebutuhan Perlengkapan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">{{ $cleanPerlengkapan ?: '-' }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="ps-3">Dokumen Pendukung</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @if ($pengajuan->upload_file)
                                                                @php
                                                                    $ext = strtoupper(pathinfo($pengajuan->upload_file, PATHINFO_EXTENSION));
                                                                @endphp
                                                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center text-wrap" style="white-space: normal !important;">
                                                                    <i class="fa-solid fa-file-arrow-down me-2"></i> Unduh Dokumen Pendukung @if($ext)({{ $ext }})@endif
                                                                </a>
                                                            @else
                                                                <span class="text-muted fst-italic">Tidak ada dokumen pendukung</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Bahan Materi</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @if ($pengajuan->upload_png)
                                                                <div class="d-inline-block p-2 bg-light border rounded" style="max-width: 100%;">
                                                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="d-block mb-2">
                                                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Bahan Materi" class="img-fluid rounded border shadow-sm" style="max-height: 150px; object-fit: contain;">
                                                                    </a>
                                                                    <div>
                                                                        <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="btn btn-xs btn-outline-success d-inline-flex align-items-center">
                                                                            <i class="fa-solid fa-eye me-1"></i> Buka Bahan Materi
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted fst-italic">Tidak ada bahan materi</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">PIC yang Ditugaskan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @php
                                                                $picList = $pengajuan->getVisiblePicPetugas(Auth::user());
                                                                $picDesain = array_values(array_filter($picList, fn($p) => ($p['peran'] ?? 'desain') === 'desain'));
                                                                $picDokumentasi = array_values(array_filter($picList, fn($p) => ($p['peran'] ?? '') === 'dokumentasi'));
                                                            @endphp

                                                            @if(!empty($picDesain) || !empty($picDokumentasi))
                                                                @if(!empty($picDesain))
                                                                    <div class="mb-2">
                                                                        <span class="badge bg-primary text-white mb-1"><i class="fa-solid fa-palette me-1"></i> PIC Desain:</span>
                                                                        <div class="d-flex flex-column gap-1 ms-1">
                                                                            @foreach($picDesain as $pd)
                                                                                @if(!empty($pd['nama']) || !empty($pd['no_wa']))
                                                                                <div class="small text-gray-800 text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                                                    <strong>{{ $pd['nama'] ?: '-' }}</strong>
                                                                                    @if(!empty($pd['no_wa'])) <small class="text-success ms-1"><i class="fa-brands fa-whatsapp"></i> {{ $pd['no_wa'] }}</small> @endif
                                                                                    @if(!empty($pd['catatan'])) <div class="text-muted fst-italic ps-2 border-start border-2 border-primary mt-1 text-break" style="white-space: normal !important; word-break: break-word !important;"><i class="fa-solid fa-comment-dots me-1 text-primary"></i>Catatan: {{ $pd['catatan'] }}</div> @endif
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if(!empty($picDokumentasi))
                                                                    <div>
                                                                        <span class="badge bg-info text-white mb-1"><i class="fa-solid fa-camera me-1"></i> PIC Dokumentasi:</span>
                                                                        <div class="d-flex flex-column gap-1 ms-1">
                                                                            @foreach($picDokumentasi as $pdk)
                                                                                @if(!empty($pdk['nama']) || !empty($pdk['no_wa']))
                                                                                <div class="small text-gray-800 text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                                                    <strong>{{ $pdk['nama'] ?: '-' }}</strong>
                                                                                    @if(!empty($pdk['no_wa'])) <small class="text-success ms-1"><i class="fa-brands fa-whatsapp"></i> {{ $pdk['no_wa'] }}</small> @endif
                                                                                    @if(!empty($pdk['catatan'])) <div class="text-muted fst-italic ps-2 border-start border-2 border-info mt-1 text-break" style="white-space: normal !important; word-break: break-word !important;"><i class="fa-solid fa-comment-dots me-1 text-info"></i>Catatan: {{ $pdk['catatan'] }}</div> @endif
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @elseif (!empty($pengajuan->petugas_ditugaskan))
                                                                <span class="badge bg-primary text-white px-2 py-1 text-wrap" style="white-space: normal !important;">
                                                                    <i class="fa-solid fa-user-check me-1"></i> {{ $pengajuan->petugas_ditugaskan }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted fst-italic">Belum ada PIC yang ditugaskan</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!empty($pengajuan->alasan_ditolak))
                                                    <tr>
                                                        <th class="ps-3">Keterangan</th>
                                                        <td class="text-muted text-break" style="white-space: normal !important; word-break: break-word !important;">{{ $pengajuan->alasan_ditolak }}</td>
                                                    </tr>
                                                    @endif
                                                    @if (!Auth::user()->is_admin)
                                                    <tr>
                                                        <th class="ps-3">Status Pengajuan</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            @if ($pengajuan->status === 'diterima')
                                                                <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-check me-1"></i> Diterima</span>
                                                            @elseif ($pengajuan->status === 'ditolak')
                                                                <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-xmark me-1"></i> Ditolak</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!empty($pengajuan->link_desain))
                                                    <tr>
                                                        <th class="ps-3">Link Desain</th>
                                                        <td class="text-break" style="white-space: normal !important; word-break: break-word !important;">
                                                            <a href="{{ $pengajuan->link_desain }}" target="_blank" class="text-primary text-decoration-underline text-break" style="white-space: normal !important; word-break: break-all !important;">
                                                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ $pengajuan->link_desain }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if (!Auth::user()->is_admin)
                                    <div class="card-footer bg-white border-top py-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('kotak-masuk-pengajuan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Kotak Masuk
                                            </a>
                                        </div>
                                        <div>
                                            @if ($pengajuan->status == 'diterima' && $pengajuan->publikasi)
                                                <a href="{{ route('publikasi.edit', $pengajuan->publikasi->slug ?? $pengajuan->publikasi->id) }}" class="btn btn-success text-white d-inline-flex align-items-center">
                                                    <i class="fa-solid fa-file-pen me-2"></i> Laporkan Kegiatan
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if (Auth::user()->is_admin)
                            <!-- Bagian Bawah: Verifikasi & Penugasan (Full Width di Bawah Detail Pengajuan) -->
                            <div class="col-12 mb-4">
                                <div class="card border border-light shadow-sm">
                                    <div class="card-header bg-gray-100 py-3">
                                        <h5 class="card-title mb-0 text-gray-800">
                                            <i class="fa-solid fa-clipboard-check me-2 text-success"></i> Verifikasi & Penugasan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status Pengajuan (Pilih Langsung / Radio Cards) -->
                                        <div class="mb-4">
                                            <label class="form-label font-weight-bold d-block mb-2">Status Pengajuan <span class="text-danger">*</span></label>
                                            @php
                                                $currentStatus = old('status', $pengajuan->status ?? 'pending');
                                            @endphp
                                            <div class="row g-2" id="status_pengajuan_group">
                                                <div class="col-4">
                                                    <label class="status-option-card d-flex align-items-center justify-content-center gap-2 p-2 border rounded w-100 text-center {{ $currentStatus == 'pending' ? 'border-warning bg-warning bg-opacity-10 fw-bold' : 'bg-white' }}" for="status_pending" style="cursor: pointer; transition: all 0.15s ease;">
                                                        <input class="form-check-input m-0 d-none" type="radio" name="status" id="status_pending" value="pending" {{ $currentStatus == 'pending' ? 'checked' : '' }} required onchange="updateStatusCardStyle()">
                                                        <span class="small text-dark"><i class="fa-solid fa-clock text-warning me-1"></i> Pending</span>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label class="status-option-card d-flex align-items-center justify-content-center gap-2 p-2 border rounded w-100 text-center {{ $currentStatus == 'diterima' ? 'border-success bg-success bg-opacity-10 fw-bold' : 'bg-white' }}" for="status_diterima" style="cursor: pointer; transition: all 0.15s ease;">
                                                        <input class="form-check-input m-0 d-none" type="radio" name="status" id="status_diterima" value="diterima" {{ $currentStatus == 'diterima' ? 'checked' : '' }} required onchange="updateStatusCardStyle()">
                                                        <span class="small text-dark"><i class="fa-solid fa-circle-check text-success me-1"></i> Diterima</span>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label class="status-option-card d-flex align-items-center justify-content-center gap-2 p-2 border rounded w-100 text-center {{ $currentStatus == 'ditolak' ? 'border-danger bg-danger bg-opacity-10 fw-bold' : 'bg-white' }}" for="status_ditolak" style="cursor: pointer; transition: all 0.15s ease;">
                                                        <input class="form-check-input m-0 d-none" type="radio" name="status" id="status_ditolak" value="ditolak" {{ $currentStatus == 'ditolak' ? 'checked' : '' }} required onchange="updateStatusCardStyle()">
                                                        <span class="small text-dark"><i class="fa-solid fa-circle-xmark text-danger me-1"></i> Ditolak</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Petugas / PIC yang Ditugaskan (Dibagi 2 Bagian Kiri-Kanan: PIC Desain & PIC Dokumentasi) -->
                                        <div class="mb-4">
                                            <div class="mb-3 border-bottom pb-2">
                                                <label class="form-label font-weight-bold mb-0 text-dark fs-6">
                                                    <i class="fa-solid fa-users text-primary me-1"></i> PIC Kegiatan
                                                </label>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fa-solid fa-circle-info me-1"></i> Pisahkan penugasan untuk tim <strong>Desain</strong> dan tim <strong>Dokumentasi</strong>, serta berikan instruksi/catatan tugas spesifik.
                                                </small>
                                            </div>

                                            @php
                                                $daftarPic = old('pic_nama') !== null
                                                    ? array_map(function($nama, $noWa, $peran, $catatan) {
                                                        return [
                                                            'nama' => $nama,
                                                            'no_wa' => $noWa,
                                                            'peran' => $peran,
                                                            'catatan' => $catatan
                                                        ];
                                                    }, old('pic_nama', []), old('pic_no_wa', []), old('pic_peran', []), old('pic_catatan', []))
                                                    : ($pengajuan->pic_petugas ?? []);

                                                if (empty($daftarPic) && !empty($pengajuan->petugas_ditugaskan)) {
                                                    $arrNama = array_map('trim', explode(',', $pengajuan->petugas_ditugaskan));
                                                    $arrWa = array_map('trim', explode(',', $pengajuan->no_wa_tujuan ?? ''));
                                                    foreach ($arrNama as $idx => $nama) {
                                                        $daftarPic[] = [
                                                            'nama' => $nama,
                                                            'no_wa' => $arrWa[$idx] ?? '',
                                                            'peran' => $idx === 0 ? 'desain' : 'dokumentasi',
                                                            'catatan' => ''
                                                        ];
                                                    }
                                                }

                                                $jenisPengajuanVal = trim((string) ($pengajuan->nama_kegiatan ?? ''));
                                                $jenisLower = strtolower($jenisPengajuanVal);
                                                $visibleRoles = [];
                                                if (str_contains($jenisLower, 'desain') || str_contains($jenisLower, 'design')) {
                                                    $visibleRoles[] = 'desain';
                                                }
                                                if (str_contains($jenisLower, 'dokumentasi')) {
                                                    $visibleRoles[] = 'dokumentasi';
                                                }
                                                if (str_contains($jenisLower, 'publikasi')) {
                                                    $visibleRoles[] = 'publikasi';
                                                }
                                                if (empty($visibleRoles)) {
                                                    $visibleRoles = ['general'];
                                                }

                                                $roleConfigs = [
                                                    'desain' => [
                                                        'title' => 'PIC Desain',
                                                        'containerId' => 'kontainerPicDesain',
                                                        'icon' => 'fa-palette',
                                                        'color' => 'primary',
                                                        'btnText' => 'Tambah PIC Desain',
                                                        'catatanLabel' => 'Catatan / Instruksi Tugas Desain',
                                                        'placeholder' => 'Contoh: Buat banner ukuran 3x1m & flyer IG, gunakan tema biru...',
                                                    ],
                                                    'dokumentasi' => [
                                                        'title' => 'PIC Dokumentasi',
                                                        'containerId' => 'kontainerPicDokumentasi',
                                                        'icon' => 'fa-camera',
                                                        'color' => 'info',
                                                        'btnText' => 'Tambah PIC Dokumentasi',
                                                        'catatanLabel' => 'Catatan / Instruksi Tugas Dokumentasi',
                                                        'placeholder' => 'Contoh: Foto saat pembukaan, sesi pemateri, dan foto bersama peserta...',
                                                    ],
                                                    'publikasi' => [
                                                        'title' => 'PIC Publikasi',
                                                        'containerId' => 'kontainerPicPublikasi',
                                                        'icon' => 'fa-bullhorn',
                                                        'color' => 'success',
                                                        'btnText' => 'Tambah PIC Publikasi',
                                                        'catatanLabel' => 'Catatan / Instruksi Tugas Publikasi',
                                                        'placeholder' => 'Contoh: Publikasikan berita di website, Instagram, dan media sosial kampus...',
                                                    ],
                                                    'general' => [
                                                        'title' => 'PIC General',
                                                        'containerId' => 'kontainerPicGeneral',
                                                        'icon' => 'fa-user-gear',
                                                        'color' => 'primary',
                                                        'btnText' => 'Tambah PIC General',
                                                        'catatanLabel' => 'Catatan / Instruksi Tugas General',
                                                        'placeholder' => 'Contoh: Koordinasi kegiatan, pendampingan tamu, atau tugas umum lainnya...',
                                                    ],
                                                ];

                                                $daftarPicByRole = [];
                                                foreach ($daftarPic as $picItem) {
                                                    $rawPeran = trim((string) ($picItem['peran'] ?? 'desain'));
                                                    if ($rawPeran === '') {
                                                        $rawPeran = 'general';
                                                    }
                                                    $lowerPeran = strtolower($rawPeran);
                                                    $roleKey = in_array($lowerPeran, ['desain', 'dokumentasi', 'publikasi', 'general'], true)
                                                        ? $lowerPeran
                                                        : $rawPeran;
                                                    $daftarPicByRole[$roleKey][] = array_merge($picItem, ['peran' => $roleKey]);
                                                }

                                                // Kompatibilitas data lama sebelum pemisahan PIC Publikasi & PIC General
                                                if ($jenisLower === 'publikasi' && empty($daftarPicByRole['publikasi']) && !empty($daftarPicByRole['desain'])) {
                                                    $daftarPicByRole['publikasi'] = array_map(fn($p) => array_merge($p, ['peran' => 'publikasi']), $daftarPicByRole['desain']);
                                                    unset($daftarPicByRole['desain']);
                                                }
                                                if ($visibleRoles === ['general'] && empty($daftarPicByRole['general']) && !empty($daftarPic)) {
                                                    $hasCustomSaved = false;
                                                    foreach ($daftarPicByRole as $k => $v) {
                                                        if (!in_array($k, ['desain', 'dokumentasi', 'publikasi', 'general'], true) && !empty($v)) {
                                                            $hasCustomSaved = true;
                                                            break;
                                                        }
                                                    }
                                                    if (!$hasCustomSaved) {
                                                        $daftarPicByRole['general'] = array_map(fn($p) => array_merge($p, ['peran' => 'general']), $daftarPic);
                                                    }
                                                }

                                                // Pastikan kategori yang sudah memiliki data PIC tersimpan tetap tampil di form edit
                                                foreach ($daftarPicByRole as $savedRoleKey => $savedItems) {
                                                    if (!empty($savedItems) && !in_array($savedRoleKey, $visibleRoles, true)) {
                                                        $visibleRoles[] = $savedRoleKey;
                                                    }
                                                }

                                                foreach ($visibleRoles as $rKey) {
                                                    if (empty($daftarPicByRole[$rKey])) {
                                                        $daftarPicByRole[$rKey] = [['nama' => '', 'no_wa' => '', 'peran' => $rKey, 'catatan' => '']];
                                                    }
                                                }
                                            @endphp

                                            <!-- Toolbar Tambah Kategori PIC Dinamis / Custom -->
                                            <div class="d-flex flex-wrap align-items-center gap-2 p-2 mb-3 bg-light border rounded" id="toolbarTambahKategoriPic">
                                                <span class="small fw-bold text-gray-700 me-1">
                                                    <i class="fa-solid fa-sliders me-1 text-primary"></i> Tambah Kategori PIC:
                                                </span>
                                                <select id="selectTambahKategoriPic" class="form-select form-select-sm w-auto" style="min-width: 280px; max-width: 100%; padding-right: 2.25rem;" onchange="toggleCustomKategoriInput()">
                                                    <option value="desain">PIC Desain</option>
                                                    <option value="dokumentasi">PIC Dokumentasi</option>
                                                    <option value="publikasi">PIC Publikasi</option>
                                                    <option value="general">PIC General</option>
                                                    <option value="__custom__">➕ Custom Kategori (Ketik Manual)...</option>
                                                </select>
                                                <input type="text" id="inputCustomKategoriPic" class="form-control form-control-sm d-none" style="width: 260px; max-width: 100%;"
                                                    placeholder="Contoh: MC / Protokol, Videografer...">
                                                <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center" onclick="tambahKategoriPic()">
                                                    <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
                                                </button>
                                            </div>

                                            <div class="row g-3" id="picCategoriesRow">
                                                @foreach ($visibleRoles as $roleIndex => $roleKey)
                                                    @php
                                                        $cfg = $roleConfigs[$roleKey] ?? [
                                                            'title' => str_starts_with(strtolower($roleKey), 'pic ') ? $roleKey : 'PIC ' . $roleKey,
                                                            'containerId' => 'kontainerPic_' . md5($roleKey),
                                                            'icon' => 'fa-user-tag',
                                                            'color' => 'primary',
                                                            'btnText' => 'Tambah PIC',
                                                            'catatanLabel' => 'Catatan / Instruksi Tugas (' . $roleKey . ')',
                                                            'placeholder' => 'Tuliskan instruksi atau catatan tugas untuk ' . $roleKey . '...',
                                                        ];
                                                        $items = $daftarPicByRole[$roleKey];
                                                        $colClass = count($visibleRoles) > 1 ? 'col-12 col-lg-6' : 'col-12';
                                                    @endphp
                                                    <div class="{{ $colClass }} pic-category-col" data-role-card="1" data-peran-key="{{ $roleKey }}">
                                                        <div class="card border border-{{ $cfg['color'] }} border-opacity-25 h-100 shadow-xs mb-0">
                                                            <div class="card-header bg-{{ $cfg['color'] }} bg-opacity-10 py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                                <span class="fw-bold text-{{ $cfg['color'] }} small text-uppercase pic-category-title">
                                                                    <i class="fa-solid {{ $cfg['icon'] }} me-1"></i> <span class="pic-category-label" data-role-title="{{ $cfg['title'] }}">{{ $roleIndex + 1 }}. {{ $cfg['title'] }}</span>
                                                                </span>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <button type="button" class="btn btn-xs btn-{{ $cfg['color'] }} text-white d-inline-flex align-items-center px-2 py-1 text-nowrap" style="line-height: 1.2;" onclick="tambahBarisPic(@js($roleKey))">
                                                                        <i class="fa-solid fa-plus me-1"></i> {{ $cfg['btnText'] }}
                                                                    </button>
                                                                    <button type="button" class="btn btn-xs btn-outline-danger d-inline-flex align-items-center px-2 py-1 text-nowrap" style="line-height: 1.2;" title="Hapus kategori PIC ini" onclick="hapusKategoriPic(this)">
                                                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body p-3">
                                                                <div id="{{ $cfg['containerId'] }}" data-pic-container="{{ $roleKey }}" class="d-flex flex-column gap-3">
                                                                    @foreach ($items as $pic)
                                                                        <div class="baris-pic border rounded p-3 bg-white shadow-sm" data-peran="{{ $roleKey }}">
                                                                            <input type="hidden" name="pic_peran[]" value="{{ $roleKey }}">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                <span class="badge bg-{{ $cfg['color'] }} text-white small">
                                                                                    <i class="fa-solid {{ $cfg['icon'] }} me-1"></i> {{ $cfg['title'] }}
                                                                                </span>
                                                                                <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus {{ $cfg['title'] }} ini" onclick="hapusBarisPic(this)">
                                                                                    <i class="fa-solid fa-trash me-1"></i> Hapus
                                                                                </button>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label class="form-label small text-muted fw-bold mb-1">
                                                                                    <i class="fa-solid fa-user me-1 text-primary"></i> Nama PIC
                                                                                </label>
                                                                                <select name="pic_nama[]" class="form-select select-petugas-humas" style="width: 100%;">
                                                                                    <option value="">-- Cari & Pilih PIC Humas --</option>
                                                                                    <option value="__clear__">-- Tidak Dipilih / Kosongkan PIC --</option>
                                                                                    @php
                                                                                        $selectedName = $pic['nama'] ?? '';
                                                                                        $matched = false;
                                                                                    @endphp
                                                                                    @if(isset($petugasHumas))
                                                                                        @foreach ($petugasHumas as $ph)
                                                                                            @php
                                                                                                $isSelected = ($selectedName === $ph->name);
                                                                                                if ($isSelected) $matched = true;
                                                                                            @endphp
                                                                                            <option value="{{ $ph->name }}" data-wa="{{ $ph->no_wa }}" {{ $isSelected ? 'selected' : '' }}>
                                                                                                {{ $ph->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                    @if(!empty($selectedName) && !$matched)
                                                                                        <option value="{{ $selectedName }}" data-wa="{{ $pic['no_wa'] ?? '' }}" selected>
                                                                                            {{ $selectedName }}
                                                                                        </option>
                                                                                    @endif
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label class="form-label small text-muted fw-bold mb-1">
                                                                                    <i class="fa-brands fa-whatsapp me-1 text-success"></i> No. WhatsApp PIC
                                                                                </label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text bg-success text-white">
                                                                                        <i class="fa-brands fa-whatsapp"></i>
                                                                                    </span>
                                                                                    <input type="text" name="pic_no_wa[]" class="form-control input-pic-wa"
                                                                                        value="{{ $pic['no_wa'] ?? '' }}" placeholder="082283736181">
                                                                                    <button type="button" class="btn btn-outline-success d-inline-flex align-items-center px-3" title="Chat WhatsApp PIC" onclick="chatWaPic(this)">
                                                                                        <i class="fa-brands fa-whatsapp me-1"></i> Chat
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            <div>
                                                                                <label class="form-label small text-muted fw-bold mb-1">
                                                                                    <i class="fa-solid fa-note-sticky me-1 text-warning"></i> {{ $cfg['catatanLabel'] }}
                                                                                </label>
                                                                                <textarea name="pic_catatan[]" class="form-control form-control-sm input-pic-catatan" rows="2"
                                                                                    placeholder="{{ $cfg['placeholder'] }}">{{ $pic['catatan'] ?? '' }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mt-2">
                                                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Notifikasi WhatsApp penugasan otomatis disiapkan untuk setiap PIC saat UPDATE.</small>
                                            </div>
                                        </div>

                                        <!-- Email Tujuan -->
                                        <div class="mb-3">
                                            <label for="email_tujuan" class="form-label font-weight-bold">Email Tujuan Notifikasi</label>
                                            <input type="email" class="form-control" name="email_tujuan" id="email_tujuan"
                                                value="{{ old('email_tujuan', $pengajuan->email_tujuan ?? $pengajuan->user?->email) }}"
                                                placeholder="contoh: pengguna@gmail.com">
                                        </div>

                                        <!-- Keterangan (Opsional) -->
                                        <div class="mb-3">
                                            <label for="alasan_ditolak" class="form-label font-weight-bold">
                                                Keterangan <span class="badge bg-secondary text-white fw-normal ms-1">Opsional</span>
                                            </label>
                                            <textarea class="form-control" name="alasan_ditolak" id="alasan_ditolak" rows="2"
                                                placeholder="Tuliskan keterangan atau catatan tambahan pengajuan (opsional)...">{{ old('alasan_ditolak', $pengajuan->alasan_ditolak) }}</textarea>
                                            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Jika diisi oleh Admin, keterangan ini akan tampil pada tabel Pengajuan Kegiatan.</small>
                                        </div>



                                        <!-- Action Buttons -->
                                        <div class="pt-3 border-top d-flex gap-2">
                                            <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center">
                                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                    </path>
                                                </svg>
                                                UPDATE
                                            </button>

                                            @if ($pengajuan->status == 'diterima' && $pengajuan->publikasi)
                                                <a href="{{ route('publikasi.edit', $pengajuan->publikasi->slug ?? $pengajuan->publikasi->id) }}" class="btn btn-success text-white d-inline-flex align-items-center">
                                                    <i class="fa-solid fa-file-pen me-2"></i> Laporkan Kegiatan
                                                </a>
                                            @endif

                                            <a href="{{ route('pengajuan.index') }}" class="btn btn-danger d-inline-flex align-items-center">
                                                <i class="fa-solid fa-right-from-bracket me-2"></i> BACK
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
        <script>
            function formatFileSizeEdit(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            function previewDokumenEdit(input) {
                const container = document.getElementById('filePreviewContainerEdit');
                const icon = document.getElementById('filePreviewIconEdit');
                const nameEl = document.getElementById('filePreviewNameEdit');
                const sizeEl = document.getElementById('filePreviewSizeEdit');
                const linkEl = document.getElementById('filePreviewLinkEdit');
                const pdfWrapper = document.getElementById('pdfEmbedWrapperEdit');
                const pdfFrame = document.getElementById('pdfEmbedPreviewEdit');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const ext = (file.name.split('.').pop() || '').toLowerCase();
                    const objUrl = URL.createObjectURL(file);

                    if (nameEl) nameEl.textContent = file.name;
                    if (sizeEl) sizeEl.textContent = formatFileSizeEdit(file.size) + ' • ' + ext.toUpperCase();
                    if (linkEl) linkEl.href = objUrl;

                    if (ext === 'pdf') {
                        if (icon) icon.className = 'fa-solid fa-file-pdf fa-2x text-danger';
                        if (pdfFrame) pdfFrame.src = objUrl;
                        if (pdfWrapper) pdfWrapper.classList.remove('d-none');
                    } else if (['doc', 'docx'].includes(ext)) {
                        if (icon) icon.className = 'fa-solid fa-file-word fa-2x text-primary';
                        if (pdfFrame) pdfFrame.src = '';
                        if (pdfWrapper) pdfWrapper.classList.add('d-none');
                    } else if (['xls', 'xlsx'].includes(ext)) {
                        if (icon) icon.className = 'fa-solid fa-file-excel fa-2x text-success';
                        if (pdfFrame) pdfFrame.src = '';
                        if (pdfWrapper) pdfWrapper.classList.add('d-none');
                    } else if (['zip', 'rar'].includes(ext)) {
                        if (icon) icon.className = 'fa-solid fa-file-zipper fa-2x text-warning';
                        if (pdfFrame) pdfFrame.src = '';
                        if (pdfWrapper) pdfWrapper.classList.add('d-none');
                    } else {
                        if (icon) icon.className = 'fa-solid fa-file-lines fa-2x text-secondary';
                        if (pdfFrame) pdfFrame.src = '';
                        if (pdfWrapper) pdfWrapper.classList.add('d-none');
                    }

                    if (container) container.classList.remove('d-none');
                } else {
                    resetDokumenPreviewEdit();
                }
            }

            function resetDokumenPreviewEdit() {
                const input = document.getElementById('upload_file');
                const container = document.getElementById('filePreviewContainerEdit');
                const pdfWrapper = document.getElementById('pdfEmbedWrapperEdit');
                const pdfFrame = document.getElementById('pdfEmbedPreviewEdit');
                if (input) input.value = '';
                if (pdfFrame) pdfFrame.src = '';
                if (pdfWrapper) pdfWrapper.classList.add('d-none');
                if (container) container.classList.add('d-none');
            }

            function previewImageEdit(input) {
                const previewContainer = document.getElementById('imagePreviewContainerEdit');
                const preview = document.getElementById('imagePreviewEdit');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '#';
                    previewContainer.classList.add('d-none');
                }
            }

            const listPetugasHumas = @json(isset($petugasHumas) ? $petugasHumas->map(fn($p) => ['name' => $p->name, 'no_wa' => $p->no_wa]) : []);

            function inisialisasiSelect2(selectElem) {
                if (window.$ && $.fn.select2) {
                    const $el = $(selectElem);
                    if ($el.hasClass('select2-hidden-accessible')) return;
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: '-- Cari & Pilih PIC Humas --',
                        allowClear: true,
                        tags: true
                    }).on('select2:select', function () {
                        pilihPetugasHumas(this);
                    }).on('select2:clear', function () {
                        const row = this.closest('.baris-pic');
                        if (row) {
                            const inputWa = row.querySelector('.input-pic-wa');
                            if (inputWa) inputWa.value = '';
                            const inputCatatan = row.querySelector('.input-pic-catatan');
                            if (inputCatatan) inputCatatan.value = '';
                        }
                    });
                }
            }

            // Auto-focus input pencarian saat Select2 dibuka
            if (window.$) {
                $(document).on('select2:open', function () {
                    const searchField = document.querySelector('.select2-search__field');
                    if (searchField) searchField.focus();
                });
            }

            function inisialisasiSemuaSelect2() {
                document.querySelectorAll('.select-petugas-humas').forEach(function (select) {
                    inisialisasiSelect2(select);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', inisialisasiSemuaSelect2);
            } else {
                inisialisasiSemuaSelect2();
            }

            function pilihPetugasHumas(selectElem) {
                const row = selectElem.closest('.baris-pic');
                if (!row) return;
                const inputWa = row.querySelector('.input-pic-wa');
                const inputCatatan = row.querySelector('.input-pic-catatan');
                const selectedVal = selectElem.value;

                if (!selectedVal || selectedVal === '__clear__') {
                    if (selectedVal === '__clear__') {
                        if (window.$ && $(selectElem).hasClass('select2-hidden-accessible')) {
                            $(selectElem).val('').trigger('change');
                        } else {
                            selectElem.value = '';
                        }
                    }
                    if (inputWa) inputWa.value = '';
                    if (inputCatatan) inputCatatan.value = '';
                    return;
                }

                const selectedOption = selectElem.options[selectElem.selectedIndex];
                let wa = selectedOption ? (selectedOption.getAttribute('data-wa') || '') : '';
                if (!wa && listPetugasHumas) {
                    const found = listPetugasHumas.find(p => p.name === selectedVal);
                    if (found && found.no_wa) {
                        wa = found.no_wa;
                    }
                }
                if (wa && inputWa) {
                    inputWa.value = wa;
                }
            }

            const picRoleConfig = {
                desain: {
                    kontainerId: 'kontainerPicDesain',
                    color: 'primary',
                    badgeClass: 'bg-primary text-white',
                    badgeIcon: 'fa-palette',
                    badgeTitle: 'PIC Desain',
                    btnText: 'Tambah PIC Desain',
                    catatanLabel: 'Catatan / Instruksi Tugas Desain',
                    placeholder: 'Contoh: Buat banner ukuran 3x1m & flyer IG, gunakan tema biru...'
                },
                dokumentasi: {
                    kontainerId: 'kontainerPicDokumentasi',
                    color: 'info',
                    badgeClass: 'bg-info text-white',
                    badgeIcon: 'fa-camera',
                    badgeTitle: 'PIC Dokumentasi',
                    btnText: 'Tambah PIC Dokumentasi',
                    catatanLabel: 'Catatan / Instruksi Tugas Dokumentasi',
                    placeholder: 'Contoh: Foto saat pembukaan, sesi pemateri, dan foto bersama peserta...'
                },
                publikasi: {
                    kontainerId: 'kontainerPicPublikasi',
                    color: 'success',
                    badgeClass: 'bg-success text-white',
                    badgeIcon: 'fa-bullhorn',
                    badgeTitle: 'PIC Publikasi',
                    btnText: 'Tambah PIC Publikasi',
                    catatanLabel: 'Catatan / Instruksi Tugas Publikasi',
                    placeholder: 'Contoh: Publikasikan berita di website, Instagram, dan media sosial kampus...'
                },
                general: {
                    kontainerId: 'kontainerPicGeneral',
                    color: 'primary',
                    badgeClass: 'bg-primary text-white',
                    badgeIcon: 'fa-user-gear',
                    badgeTitle: 'PIC General',
                    btnText: 'Tambah PIC General',
                    catatanLabel: 'Catatan / Instruksi Tugas General',
                    placeholder: 'Contoh: Koordinasi kegiatan, pendampingan tamu, atau tugas umum lainnya...'
                }
            };

            function escapeHtmlPic(str) {
                return String(str ?? '').replace(/[&<>"']/g, function (m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
                });
            }

            function getPicRoleConfig(peran) {
                if (picRoleConfig[peran]) {
                    return picRoleConfig[peran];
                }
                const clean = String(peran || 'General').trim();
                const title = /^pic\s+/i.test(clean) ? clean : `PIC ${clean}`;
                return {
                    color: 'primary',
                    badgeClass: 'bg-primary text-white',
                    badgeIcon: 'fa-user-tag',
                    badgeTitle: title,
                    btnText: 'Tambah PIC',
                    catatanLabel: `Catatan / Instruksi Tugas (${clean})`,
                    placeholder: `Tuliskan instruksi atau catatan tugas untuk ${clean}...`
                };
            }

            function toggleCustomKategoriInput() {
                const sel = document.getElementById('selectTambahKategoriPic');
                const customInput = document.getElementById('inputCustomKategoriPic');
                if (!sel || !customInput) return;
                if (sel.value === '__custom__') {
                    customInput.classList.remove('d-none');
                    customInput.focus();
                } else {
                    customInput.classList.add('d-none');
                    customInput.value = '';
                }
            }

            function refreshPicCategoryLayout() {
                const row = document.getElementById('picCategoriesRow');
                if (!row) return;
                const cols = row.querySelectorAll('.pic-category-col');
                const isMulti = cols.length > 1;
                cols.forEach((col, idx) => {
                    col.classList.toggle('col-lg-6', isMulti);
                    const lblEl = col.querySelector('.pic-category-label');
                    if (lblEl) {
                        const title = lblEl.getAttribute('data-role-title') || '';
                        lblEl.textContent = `${idx + 1}. ${title}`;
                    }
                });
            }

            function buildBarisPicElement(peran) {
                const cfg = getPicRoleConfig(peran);
                let optionsHtml = '<option value="">-- Cari & Pilih PIC Humas --</option><option value="__clear__">-- Tidak Dipilih / Kosongkan PIC --</option>';
                if (listPetugasHumas && listPetugasHumas.length > 0) {
                    optionsHtml += listPetugasHumas.map(ph => `<option value="${escapeHtmlPic(ph.name)}" data-wa="${escapeHtmlPic(ph.no_wa || '')}">${escapeHtmlPic(ph.name)}</option>`).join('');
                }

                const div = document.createElement('div');
                div.className = 'baris-pic border rounded p-3 bg-white shadow-sm';
                div.setAttribute('data-peran', peran);
                div.innerHTML = `
                    <input type="hidden" name="pic_peran[]" value="${escapeHtmlPic(peran)}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge ${cfg.badgeClass} small">
                            <i class="fa-solid ${cfg.badgeIcon} me-1"></i> ${escapeHtmlPic(cfg.badgeTitle)}
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC ini" onclick="hapusBarisPic(this)">
                            <i class="fa-solid fa-trash me-1"></i> Hapus
                        </button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted fw-bold mb-1">
                            <i class="fa-solid fa-user me-1 text-primary"></i> Nama PIC
                        </label>
                        <select name="pic_nama[]" class="form-select select-petugas-humas" style="width: 100%;">
                            ${optionsHtml}
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted fw-bold mb-1">
                            <i class="fa-brands fa-whatsapp me-1 text-success"></i> No. WhatsApp PIC
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                            <input type="text" name="pic_no_wa[]" class="form-control input-pic-wa" placeholder="082283736181">
                            <button type="button" class="btn btn-outline-success d-inline-flex align-items-center px-3" title="Chat WhatsApp PIC" onclick="chatWaPic(this)">
                                <i class="fa-brands fa-whatsapp me-1"></i> Chat
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small text-muted fw-bold mb-1">
                            <i class="fa-solid fa-note-sticky me-1 text-warning"></i> ${escapeHtmlPic(cfg.catatanLabel)}
                        </label>
                        <textarea name="pic_catatan[]" class="form-control form-control-sm input-pic-catatan" rows="2"
                            placeholder="${escapeHtmlPic(cfg.placeholder)}"></textarea>
                    </div>
                `;
                return div;
            }

            function tambahKategoriPic() {
                const sel = document.getElementById('selectTambahKategoriPic');
                const customInput = document.getElementById('inputCustomKategoriPic');
                const row = document.getElementById('picCategoriesRow');
                if (!sel || !row) return;

                let peran = sel.value;
                if (peran === '__custom__') {
                    let customVal = (customInput ? customInput.value : '').trim().replace(/\s+/g, ' ');
                    customVal = customVal.replace(/^pic\s+/i, '').trim();
                    if (!customVal) {
                        alert('Silakan ketik nama kategori PIC custom terlebih dahulu.');
                        if (customInput) customInput.focus();
                        return;
                    }
                    const lowerCustom = customVal.toLowerCase();
                    if (['desain', 'dokumentasi', 'publikasi', 'general'].includes(lowerCustom)) {
                        peran = lowerCustom;
                    } else {
                        peran = customVal;
                    }
                }

                // Anti-duplikat: jika card kategori sudah ada (case-insensitive), tambah baris PIC di card tersebut
                const existingCard = Array.from(row.querySelectorAll('.pic-category-col')).find(col => {
                    return (col.getAttribute('data-peran-key') || '').toLowerCase() === peran.toLowerCase();
                });

                if (existingCard) {
                    const actualKey = existingCard.getAttribute('data-peran-key');
                    tambahBarisPic(actualKey);
                    existingCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    return;
                }

                const cfg = getPicRoleConfig(peran);
                const col = document.createElement('div');
                col.className = 'col-12 pic-category-col';
                col.setAttribute('data-role-card', '1');
                col.setAttribute('data-peran-key', peran);

                const cardColor = cfg.color || 'primary';
                col.innerHTML = `
                    <div class="card border border-${cardColor} border-opacity-25 h-100 shadow-xs mb-0">
                        <div class="card-header bg-${cardColor} bg-opacity-10 py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-1">
                            <span class="fw-bold text-${cardColor} small text-uppercase pic-category-title">
                                <i class="fa-solid ${cfg.badgeIcon} me-1"></i> <span class="pic-category-label" data-role-title="${escapeHtmlPic(cfg.badgeTitle)}">1. ${escapeHtmlPic(cfg.badgeTitle)}</span>
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-xs btn-${cardColor} text-white d-inline-flex align-items-center px-2 py-1 text-nowrap btn-tambah-pic-role" style="line-height: 1.2;">
                                    <i class="fa-solid fa-plus me-1"></i> ${escapeHtmlPic(cfg.btnText)}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-danger d-inline-flex align-items-center px-2 py-1 text-nowrap" style="line-height: 1.2;" title="Hapus kategori PIC ini" onclick="hapusKategoriPic(this)">
                                    <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div data-pic-container="${escapeHtmlPic(peran)}" class="d-flex flex-column gap-3"></div>
                        </div>
                    </div>
                `;

                col.querySelector('.btn-tambah-pic-role').addEventListener('click', function () {
                    tambahBarisPic(peran);
                });

                const container = col.querySelector('[data-pic-container]');
                const barisDiv = buildBarisPicElement(peran);
                container.appendChild(barisDiv);

                row.appendChild(col);
                refreshPicCategoryLayout();

                const newSelect = barisDiv.querySelector('.select-petugas-humas');
                if (newSelect) {
                    inisialisasiSelect2(newSelect);
                }
                if (customInput) {
                    customInput.value = '';
                }
                col.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function hapusKategoriPic(btn) {
                const col = btn.closest('.pic-category-col');
                if (!col) return;
                col.querySelectorAll('.select-petugas-humas').forEach(select => {
                    if (window.$ && $(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                    }
                });
                col.remove();
                refreshPicCategoryLayout();
            }

            function tambahBarisPic(peran) {
                peran = peran || 'desain';
                const row = document.getElementById('picCategoriesRow');
                const kontainer = row
                    ? Array.from(row.querySelectorAll('[data-pic-container]')).find(el => (el.getAttribute('data-pic-container') || '') === peran)
                    : null;
                if (!kontainer) return;

                const div = buildBarisPicElement(peran);
                kontainer.appendChild(div);

                const newSelect = div.querySelector('.select-petugas-humas');
                if (newSelect) {
                    inisialisasiSelect2(newSelect);
                    if (window.$) {
                        $(newSelect).select2('open');
                    }
                }
            }

            function hapusBarisPic(btn) {
                const row = btn.closest('.baris-pic');
                if (!row) return;
                const kontainer = row.parentElement;
                const items = kontainer ? kontainer.querySelectorAll('.baris-pic') : [];

                if (items.length > 1) {
                    const select = row.querySelector('.select-petugas-humas');
                    if (window.$ && select && $(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                    }
                    row.remove();
                } else {
                    const inputWa = row.querySelector('.input-pic-wa');
                    if (inputWa) inputWa.value = '';
                    const inputCatatan = row.querySelector('.input-pic-catatan');
                    if (inputCatatan) inputCatatan.value = '';
                    const select = row.querySelector('.select-petugas-humas');
                    if (select) {
                        if (window.$ && $(select).hasClass('select2-hidden-accessible')) {
                            $(select).val('').trigger('change');
                        } else {
                            select.value = '';
                        }
                    }
                }
            }

            function chatWaPic(btn) {
                const row = btn.closest('.baris-pic') || btn.closest('tr');
                const select = row.querySelector('.select-petugas-humas');
                const nama = (select ? select.value.trim() : '') || 'Petugas Humas';
                const inputWa = row.querySelector('.input-pic-wa');
                const rawWa = inputWa ? inputWa.value.trim() : '';

                if (!rawWa) {
                    alert('Silakan masukkan nomor WhatsApp PIC ini terlebih dahulu.');
                    if (inputWa) inputWa.focus();
                    return;
                }

                let phone = rawWa.replace(/\D/g, '');
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('8')) {
                    phone = '62' + phone;
                }

                const peranInput = row.querySelector('input[name="pic_peran[]"]');
                const peranKey = peranInput ? peranInput.value : 'desain';
                const peranVal = getPicRoleConfig(peranKey).badgeTitle;
                const inputCatatan = row.querySelector('.input-pic-catatan');
                const catatanKhusus = inputCatatan && inputCatatan.value.trim() ? inputCatatan.value.trim() : '';

                const kegiatan = @json($pengajuan->nama_kegiatan);
                const unit = @json($pengajuan->unit_kegiatan);
                const tglAwal = @json($pengajuan->tgl_awal ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM Y') : '-');
                const tglSelesai = @json($pengajuan->tgl_selesai ? \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->isoFormat('dddd, D MMMM Y') : '-');
                const waktu = @json("{$pengajuan->jam_kegiatan} - {$pengajuan->waktu_selesai} WIB");
                const tempat = @json($pengajuan->tempat_kegiatan ?? '-');
                const pemohon = @json($pengajuan->user?->name ?? '-');
                const pemohonWa = @json($pengajuan->user?->no_wa ?? '-');

                const checkedStatus = document.querySelector('input[name="status"]:checked');
                const statusText = checkedStatus ? checkedStatus.value.toUpperCase() : 'DITERIMA';
                const catatanAdminInput = document.getElementById('alasan_ditolak');
                const catatanAdmin = (catatanAdminInput && catatanAdminInput.value.trim()) ? catatanAdminInput.value.trim() : '';

                let message = `*PENUGASAN KEGIATAN HUMAS*\n\n` +
                    `Halo *${nama}*,\n` +
                    `Anda telah ditugaskan untuk kegiatan berikut:\n\n` +
                    `- *Peran Penugasan:* ${peranVal}\n` +
                    `- *Jenis Pengajuan:* ${kegiatan}\n` +
                    `- *Unit:* ${unit}\n` +
                    `- *Tanggal:* ${tglAwal} s/d ${tglSelesai}\n` +
                    `- *Waktu:* ${waktu}\n` +
                    `- *Tempat:* ${tempat}\n` +
                    `- *Pemohon:* ${pemohon} (${pemohonWa})\n` +
                    (catatanKhusus ? `- *Catatan Tugas:* ${catatanKhusus}\n` : '') +
                    `- *Status Pengajuan:* ${statusText}\n` +
                    (catatanAdmin ? `- *Catatan Tambahan:* ${catatanAdmin}\n` : '') +
                    `\nSilakan login ke SIHUMAS:\nhttps://sihumas.uisbatam.com/\n\n` +
                    `Mohon dikoordinasikan dan dipersiapkan dengan baik. Terima kasih.\n_SIHUMAS_`;

                window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank');
            }

            function updateStatusCardStyle() {
                const map = {
                    'pending': 'border-warning bg-warning bg-opacity-10 fw-bold',
                    'diterima': 'border-success bg-success bg-opacity-10 fw-bold',
                    'ditolak': 'border-danger bg-danger bg-opacity-10 fw-bold'
                };
                document.querySelectorAll('#status_pengajuan_group input[name="status"]').forEach(radio => {
                    const label = radio.closest('.status-option-card');
                    if (!label) return;
                    label.className = 'status-option-card d-flex align-items-center justify-content-center gap-2 p-2 border rounded w-100 text-center ' +
                        (radio.checked ? (map[radio.value] || 'bg-white') : 'bg-white');
                });
            }

            let isFormConfirmed = false;

            function handleFormSubmit(event) {
                if (isFormConfirmed) {
                    return true;
                }
                const statusInput = document.querySelector('input[name="status"]');
                if (!statusInput) {
                    return true;
                }

                event.preventDefault();
                konfirmasiSimpanVerifikasi();
                return false;
            }

            function konfirmasiSimpanVerifikasi() {
                const form = document.getElementById('formEditPengajuan');
                if (form && !form.reportValidity()) {
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: 'Yakin ingin menyimpan perubahan verifikasi dan penugasan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#e11d48',
                    confirmButtonText: '<i class="fa-solid fa-check me-1"></i> Ya, Simpan',
                    cancelButtonText: '<i class="fa-solid fa-xmark me-1"></i> Batal',
                    reverseButtons: true,
                    focusConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        isFormConfirmed = true;
                        form.submit();
                    }
                });
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const $select = $('#tempat_kegiatan_select');
                const $customWrapper = $('#wrapper_custom_tempat');
                const $customInput = $('#input_custom_tempat');

                if ($select.length) {
                    if (window.$ && $.fn.select2) {
                        $select.select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            tags: true,
                            placeholder: '-- Pilih Tempat Kegiatan atau Ketik Custom --',
                            allowClear: true,
                            createTag: function (params) {
                                const term = $.trim(params.term);
                                if (term === '' || term.toLowerCase() === 'lainnya') {
                                    return null;
                                }
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true
                                };
                            }
                        });
                    }

                    function handleTempatChange() {
                        const jenis = $('#nama_kegiatan').val();
                        const isFlexible = (jenis === 'Desain' || jenis === 'Publikasi' || jenis === 'Desain dan Publikasi');
                        const val = $select.val();
                        if (isFlexible) {
                            $customWrapper.addClass('d-none');
                            $customInput.prop('disabled', true).removeAttr('name').removeAttr('required');
                            $select.attr('name', 'tempat_kegiatan').removeAttr('required');
                            return;
                        }
                        if (val === '__custom__') {
                            $customWrapper.removeClass('d-none');
                            $customInput.prop('disabled', false).attr('name', 'tempat_kegiatan').attr('required', true).focus();
                            $select.removeAttr('name').removeAttr('required');
                        } else {
                            $customWrapper.addClass('d-none');
                            $customInput.prop('disabled', true).removeAttr('name').removeAttr('required');
                            $select.attr('name', 'tempat_kegiatan').attr('required', true);
                        }
                    }

                    $select.on('change', handleTempatChange);
                    handleTempatChange();
                }

                // Adaptive form: khusus pilihan "Desain" dan "Publikasi"
                const namaKegiatanSelect = document.getElementById('nama_kegiatan');
                const colTglAwal = document.getElementById('col_tgl_awal');
                const colTargetPublikasi = document.getElementById('col_target_publikasi');
                const inputTargetPublikasi = document.getElementById('target_publikasi_tgl');
                const inputTglSelesai = document.getElementById('tgl_selesai');
                const labelTglAwal = document.getElementById('label_tgl_awal');
                const wrapperJam = document.getElementById('wrapper_jam_kegiatan');
                const wrapperSelesai = document.getElementById('wrapper_tgl_selesai_waktu');
                const wrapperTempat = document.getElementById('wrapper_tempat_kegiatan');
                const wrapperPerlengkapan = document.getElementById('wrapper_perlengkapan');
                const wrapperLinkZoom = document.getElementById('wrapper_link_zoom');
                const labelDeskripsi = document.getElementById('label_deskripsi_kegiatan');
                const hintBriefDesain = document.getElementById('hint_brief_desain');

                function toggleFormByNamaKegiatan() {
                    if (!namaKegiatanSelect) return;
                    const jenis = namaKegiatanSelect.value;
                    const isDesain = jenis === 'Desain';
                    const isPublikasi = jenis === 'Publikasi';
                    const isDesainDanPublikasi = jenis === 'Desain dan Publikasi';
                    const isFlexible = isDesain || isPublikasi || isDesainDanPublikasi;

                    if (isFlexible) {
                        if (colTglAwal) {
                            colTglAwal.classList.toggle('col-md-6', isDesainDanPublikasi);
                            colTglAwal.classList.toggle('col-12', !isDesainDanPublikasi);
                        }
                        if (colTargetPublikasi) {
                            colTargetPublikasi.classList.toggle('d-none', !isDesainDanPublikasi);
                        }
                        if (inputTargetPublikasi) {
                            inputTargetPublikasi.disabled = !isDesainDanPublikasi;
                            inputTargetPublikasi.required = isDesainDanPublikasi;
                        }
                        if (inputTglSelesai) {
                            inputTglSelesai.disabled = isDesainDanPublikasi;
                        }
                        if (labelTglAwal) {
                            labelTglAwal.innerHTML = isDesain
                                ? 'Deadline / Batas Waktu Desain <span class="text-danger">*</span>'
                                : (isDesainDanPublikasi
                                    ? 'Deadline Desain <span class="text-danger">*</span>'
                                    : 'Target Tayang / Tanggal Publikasi <span class="text-danger">*</span>');
                        }
                        if (wrapperJam) wrapperJam.classList.add('d-none');
                        if (wrapperSelesai) wrapperSelesai.classList.add('d-none');
                        if (wrapperTempat) wrapperTempat.classList.add('d-none');
                        if (wrapperPerlengkapan) wrapperPerlengkapan.classList.add('d-none');
                        if (wrapperLinkZoom) wrapperLinkZoom.classList.add('d-none');
                        if (labelDeskripsi) {
                            labelDeskripsi.textContent = isDesain
                                ? 'Deskripsi / Brief Desain'
                                : (isDesainDanPublikasi
                                    ? 'Deskripsi / Brief Desain & Publikasi (5W+1H)'
                                    : 'Deskripsi / Brief Publikasi (5W+1H)');
                        }
                        if (hintBriefDesain) {
                            hintBriefDesain.innerHTML = isDesain
                                ? '<i class="fa-solid fa-circle-info me-1"></i> Untuk pengajuan <strong>Desain</strong>, cukup isi deadline, brief desain (ukuran, teks, referensi warna), dan lampiran materi jika ada.'
                                : (isDesainDanPublikasi
                                    ? '<i class="fa-solid fa-circle-info me-1"></i> Untuk pengajuan <strong>Desain dan Publikasi</strong>, isi deadline/target tayang, brief desain (ukuran, teks, tema warna) sekaligus poin publikasi (5W+1H / caption), serta lampiran materi jika ada.'
                                    : '<i class="fa-solid fa-circle-info me-1"></i> Untuk pengajuan <strong>Publikasi</strong>, cukup isi target tayang, poin berita (5W+1H) atau caption, serta lampiran foto/file/link GDrive materi jika ada.');
                            hintBriefDesain.classList.remove('d-none');
                        }

                        if ($select.length) $select.removeAttr('required');
                        if ($customInput.length) $customInput.removeAttr('required');
                    } else {
                        if (colTglAwal) {
                            colTglAwal.classList.remove('col-12');
                            colTglAwal.classList.add('col-md-6');
                        }
                        if (colTargetPublikasi) colTargetPublikasi.classList.add('d-none');
                        if (inputTargetPublikasi) {
                            inputTargetPublikasi.disabled = true;
                            inputTargetPublikasi.required = false;
                        }
                        if (inputTglSelesai) inputTglSelesai.disabled = false;
                        if (labelTglAwal) labelTglAwal.textContent = 'tanggal mulai kegiatan';
                        if (wrapperJam) wrapperJam.classList.remove('d-none');
                        if (wrapperSelesai) wrapperSelesai.classList.remove('d-none');
                        if (wrapperTempat) wrapperTempat.classList.remove('d-none');
                        if (wrapperPerlengkapan) wrapperPerlengkapan.classList.remove('d-none');
                        if (wrapperLinkZoom) wrapperLinkZoom.classList.remove('d-none');
                        if (labelDeskripsi) labelDeskripsi.textContent = 'deskripsi kegiatan';
                        if (hintBriefDesain) hintBriefDesain.classList.add('d-none');

                        if ($select.length && $select.val() !== '__custom__') {
                            $select.attr('required', true);
                        }
                    }
                }

                if (namaKegiatanSelect) {
                    const $jenisSelect = $('#nama_kegiatan');
                    const $customJenisWrapper = $('#wrapper_custom_jenis');
                    const $customJenisInput = $('#input_custom_jenis');

                    if (window.$ && $.fn.select2) {
                        if ($jenisSelect.hasClass('select2-hidden-accessible')) {
                            $jenisSelect.select2('destroy');
                        }
                        $jenisSelect.select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            tags: true,
                            placeholder: 'Pilih Jenis Pengajuan atau Ketik Custom',
                            allowClear: true,
                            createTag: function (params) {
                                const term = $.trim(params.term);
                                if (term === '' || term.toLowerCase() === 'lainnya') {
                                    return null;
                                }
                                return { id: term, text: term, newTag: true };
                            }
                        });
                    }

                    function handleJenisChange() {
                        const val = $jenisSelect.val();
                        if (val === '__custom__') {
                            $customJenisWrapper.removeClass('d-none');
                            $customJenisInput.prop('disabled', false).attr('name', 'nama_kegiatan').attr('required', true).focus();
                            $jenisSelect.removeAttr('name').removeAttr('required');
                        } else {
                            $customJenisWrapper.addClass('d-none');
                            $customJenisInput.prop('disabled', true).removeAttr('name').removeAttr('required');
                            $jenisSelect.attr('name', 'nama_kegiatan').attr('required', true);
                        }
                        toggleFormByNamaKegiatan();
                    }

                    namaKegiatanSelect.addEventListener('change', handleJenisChange);
                    if (window.$) {
                        $jenisSelect.on('change', handleJenisChange);
                    }
                    handleJenisChange();
                }
            });
        </script>
    @endpush

    @push('style')
        <style>
            .baris-pic .input-group {
                display: flex !important;
                align-items: stretch !important;
            }
            .baris-pic .input-group .input-group-text,
            .baris-pic .input-group .form-control,
            .baris-pic .input-group .btn {
                height: 40px !important;
                min-height: 40px !important;
                max-height: 40px !important;
                font-size: 0.875rem !important;
                line-height: 1.5 !important;
                box-sizing: border-box !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }
            .baris-pic .input-group .form-control {
                border-color: #d1d5db !important;
            }
            .baris-pic .input-group .btn {
                min-width: 80px;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-weight: 500 !important;
            }
            .baris-pic .input-group .btn-outline-success {
                border-color: #10b981 !important;
                color: #10b981 !important;
            }
            .baris-pic .input-group .btn-outline-success:hover,
            .baris-pic .input-group .btn-outline-success:active {
                background-color: #10b981 !important;
                border-color: #10b981 !important;
                color: #ffffff !important;
            }
            .baris-pic .input-group .btn-outline-danger {
                border-color: #ef4444 !important;
                color: #ef4444 !important;
            }
            .baris-pic .input-group .btn-outline-danger:hover,
            .baris-pic .input-group .btn-outline-danger:active {
                background-color: #ef4444 !important;
                border-color: #ef4444 !important;
                color: #ffffff !important;
            }
            .baris-pic .input-group .btn:focus {
                box-shadow: none !important;
            }
        </style>
    @endpush