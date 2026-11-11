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
                <h1 class="h4 text-capitalize">form edit pelaporan kegiatan</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                @if ($publikasi->status_validasi === 'perlu_revisi')
                    <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation fa-2x me-3 text-danger"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Catatan Revisi dari Super Admin:</h6>
                            <p class="mb-0 text-danger fw-semibold">
                                {{ $publikasi->catatan_validasi ?? 'Mohon periksa dan perbaiki kelengkapan dokumentasi atau rilis laporan kegiatan Anda.' }}
                            </p>
                        </div>
                    </div>
                @elseif ($publikasi->status_validasi === 'menunggu_validasi')
                    <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert">
                        <i class="fa-solid fa-clock fa-2x me-3 text-warning"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Status: Menunggu Validasi Admin</h6>
                            <p class="mb-0">Laporan kegiatan ini sedang dalam antrean validasi oleh Super Admin.</p>
                        </div>
                    </div>
                @elseif ($publikasi->status_validasi === 'disetujui')
                    <div class="alert alert-success d-flex align-items-center shadow-sm mb-4" role="alert">
                        <i class="fa-solid fa-circle-check fa-2x me-3 text-success"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Status: Laporan Telah Tervalidasi &amp; Disetujui</h6>
                            <p class="mb-0">Laporan kegiatan ini telah disetujui oleh Super Admin.</p>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow components-section">
                    <form action="{{ route('publikasi.update', $publikasi) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            <div class="row mb-4">
                                <div class="col-12">
                                    <!-- Nama Kegiatan -->
                                    <div class="mb-4">
                                        <label for="nama_kegiatan" class="text-uppercase">Nama Kegiatan</label>
                                        <textarea name="nama_kegiatan" class="form-control" id="nama_kegiatan" readonly>{{ old('nama_kegiatan') ?? ($pengajuan?->nama_kegiatan ?? '') }}</textarea>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">

                                            <div class="mb-4 mb-md-0">
                                                <label for="tgl_awal" class="text-uppercase">Tanggal Awal</label>
                                                <input type="date" name="tgl_awal"
                                                    value="{{ old('tgl_awal') ?? ($pengajuan?->tgl_awal ?? '') }}"
                                                    class="form-control" id="tgl_awal" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="tgl_akhir" class="text-uppercase">Tanggal Akhir</label>
                                                <input type="date" name="tgl_akhir"
                                                    value="{{ old('tgl_akhir') ?? (old('tgl_selesai') ?? ($pengajuan?->tgl_selesai ?? '')) }}"
                                                    class="form-control" id="tgl_akhir" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="tgl_awal" class="text-uppercase">Tanggal Awal</label>
                                                <input type="date" name="tgl_awal"
                                                    value="{{ old('tgl_awal') ?? (isset($pengajuan->tgl_awal) ? \Carbon\Carbon::parse($pengajuan->tgl_awal)->format('Y-m-d') : '') }}"
                                                    class="form-control" id="tgl_awal" readonly>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="tgl_akhir" class="text-uppercase">Tanggal Akhir</label>
                                                <input type="date" name="tgl_akhir"
                                                    value="{{ old('tgl_selesai') ?? (isset($pengajuan->tgl_selesai) ? \Carbon\Carbon::parse($pengajuan->tgl_selesai)->format('Y-m-d') : '') }}"
                                                    class="form-control" id="tgl_akhir" readonly>
                                            </div>
                                        </div>
                                    </div> --}}
                                    

                                    <!-- Laporan Kegiatan (Textarea TinyMCE) -->
                                    <div class="mb-4">
                                        <label for="upload_laporan" class="form-label text-uppercase fw-bold">
                                            <i class="fa-solid fa-file-lines me-1 text-primary"></i> Laporan Kegiatan / Ringkasan Liputan
                                        </label>
                                        <textarea name="upload_laporan" class="form-control" id="upload_laporan" rows="6"
                                            placeholder="Tuliskan ringkasan hasil kegiatan, rilis berita, atau laporan peliputan...">{{ old('upload_laporan') ?? ($publikasi->upload_laporan ?? ($pengajuan->upload_laporan ?? '')) }}</textarea>
                                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Format teks didukung, sistem akan otomatis membersihkan tag HTML agar data tersimpan rapi.</small>
                                    </div>

                                    <!-- Link Dokumentasi -->
                                    <div class="mb-4">
                                        <label for="link_dokumentasi" class="form-label text-uppercase fw-bold">
                                            <i class="fa-solid fa-folder-open me-1 text-primary"></i> Link Dokumentasi (Google Drive / Foto / Video)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-primary">
                                                <i class="fa-solid fa-link"></i>
                                            </span>
                                            <input type="url" name="link_dokumentasi" class="form-control" id="link_dokumentasi"
                                                value="{{ old('link_dokumentasi') ?? ($publikasi->link_dokumentasi ?? ($pengajuan->link_dokumentasi ?? '')) }}"
                                                placeholder="https://drive.google.com/drive/folders/...">
                                            @if (!empty($publikasi->link_dokumentasi))
                                                <a href="{{ $publikasi->link_dokumentasi }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Link
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Link Publikasi (Bisa Tambah Lebih Dari 1 Link) -->
                                    <div class="mb-4">
                                        <label class="form-label text-uppercase fw-bold d-flex justify-content-between align-items-center">
                                            <span><i class="fa-solid fa-globe me-1 text-success"></i> Link Publikasi (Media Online / Website / Sosmed)</span>
                                            <small class="text-muted fw-normal">Bisa lebih dari 1 link</small>
                                        </label>

                                        @php
                                            $existingLinks = old('link_publikasi', $publikasi->link_publikasi_array ?? []);
                                            if (empty($existingLinks)) {
                                                $existingLinks = [''];
                                            }
                                        @endphp

                                        <div id="linkPublikasiContainer">
                                            @foreach($existingLinks as $index => $link)
                                                <div class="input-group mb-2 link-publikasi-row">
                                                    <span class="input-group-text bg-white text-success">
                                                        <i class="fa-solid fa-link"></i>
                                                    </span>
                                                    <input type="url" name="link_publikasi[]" class="form-control input-link-pub"
                                                        value="{{ $link }}" placeholder="https://... (link berita website atau media sosial)">
                                                    @if(!empty($link) && filter_var($link, FILTER_VALIDATE_URL))
                                                        <a href="{{ $link }}" target="_blank" class="btn btn-outline-success d-inline-flex align-items-center" title="Buka Link">
                                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-danger btn-remove-link d-inline-flex align-items-center px-3"
                                                        title="Hapus Link" {{ count($existingLinks) <= 1 ? 'style=display:none;' : '' }}>
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-success mt-1 d-inline-flex align-items-center" id="btnAddLinkPublikasi">
                                            <i class="fa-solid fa-circle-plus me-1"></i> Tambah Link Publikasi
                                        </button>
                                    </div>
                                    @if (Auth::user()->is_admin)
                                    <!-- Validasi Pelaporan (Super Admin) -->
                                    <div class="card border border-light shadow-sm mb-4 bg-gray-50">
                                        <div class="card-header bg-gray-100 py-2">
                                            <h6 class="mb-0 text-gray-800 fw-bold">
                                                <i class="fa-solid fa-clipboard-check me-2 text-success"></i> Validasi Pelaporan (Super Admin)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5 mb-3">
                                                    <label for="status_validasi" class="form-label fw-bold">Status Validasi</label>
                                                    <select name="status_validasi" id="status_validasi" class="form-select">
                                                        <option value="disetujui" {{ old('status_validasi', $publikasi->status_validasi) == 'disetujui' ? 'selected' : '' }}>
                                                            ✅ Disetujui / Valid
                                                        </option>
                                                        <option value="perlu_revisi" {{ old('status_validasi', $publikasi->status_validasi) == 'perlu_revisi' ? 'selected' : '' }}>
                                                            ⚠️ Perlu Revisi (Kembalikan ke PIC)
                                                        </option>
                                                        <option value="menunggu_validasi" {{ old('status_validasi', $publikasi->status_validasi) == 'menunggu_validasi' ? 'selected' : '' }}>
                                                            ⏳ Menunggu Validasi
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7 mb-3">
                                                    <label for="catatan_validasi" class="form-label fw-bold">Catatan / Alasan Validasi</label>
                                                    <textarea name="catatan_validasi" id="catatan_validasi" class="form-control" rows="2" placeholder="Tuliskan catatan perbaikan jika perlu revisi...">{{ old('catatan_validasi', $publikasi->catatan_validasi) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="alert alert-info py-2 px-3 mb-4 d-flex align-items-center" role="alert">
                                        <i class="fa-solid fa-circle-info me-2 text-primary"></i>
                                        <small class="mb-0">
                                            Setelah Anda menekan tombol <strong>SUBMIT / UPDATE LAPORAN</strong>, status laporan ini otomatis menjadi <strong>Menunggu Validasi</strong> untuk diverifikasi oleh Super Admin.
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                    </path>
                                </svg>
                                UPDATE
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
            // Inisialisasi TinyMCE untuk Laporan Kegiatan
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

            // Pastikan TinyMCE disave ke textarea sebelum form disubmit
            const form = document.querySelector('form[action*="publikasi"]');
            if (form) {
                form.addEventListener('submit', function() {
                    if (window.tinymce) {
                        tinymce.triggerSave();
                    }
                });
            }

            // Fitur Tambah & Hapus Link Publikasi Dinamis
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
                        <input type="url" name="link_publikasi[]" class="form-control input-link-pub"
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
