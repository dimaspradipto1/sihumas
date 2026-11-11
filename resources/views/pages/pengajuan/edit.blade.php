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
                {{ Auth::user()->is_admin ? 'form edit pengajuan berita' : 'detail pengajuan tugas kegiatan' }}
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

                                <div class="mb-4">
                                    <label for="nama_kegiatan" class="text-capitalize">nama kegiatan</label>
                                    <select name="nama_kegiatan" id="nama_kegiatan" class="form-select single" required>
                                        <option value="">Pilih Nama Kegiatan</option>
                                        <option value="Desain" {{ old('nama_kegiatan', $pengajuan->nama_kegiatan) == 'Desain' ? 'selected' : '' }}>Desain</option>
                                        <option value="Dokumentasi" {{ old('nama_kegiatan', $pengajuan->nama_kegiatan) == 'Dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                                        <option value="Desain dan Dokumentasi" {{ old('nama_kegiatan', $pengajuan->nama_kegiatan) == 'Desain dan Dokumentasi' ? 'selected' : '' }}>Desain dan Dokumentasi</option>
                                        <option value="Publikasi" {{ old('nama_kegiatan', $pengajuan->nama_kegiatan) == 'Publikasi' ? 'selected' : '' }}>Publikasi</option>
                                        <option value="Tamu Undangan" {{ old('nama_kegiatan', $pengajuan->nama_kegiatan) == 'Tamu Undangan' ? 'selected' : '' }}>Tamu</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="tgl_awal" class="text-capitalize">tanggal awal</label>
                                    <input type="date" name="tgl_awal"
                                        value="{{ old('tgl_awal', $pengajuan->tgl_awal) }}" class="form-control"
                                        id="tgl_awal">
                                </div>
                                <div class="mb-4">
                                    <label for="tgl_selesai" class="text-capitalize">tanggal Selesai</label>
                                    <input type="date" name="tgl_selesai"
                                        value="{{ old('tgl_selesai', $pengajuan->tgl_selesai) }}" class="form-control"
                                        id="tgl_selesai">
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="deskripsi_kegiatan" class="text-capitalize">deskripsi
                                            kegiatan</label>
                                        <textarea class="form-control" name="deskripsi_kegiatan"
                                            rows="3">{{ old('deskripsi_kegiatan', $pengajuan->deskripsi_kegiatan) }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="link_zoom" class="text-capitalize">link zoom</label>
                                        <textarea class="form-control" name="link_zoom"
                                            rows="3">{{ old('link_zoom', $pengajuan->link_zoom) }}</textarea>

                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="jam_kegiatan" class="text-capitalize">jam kegiatan</label>
                                    <input type="time" name="jam_kegiatan" class="form-control"
                                        value="{{ old('jam_kegiatan', $pengajuan->jam_kegiatan) }}">
                                </div>

                                <div class="mb-4">
                                    <label for="tempat_kegiatan" class="text-capitalize">tempat kegiatan</label>
                                    <input type="text" name="tempat_kegiatan" class="form-control"
                                        value="{{ old('tempat_kegiatan', $pengajuan->tempat_kegiatan) }}">
                                </div>

                                <div class="mb-4">
                                    <label for="unit_kegiatan" class="text-capitalize">unit kegiatan</label>
                                    <input type="text" name="unit_kegiatan" class="form-control"
                                        value="{{ old('unit_kegiatan', $pengajuan->unit_kegiatan) }}">
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="deskripsi_kegiatan" class="text-capitalize">Kebutuhan
                                            perlengkapan</label>
                                        <textarea class="form-control" name="perlengkapan" id=""
                                            rows="3">{{ old('perlengkapan', $pengajuan->perlengkapan) }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="link_zoom" class="text-capitalize">link zoom <span
                                                class="text-danger">(jika ada)</span></label>
                                        <textarea class="form-control" name="link_zoom" id=""
                                            rows="3">{{ old('link_zoom', $pengajuan->link_zoom) }}</textarea>
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
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                                        </div>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah dokumen pendukung.</small>
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

                        <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 aria-haspopup=" true"
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
                            <!-- Kolom Kiri: Detail Pengajuan / Review -->
                            <div class="col-12 {{ Auth::user()->is_admin ? 'col-lg-6' : 'col-lg-12' }} mb-4">
                                <div class="card border border-light shadow-sm h-100">
                                    <div class="card-header bg-gray-100 py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 text-gray-800">
                                            <i class="fa-solid fa-file-lines me-2 text-primary"></i> Detail Pengajuan (Review)
                                        </h5>
                                        @if (!Auth::user()->is_admin && Auth::user()->is_humas && $pengajuan->status == 'diterima' && $pengajuan->publikasi)
                                            <a href="{{ route('publikasi.edit', $pengajuan->publikasi->slug ?? $pengajuan->publikasi->id) }}" class="btn btn-sm btn-success text-white d-inline-flex align-items-center">
                                                <i class="fa-solid fa-file-pen me-1"></i> Laporkan Kegiatan
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 32%; white-space: nowrap;" class="ps-3">Pemohon</th>
                                                        <td class="text-break">
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
                                                        <th class="ps-3">Nama Kegiatan</th>
                                                        <td><span class="badge bg-primary text-uppercase">{{ $pengajuan->nama_kegiatan }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Tanggal Kegiatan</th>
                                                        <td>
                                                            @if (Auth::user()->is_admin)
                                                                <div class="p-2 bg-light border rounded">
                                                                    <div class="row g-2">
                                                                        <div class="col-12 col-sm-6">
                                                                            <label for="tgl_awal" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-calendar-check me-1 text-primary"></i> Mulai
                                                                            </label>
                                                                            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control form-control-sm" value="{{ $pengajuan->tgl_awal }}">
                                                                        </div>
                                                                        <div class="col-12 col-sm-6">
                                                                            <label for="tgl_selesai" class="form-label small text-muted mb-1 fw-bold">
                                                                                <i class="fa-regular fa-calendar-check me-1 text-success"></i> Selesai
                                                                            </label>
                                                                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-sm" value="{{ $pengajuan->tgl_selesai }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="fw-semibold text-gray-800">
                                                                    {{ $pengajuan->tgl_awal ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                                                    @if($pengajuan->tgl_selesai && $pengajuan->tgl_selesai !== $pengajuan->tgl_awal)
                                                                        <br><small class="text-muted">s/d {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') }}</small>
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Waktu Kegiatan</th>
                                                        <td>{{ $pengajuan->jam_kegiatan }} WIB - {{ $pengajuan->waktu_selesai }} WIB</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Unit Kegiatan</th>
                                                        <td>{{ $pengajuan->unit_kegiatan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Tempat Kegiatan</th>
                                                        <td class="text-break">{{ $pengajuan->tempat_kegiatan ?? '-' }}</td>
                                                    </tr>
                                                    @if ($pengajuan->link_zoom)
                                                    <tr>
                                                        <th class="ps-3">Link Zoom</th>
                                                        <td class="text-break">
                                                            <a href="{{ $pengajuan->link_zoom }}" target="_blank" class="text-decoration-underline text-break">
                                                                {{ $pengajuan->link_zoom }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="ps-3">Deskripsi</th>
                                                        <td class="text-break">{{ trim(strip_tags($pengajuan->deskripsi_kegiatan ?? '')) ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Kebutuhan Perlengkapan</th>
                                                        <td class="text-break">{{ trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($pengajuan->perlengkapan ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'))) ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Dokumen Pendukung</th>
                                                        <td>
                                                            @if ($pengajuan->upload_file)
                                                                @php
                                                                    $ext = strtoupper(pathinfo($pengajuan->upload_file, PATHINFO_EXTENSION));
                                                                @endphp
                                                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                                                    <i class="fa-solid fa-file-arrow-down me-2"></i> Unduh Dokumen Pendukung @if($ext)({{ $ext }})@endif
                                                                </a>
                                                            @else
                                                                <span class="text-muted fst-italic">Tidak ada dokumen pendukung</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Bahan Materi</th>
                                                        <td>
                                                            @if ($pengajuan->upload_png)
                                                                <div class="d-inline-block p-2 bg-light border rounded">
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
                                                        <td>
                                                            @php
                                                                $picList = $pengajuan->pic_petugas ?? [];
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
                                                                                <div class="small text-gray-800">
                                                                                    <strong>{{ $pd['nama'] ?: '-' }}</strong>
                                                                                    @if(!empty($pd['no_wa'])) <small class="text-success ms-1"><i class="fa-brands fa-whatsapp"></i> {{ $pd['no_wa'] }}</small> @endif
                                                                                    @if(!empty($pd['catatan'])) <div class="text-muted fst-italic ps-2 border-start border-2 border-primary mt-1"><i class="fa-solid fa-comment-dots me-1 text-primary"></i>Catatan: {{ $pd['catatan'] }}</div> @endif
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
                                                                                <div class="small text-gray-800">
                                                                                    <strong>{{ $pdk['nama'] ?: '-' }}</strong>
                                                                                    @if(!empty($pdk['no_wa'])) <small class="text-success ms-1"><i class="fa-brands fa-whatsapp"></i> {{ $pdk['no_wa'] }}</small> @endif
                                                                                    @if(!empty($pdk['catatan'])) <div class="text-muted fst-italic ps-2 border-start border-2 border-info mt-1"><i class="fa-solid fa-comment-dots me-1 text-info"></i>Catatan: {{ $pdk['catatan'] }}</div> @endif
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @elseif (!empty($pengajuan->petugas_ditugaskan))
                                                                <span class="badge bg-primary text-white px-2 py-1">
                                                                    <i class="fa-solid fa-user-check me-1"></i> {{ $pengajuan->petugas_ditugaskan }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted fst-italic">Belum ada PIC yang ditugaskan</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!Auth::user()->is_admin)
                                                    <tr>
                                                        <th class="ps-3">Status Pengajuan</th>
                                                        <td>
                                                            @if ($pengajuan->status === 'diterima')
                                                                <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-check me-1"></i> Diterima</span>
                                                            @elseif ($pengajuan->status === 'ditolak')
                                                                <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-xmark me-1"></i> Ditolak</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!empty($pengajuan->alasan_ditolak))
                                                    <tr>
                                                        <th class="ps-3">Catatan Verifikasi</th>
                                                        <td class="text-muted">{{ $pengajuan->alasan_ditolak }}</td>
                                                    </tr>
                                                    @endif
                                                    @if (!empty($pengajuan->link_desain))
                                                    <tr>
                                                        <th class="ps-3">Link Desain</th>
                                                        <td>
                                                            <a href="{{ $pengajuan->link_desain }}" target="_blank" class="text-primary text-decoration-underline">
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
                            <!-- Kolom Kanan: Verifikasi & Penugasan (Khusus Super Admin) -->
                            <div class="col-12 col-lg-6 mb-4">
                                <div class="card border border-light shadow-sm">
                                    <div class="card-header bg-gray-100 py-3">
                                        <h5 class="card-title mb-0 text-gray-800">
                                            <i class="fa-solid fa-clipboard-check me-2 text-success"></i> Verifikasi & Penugasan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status Pengajuan -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label font-weight-bold">Status Pengajuan <span class="text-danger">*</span></label>
                                            <select class="form-select" name="status" id="status" required>
                                                <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>

                                        <!-- Petugas / PIC yang Ditugaskan (Dibagi 2 Bagian: PIC Desain & PIC Dokumentasi dengan Catatan Tugas) -->
                                        <div class="mb-4">
                                            <div class="mb-3 border-bottom pb-2">
                                                <label class="form-label font-weight-bold mb-0 text-dark fs-6">
                                                    <i class="fa-solid fa-users text-primary me-1"></i> Penugasan Petugas / PIC Kegiatan
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

                                                $daftarPicDesain = array_values(array_filter($daftarPic, fn($p) => ($p['peran'] ?? 'desain') === 'desain'));
                                                $daftarPicDokumentasi = array_values(array_filter($daftarPic, fn($p) => ($p['peran'] ?? '') === 'dokumentasi'));

                                                if (empty($daftarPicDesain)) {
                                                    $daftarPicDesain = [['nama' => '', 'no_wa' => '', 'peran' => 'desain', 'catatan' => '']];
                                                }
                                                if (empty($daftarPicDokumentasi)) {
                                                    $daftarPicDokumentasi = [['nama' => '', 'no_wa' => '', 'peran' => 'dokumentasi', 'catatan' => '']];
                                                }
                                            @endphp

                                            <!-- BAGIAN 1: PIC DESAIN -->
                                            <div class="card border border-primary border-opacity-25 mb-3 shadow-xs">
                                                <div class="card-header bg-primary bg-opacity-10 py-2 px-3 d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-primary small text-uppercase">
                                                        <i class="fa-solid fa-palette me-1"></i> 1. Petugas / PIC Desain
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-primary d-inline-flex align-items-center" onclick="tambahBarisPic('desain')">
                                                        <i class="fa-solid fa-plus me-1"></i> Tambah PIC Desain
                                                    </button>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div id="kontainerPicDesain" class="d-flex flex-column gap-3">
                                                        @foreach ($daftarPicDesain as $index => $pic)
                                                            <div class="baris-pic border rounded p-3 bg-white shadow-sm" data-peran="desain">
                                                                <input type="hidden" name="pic_peran[]" value="desain">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="badge bg-primary text-white small">
                                                                        <i class="fa-solid fa-palette me-1"></i> PIC Desain
                                                                    </span>
                                                                    <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC Desain ini" onclick="hapusBarisPic(this)">
                                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                                    </button>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="form-label small text-muted fw-bold mb-1">
                                                                        <i class="fa-solid fa-user me-1 text-primary"></i> Nama Petugas / PIC
                                                                    </label>
                                                                    <select name="pic_nama[]" class="form-select select-petugas-humas" style="width: 100%;">
                                                                        <option value="">-- Cari & Pilih Petugas Humas --</option>
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
                                                                        <i class="fa-solid fa-note-sticky me-1 text-warning"></i> Catatan / Instruksi Tugas Desain
                                                                    </label>
                                                                    <textarea name="pic_catatan[]" class="form-control form-control-sm input-pic-catatan" rows="2"
                                                                        placeholder="Contoh: Buat banner ukuran 3x1m & flyer IG, gunakan tema biru...">{{ $pic['catatan'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- BAGIAN 2: PIC DOKUMENTASI -->
                                            <div class="card border border-info border-opacity-25 mb-3 shadow-xs">
                                                <div class="card-header bg-info bg-opacity-10 py-2 px-3 d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-info small text-uppercase">
                                                        <i class="fa-solid fa-camera me-1"></i> 2. Petugas / PIC Dokumentasi
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-info text-white d-inline-flex align-items-center" onclick="tambahBarisPic('dokumentasi')">
                                                        <i class="fa-solid fa-plus me-1"></i> Tambah PIC Dokumentasi
                                                    </button>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div id="kontainerPicDokumentasi" class="d-flex flex-column gap-3">
                                                        @foreach ($daftarPicDokumentasi as $index => $pic)
                                                            <div class="baris-pic border rounded p-3 bg-white shadow-sm" data-peran="dokumentasi">
                                                                <input type="hidden" name="pic_peran[]" value="dokumentasi">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="badge bg-info text-white small">
                                                                        <i class="fa-solid fa-camera me-1"></i> PIC Dokumentasi
                                                                    </span>
                                                                    <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC Dokumentasi ini" onclick="hapusBarisPic(this)">
                                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                                    </button>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="form-label small text-muted fw-bold mb-1">
                                                                        <i class="fa-solid fa-user me-1 text-primary"></i> Nama Petugas / PIC
                                                                    </label>
                                                                    <select name="pic_nama[]" class="form-select select-petugas-humas" style="width: 100%;">
                                                                        <option value="">-- Cari & Pilih Petugas Humas --</option>
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
                                                                        <i class="fa-solid fa-note-sticky me-1 text-warning"></i> Catatan / Instruksi Tugas Dokumentasi
                                                                    </label>
                                                                    <textarea name="pic_catatan[]" class="form-control form-control-sm input-pic-catatan" rows="2"
                                                                        placeholder="Contoh: Foto saat pembukaan, sesi pemateri, dan foto bersama peserta...">{{ $pic['catatan'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-1">
                                                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Notifikasi WhatsApp penugasan otomatis disiapkan untuk setiap PIC Desain dan Dokumentasi saat UPDATE.</small>
                                            </div>
                                        </div>

                                        <!-- Email Tujuan -->
                                        <div class="mb-3">
                                            <label for="email_tujuan" class="form-label font-weight-bold">Email Tujuan Notifikasi</label>
                                            <input type="email" class="form-control" name="email_tujuan" id="email_tujuan"
                                                value="{{ old('email_tujuan', $pengajuan->email_tujuan ?? $pengajuan->user?->email) }}"
                                                placeholder="contoh: pengguna@gmail.com">
                                        </div>

                                        <!-- Alasan / Catatan Verifikasi -->
                                        <div class="mb-3">
                                            <label for="alasan_ditolak" class="form-label font-weight-bold">Catatan / Alasan Verifikasi</label>
                                            <textarea class="form-control" name="alasan_ditolak" id="alasan_ditolak" rows="3"
                                                placeholder="Tuliskan catatan verifikasi atau alasan bila status ditolak/diterima...">{{ old('alasan_ditolak', $pengajuan->alasan_ditolak) ?: '' }}</textarea>
                                        </div>

                                        <!-- Link Desain jika relevan -->
                                        @if ($pengajuan->nama_kegiatan == 'Desain' || $pengajuan->nama_kegiatan == 'Desain dan Dokumentasi')
                                        <div class="mb-3">
                                            <label for="link_desain" class="form-label font-weight-bold">Link Desain</label>
                                            <textarea class="form-control" name="link_desain" id="link_desain" rows="2"
                                                placeholder="Masukkan link Google Drive / Figma / Canva desain...">{{ old('link_desain', $pengajuan->link_desain) }}</textarea>
                                        </div>
                                        @endif

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
                        placeholder: '-- Cari & Pilih Petugas Humas --',
                        allowClear: true,
                        tags: true
                    }).on('select2:select', function () {
                        pilihPetugasHumas(this);
                    }).on('select2:clear', function () {
                        const row = this.closest('.baris-pic');
                        if (row) {
                            const inputWa = row.querySelector('.input-pic-wa');
                            if (inputWa) inputWa.value = '';
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
                const selectedVal = selectElem.value;

                if (!selectedVal) {
                    if (inputWa) inputWa.value = '';
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

            function tambahBarisPic(peran) {
                peran = peran || 'desain';
                const kontainerId = peran === 'dokumentasi' ? 'kontainerPicDokumentasi' : 'kontainerPicDesain';
                const kontainer = document.getElementById(kontainerId);
                if (!kontainer) return;

                const isDesain = (peran === 'desain');
                const badgeClass = isDesain ? 'bg-primary' : 'bg-info text-white';
                const badgeIcon = isDesain ? 'fa-palette' : 'fa-camera';
                const badgeTitle = isDesain ? 'PIC Desain' : 'PIC Dokumentasi';
                const placeholderCatatan = isDesain
                    ? 'Contoh: Buat banner ukuran 3x1m & flyer IG, gunakan tema biru...'
                    : 'Contoh: Foto saat pembukaan, sesi pemateri, dan foto bersama peserta...';

                let optionsHtml = '<option value="">-- Cari & Pilih Petugas Humas --</option>';
                if (listPetugasHumas && listPetugasHumas.length > 0) {
                    optionsHtml += listPetugasHumas.map(ph => `<option value="${ph.name}" data-wa="${ph.no_wa || ''}">${ph.name}</option>`).join('');
                }

                const div = document.createElement('div');
                div.className = 'baris-pic border rounded p-3 bg-white shadow-sm';
                div.setAttribute('data-peran', peran);
                div.innerHTML = `
                    <input type="hidden" name="pic_peran[]" value="${peran}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge ${badgeClass} small">
                            <i class="fa-solid ${badgeIcon} me-1"></i> ${badgeTitle}
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC ini" onclick="hapusBarisPic(this)">
                            <i class="fa-solid fa-trash me-1"></i> Hapus
                        </button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted fw-bold mb-1">
                            <i class="fa-solid fa-user me-1 text-primary"></i> Nama Petugas / PIC
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
                            <i class="fa-solid fa-note-sticky me-1 text-warning"></i> Catatan / Instruksi Tugas (${isDesain ? 'Desain' : 'Dokumentasi'})
                        </label>
                        <textarea name="pic_catatan[]" class="form-control form-control-sm input-pic-catatan" rows="2"
                            placeholder="${placeholderCatatan}"></textarea>
                    </div>
                `;
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
                const peranVal = (peranInput && peranInput.value === 'dokumentasi') ? 'PIC Dokumentasi' : 'PIC Desain';
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

                const statusSelect = document.getElementById('status');
                const statusText = statusSelect ? statusSelect.options[statusSelect.selectedIndex].text.toUpperCase() : 'DITERIMA';
                const catatanAdminInput = document.getElementById('alasan_ditolak');
                const catatanAdmin = (catatanAdminInput && catatanAdminInput.value.trim()) ? catatanAdminInput.value.trim() : '';

                let message = `*PENUGASAN KEGIATAN HUMAS*\n\n` +
                    `Halo *${nama}*,\n` +
                    `Anda telah ditugaskan untuk kegiatan berikut:\n\n` +
                    `- *Peran Penugasan:* ${peranVal}\n` +
                    `- *Nama Kegiatan:* ${kegiatan}\n` +
                    `- *Unit:* ${unit}\n` +
                    `- *Tanggal:* ${tglAwal} s/d ${tglSelesai}\n` +
                    `- *Waktu:* ${waktu}\n` +
                    `- *Tempat:* ${tempat}\n` +
                    `- *Pemohon:* ${pemohon} (${pemohonWa})\n` +
                    (catatanKhusus ? `- *Catatan Tugas:* ${catatanKhusus}\n` : '') +
                    `- *Status Pengajuan:* ${statusText}\n` +
                    (catatanAdmin ? `- *Catatan Tambahan:* ${catatanAdmin}\n` : '') +
                    `\nMohon dikoordinasikan dan dipersiapkan dengan baik. Terima kasih.\n_SIHUMAS_`;

                window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank');
            }

            let isFormConfirmed = false;

            function handleFormSubmit(event) {
                if (isFormConfirmed) {
                    return true;
                }
                const statusSelect = document.getElementById('status');
                if (!statusSelect) {
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