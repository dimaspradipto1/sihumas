@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <!-- Breadcrumb & Header -->
        <div class="d-block mb-4 mb-md-0">
            <h1 class="h3 fw-bold mb-1" style="color: #1a2e51;">Backup Database &amp; Storage</h1>
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ol class="breadcrumb breadcrumb-transparent p-0 mb-0" style="font-size: 13px;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-secondary">Pengaturan Sistem</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Backup Database &amp; Storage</li>
                </ol>
            </nav>
        </div>

        <!-- 2 Kolom Card -->
        <div class="row mt-4">
            <!-- Card 1: Ekspor & Impor Database -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="background: #ffffff;">
                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <div>
                            <!-- Header Card -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                    style="width: 52px; height: 52px; background: #236c3d; color: #ffffff;">
                                    <i class="fa-solid fa-database fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1f2d3d;">Ekspor &amp; Impor Database</h5>
                                    <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.4;">
                                        Unduh atau pulihkan struktur tabel dan data database aplikasi sebagai berkas SQL.
                                    </p>
                                </div>
                            </div>

                            <!-- Detail Spesifikasi Database -->
                            <div class="my-4" style="font-size: 13.5px;">
                                <div class="row py-2 border-bottom border-light align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Koneksi</div>
                                    <div class="col-8">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px;">
                                            {{ strtoupper($dbConnection) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Database</div>
                                    <div class="col-8">
                                        <code class="fw-bold" style="color: #d63384; font-size: 13.5px;">{{ $dbName }}</code>
                                    </div>
                                </div>
                                <div class="row py-2 align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Isi backup</div>
                                    <div class="col-8 text-dark">
                                        Struktur dan data semua tabel MySQL
                                    </div>
                                </div>
                            </div>

                            <!-- Alert Box Info -->
                            <div class="p-3 mb-4 rounded-3 d-flex align-items-start" style="background: #ebf7fd; border: 1px solid #d4effc; color: #155776; font-size: 12.5px; line-height: 1.5;">
                                <i class="fa-solid fa-shield-halved mt-1 me-2 fs-6 flex-shrink-0" style="color: #1976d2;"></i>
                                <div>
                                    Hanya administrator yang dapat mengunduh dan memulihkan database. Simpan berkas SQL di lokasi aman karena berisi seluruh data aplikasi.
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light">
                            <a href="{{ route('backup.database.download') }}" class="btn text-white fw-semibold px-3 py-2 d-inline-flex align-items-center" style="background: #256b3e; border-color: #256b3e; font-size: 13px;">
                                <i class="fa-solid fa-download me-2"></i> Unduh Backup SQL
                            </a>
                            <button type="button" class="btn btn-outline-primary fw-semibold px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalRestoreDb" style="border-color: #8da4f7; color: #4361ee; font-size: 13px;">
                                <i class="fa-solid fa-arrow-up-from-bracket me-2"></i> Import / Pulihkan Database
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Backup & Impor Storage Public -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="background: #ffffff;">
                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <div>
                            <!-- Header Card -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                    style="width: 52px; height: 52px; background: #eafaf1; color: #27ae60; border: 1px solid #d4efdf;">
                                    <i class="fa-solid fa-folder-open fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1f2d3d;">Backup &amp; Impor Storage Public</h5>
                                    <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.4;">
                                        Arsipkan atau pulihkan semua file pada folder <code style="color: #d63384;">storage/app/public</code> ke dalam berkas ZIP.
                                    </p>
                                </div>
                            </div>

                            <!-- Detail Spesifikasi Storage -->
                            <div class="my-4" style="font-size: 13.5px;">
                                <div class="row py-2 border-bottom border-light align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Direktori</div>
                                    <div class="col-8">
                                        <code class="fw-bold" style="color: #d63384; font-size: 13px;">storage/app/public</code>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Total Berkas</div>
                                    <div class="col-8 text-dark fw-semibold">
                                        {{ $storageFileCount }} berkas ({{ $formattedStorageSize }})
                                    </div>
                                </div>
                                <div class="row py-2 align-items-center">
                                    <div class="col-4 fw-semibold text-secondary">Format Arsip</div>
                                    <div class="col-8">
                                        <span class="badge px-2 py-1 text-uppercase fw-bold" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px;">
                                            ZIP ARCHIVE
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Alert Box Warning -->
                            <div class="p-3 mb-4 rounded-3 d-flex align-items-start" style="background: #fef9e7; border: 1px solid #fcf3cf; color: #8a6d1b; font-size: 12.5px; line-height: 1.5;">
                                <i class="fa-solid fa-triangle-exclamation mt-1 me-2 fs-6 flex-shrink-0" style="color: #d4ac0d;"></i>
                                <div>
                                    Backup storage memuat seluruh berkas lampiran, SK, foto kegiatan, dan dokumen publik aplikasi.
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light">
                            <a href="{{ route('backup.storage.download') }}" class="btn text-white fw-semibold px-3 py-2 d-inline-flex align-items-center" style="background: #256b3e; border-color: #256b3e; font-size: 13px;">
                                <i class="fa-solid fa-download me-2"></i> Unduh Backup Storage ZIP
                            </a>
                            <button type="button" class="btn btn-outline-success fw-semibold px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalRestoreStorage" style="border-color: #52be80; color: #1e8449; font-size: 13px;">
                                <i class="fa-solid fa-arrow-up-from-bracket me-2"></i> Import / Pulihkan Storage
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="text-center mt-5 mb-3" style="font-size: 12.5px; color: #64748b;">
            &copy; {{ date('Y') }} <strong>SIHUMAS</strong> Universitas Ibnu Sina. All Rights Reserved.
        </div>
    </div>

    <!-- Modal 1: Restore Database -->
    <div class="modal fade" id="modalRestoreDb" tabindex="-1" aria-labelledby="modalRestoreDbLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('backup.database.restore') }}" method="POST" enctype="multipart/form-data" id="formRestoreDb">
                    @csrf
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold" id="modalRestoreDbLabel" style="font-size: 16px;">
                            <i class="fa-solid fa-database text-primary me-2"></i> Pulihkan / Import Database SQL
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning d-flex align-items-start" style="font-size: 12.5px;">
                            <i class="fa-solid fa-triangle-exclamation me-2 mt-1 fs-6"></i>
                            <div>
                                <strong>Peringatan Penting:</strong> Proses pemulihan ini akan menimpa seluruh tabel dan data yang saat ini ada di database <code>{{ $dbName }}</code>. Disarankan untuk mengunduh backup SQL terlebih dahulu sebelum melanjutkan.
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="sql_file" class="form-label fw-bold text-dark" style="font-size: 13px;">Pilih Berkas Backup SQL (.sql)</label>
                            <input class="form-control" type="file" id="sql_file" name="sql_file" accept=".sql,.txt" required>
                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Maksimal ukuran berkas 50 MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                        <button type="submit" class="btn btn-danger px-3 py-2 fw-semibold" id="btnSubmitRestoreDb" style="font-size: 13px;" onclick="return confirm('Apakah Anda yakin ingin memulihkan database? Seluruh data yang ada saat ini akan digantikan oleh isi berkas backup.');">
                            <i class="fa-solid fa-rotate-left me-1"></i> Mulai Pulihkan Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 2: Restore Storage -->
    <div class="modal fade" id="modalRestoreStorage" tabindex="-1" aria-labelledby="modalRestoreStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('backup.storage.restore') }}" method="POST" enctype="multipart/form-data" id="formRestoreStorage">
                    @csrf
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold" id="modalRestoreStorageLabel" style="font-size: 16px;">
                            <i class="fa-solid fa-folder-open text-success me-2"></i> Pulihkan / Import Storage Public
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info d-flex align-items-start" style="font-size: 12.5px;">
                            <i class="fa-solid fa-circle-info me-2 mt-1 fs-6"></i>
                            <div>
                                Berkas ZIP akan diekstrak langsung ke dalam direktori <code>storage/app/public</code>. Berkas dengan nama yang sama akan diperbarui dengan berkas dari arsip ZIP.
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="zip_file" class="form-label fw-bold text-dark" style="font-size: 13px;">Pilih Berkas Backup ZIP (.zip)</label>
                            <input class="form-control" type="file" id="zip_file" name="zip_file" accept=".zip" required>
                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Maksimal ukuran berkas ZIP 200 MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                        <button type="submit" class="btn btn-success text-white px-3 py-2 fw-semibold" id="btnSubmitRestoreStorage" style="font-size: 13px;" onclick="return confirm('Apakah Anda yakin ingin mengekstrak berkas ZIP ini ke storage publik?');">
                            <i class="fa-solid fa-upload me-1"></i> Mulai Pulihkan Storage
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
