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
                if (!picList || picList.length === 0) return;

                // Langsung kirim / buka WhatsApp ke masing-masing PIC
                picList.forEach((pic, index) => {
                    setTimeout(() => {
                        window.open(pic.url, '_blank');
                    }, index * 400);
                });

                let htmlContent = '<p class="text-muted mb-3">WhatsApp penugasan sedang/telah dibuka ke masing-masing PIC. Klik tombol di bawah jika ingin membuka kembali WhatsApp petugas:</p>';
                htmlContent += '<div class="d-grid gap-2 mb-2">';
                picList.forEach((pic) => {
                    htmlContent += `
                        <a href="${pic.url}" target="_blank" class="btn btn-success text-white py-2 fw-semibold text-start px-3">
                            <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Kirim ke <b>${pic.nama}</b> (${pic.no_wa})
                        </a>
                    `;
                });
                if (picList.length > 1) {
                    htmlContent += `
                        <button type="button" class="btn btn-outline-primary py-2 mt-1" id="btnBukaSemuaWa">
                            <i class="fa-solid fa-up-right-from-square me-2"></i> Buka Semua WhatsApp Sekaligus
                        </button>
                    `;
                }
                htmlContent += '</div>';

                Swal.fire({
                    title: 'Verifikasi & Penugasan Berhasil!',
                    html: htmlContent,
                    icon: 'success',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: '<i class="fa-solid fa-forward me-1"></i> Lewati',
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: false,
                    focusCancel: false,
                    didOpen: () => {
                        const btnAll = document.getElementById('btnBukaSemuaWa');
                        if (btnAll) {
                            btnAll.addEventListener('click', (e) => {
                                e.preventDefault();
                                picList.forEach((pic, i) => {
                                    setTimeout(() => {
                                        window.open(pic.url, '_blank');
                                    }, i * 300);
                                });
                            });
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
