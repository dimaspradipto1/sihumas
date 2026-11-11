@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <div class="card-body">
            <div class="card-block table-border-style">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle shadow-sm px-3 py-2" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-print me-2 text-warning"></i>
                        <span class="fw-bold">Cetak Rekap Laporan</span>
                        <i class="fa-solid fa-chevron-down ms-2 fs-xs"></i>
                    </button>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 p-3 shadow-lg border"
                        style="min-width: 480px; max-width: 620px; width: 100%; z-index: 1050; border-radius: 0.5rem;">
                        
                        <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom">
                            <h6 class="mb-0 fw-bold text-gray-800">
                                <i class="fa-solid fa-sliders text-primary me-2"></i> Opsi Cetak Laporan
                            </h6>
                            <span class="badge bg-light text-muted border">Rekap Kegiatan</span>
                        </div>

                        <form action="{{ route('laporan-publikasi.show') }}" method="GET" target="_blank" id="formCetakLaporan">
                            <!-- Tombol Pilihan Metode Filter -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase text-gray-600 mb-1">Pilih Filter Cetak:</label>
                                <div class="btn-group w-100 p-1 bg-light border rounded" role="group">
                                    <input type="radio" class="btn-check" name="filter_type" id="filter_all" value="all" checked autocomplete="off">
                                    <label class="btn btn-sm btn-outline-secondary flex-fill mb-0" for="filter_all">
                                        <i class="fa-solid fa-layer-group me-1"></i> Semua
                                    </label>

                                    <input type="radio" class="btn-check" name="filter_type" id="filter_ta" value="tahun_akademik" autocomplete="off">
                                    <label class="btn btn-sm btn-outline-primary flex-fill mb-0" for="filter_ta">
                                        <i class="fa-solid fa-graduation-cap me-1"></i> Thn Akademik
                                    </label>

                                    <input type="radio" class="btn-check" name="filter_type" id="filter_bulan" value="bulan" autocomplete="off">
                                    <label class="btn btn-sm btn-outline-info flex-fill mb-0" for="filter_bulan">
                                        <i class="fa-solid fa-calendar-days me-1"></i> Bulan
                                    </label>

                                    <input type="radio" class="btn-check" name="filter_type" id="filter_tahun" value="tahun" autocomplete="off">
                                    <label class="btn btn-sm btn-outline-warning flex-fill mb-0" for="filter_tahun">
                                        <i class="fa-solid fa-calendar me-1"></i> Tahun
                                    </label>

                                    <input type="radio" class="btn-check" name="filter_type" id="filter_tanggal" value="tanggal" autocomplete="off">
                                    <label class="btn btn-sm btn-outline-success flex-fill mb-0" for="filter_tanggal">
                                        <i class="fa-solid fa-calendar-week me-1"></i> Tanggal
                                    </label>
                                </div>
                            </div>

                            <!-- 1. Panel Semua -->
                            <div class="filter-panel mb-3" id="panel-all">
                                <div class="alert alert-info py-2 px-3 mb-0 small text-gray-700">
                                    <i class="fa-solid fa-circle-info me-1 text-info"></i> Mencetak seluruh data rekap laporan publikasi tanpa batasan periode.
                                </div>
                            </div>

                            <!-- 2. Panel Tahun Akademik -->
                            <div class="filter-panel mb-3 d-none" id="panel-ta">
                                <label for="filter_tahun_akademik" class="form-label small fw-bold">Pilih Tahun Akademik</label>
                                <select name="tahun_akademik" id="filter_tahun_akademik" class="form-select form-select-sm" disabled>
                                    <option value="">-- Pilih Tahun Akademik --</option>
                                    @foreach ($tahunAkademik as $ta)
                                        <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                            {{ $ta->tahun_akademik }} {{ $ta->is_active ? '✅ (Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 3. Panel Bulan & Tahun -->
                            <div class="filter-panel mb-3 d-none" id="panel-bulan">
                                <div class="row g-2">
                                    <div class="col-7">
                                        <label for="filter_select_bulan" class="form-label small fw-bold">Bulan</label>
                                        <select name="bulan" id="filter_select_bulan" class="form-select form-select-sm" disabled>
                                            <option value="">-- Pilih Bulan --</option>
                                            @php
                                                $namaBulanList = [
                                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                ];
                                                $currBulan = date('n');
                                            @endphp
                                            @foreach ($namaBulanList as $num => $nama)
                                                <option value="{{ $num }}" {{ $num == $currBulan ? 'selected' : '' }}>
                                                    {{ $nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="filter_tahun_for_bulan" class="form-label small fw-bold">Tahun</label>
                                        <select name="tahun" id="filter_tahun_for_bulan" class="form-select form-select-sm" disabled>
                                            @php $currY = date('Y'); @endphp
                                            @for ($y = $currY + 1; $y >= $currY - 5; $y--)
                                                <option value="{{ $y }}" {{ $y == $currY ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Panel Tahun -->
                            <div class="filter-panel mb-3 d-none" id="panel-tahun">
                                <label for="filter_tahun_only" class="form-label small fw-bold">Pilih Tahun Kegiatan</label>
                                <select name="tahun" id="filter_tahun_only" class="form-select form-select-sm" disabled>
                                    @php $currY = date('Y'); @endphp
                                    @for ($y = $currY + 1; $y >= $currY - 5; $y--)
                                        <option value="{{ $y }}" {{ $y == $currY ? 'selected' : '' }}>Tahun {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- 5. Panel Rentang Tanggal -->
                            <div class="filter-panel mb-3 d-none" id="panel-tanggal">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="tgl_awal" class="form-label small fw-bold">Tanggal Awal</label>
                                        <input type="date" class="form-control form-control-sm" name="tgl_awal" id="tgl_awal" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label for="tgl_selesai" class="form-label small fw-bold">Tanggal Selesai</label>
                                        <input type="date" class="form-control form-control-sm" name="tgl_selesai" id="tgl_selesai" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="pt-3 border-top d-flex gap-2 justify-content-end">
                                <a href="{{ route('laporan-publikasi.show') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
                                    <i class="fa-solid fa-clipboard-list me-1"></i> Cetak Semua
                                </a>
                                <button type="submit" class="btn btn-sm btn-success text-white px-3 d-inline-flex align-items-center shadow-sm">
                                    <i class="fa-solid fa-print me-1"></i> CETAK LAPORAN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            {!! $dataTable->table(
                ['class' => 'table table-striped table-bordered', 'id' => 'laporanpublikasi-table'],
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panels = {
                'all': document.getElementById('panel-all'),
                'tahun_akademik': document.getElementById('panel-ta'),
                'bulan': document.getElementById('panel-bulan'),
                'tahun': document.getElementById('panel-tahun'),
                'tanggal': document.getElementById('panel-tanggal'),
            };

            const radios = document.querySelectorAll('input[name="filter_type"]');

            function updatePanels(selectedType) {
                Object.keys(panels).forEach(function (key) {
                    const panel = panels[key];
                    if (!panel) return;
                    const isSelected = (key === selectedType);

                    if (isSelected) {
                        panel.classList.remove('d-none');
                    } else {
                        panel.classList.add('d-none');
                    }

                    // Enable inputs in active panel, disable in others
                    const inputs = panel.querySelectorAll('input, select');
                    inputs.forEach(function (input) {
                        input.disabled = !isSelected;
                    });
                });
            }

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (this.checked) {
                        updatePanels(this.value);
                    }
                });
            });

            // Initialize state
            const checkedRadio = document.querySelector('input[name="filter_type"]:checked');
            if (checkedRadio) {
                updatePanels(checkedRadio.value);
            }

            // Prevent dropdown from closing prematurely when clicking inside form
            const dropdownMenu = document.getElementById('printFilterDropdown');
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
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
