@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <div class="card-body">
            <div class="card-block table-border-style">
                <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Reports
                    <svg class="icon icon-xs ms-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-2"
                    style="max-width: 800px; width: 100%; padding: 10px;">
                    <form class="row g-3" action="{{ route('laporan-publikasi.show') }}" method="GET" target="_blank">
                        <div class="row my-2">
                            <!-- Tanggal Awal dan Tanggal Selesai -->
                            <div class="row my-2">
                                <!-- Tanggal Awal -->
                                <div class="col-md-6" style="padding: 5px;">
                                    <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                                    <input type="date" class="form-control" name="tgl_awal"
                                        value="{{ request('tgl_awal') }}" style="width: 100%; padding: 8px;">
                                </div>

                                <!-- Tanggal Selesai -->
                                <div class="col-md-6" style="padding: 5px;">
                                    <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="tgl_selesai"
                                        value="{{ request('tgl_selesai') }}" style="width: 100%; padding: 8px;">
                                </div>
                            </div>

                            <div role="separator" class="dropdown-divider my-1"></div>
                            <div class="row my-4">
                                <div class="col-md-6 col-xl-3">
                                    <button type="submit" class="btn btn-outline-success button-gap"
                                        style="padding: 8px 16px; width: 100%; text-align: center;">
                                        <i class="fa-solid fa-print"></i> CETAK
                                    </button>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <a href="{{ route('laporan-publikasi.show') }}" target="_blank" class="btn btn-outline-primary button-gap-all d-inline-flex align-items-center justify-content-center"
                                        style="padding: 8px 16px; width: 100%; text-align: center;">
                                        <i class="fa-solid fa-clipboard-list me-1"></i> All Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            {!! $dataTable->table(
                ['class' => 'table table-striped table-bordered w-100', 'id' => 'laporanpublikasi-table'],
                true,
            ) !!}
        </div>
    </div>
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush

@push('style')
    <style>
        @media (max-width: 767px) {

            .col-md-5,
            .col-md-6 {
                width: 100%;
                /* Agar elemen input dan tombol menyesuaikan lebar layar */
                margin-bottom: 10px;
            }

            .dropdown-menu {
                width: 100%;
            }

            /* Jarak antara tombol CETAK dan All Reports */
            .button-gap {
                margin-bottom: 5px;
            }

            .button-gap-all {
                margin-top: 5px;
            }

        }
    </style>
@endpush
