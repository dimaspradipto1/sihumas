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
        <h1 class="h4">Forms Pengguna</h1>
        <div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <form action="{{route('users.update', $user->id)}}" method="POST">
            @csrf
            @method('PUT')
            

          <div class="card-body">
            <div class="row mb-4">
              <div class="col-12">

                <div class="mb-4">
                  <label for="nama_mahasiswa" class="text-uppercase">nama pengguna</label>
                  <input type="text" name="name" value="{{old('name') ?? $user->name }}" class="form-control" id="nama_mahasiswa">
                </div>

                <div class="mb-4">
                  <label for="email" class="text-uppercase">Email</label>
                  <input type="email" name="email" value="{{old('email') ?? $user->email}}" class="form-control" id="email">
                </div>

                <div class="mb-4">
                  <label for="no_wa" class="text-uppercase">Nomor WhatsApp</label>
                  @php
                    $valNoWa = $user->no_wa ?? '';
                    if (str_starts_with($valNoWa, '628')) {
                        $valNoWa = '0' . substr($valNoWa, 2);
                    }
                  @endphp
                  <input type="text" name="no_wa" value="{{old('no_wa', $valNoWa)}}" class="form-control" id="no_wa" placeholder="Contoh: 08123456789">
                </div>

                <div class="mb-4">
                  <label for="unit_kegiatan_id" class="text-uppercase fw-bold">Unit Kegiatan</label>
                  <select name="unit_kegiatan_id" class="form-select" id="unit_kegiatan_id">
                    <option value="">-- PILIH UNIT KEGIATAN --</option>
                    @if (isset($units) && count($units) > 0)
                      @php
                        $order = ['BAAK', 'Perpustakaan', 'BAUK', 'BKAK', 'LPTI', 'Ka.LPMM', 'Ka.LPMI', 'Fakultas & Rektorat'];
                        $groupedUnits = $units->groupBy(function($item) {
                            return $item->kategori ?: 'Lainnya';
                        })->sortBy(function($items, $key) use ($order) {
                            $idx = array_search($key, $order);
                            return $idx === false ? 999 : $idx;
                        });
                      @endphp
                      @foreach ($groupedUnits as $kategori => $items)
                        <optgroup label="🏢 {{ $kategori }}">
                          @foreach ($items as $unit)
                            <option value="{{ $unit->id }}" title="{{ $unit->unit_kegiatan }}" {{ old('unit_kegiatan_id', $user->unit_kegiatan_id) == $unit->id ? 'selected' : '' }}>
                              {{ $unit->nama_sub }}
                            </option>
                          @endforeach
                        </optgroup>
                      @endforeach
                    @endif
                  </select>
                </div>

                {{-- <div class="mb-4">
                  <label for="password" class="text-uppercase">password</label>
                  <input type="text" class="form-control" name="password" value="{{old('password') ?? $user->password }}">
                </div> --}}

              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">ROLE AKSES</label>
              <div class="col-sm-10">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-admin-utama" name="is_admin" {{ $user->is_admin ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase" for="checkbox-admin-utama">
                    admin
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-humas" name="is_humas" {{ $user->is_humas ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase fw-bold text-primary" for="checkbox-humas">
                    <i class="fa-solid fa-bullhorn me-1"></i> petugas humas
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-admin" name="is_rektorat" {{ $user->is_rektorat ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase" >
                    admin rektorat
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-dosen" name="is_feb" {{ $user->is_feb ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase">
                    admin feb
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-admin" name="is_fst" {{ $user->is_fst ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase">
                    admin fst
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-wakil-dekan-I" name="is_fikes" {{ $user->is_fikes ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase">
                    admin fikes
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="true" id="checkbox-wakil-dekan-II" name="is_users" {{ $user->is_users ? 'checked' : '' }}>
                  <label class="form-check-label text-uppercase">
                    pengguna
                  </label>
                </div>

              </div>
            </div>
            <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 aria-haspopup="true"
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
