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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">data pengajuan
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">form pengajuan</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    {{-- <div class="mb-4">
                                        <label for="tahun_akademik_id" class="text-capitalize">tahun akademik</label>
                                       <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select single">
                                        <option>Pilih Tahun Akademik</option>
                                        <option>====================================================</option>
                                            @foreach ($tahunAkademik as $tahun)
                                                <option value="{{ $tahun->id }}"
                                                    {{ request('tahun_akademik_id') == $tahun->id ? 'selected' : '' }}>{{ $tahun->tahun_akademik }}</option>
                                            @endforeach
                                            <option>====================================================</option>
                                        </select>
                                    </div> --}}

                                    @php
                                        $daftarJenisPengajuan = [
                                            'Desain' => 'Desain',
                                            'Dokumentasi' => 'Dokumentasi',
                                            'Desain dan Dokumentasi' => 'Desain dan Dokumentasi',
                                            'Publikasi' => 'Publikasi',
                                            'Desain dan Publikasi' => 'Desain dan Publikasi',
                                            'Tamu Undangan' => 'Tamu',
                                        ];
                                        $valJenis = old('nama_kegiatan', '');
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
                                                <input type="date" name="tgl_awal" value="{{ old('tgl_awal') }}"
                                                    class="form-control" id="tgl_awal" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none" id="col_target_publikasi">
                                            <div class="mb-4 mb-md-0">
                                                <label for="target_publikasi_tgl" class="text-capitalize fw-bold">Target Publikasi <span class="text-danger">*</span></label>
                                                <input type="date" name="tgl_selesai" id="target_publikasi_tgl" class="form-control"
                                                    value="{{ old('tgl_selesai') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="wrapper_jam_kegiatan">
                                            <div class="mb-4 mb-md-0">
                                                <label for="jam_kegiatan" class="text-capitalize">jam kegiatan</label>
                                                <input type="time" name="jam_kegiatan" id="jam_kegiatan" class="form-control"
                                                    value="{{ old('jam_kegiatan') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4" id="wrapper_tgl_selesai_waktu">
                                        <div class="col-md-6">

                                            <div class="mb-4 mb-md-0">
                                                <label for="tgl_selesai" class="text-capitalize">tanggal Selesai
                                                    kegiatan</label>
                                                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}"
                                                    class="form-control" id="tgl_selesai">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="waktu_selesai" class="text-capitalize">waktu selesai</label>
                                                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control"
                                                    value="{{ old('waktu_selesai') }}">
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
                                        $valTempat = old('tempat_kegiatan', '');
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

                                    @if (auth()->user()?->unitKegiatan)
                                        <input type="hidden" name="unit_kegiatan" value="{{ auth()->user()->unitKegiatan->unit_kegiatan }}">
                                    @endif


                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="deskripsi_kegiatan" id="label_deskripsi_kegiatan" class="text-capitalize fw-bold">deskripsi
                                                kegiatan</label>
                                            <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan"
                                                value="{{ old('deskripsi_kegiatan') }}" rows="3"
                                                placeholder="Masukkan Deskripsi Kegiatan">{{ old('deskripsi_kegiatan') }}</textarea>
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
                                                value="{{ old('perlengkapan') }}" rows="3"
                                                placeholder="Masukkan Kebutuhan Perlengkapan">{{ old('perlengkapan') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="wrapper_link_zoom">
                                        <div class="form-group">
                                            <label for="link_zoom" class="text-capitalize">link zoom <span
                                                    class="text-danger">(jika ada)</span></label>
                                            <textarea class="form-control" name="link_zoom" id="link_zoom" rows="3"
                                                placeholder="contoh: https://zoom.us/j/123456789">{{ old('link_zoom') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <!-- Upload Dokumen Pendukung -->
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="upload_file" class="text-capitalize fw-bold">Upload Dokumen Pendukung <span
                                                    class="text-muted small fw-normal">(PDF, DOC, DOCX, XLS, ZIP - Maks. 10MB)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-file-arrow-up"></i></span>
                                                <input type="file" name="upload_file" id="upload_file" class="form-control"
                                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" onchange="previewDokumen(this)">
                                            </div>
                                            <small class="text-muted">Lampirkan file proposal, surat permohonan, atau berkas pendukung kegiatan.</small>

                                            <div id="filePreviewContainer" class="mt-3 d-none">
                                                <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Dokumen Pendukung:</p>
                                                <div class="border rounded p-2 bg-light shadow-sm">
                                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                                        <div class="d-flex align-items-center gap-2 text-truncate">
                                                            <i id="filePreviewIcon" class="fa-solid fa-file-pdf fa-2x text-danger"></i>
                                                            <div class="text-truncate">
                                                                <div id="filePreviewName" class="small fw-bold text-dark text-truncate"></div>
                                                                <div id="filePreviewSize" class="text-muted" style="font-size: 0.75rem;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-1 flex-shrink-0">
                                                            <a id="filePreviewLink" href="#" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" title="Buka Pratinjau">
                                                                <i class="fa-solid fa-eye me-1"></i> Lihat
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2" onclick="resetDokumenPreview()" title="Hapus Pilihan">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="pdfEmbedWrapper" class="mt-2 d-none">
                                                        <iframe id="pdfEmbedPreview" src="" class="w-100 border rounded bg-white" style="height: 220px;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload Bahan Materi -->
                                        <div class="col-md-6">
                                            <label for="upload_png" class="text-capitalize fw-bold">Upload Bahan Materi <span
                                                    class="text-muted small fw-normal">(Format PNG, JPG, JPEG, WEBP - Maks. 5MB)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                                <input type="file" name="upload_png" id="upload_png" class="form-control"
                                                    accept=".png,.jpg,.jpeg,.webp" onchange="previewImage(this)">
                                            </div>
                                            <small class="text-muted">Lampirkan materi kegiatan seperti flyer, banner, pamflet, foto, atau logo pendukung.</small>
                                            <div id="imagePreviewContainer" class="mt-3 d-none">
                                                <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Bahan Materi:</p>
                                                <div class="border rounded p-2 bg-light shadow-sm">
                                                    <div class="text-center mb-2">
                                                        <a id="imagePreviewFullLink" href="#" target="_blank">
                                                            <img id="imagePreview" src="#" alt="Preview"
                                                                class="img-fluid rounded border bg-white" style="max-height: 200px; object-fit: contain;">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between gap-2 border-top pt-2">
                                                        <div class="text-truncate">
                                                            <div id="imagePreviewName" class="small fw-bold text-dark text-truncate"></div>
                                                            <div id="imagePreviewSize" class="text-muted" style="font-size: 0.75rem;"></div>
                                                        </div>
                                                        <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2 flex-shrink-0" onclick="resetImagePreview()" title="Hapus Pilihan">
                                                            <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (Auth::user()->is_admin || Auth::user()->is_humas)
                                        <div class="mb-4">
                                            <label for="status" class="text-capitalize">status</label>
                                            <div class="form-group">
                                                <select class="form-control form-control-sm" name="status"
                                                    id="status">
                                                    <option value="">Pilih Status</option>
                                                    <option value="pending">pending</option>
                                                    <option value="approved">disetujui</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="email_tujuan" class="text-capitalize">Email yang ditujukan kepada
                                                pemberi tugas</label>
                                            <input type="email" class="form-control" name="email_tujuan"
                                                value="{{ old('email_tujuan') }}"
                                                placeholder="contoh: pengguna@gmail.com">
                                        </div>
                                    @endif


                                </div>
                            </div>

                            <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 aria-haspopup="true"
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
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            function formatFileSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            function previewDokumen(input) {
                const container = document.getElementById('filePreviewContainer');
                const icon = document.getElementById('filePreviewIcon');
                const nameEl = document.getElementById('filePreviewName');
                const sizeEl = document.getElementById('filePreviewSize');
                const linkEl = document.getElementById('filePreviewLink');
                const pdfWrapper = document.getElementById('pdfEmbedWrapper');
                const pdfFrame = document.getElementById('pdfEmbedPreview');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const ext = (file.name.split('.').pop() || '').toLowerCase();
                    const objUrl = URL.createObjectURL(file);

                    nameEl.textContent = file.name;
                    sizeEl.textContent = formatFileSize(file.size) + ' • ' + ext.toUpperCase();
                    linkEl.href = objUrl;

                    if (ext === 'pdf') {
                        icon.className = 'fa-solid fa-file-pdf fa-2x text-danger';
                        pdfFrame.src = objUrl;
                        pdfWrapper.classList.remove('d-none');
                    } else if (['doc', 'docx'].includes(ext)) {
                        icon.className = 'fa-solid fa-file-word fa-2x text-primary';
                        pdfFrame.src = '';
                        pdfWrapper.classList.add('d-none');
                    } else if (['xls', 'xlsx'].includes(ext)) {
                        icon.className = 'fa-solid fa-file-excel fa-2x text-success';
                        pdfFrame.src = '';
                        pdfWrapper.classList.add('d-none');
                    } else if (['zip', 'rar'].includes(ext)) {
                        icon.className = 'fa-solid fa-file-zipper fa-2x text-warning';
                        pdfFrame.src = '';
                        pdfWrapper.classList.add('d-none');
                    } else {
                        icon.className = 'fa-solid fa-file-lines fa-2x text-secondary';
                        pdfFrame.src = '';
                        pdfWrapper.classList.add('d-none');
                    }

                    container.classList.remove('d-none');
                } else {
                    resetDokumenPreview();
                }
            }

            function resetDokumenPreview() {
                const input = document.getElementById('upload_file');
                const container = document.getElementById('filePreviewContainer');
                const pdfWrapper = document.getElementById('pdfEmbedWrapper');
                const pdfFrame = document.getElementById('pdfEmbedPreview');
                if (input) input.value = '';
                if (pdfFrame) pdfFrame.src = '';
                if (pdfWrapper) pdfWrapper.classList.add('d-none');
                if (container) container.classList.add('d-none');
            }

            function previewImage(input) {
                const previewContainer = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');
                const fullLink = document.getElementById('imagePreviewFullLink');
                const nameEl = document.getElementById('imagePreviewName');
                const sizeEl = document.getElementById('imagePreviewSize');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    if (nameEl) nameEl.textContent = file.name;
                    if (sizeEl) sizeEl.textContent = formatFileSize(file.size);
                    const objUrl = URL.createObjectURL(file);
                    if (fullLink) fullLink.href = objUrl;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    resetImagePreview();
                }
            }

            function resetImagePreview() {
                const input = document.getElementById('upload_png');
                const previewContainer = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');
                if (input) input.value = '';
                if (preview) preview.src = '#';
                if (previewContainer) previewContainer.classList.add('d-none');
            }
        </script>
        <script>
            CKEDITOR.replace('deskripsi_kegiatan', {
                versionCheck: false
            });
        </script>
        <script>
            CKEDITOR.replace('perlengkapan', {
                versionCheck: false
            });
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

                // Adaptive form: khusus pilihan "Desain"
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
