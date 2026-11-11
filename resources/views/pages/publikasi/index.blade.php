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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">Pelaporan Kegiatan</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">Pelaporan Kegiatan</h1>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-striped table-bordered w-100', 'id' => 'publikasi-table']) !!}
        </div>
    </div>

    @if (Auth::user()->is_admin)
    <!-- Modal Quick Validasi Pelaporan Kegiatan (Super Admin) -->
    <div class="modal fade" id="modalValidasiPublikasi" tabindex="-1" aria-labelledby="modalValidasiPublikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="formValidasiPublikasi" action="" method="POST">
                    @csrf
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold text-gray-800" id="modalValidasiPublikasiLabel">
                            <i class="fa-solid fa-clipboard-check text-success me-2"></i> Validasi Pelaporan Kegiatan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3 p-3 bg-gray-50 border rounded">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">Nama Kegiatan</small>
                            <span id="modalKegiatanNama" class="fw-bold text-gray-900 fs-6">-</span>
                        </div>

                        <div class="mb-3">
                            <label for="modalStatusValidasi" class="form-label fw-bold text-gray-800">Status Validasi <span class="text-danger">*</span></label>
                            <select name="status_validasi" id="modalStatusValidasi" class="form-select" required>
                                <option value="disetujui">✅ Disetujui / Valid</option>
                                <option value="perlu_revisi">⚠️ Perlu Revisi (Kembalikan ke PIC)</option>
                                <option value="menunggu_validasi">⏳ Menunggu Validasi</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalCatatanValidasi" class="form-label fw-bold text-gray-800">Catatan Validasi / Instruksi Revisi</label>
                            <textarea name="catatan_validasi" id="modalCatatanValidasi" rows="3" class="form-control"
                                placeholder="Tuliskan catatan untuk PIC Humas jika ada revisi atau catatan apresiasi..."></textarea>
                            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Catatan ini akan terbaca langsung oleh PIC Humas terkait.</small>
                        </div>

                        <div class="text-end">
                            <a href="#" id="modalLinkDetail" target="_blank" class="small text-primary text-decoration-underline">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Rincian Lengkap Laporan
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Validasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    @if (Auth::user()->is_admin)
    <script>
        $(document).ready(function() {
            // Delegated click event agar tombol berfungsi di semua halaman pagination DataTables
            $(document).on('click', '.btn-modal-validasi', function(e) {
                e.preventDefault();
                const btn = $(this);
                const url = btn.attr('data-url');
                const kegiatan = btn.attr('data-kegiatan');
                const status = btn.attr('data-status');
                const catatan = btn.attr('data-catatan');
                const showUrl = btn.attr('data-show');

                $('#formValidasiPublikasi').attr('action', url);
                $('#modalKegiatanNama').text(kegiatan);
                $('#modalStatusValidasi').val(status || 'disetujui');
                $('#modalCatatanValidasi').val(catatan || '');
                $('#modalLinkDetail').attr('href', showUrl);

                const modalElem = document.getElementById('modalValidasiPublikasi');
                if (modalElem) {
                    const modal = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
                    modal.show();
                }
            });
        });
    </script>
    @endif
@endpush
