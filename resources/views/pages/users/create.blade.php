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
        <li class="breadcrumb-item active" aria-current="page">Data Pengguna</li>
      </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
      <div class="mb-3 mb-lg-0">
        <h1 class="h4">Forms Tambah Pengguna</h1>
        <div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <form action="{{route('users.store')}}" method="POST">
            @csrf
            

          <div class="card-body">
            <div class="row mb-4">
              <div class="col-12">

                <div class="mb-4">
                  <label for="nama_mahasiswa" class="text-uppercase">nama pengguna</label>
                  <input type="text" name="name" value="{{old('name') }}" class="form-control" id="nama_mahasiswa">
                </div>

                <div class="mb-4">
                  <label for="email" class="text-uppercase">Email</label>
                  <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email">
                </div>

                <div class="mb-4">
                  <label for="no_wa" class="text-uppercase">Nomor WhatsApp</label>
                  <input type="text" name="no_wa" value="{{old('no_wa')}}" class="form-control" id="no_wa" placeholder="Contoh: 08123456789">
                </div>

                <div class="mb-4">
                  <label for="unit_kegiatan_id" class="text-uppercase fw-bold">Unit Kegiatan</label>
                  <select name="unit_kegiatan_id" class="form-select" id="unit_kegiatan_id">
                    <option value="">-- PILIH UNIT KEGIATAN --</option>
                    @if (isset($units) && count($units) > 0)
                      @php
                        $hasKategori = $units->contains(fn($u) => !empty($u->kategori));
                        if ($hasKategori) {
                            $order = [
                                'BAAK',
                                'Perpustakaan',
                                'BAUK',
                                'BKAK',
                                'LPTI',
                                'Ka.LPMM',
                                'Ka.LPMI',
                                'Rektorat',
                                'FAKULTAS EKONOMI DAN BISNIS (FEB)',
                                'FAKULTAS SAINS DAN TEKNOLOGI (FST)',
                                'FAKULTAS ILMU KESEHATAN (FIKes)',
                            ];
                            $groupedUnits = $units->groupBy(function($item) {
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
                              <option value="{{ $unit->id }}" title="{{ $unit->unit_kegiatan }}" {{ old('unit_kegiatan_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama_sub }}
                              </option>
                            @endforeach
                          </optgroup>
                        @endforeach
                      @else
                        @foreach ($units as $unit)
                          <option value="{{ $unit->id }}" {{ old('unit_kegiatan_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit_kegiatan }}
                          </option>
                        @endforeach
                      @endif
                    @endif
                  </select>
                </div>

                <div class="mb-4">
                  <label for="inputPassword" class="text-uppercase">Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control" id="inputPassword" name="password" value="{{ old('password', 'password') }}" placeholder="Default: password" style="padding-right: 2.75rem;">
                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent pe-3" onclick="togglePasswordVisibility('inputPassword', 'iconTogglePassword')" title="Lihat/Sembunyikan Password" tabindex="-1" style="z-index: 5; box-shadow: none; cursor: pointer;">
                      <i class="fa-solid fa-eye" id="iconTogglePassword"></i>
                    </button>
                  </div>
                  <small class="text-muted"><i class="fa-solid fa-circle-info me-1 text-primary"></i>Password default: <code>password</code> (klik ikon mata untuk melihat)</small>
                </div>

              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-sm-2 col-form-label text-uppercase fw-bold">ROLE AKSES</label>
              <div class="col-sm-10">
                <div class="row g-3">
                  <!-- Option 1: Admin Humas -->
                  <div class="col-md-4">
                    <label class="card h-100 p-3 border rounded shadow-xs" for="role_admin" style="cursor: pointer;">
                      <div class="d-flex align-items-center mb-2">
                        <input class="form-check-input mt-0 me-2" type="radio" name="role" id="role_admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} required>
                        <span class="badge text-white px-2 py-1" style="background-color: #dc3545 !important; font-size: 11px;"><i class="fa-solid fa-shield-halved me-1"></i> Admin Humas</span>
                      </div>
                      <small class="text-muted" style="font-size: 11.5px; line-height: 1.3;">Akses penuh: persetujuan pengajuan, penugasan PIC, validasi laporan, dan master data.</small>
                    </label>
                  </div>

                  <!-- Option 2: Petugas Humas -->
                  <div class="col-md-4">
                    <label class="card h-100 p-3 border rounded shadow-xs" for="role_humas" style="cursor: pointer;">
                      <div class="d-flex align-items-center mb-2">
                        <input class="form-check-input mt-0 me-2" type="radio" name="role" id="role_humas" value="humas" {{ old('role') === 'humas' ? 'checked' : '' }}>
                        <span class="badge text-white px-2 py-1" style="background-color: #0d6efd !important; font-size: 11px;"><i class="fa-solid fa-bullhorn me-1"></i> Petugas Humas</span>
                      </div>
                      <small class="text-muted" style="font-size: 11.5px; line-height: 1.3;">Tim peliputan: menerima tugas peliputan di Kotak Masuk dan mengunggah laporan dokumentasi.</small>
                    </label>
                  </div>

                  <!-- Option 3: Pengusul Unit -->
                  <div class="col-md-4">
                    <label class="card h-100 p-3 border rounded shadow-xs" for="role_operator" style="cursor: pointer;">
                      <div class="d-flex align-items-center mb-2">
                        <input class="form-check-input mt-0 me-2" type="radio" name="role" id="role_operator" value="operator" {{ old('role', 'operator') === 'operator' ? 'checked' : '' }}>
                        <span class="badge text-white px-2 py-1" style="background-color: #198754 !important; font-size: 11px;"><i class="fa-solid fa-building me-1"></i> Pengusul Unit</span>
                      </div>
                      <small class="text-muted" style="font-size: 11.5px; line-height: 1.3;">Pemohon kegiatan: membuat pengajuan kegiatan baru mewakili unit kerja yang dipilih di atas.</small>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center me-2 aria-haspopup="true"
              aria-expanded="false">
              <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
              </svg>
              SUBMIT
            </button>

           <a href="{{route('users.index')}}" class="btn btn-sm btn-danger px-2 py-2">BACK <i class="fa-solid fa-right-from-bracket"></i></a>
          </div>
        </form>
        </div>
      </div>
    </div>
  @endsection

  @push('scripts')
  <script>
    function togglePasswordVisibility(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (!input || !icon) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
  @endpush
