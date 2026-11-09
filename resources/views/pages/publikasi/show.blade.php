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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">detail pelaporan kegiatan
                </li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">detail pelaporan kegiatan</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">

                        <div class="card-body px-3 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7"
                                                style="width: 5%;">Tahun Akademik</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1"
                                                style="width: 1%;">:</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize"
                                                style="width: 95%;">{{ $publikasi->tahunAkademik?->tahun_akademik ?? '-' }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">nama
                                                kegiatan</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td
                                                class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize">
                                                {{ $publikasi->pengajuan?->nama_kegiatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">
                                                tanggal kegiatan</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">
                                                {{ $publikasi->pengajuan?->tgl_awal ? \Carbon\Carbon::parse($publikasi->pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">
                                                tanggal selesai</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">
                                                {{ $publikasi->pengajuan?->tgl_selesai ? \Carbon\Carbon::parse($publikasi->pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize">
                                                link laporan</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td class="text-lowercase text-dark text-xxs font-weight-bolder opacity-7">
                                                @if ($publikasi->upload_laporan)
                                                    <a href="{{ $publikasi->upload_laporan }}" target="_blank"
                                                        class="text-success px-3 me-1 text-capitalize"><i
                                                            class="fa-solid fa-eye"></i> Lihat Laporan</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize">
                                                link dokumentasi</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td class="text-lowercase text-dark text-xxs font-weight-bolder opacity-7">
                                                @if ($publikasi->link_dokumentasi)
                                                    <a href="{{ $publikasi->link_dokumentasi }}" target="_blank"
                                                        class="text-success px-3 me-1 text-capitalize"><i
                                                            class="fa-solid fa-eye"></i> Lihat Dokumentasi</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 text-capitalize">
                                                link publikasi</td>
                                            <td class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 px-1">
                                                :</td>
                                            <td class="text-lowercase text-dark text-xxs font-weight-bolder opacity-7">
                                                @if ($publikasi->link_publikasi)
                                                    <a href="{{ $publikasi->link_publikasi }}" target="_blank"
                                                        class="text-success px-3 me-1 text-capitalize"><i
                                                            class="fa-solid fa-eye"></i> Lihat Publikasi</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ route('publikasi.index') }}" class="btn btn-sm btn-danger px-2 py-2">BACK <i
                                class="fa-solid fa-right-from-bracket"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
