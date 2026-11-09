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
                <li class="breadcrumb-item"><a href="#">Tables</a></li>
                <li class="breadcrumb-item active text-capitalize" aria-current="page">Kotak Masuk Pengajuan Berita</li>
            </ol>
            
        </nav>
        
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">Kotak Masuk Pengajuan Berita</h1>
            </div>
            {{-- <div>
                <a href="{{ route('pengajuan.create') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                    + Tambah
                </a>
            </div> --}}
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-striped table-bordered w-100', 'id' => 'pengajuan-table']) !!}
        </div>
    </div>
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    @if(session('wa_pic_list') && count(session('wa_pic_list')) > 0)
        @php
            $waPics = session('wa_pic_list');
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const picList = @json($waPics);

                if (picList.length === 1) {
                    Swal.fire({
                        title: 'Verifikasi & Penugasan Berhasil!',
                        html: 'Pesan penugasan siap dikirim ke WhatsApp Petugas: <b>' + picList[0].nama + '</b> (' + picList[0].no_wa + ').',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#25D366',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-brands fa-whatsapp me-2"></i> Kirim ke ' + picList[0].nama,
                        cancelButtonText: 'Lewati',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(picList[0].url, '_blank');
                        }
                    });
                } else {
                    let htmlContent = '<p class="text-muted mb-3">Klik tombol di bawah untuk mengirim pesan penugasan WhatsApp ke masing-masing petugas:</p>';
                    htmlContent += '<div class="d-grid gap-2">';
                    picList.forEach((pic, i) => {
                        htmlContent += `<a href="${pic.url}" target="_blank" class="btn btn-success text-white py-2"><i class="fa-brands fa-whatsapp me-2"></i> Kirim ke ${pic.nama} (${pic.no_wa})</a>`;
                    });
                    htmlContent += '</div>';
                    htmlContent += `<div class="mt-3"><button type="button" class="btn btn-primary btn-sm w-100" id="btnBukaSemuaWa"><i class="fa-solid fa-up-right-from-square me-1"></i> Buka Semua WhatsApp Sekaligus</button></div>`;

                    Swal.fire({
                        title: 'Verifikasi & Penugasan Berhasil!',
                        html: htmlContent,
                        icon: 'success',
                        showConfirmButton: false,
                        showCloseButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            const btnAll = document.getElementById('btnBukaSemuaWa');
                            if (btnAll) {
                                btnAll.addEventListener('click', () => {
                                    picList.forEach(pic => window.open(pic.url, '_blank'));
                                });
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endpush
