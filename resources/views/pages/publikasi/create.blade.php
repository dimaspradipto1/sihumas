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
                    <form action="{{ route('publikasi.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    <div class="mb-4">
                                        <label for="tahun_akademik" class="text-capitalize">Tahun Akademik</label>
                                         {{-- <input type="text" name="tahun_akademik_id" value="{{ old('tahun_akademik') }}"
                                            class="form-control" id="tahun_akademik"> --}}
                                        <select name="tahun_akademik_id" id="tahun_akademik" class="form-control">
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademik as $tahun)
                                                <option value="{{ $tahun->id }}" {{ old('tahun_akademik_id') == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->tahun_akademik }}
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

                                    <div class="mb-4">
                                        <label for="laporan" class="text-capitalize">link laporan</label>
                                        <textarea class="form-control" name="upload_laporan" id="" rows="3">{{ old('upload_laporan') }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="laporan" class="text-capitalize">link dokumentasi</label>
                                        <textarea class="form-control" name="link_dokumentasi" id="" rows="3">{{ old('link_dokumentasi') }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="laporan" class="text-capitalize">link publikasi</label>
                                        <textarea class="form-control" name="link_publikasi" id="" rows="3">{{ old('link_publikasi') }}</textarea>
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
