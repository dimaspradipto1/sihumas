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
                <li class="breadcrumb-item active" aria-current="page">Pengaturan WA</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Pengaturan Notifikasi WhatsApp</h1>
                <p class="text-muted">Konfigurasi nomor WhatsApp Admin dan template pesan untuk pengajuan kegiatan.</p>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="row mt-3">
                <!-- Kolom Utama -->
                <div class="col-12 col-xl-8 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header border-bottom d-flex align-items-center">
                            <i class="fa-solid fa-gears text-primary me-2 fs-5"></i>
                            <h2 class="fs-5 fw-bold mb-0 text-uppercase">Konfigurasi WhatsApp</h2>
                        </div>
                        <div class="card-body">
                            <!-- Nomor WA Admin -->
                            <div class="mb-4">
                                <label for="admin_whatsapp" class="form-label text-uppercase fw-semibold">Nomor WhatsApp Admin</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-brands fa-whatsapp text-success fs-5"></i></span>
                                    <input type="text" name="admin_whatsapp" id="admin_whatsapp" 
                                        value="{{ old('admin_whatsapp', $settings['admin_whatsapp']) }}" 
                                        class="form-control @error('admin_whatsapp') is-invalid @enderror" 
                                        placeholder="Contoh: 628123456789 (Gunakan kode negara, tanpa tanda + atau 0)" required>
                                </div>
                                <small class="form-text text-muted mt-1 d-block">
                                    Format nomor harus menggunakan kode negara di depannya. Contoh: <strong>628123456789</strong> untuk nomor Indonesia (+62).
                                </small>
                                @error('admin_whatsapp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Template Pesan -->
                            <div class="mb-4">
                                <label for="wa_message_template" class="form-label text-uppercase fw-semibold">Template Pesan WhatsApp</label>
                                <textarea name="wa_message_template" id="wa_message_template" rows="8" 
                                    class="form-control @error('wa_message_template') is-invalid @enderror" 
                                    placeholder="Masukkan template pesan notifikasi..." required>{{ old('wa_message_template', $settings['wa_message_template']) }}</textarea>
                                @error('wa_message_template')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi Placeholder -->
                            <div class="alert alert-info border-0 shadow-sm" role="alert">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <i class="fa-solid fa-circle-info fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading fw-bold mb-1">Panduan Penggunaan Placeholder:</h6>
                                        <p class="mb-2 text-sm">Anda dapat menggunakan kata kunci berikut di dalam template pesan Anda. Sistem akan menggantinya secara otomatis dengan data kegiatan:</p>
                                        <ul class="mb-0 text-sm list-unstyled">
                                            <li><code>{nama_kegiatan}</code> : Nama kegiatan yang diajukan</li>
                                            <li><code>{tgl_awal}</code> : Tanggal mulai kegiatan</li>
                                            <li><code>{tgl_selesai}</code> : Tanggal selesai kegiatan</li>
                                            <li><code>{tempat_kegiatan}</code> : Lokasi pelaksanaan kegiatan</li>
                                            <li><code>{nama_user}</code> : Nama pengguna yang mengajukan kegiatan</li>
                                            <li><code>{no_wa_user}</code> : Nomor WhatsApp pengguna yang mengajukan</li>
                                            <li><code>{link_sistem}</code> : Link akses sistem SIHUMAS (https://sihumas.uisbatam.com/)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Samping (Tombol Simpan) -->
                <div class="col-12 col-xl-4">
                    <div class="card border-0 shadow">
                        <div class="card-body d-grid">
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center py-2 text-uppercase fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Pengaturan
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-link text-gray-700 text-sm mt-2 text-center text-uppercase">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
