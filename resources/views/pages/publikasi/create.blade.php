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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">data pelaporan kegiatan
                </li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">form tambah pelaporan kegiatan</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    <div class="mb-4">
                                        @php
                                            $defaultTahunId = old('tahun_akademik_id', \App\Models\TahunAkademik::getActive()?->id);
                                        @endphp
                                        <label for="tahun_akademik" class="form-label text-uppercase fw-bold d-flex justify-content-between align-items-center">
                                            <span><i class="fa-solid fa-calendar-check me-1 text-primary"></i> Tahun Akademik</span>
                                            <span class="badge bg-success text-white"><i class="fa-solid fa-check me-1"></i> Terpilih Otomatis (Aktif)</span>
                                        </label>
                                        <select name="tahun_akademik_id" id="tahun_akademik" class="form-select">
                                            @foreach($tahunAkademik as $tahun)
                                                <option value="{{ $tahun->id }}" {{ $defaultTahunId == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->tahun_akademik }} {{ $tahun->is_active ? ' (Aktif)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="nama_mahasiswa" class="text-capitalize">nama kegiatan</label>
                                        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                            class="form-control" id="nama_kegiatan">
                                    </div>

                                    
                                    <div class="mb-4">
                                        <label for=date" class="text-capitalize">tanggal mulai kegiatan</label>
                                        <input type="date" name="tgl_awal" value="{{ old('tgl_awal') }}"
                                            class="form-control" id="tgl_awal">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for=date" class="text-capitalize">tanggal akhir</label>
                                        <input type="date" name="tgl_akhir" value="{{ old('tgl_akhir') }}"
                                            class="form-control" id="tgl_akhir">
                                    </div>

                                    <!-- Laporan Kegiatan (Textarea TinyMCE) -->
                                    <div class="mb-4">
                                        <label for="upload_laporan" class="form-label text-uppercase fw-bold">
                                            <i class="fa-solid fa-file-lines me-1 text-primary"></i> Laporan Kegiatan / Ringkasan Liputan
                                        </label>
                                        <textarea class="form-control" name="upload_laporan" id="upload_laporan" rows="6"
                                            placeholder="Tuliskan ringkasan hasil kegiatan, rilis berita, atau laporan peliputan...">{{ old('upload_laporan') }}</textarea>
                                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Format teks didukung, sistem akan otomatis membersihkan tag HTML agar data tersimpan rapi.</small>
                                    </div>

                                    <!-- Link Dokumentasi -->
                                    <div class="mb-4">
                                        <label for="link_dokumentasi" class="form-label text-uppercase fw-bold">
                                            <i class="fa-solid fa-folder-open me-1 text-primary"></i> Link Dokumentasi
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-primary">
                                                <i class="fa-solid fa-link"></i>
                                            </span>
                                            <input type="url" class="form-control" name="link_dokumentasi" id="link_dokumentasi"
                                                value="{{ old('link_dokumentasi') }}" placeholder="https://drive.google.com/drive/folders/...">
                                        </div>
                                    </div>

                                    <!-- Upload File / Gambar Dokumentasi (Multi, Optional) -->
                                    <div class="mb-4">
                                        <label for="dokumentasi_files" class="form-label text-uppercase fw-bold d-flex justify-content-between align-items-center">
                                            <span><i class="fa-solid fa-images me-1 text-info"></i> Upload File / Foto Dokumentasi</span>
                                            <span class="badge bg-secondary text-white fw-normal">Opsional</span>
                                        </label>
                                        <input type="file" name="dokumentasi_files[]" id="dokumentasi_files" class="form-control" multiple
                                            accept="image/*,.pdf,.doc,.docx,.zip,.rar">
                                        <small class="text-muted d-block mt-1">
                                            <i class="fa-solid fa-circle-info me-1"></i> Anda dapat memilih beberapa file atau foto sekaligus (format: JPG, PNG, PDF, DOCX, ZIP/RAR, maks 10MB per file).
                                        </small>
                                    </div>

                                    <!-- Link Publikasi (Bisa Tambah Lebih Dari 1 Link) -->
                                    <div class="mb-4">
                                        <label class="form-label text-uppercase fw-bold d-flex justify-content-between align-items-center">
                                            <span><i class="fa-solid fa-globe me-1 text-success"></i> Link Publikasi (Media Online / Website / Sosmed)</span>
                                            <small class="text-muted fw-normal">Bisa lebih dari 1 link</small>
                                        </label>

                                        @php
                                            $linksOld = old('link_publikasi', ['']);
                                            if (!is_array($linksOld)) {
                                                $linksOld = [$linksOld];
                                            }
                                        @endphp

                                        <div id="linkPublikasiContainer">
                                            @foreach($linksOld as $link)
                                                <div class="input-group mb-2 link-publikasi-row">
                                                    <span class="input-group-text bg-white text-success">
                                                        <i class="fa-solid fa-link"></i>
                                                    </span>
                                                    <input type="url" name="link_publikasi[]" class="form-control"
                                                        value="{{ $link }}" placeholder="https://... (link berita website atau media sosial)">
                                                    <button type="button" class="btn btn-outline-danger btn-remove-link d-inline-flex align-items-center px-3"
                                                        title="Hapus Link" {{ count($linksOld) <= 1 ? 'style=display:none;' : '' }}>
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-success mt-1 d-inline-flex align-items-center" id="btnAddLinkPublikasi">
                                            <i class="fa-solid fa-circle-plus me-1"></i> Tambah Link Publikasi
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                    </path>
                                </svg>
                                SUBMIT
                            </button>

                            <a href="{{ route('publikasi.index') }}" class="btn btn-sm btn-danger px-2 py-2">BACK <i
                                    class="fa-solid fa-right-from-bracket"></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
    <!-- TinyMCE CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.tinymce) {
                tinymce.init({
                    selector: '#upload_laporan',
                    menubar: false,
                    plugins: 'lists link wordcount',
                    toolbar: 'undo redo | bold italic underline | bullist numlist | removeformat',
                    branding: false,
                    promotion: false,
                    height: 250,
                    placeholder: 'Tuliskan rincian atau ringkasan laporan kegiatan hasil peliputan di sini...',
                    setup: function(editor) {
                        editor.on('change keyup', function() {
                            editor.save();
                        });
                    }
                });
            }

            const form = document.querySelector('form[action*="publikasi"]');
            if (form) {
                form.addEventListener('submit', function() {
                    if (window.tinymce) {
                        tinymce.triggerSave();
                    }
                });
            }

            const container = document.getElementById('linkPublikasiContainer');
            const btnAdd = document.getElementById('btnAddLinkPublikasi');

            function updateRemoveButtons() {
                if (!container) return;
                const rows = container.querySelectorAll('.link-publikasi-row');
                rows.forEach(function(row) {
                    const btn = row.querySelector('.btn-remove-link');
                    if (btn) {
                        btn.style.display = rows.length > 1 ? 'inline-flex' : 'none';
                    }
                });
            }

            if (btnAdd && container) {
                btnAdd.addEventListener('click', function () {
                    const newRow = document.createElement('div');
                    newRow.className = 'input-group mb-2 link-publikasi-row';
                    newRow.innerHTML = `
                        <span class="input-group-text bg-white text-success">
                            <i class="fa-solid fa-link"></i>
                        </span>
                        <input type="url" name="link_publikasi[]" class="form-control"
                            placeholder="https://... (link berita website atau media sosial)">
                        <button type="button" class="btn btn-outline-danger btn-remove-link d-inline-flex align-items-center px-3" title="Hapus Link">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    `;
                    container.appendChild(newRow);
                    updateRemoveButtons();
                    const input = newRow.querySelector('input');
                    if (input) input.focus();
                });

                container.addEventListener('click', function (e) {
                    const btn = e.target.closest('.btn-remove-link');
                    if (btn) {
                        const row = btn.closest('.link-publikasi-row');
                        if (row && container.querySelectorAll('.link-publikasi-row').length > 1) {
                            row.remove();
                            updateRemoveButtons();
                        }
                    }
                });

                updateRemoveButtons();
            }
        });
    </script>
    @endpush

    @push('style')
        <style>
            .link-publikasi-row {
                display: flex !important;
                align-items: stretch !important;
            }
            .link-publikasi-row .input-group-text,
            .link-publikasi-row .form-control,
            .link-publikasi-row .btn {
                height: 42px !important;
                min-height: 42px !important;
                max-height: 42px !important;
                box-sizing: border-box !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                font-size: 0.875rem !important;
                line-height: 1.5 !important;
            }
            .link-publikasi-row .btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        </style>
    @endpush
