@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                <li class="breadcrumb-item active text-capitalize" aria-current="page">Triwulan (TW)</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
            <div class="mb-2 mb-lg-0">
                <h1 class="h4 text-capitalize mb-1">Triwulan (TW)</h1>
                <p class="mb-0 text-muted small">
                    Acuan periode triwulan aktif otomatis mengikuti bulan berjalan
                    <span id="activeTwSubtitle">
                        @if ($activeTw)
                            (Saat ini: <strong>{{ $activeTw->nama }}</strong>).
                        @endif
                    </span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('triwulan.sync-auto') }}" method="POST" class="d-inline form-sync-auto">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary d-inline-flex align-items-center">
                        <i class="fa-solid fa-rotate me-1"></i> Sinkron Bulan Berjalan
                    </button>
                </form>
                <a href="{{ route('triwulan.create') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                    + Tambah
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-bordered align-middle w-100', 'id' => 'triwulan-table']) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    <script>
        $(document).on('submit', '.form-toggle-active, .form-sync-auto', function (e) {
            e.preventDefault();
            const form = this;
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    if (window.LaravelDataTables && window.LaravelDataTables['triwulan-table']) {
                        window.LaravelDataTables['triwulan-table'].ajax.reload(null, false);
                    }
                    const subtitle = document.getElementById('activeTwSubtitle');
                    if (subtitle) {
                        subtitle.innerHTML = data.active_name
                            ? `(Saat ini: <strong>${data.active_name}</strong>).`
                            : '(Saat ini: <em>Tidak ada yang aktif</em>).';
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                        });
                    }
                }
            })
            .catch(() => {
                form.submit();
            })
            .finally(() => {
                if (btn) btn.disabled = false;
            });
        });
    </script>
@endpush
