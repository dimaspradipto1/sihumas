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
                                    

                                    <!-- Link Laporan -->
                                    <div class="mb-4">
                                        <label for="upload_laporan" class="text-uppercase">Link Laporan</label>
                                        <textarea name="upload_laporan" class="form-control" id="upload_laporan">{{ old('upload_laporan') ?? ($publikasi->upload_laporan ?? ($pengajuan->upload_laporan ?? '')) }}</textarea>
                                    </div>

                                    <!-- Link Dokumentasi -->
                                    <div class="mb-4">
                                        <label for="link_dokumentasi" class="text-uppercase">Link Dokumentasi</label>
                                        <textarea name="link_dokumentasi" class="form-control" id="link_dokumentasi">{{ old('link_dokumentasi') ?? ($publikasi->link_dokumentasi ?? ($pengajuan->link_dokumentasi ?? '')) }}</textarea>
                                    </div>

                                    <!-- Link Publikasi -->
                                    <div class="mb-4">
                                        <label for="link_publikasi" class="text-uppercase">Link Publikasi</label>
                                        <textarea name="link_publikasi" class="form-control" id="link_publikasi">{{ old('link_publikasi') ?? ($publikasi->link_publikasi ?? ($pengajuan->link_publikasi ?? '')) }}</textarea>
                                    </div>
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
