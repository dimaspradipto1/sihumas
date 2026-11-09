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
            <h1 class="h4 text-capitalize">form edit pengajuan berita</h1>
            <div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if (Auth::user()->is_feb || Auth::user()->is_fst || Auth::user()->is_fikes ||
                    Auth::user()->is_rektorat)
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
                                    <!-- Upload File Dokumen -->
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="upload_file" class="text-capitalize fw-bold">Upload File / Berkas Lampiran <span
                                                class="text-muted small fw-normal">(PDF, DOC, DOCX, XLS, ZIP - Maks. 10MB)</span></label>
                                        @if ($pengajuan->upload_file)
                                            <div class="mb-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                                <span class="small text-truncate me-2"><i class="fa-solid fa-file-lines text-primary me-1"></i> File saat ini: <strong>{{ basename($pengajuan->upload_file) }}</strong></span>
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
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file lampiran.</small>
                                    </div>

                                    <!-- Upload Gambar / PNG -->
                                    <div class="col-md-6">
                                        <label for="upload_png" class="text-capitalize fw-bold">Upload Gambar / Desain <span
                                                class="text-muted small fw-normal">(Format PNG, JPG, JPEG, WEBP - Maks. 5MB)</span></label>
                                        @if ($pengajuan->upload_png)
                                            <div class="mb-2">
                                                <p class="text-xs text-muted mb-1">Gambar saat ini:</p>
                                                <div class="border rounded p-1 bg-light d-inline-block shadow-sm">
                                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Current Image"
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
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar/desain.</small>
                                        <div id="imagePreviewContainerEdit" class="mt-3 d-none">
                                            <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Gambar Baru:</p>
                                            <div class="border rounded p-2 bg-light d-inline-block shadow-sm">
                                                <img id="imagePreviewEdit" src="#" alt="Preview"
                                                    class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (Auth::user()->is_admin)
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

                    @if (Auth::user()->is_admin)
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Kolom Kiri: Detail Pengajuan / Review -->
                            <div class="col-12 col-xl-7 mb-4">
                                <div class="card border border-light shadow-sm h-100">
                                    <div class="card-header bg-gray-100 py-3">
                                        <h5 class="card-title mb-0 text-gray-800">
                                            <i class="fa-solid fa-file-lines me-2 text-primary"></i> Detail Pengajuan (Review)
                                        </h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 35%;" class="ps-3">Pemohon</th>
                                                        <td>
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
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">Mulai:</small>
                                                                    <input type="date" name="tgl_awal" id="tgl_awal" class="form-control form-control-sm" value="{{ $pengajuan->tgl_awal }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">Selesai:</small>
                                                                    <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-sm" value="{{ $pengajuan->tgl_selesai }}">
                                                                </div>
                                                            </div>
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
                                                        <td>{{ $pengajuan->tempat_kegiatan ?? '-' }}</td>
                                                    </tr>
                                                    @if ($pengajuan->link_zoom)
                                                    <tr>
                                                        <th class="ps-3">Link Zoom</th>
                                                        <td>
                                                            <a href="{{ $pengajuan->link_zoom }}" target="_blank" class="text-decoration-underline text-break">
                                                                {{ $pengajuan->link_zoom }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th class="ps-3">Deskripsi</th>
                                                        <td>{{ trim(strip_tags($pengajuan->deskripsi_kegiatan ?? '')) ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Kebutuhan Perlengkapan</th>
                                                        <td>{{ trim(strip_tags($pengajuan->perlengkapan ?? '')) ?: '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">File Lampiran</th>
                                                        <td>
                                                            @if ($pengajuan->upload_file)
                                                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> Unduh File ({{ basename($pengajuan->upload_file) }})
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Tidak ada file</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-3">Gambar / Desain (PNG)</th>
                                                        <td>
                                                            @if ($pengajuan->upload_png)
                                                                <div>
                                                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank">
                                                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Desain" class="img-thumbnail" style="max-height: 140px;">
                                                                    </a>
                                                                    <div class="mt-1">
                                                                        <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                                            <i class="fa-solid fa-eye me-1"></i> Lihat Gambar Penuh
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">Tidak ada gambar</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Verifikasi & Penugasan -->
                            <div class="col-12 col-xl-5 mb-4">
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

                                        <!-- Petugas / PIC yang Ditugaskan (Multiple) -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label font-weight-bold mb-0">
                                                    <i class="fa-solid fa-users text-primary me-1"></i> Petugas / PIC yang Ditugaskan
                                                </label>
                                                <button type="button" class="btn btn-xs btn-primary" onclick="tambahBarisPic()">
                                                    <i class="fa-solid fa-plus me-1"></i> Tambah PIC
                                                </button>
                                            </div>

                                            @php
                                                $daftarPic = old('pic_nama') !== null
                                                    ? array_map(function($nama, $noWa) { return ['nama' => $nama, 'no_wa' => $noWa]; }, old('pic_nama', []), old('pic_no_wa', []))
                                                    : ($pengajuan->pic_petugas ?? []);

                                                if (empty($daftarPic) && !empty($pengajuan->petugas_ditugaskan)) {
                                                    $arrNama = array_map('trim', explode(',', $pengajuan->petugas_ditugaskan));
                                                    $arrWa = array_map('trim', explode(',', $pengajuan->no_wa_tujuan ?? ''));
                                                    foreach ($arrNama as $idx => $nama) {
                                                        $daftarPic[] = ['nama' => $nama, 'no_wa' => $arrWa[$idx] ?? ''];
                                                    }
                                                }

                                                if (empty($daftarPic)) {
                                                    $daftarPic = [['nama' => '', 'no_wa' => '']];
                                                }
                                            @endphp

                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered align-middle mb-1" id="tabelPic">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th style="width: 48%;">Nama Petugas / PIC</th>
                                                            <th style="width: 42%;">No. WhatsApp</th>
                                                            <th style="width: 10%; text-align: center;">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="kontainerPic">
                                                        @foreach ($daftarPic as $index => $pic)
                                                            <tr class="baris-pic">
                                                                <td>
                                                                    <input type="text" name="pic_nama[]" class="form-control form-control-sm input-pic-nama"
                                                                        value="{{ $pic['nama'] ?? '' }}" placeholder="Contoh: Habib">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text bg-success text-white py-0"><i class="fa-brands fa-whatsapp"></i></span>
                                                                        <input type="text" name="pic_no_wa[]" class="form-control form-control-sm input-pic-wa"
                                                                            value="{{ $pic['no_wa'] ?? '' }}" placeholder="08123456789">
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex justify-content-center gap-1">
                                                                        <button type="button" class="btn btn-xs btn-outline-success" title="Chat WA PIC ini" onclick="chatWaPic(this)">
                                                                            <i class="fa-brands fa-whatsapp"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC" onclick="hapusBarisPic(this)">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted">Bisa input lebih dari 1 PIC. Notifikasi WhatsApp penugasan akan disiapkan saat submit.</small>
                                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="tambahBarisPic()">
                                                    <i class="fa-solid fa-user-plus me-1"></i> Tambah PIC
                                                </button>
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
                                            <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center"
                                                onclick="return confirm('Yakin ingin menyimpan perubahan verifikasi dan penugasan?')">
                                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                    </path>
                                                </svg>
                                                UPDATE
                                            </button>
                                            <a href="{{ route('pengajuan.index') }}" class="btn btn-danger d-inline-flex align-items-center">
                                                <i class="fa-solid fa-right-from-bracket me-2"></i> BACK
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

            function tambahBarisPic() {
                const tbody = document.getElementById('kontainerPic');
                if (!tbody) return;
                const tr = document.createElement('tr');
                tr.className = 'baris-pic';
                tr.innerHTML = `
                    <td>
                        <input type="text" name="pic_nama[]" class="form-control form-control-sm input-pic-nama" placeholder="Contoh: Ujang">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-success text-white py-0"><i class="fa-brands fa-whatsapp"></i></span>
                            <input type="text" name="pic_no_wa[]" class="form-control form-control-sm input-pic-wa" placeholder="08123456789">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" class="btn btn-xs btn-outline-success" title="Chat WA PIC ini" onclick="chatWaPic(this)">
                                <i class="fa-brands fa-whatsapp"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger" title="Hapus PIC" onclick="hapusBarisPic(this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
                const input = tr.querySelector('.input-pic-nama');
                if (input) input.focus();
            }

            function hapusBarisPic(btn) {
                const tbody = document.getElementById('kontainerPic');
                const rows = tbody.querySelectorAll('.baris-pic');
                if (rows.length > 1) {
                    btn.closest('tr').remove();
                } else {
                    const row = btn.closest('tr');
                    row.querySelector('.input-pic-nama').value = '';
                    row.querySelector('.input-pic-wa').value = '';
                }
            }

            function chatWaPic(btn) {
                const row = btn.closest('tr');
                const nama = row.querySelector('.input-pic-nama').value.trim() || 'Petugas Humas';
                const rawWa = row.querySelector('.input-pic-wa').value.trim();

                if (!rawWa) {
                    alert('Silakan masukkan nomor WhatsApp PIC ini terlebih dahulu.');
                    row.querySelector('.input-pic-wa').focus();
                    return;
                }

                let phone = rawWa.replace(/\D/g, '');
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('8')) {
                    phone = '62' + phone;
                }

                const kegiatan = @json($pengajuan->nama_kegiatan);
                const unit = @json($pengajuan->unit_kegiatan);
                const statusSelect = document.getElementById('status');
                const statusText = statusSelect ? statusSelect.options[statusSelect.selectedIndex].text.toUpperCase() : '';
                const catatanInput = document.getElementById('alasan_ditolak');
                const catatan = (catatanInput && catatanInput.value.trim()) ? catatanInput.value.trim() : '-';

                let message = `*PENUGASAN KEGIATAN HUMAS*\n\n` +
                    `Halo *${nama}*,\n` +
                    `Anda ditugaskan untuk kegiatan berikut:\n\n` +
                    `📌 *Nama Kegiatan:* ${kegiatan}\n` +
                    `🏢 *Unit:* ${unit}\n` +
                    `📝 *Status:* ${statusText}\n` +
                    `💬 *Catatan:* ${catatan}\n\n` +
                    `Mohon untuk dipersiapkan. Terima kasih.\n_SIHUMAS_`;

                window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank');
            }
        </script>
    @endpush