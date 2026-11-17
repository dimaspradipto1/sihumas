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
                            <!-- Daftar Nomor WA Admin (Multi & Multi-Active) -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-uppercase fw-semibold mb-0">Daftar Nomor WhatsApp Admin</label>
                                    <button type="button" class="btn btn-xs btn-outline-success d-inline-flex align-items-center" id="btnAddAdminWa">
                                        <i class="fa-solid fa-plus me-1"></i> Tambah Nomor WA Admin
                                    </button>
                                </div>

                                @php
                                    $adminWaRows = $settings['admin_whatsapp_list'] ?? [];
                                    if (old('admin_wa_nomor')) {
                                        $adminWaRows = [];
                                        $oldNames = old('admin_wa_nama', []);
                                        $oldActives = old('admin_wa_active', []);
                                        foreach (old('admin_wa_nomor') as $idx => $no) {
                                            $adminWaRows[] = [
                                                'nama' => $oldNames[$idx] ?? '',
                                                'no_wa' => $no,
                                                'is_active' => !empty($oldActives[$idx]) && (string) $oldActives[$idx] !== '0',
                                            ];
                                        }
                                    }
                                @endphp

                                <div id="adminWaContainer" class="d-flex flex-column gap-2">
                                    @foreach ($adminWaRows as $idx => $row)
                                        <div class="border rounded p-2 bg-light admin-wa-row">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-4">
                                                    <input type="text" name="admin_wa_nama[]" class="form-control form-control-sm"
                                                        value="{{ $row['nama'] ?? '' }}" placeholder="Label (mis: Admin Humas 1)">
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                                        <input type="text" name="admin_wa_nomor[]" class="form-control"
                                                            value="{{ $row['no_wa'] ?? '' }}" placeholder="628123456789" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-center justify-content-between gap-2">
                                                    <div class="form-check form-switch mb-0">
                                                        <input type="hidden" name="admin_wa_active[{{ $idx }}]" value="0">
                                                        <input class="form-check-input admin-wa-active-checkbox" type="checkbox"
                                                            name="admin_wa_active[{{ $idx }}]" value="1"
                                                            id="adminWaActive{{ $idx }}" {{ !empty($row['is_active']) ? 'checked' : '' }}>
                                                        <label class="form-check-label small fw-semibold" for="adminWaActive{{ $idx }}">Aktif</label>
                                                    </div>
                                                    <button type="button" class="btn btn-xs btn-outline-danger btn-remove-admin-wa" title="Hapus nomor">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <small class="form-text text-muted mt-1 d-block">
                                    Format nomor menggunakan kode negara (contoh: <strong>628123456789</strong>). Anda dapat mengaktifkan lebih dari 1 nomor WA Admin; seluruh nomor yang berstatus <strong>Aktif</strong> akan tampil saat pengguna mengajukan kegiatan.
                                </small>
                                @error('admin_wa_nomor')
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('adminWaContainer');
        const btnAdd = document.getElementById('btnAddAdminWa');

        function reindexAdminWaRows() {
            const rows = container.querySelectorAll('.admin-wa-row');
            rows.forEach((row, index) => {
                const hidden = row.querySelector('input[type="hidden"]');
                const checkbox = row.querySelector('.admin-wa-active-checkbox');
                const label = row.querySelector('.form-check-label');
                const id = `adminWaActive${index}`;
                if (hidden) hidden.name = `admin_wa_active[${index}]`;
                if (checkbox) {
                    checkbox.name = `admin_wa_active[${index}]`;
                    checkbox.id = id;
                }
                if (label) label.setAttribute('for', id);
            });
        }

        if (btnAdd && container) {
            btnAdd.addEventListener('click', function () {
                const index = container.querySelectorAll('.admin-wa-row').length;
                const row = document.createElement('div');
                row.className = 'border rounded p-2 bg-light admin-wa-row';
                row.innerHTML = `
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="admin_wa_nama[]" class="form-control form-control-sm"
                                placeholder="Label (mis: Admin Humas ${index + 1})">
                        </div>
                        <div class="col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                <input type="text" name="admin_wa_nomor[]" class="form-control"
                                    placeholder="628123456789" required>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-between gap-2">
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="admin_wa_active[${index}]" value="0">
                                <input class="form-check-input admin-wa-active-checkbox" type="checkbox"
                                    name="admin_wa_active[${index}]" value="1"
                                    id="adminWaActive${index}" checked>
                                <label class="form-check-label small fw-semibold" for="adminWaActive${index}">Aktif</label>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-admin-wa" title="Hapus nomor">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                reindexAdminWaRows();
            });

            container.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.btn-remove-admin-wa');
                if (!removeBtn) return;
                const rows = container.querySelectorAll('.admin-wa-row');
                if (rows.length <= 1) {
                    const inputNo = rows[0].querySelector('input[name="admin_wa_nomor[]"]');
                    if (inputNo) inputNo.value = '';
                    return;
                }
                removeBtn.closest('.admin-wa-row').remove();
                reindexAdminWaRows();
            });
        }
    });
</script>
@endpush
