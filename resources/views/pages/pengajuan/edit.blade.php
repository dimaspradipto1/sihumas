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
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    {{-- <tr>
                                        <th>Tahun Akademik</th>
                                        <td
                                            class="badge bg-primary text-uppercase font-weight-bolder text-white mx-3 my-2 badge-xl">
                                            @foreach ($tahunAkademik as $tahun)
                                            @if ($tahun->id == $pengajuan->tahun_akademik_id)
                                            {{ $tahun->tahun_akademik }}
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <th>Nama Kegiatan</th>
                                        <td>{{ $pengajuan->nama_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Kegiatan</th>
                                        {{-- <td>{{
                                            \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->isoFormat('dddd,
                                            D MMMM Y') }}
                                        </td> --}}
                                        <td>
                                            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" value="{{ $pengajuan->tgl_awal }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Selesai</th>
                                        {{-- <td>{{
                                            \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->isoFormat('dddd,
                                            D MMMM Y') }}
                                        </td> --}}
                                        <td>
                                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" value="{{ $pengajuan->tgl_selesai }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Kegiatan</th>
                                        <td>{{ $pengajuan->jam_kegiatan }} WIB</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Selesai</th>
                                        <td>{{ $pengajuan->waktu_selesai }} WIB</td>
                                        {{-- <td><input type="time" name="waktu_selesai" value="{{ $pengajuan->waktu_selesai }}"></td> --}}
                                    </tr>
                                    <tr>
                                        <th>Link Zoom</th>
                                        <td>{{ $pengajuan->link_zoom }}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td>{{ trim(strip_tags($pengajuan->deskripsi_kegiatan ?? '')) ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kebutuhan Perlengkapan</th>
                                        <td>{{ trim(strip_tags($pengajuan->perlengkapan ?? '')) ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Kegiatan</th>
                                        <td>{{ $pengajuan->unit_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Pengajuan</th>
                                        <td>
                                            @if ($pengajuan->status == 'pending')
                                            <span class="badge bg-warning badge-xl px-3 py-2">{{ $pengajuan->status }}
                                                <i class="fa-solid fa-spinner"></i></span>
                                            @elseif($pengajuan->status == 'diterima')
                                            <span class="badge bg-success badge-xl px-3 py-2">{{ $pengajuan->status }}
                                                <i class="fa-solid fa-check"></i></span>
                                            @elseif($pengajuan->status == 'ditolak')
                                            <span class="badge bg-danger badge-xl px-3 py-2">{{ $pengajuan->status }}
                                                <i class="fa-solid fa-xmark"></i></span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        @if (Auth::user()->is_admin)
                                        <th>Edit Status Pengajuan</th>
                                        <td>
                                            <div class="mb-4">
                                                <label for="status" class="text-uppercase">status</label>
                                                <div class="form-group">
                                                    <select class="form-control form-control-sm" name="status"
                                                        id="status">
                                                        <option value="pending" {{ $pengajuan->status == 'pending' ?
                                                            'selected' : '' }}>
                                                            pending</option>
                                                        <option value="diterima" {{ $pengajuan->status == 'diterima' ?
                                                            'selected' : '' }}>
                                                            diterima</option>
                                                        <option value="ditolak" {{ $pengajuan->status == 'ditolak' ?
                                                            'selected' : '' }}>
                                                            ditolak</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Alasan Ditolak/Diterima</th>
                                        <td>
                                            <textarea class="form-control" name="alasan_ditolak" rows="3"
                                                placeholder="Masukkan alasan ditolak/diterima jika tidak ada masukan berikan tanda (-)">{{ old('alasan_ditolak', $pengajuan->alasan_ditolak) ?: '' }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email Tujuan</th>
                                        <td>
                                            <input type="email" class="form-control" name="email_tujuan"
                                                value="{{ old('email_tujuan', $pengajuan->email_tujuan) }}"
                                                placeholder="contoh: pengguna@gmail.com">
                                        </td>
                                    </tr>
                                    {{-- Menampilkan tr jika nama_kegiatan memuat 'Desain' --}}
                                    @if ($pengajuan->nama_kegiatan == 'Desain' || $pengajuan->nama_kegiatan == 'Desain dan Dokumentasi')
                                    <tr>
                                        <th>Link Desain</th>
                                        <td>
                                            <textarea class="form-control" name="link_desain"
                                                rows="3">{{ old('link_desain', $pengajuan->link_desain) }}</textarea>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>File Lampiran</th>
                                        <td>
                                            @if ($pengajuan->upload_file)
                                                <a href="{{ asset('storage/' . $pengajuan->upload_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> Unduh File ({{ basename($pengajuan->upload_file) }})
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada file lampiran</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gambar / Desain (PNG)</th>
                                        <td>
                                            @if ($pengajuan->upload_png)
                                                <div class="d-inline-block">
                                                    <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $pengajuan->upload_png) }}" alt="Desain" class="img-thumbnail" style="max-height: 150px;">
                                                    </a>
                                                    <div class="mt-1">
                                                        <a href="{{ asset('storage/' . $pengajuan->upload_png) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                            <i class="fa-solid fa-eye me-1"></i> Buka Gambar Penuh
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    </tr>
                                </table>
                                <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 aria-haspopup="
                                    true" onclick="return confirm('Yakin ingin mengubah status pengajuan?')">
                                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                        </path>
                                    </svg>
                                    UPDATE
                                </button>
                                <a href="{{ route('pengajuan.index') }}"
                                    class="btn btn-danger d-inline-flex align-items-center me-2">
                                    BACK
                                </a>
                                </tr>
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
        </script>
    @endpush