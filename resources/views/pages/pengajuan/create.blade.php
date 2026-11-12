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
                <li class="breadcrumb-item active text-capitalize" aria-current="page">data pengajuan
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4 text-capitalize">form pengajuan</h1>
                <div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    {{-- <div class="mb-4">
                                        <label for="tahun_akademik_id" class="text-capitalize">tahun akademik</label>
                                       <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select single">
                                        <option>Pilih Tahun Akademik</option>
                                        <option>====================================================</option>
                                            @foreach ($tahunAkademik as $tahun)
                                                <option value="{{ $tahun->id }}"
                                                    {{ request('tahun_akademik_id') == $tahun->id ? 'selected' : '' }}>{{ $tahun->tahun_akademik }}</option>
                                            @endforeach
                                            <option>====================================================</option>
                                        </select>
                                    </div> --}}

                                    <div class="mb-4">
                                        <label for="nama_kegiatan" class="text-capitalize">nama kegiatan</label>
                                        <select name="nama_kegiatan" id="nama_kegiatan" class="form-select single" required>
                                            <option value="">Pilih Nama Kegiatan</option>
                                            <option value="Desain" {{ old('nama_kegiatan') == 'Desain' ? 'selected' : '' }}>Desain</option>
                                            <option value="Dokumentasi" {{ old('nama_kegiatan') == 'Dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                                            <option value="Desain dan Dokumentasi" {{ old('nama_kegiatan') == 'Desain dan Dokumentasi' ? 'selected' : '' }}>Desain dan Dokumentasi</option>
                                            <option value="Publikasi" {{ old('nama_kegiatan') == 'Publikasi' ? 'selected' : '' }}>Publikasi</option>
                                            <option value="Tamu Undangan" {{ old('nama_kegiatan') == 'Tamu Undangan' ? 'selected' : '' }}>Tamu</option>
                                        </select>
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
                                        <label for="unit_kegiatan" class="text-capitalize fw-bold">Unit Kegiatan</label>
                                        <select name="unit_kegiatan" id="unit_kegiatan" class="form-select single">
                                            <option value="">-- Pilih Unit Kegiatan --</option>
                                            @php
                                                $hasKategori = $unitKegiatan->contains(fn($u) => !empty($u->kategori));
                                                if ($hasKategori) {
                                                    $order = ['BAAK', 'Perpustakaan', 'BAUK', 'BKAK', 'LPTI', 'Ka.LPMM', 'Ka.LPMI', 'Fakultas & Rektorat'];
                                                    $groupedUnits = $unitKegiatan->groupBy(function($item) {
                                                        return $item->kategori ?: 'Lainnya';
                                                    })->sortBy(function($items, $key) use ($order) {
                                                        $idx = array_search($key, $order);
                                                        return $idx === false ? 999 : $idx;
                                                    });
                                                }
                                            @endphp

                                            @if ($hasKategori)
                                                @foreach ($groupedUnits as $kategori => $items)
                                                    <optgroup label="🏢 {{ $kategori }}">
                                                        @foreach ($items as $unit)
                                                            <option value="{{ $unit->unit_kegiatan }}"
                                                                {{ old('unit_kegiatan', request('unit_kegiatan')) == $unit->unit_kegiatan ? 'selected' : '' }}>
                                                                {{ $unit->nama_sub }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @else
                                                @foreach ($unitKegiatan as $unit)
                                                    <option value="{{ $unit->unit_kegiatan }}"
                                                        {{ old('unit_kegiatan', request('unit_kegiatan')) == $unit->unit_kegiatan ? 'selected' : '' }}>
                                                        {{ $unit->unit_kegiatan }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="deskripsi_kegiatan" class="text-capitalize">deskripsi
                                                kegiatan</label>
                                            <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan"
                                                value="{{ old('deskripsi_kegiatan') }}" rows="3"
                                                placeholder="Masukkan Deskripsi Kegiatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="perlengkapan" class="text-capitalize">Kebutuhan
                                                perlengkapan</label>
                                            <textarea class="form-control" id="perlengkapan" name="perlengkapan"
                                                value="{{ old('perlengkapan') }}" rows="3"
                                                placeholder="Masukkan Kebutuhan Perlengkapan"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label for="link_zoom" class="text-capitalize">link zoom <span
                                                    class="text-danger">(jika ada)</span></label>
                                            <textarea class="form-control" name="link_zoom" value="{{ old('link_zoom') }}" id="" rows="3"
                                                placeholder="contoh: https://zoom.us/j/123456789"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <!-- Upload Dokumen Pendukung -->
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="upload_file" class="text-capitalize fw-bold">Upload Dokumen Pendukung <span
                                                    class="text-muted small fw-normal">(PDF, DOC, DOCX, XLS, ZIP - Maks. 10MB)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-file-arrow-up"></i></span>
                                                <input type="file" name="upload_file" id="upload_file" class="form-control"
                                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                                            </div>
                                            <small class="text-muted">Lampirkan file proposal, surat permohonan, atau berkas pendukung kegiatan.</small>
                                        </div>

                                        <!-- Upload Bahan Materi -->
                                        <div class="col-md-6">
                                            <label for="upload_png" class="text-capitalize fw-bold">Upload Bahan Materi <span
                                                    class="text-muted small fw-normal">(Format PNG, JPG, JPEG, WEBP - Maks. 5MB)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                                <input type="file" name="upload_png" id="upload_png" class="form-control"
                                                    accept=".png,.jpg,.jpeg,.webp" onchange="previewImage(this)">
                                            </div>
                                            <small class="text-muted">Lampirkan materi kegiatan seperti flyer, banner, pamflet, foto, atau logo pendukung.</small>
                                            <div id="imagePreviewContainer" class="mt-3 d-none">
                                                <p class="text-xs text-muted mb-1 fw-bold">Pratinjau Bahan Materi:</p>
                                                <div class="border rounded p-2 bg-light d-inline-block shadow-sm">
                                                    <img id="imagePreview" src="#" alt="Preview"
                                                        class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (Auth::user()->is_admin || Auth::user()->is_humas)
                                        <div class="mb-4">
                                            <label for="status" class="text-capitalize">status</label>
                                            <div class="form-group">
                                                <select class="form-control form-control-sm" name="status"
                                                    id="status">
                                                    <option value="">Pilih Status</option>
                                                    <option value="pending">pending</option>
                                                    <option value="approved">disetujui</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="email_tujuan" class="text-capitalize">Email yang ditujukan kepada
                                                pemberi tugas</label>
                                            <input type="email" class="form-control" name="email_tujuan"
                                                value="{{ old('email_tujuan') }}"
                                                placeholder="contoh: pengguna@gmail.com">
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
            function previewImage(input) {
                const previewContainer = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '#';
                    previewContainer.classList.add('d-none');
                }
            }
        </script>
        <script>
            CKEDITOR.replace('deskripsi_kegiatan', {
                versionCheck: false
            });
        </script>
        <script>
            CKEDITOR.replace('perlengkapan', {
                versionCheck: false
            });
        </script>
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
