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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">data pengajuan berita
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">form pengajuan berita</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <form action="{{ route('pengajuan.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    <div class="mb-4">
                                       <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select single">
                                            @foreach ($tahunAkademik as $tahun)
                                                <option value="{{ $tahun->id }}"
                                                    {{ request('tahun_akademik_id') == $tahun->id ? 'selected' : '' }}>
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

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="date" class="text-capitalize">tanggal mulai kegiatan</label>
                                                <input type="date" name="tgl_awal" value="{{ old('tgl_awal') }}"
                                                    class="form-control" id="tgl_awal">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="jam_kegiatan" class="text-capitalize">jam kegiatan</label>
                                                <input type="time" name="jam_kegiatan" class="form-control"
                                                    value="{{ old('jam_kegiatan') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">

                                            <div class="mb-4 mb-md-0">
                                                <label for="date" class="text-capitalize">tanggal Selesai
                                                    kegiatan</label>
                                                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}"
                                                    class="form-control" id="tgl_selesai">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4 mb-md-0">
                                                <label for="waktu_selesai" class="text-capitalize">waktu selesai</label>
                                                <input type="time" name="waktu_selesai" class="form-control"
                                                    value="{{ old('unit_kegiatan') }}">
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $daftarTempat = [
                                            'Ruang Rapat Rektorat',
                                            'Ruang Rapat FEB',
                                            'Ruang Rapat FST',
                                            'Ruang Rapat FIKES',
                                            'Ruang Rapat Gedung B LT 4',
                                            'Ruang Rapat Gedung E LT 5',
                                        ];
                                        $valTempat = old('tempat_kegiatan', '');
                                        $isCustomTempat = !empty($valTempat) && !in_array($valTempat, $daftarTempat);
                                    @endphp

                                    <div class="mb-4">
                                        <label for="tempat_kegiatan_select" class="form-label text-capitalize fw-bold">Tempat Kegiatan</label>
                                        <select id="tempat_kegiatan_select" class="form-select select2-tempat" name="{{ $isCustomTempat ? '' : 'tempat_kegiatan' }}" {{ $isCustomTempat ? '' : 'required' }}>
                                            <option value="">-- Pilih Tempat Kegiatan atau Ketik Custom --</option>
                                            @foreach ($daftarTempat as $tempat)
                                                <option value="{{ $tempat }}" {{ $valTempat === $tempat ? 'selected' : '' }}>
                                                    {{ $tempat }}
                                                </option>
                                            @endforeach
                                            @if ($isCustomTempat)
                                                <option value="{{ $valTempat }}" selected>{{ $valTempat }}</option>
                                            @endif
                                            <option value="__custom__" {{ $isCustomTempat ? 'selected' : '' }}>➕ Lainnya (Ketik Manual)...</option>
                                        </select>

                                        <div id="wrapper_custom_tempat" class="mt-2 {{ $isCustomTempat ? '' : 'd-none' }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-location-dot"></i></span>
                                                <input type="text" id="input_custom_tempat" class="form-control"
                                                    name="{{ $isCustomTempat ? 'tempat_kegiatan' : '' }}"
                                                    value="{{ $isCustomTempat ? $valTempat : '' }}"
                                                    placeholder="Tuliskan nama tempat kegiatan custom / lainnya..."
                                                    {{ $isCustomTempat ? 'required' : 'disabled' }}>
                                            </div>
                                            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i>Pilih opsi di atas atau ketik langsung nama tempat baru.</small>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="unit_kegiatan" class="text-capitalize">unit kegiatan</label>
                                        <input type="text" name="unit_kegiatan" class="form-control"
                                            value="{{ old('unit_kegiatan') }}">
                                    </div>


                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="deskripsi_kegiatan" class="text-capitalize">deskripsi
                                                kegiatan</label>
                                            <textarea class="form-control" name="deskripsi_kegiatan" value="{{ old('deskripsi_kegiatan') }}" id=""
                                                rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="deskripsi_kegiatan" class="text-capitalize">Kebutuhan
                                                perlengkapan</label>
                                            <textarea class="form-control" name="perlengkapan" value="{{ old('perlengkapan') }}" id=""
                                                rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="link_zoom" class="text-capitalize">link zoom (jika ada)</label>
                                            <textarea class="form-control" name="link_zoom" value="{{ old('link_zoom') }}" id="" rows="3"></textarea>
                                        </div>
                                    </div>


                                    {{-- <div class="mb-4">
                                        <label for="pic" class="text-capitalize">Person in Charge (PIC)</label>
                                        <input type="text" name="pic" class="form-control"
                                            value="{{ old('pic') }}">
                                    </div> --}}

                                    @if (Auth::user()->is_admin || Auth::user()->is_humas)
                                        <div class="mb-4">
                                            <label for="status" class="text-uppercase">status</label>
                                            <div class="form-group">
                                                <select class="form-control form-control-sm" name="status"
                                                    id="status">
                                                    <option value="pending">pending</option>
                                                    <option value="approved">disetujui</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif

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

                            <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-danger px-2 py-2">BACK <i
                                    class="fa-solid fa-right-from-bracket"></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const $select = $('#tempat_kegiatan_select');
                const $customWrapper = $('#wrapper_custom_tempat');
                const $customInput = $('#input_custom_tempat');

                if ($select.length) {
                    if (window.$ && $.fn.select2) {
                        $select.select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            tags: true,
                            placeholder: '-- Pilih Tempat Kegiatan atau Ketik Custom --',
                            allowClear: true,
                            createTag: function (params) {
                                const term = $.trim(params.term);
                                if (term === '' || term.toLowerCase() === 'lainnya') {
                                    return null;
                                }
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true
                                };
                            }
                        });
                    }

                    function handleTempatChange() {
                        const val = $select.val();
                        if (val === '__custom__') {
                            $customWrapper.removeClass('d-none');
                            $customInput.prop('disabled', false).attr('name', 'tempat_kegiatan').attr('required', true).focus();
                            $select.removeAttr('name').removeAttr('required');
                        } else {
                            $customWrapper.addClass('d-none');
                            $customInput.prop('disabled', true).removeAttr('name').removeAttr('required');
                            $select.attr('name', 'tempat_kegiatan').attr('required', true);
                        }
                    }

                    $select.on('change', handleTempatChange);
                    handleTempatChange();
                }
            });
        </script>
    @endpush
