@extends('layouts.dashboard.template')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <a href="{{ route('pegawai.index') }}" class="btn btn-danger">Kembali</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="user_id">nama pegawai</label>
                                <select class="form-select" id="user_id" name="user_id" disabled>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ (old('user_id') ?? $pegawai->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="last_name">NUP</label>
                                <input class="form-control" id="nup" name="NUP" type="number"
                                    value="{{ old('NUP') ?? $pegawai->NUP }}" placeholder="masukkan nup" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="tempat_lahir">tempat lahir</label>
                                <input class="form-control" id="tempat_lahir" name="tempat_lahir" type="text"
                                    value="{{ old('tempat_lahir') ?? $pegawai->tempat_lahir }}"
                                    placeholder="masukkan tempat lahir" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="last_name">NIDN</label>
                                <input class="form-control" id="nidn" name="NIDN" type="number"
                                    value="{{ old('NIDN') ?? $pegawai->NIDN }}" placeholder="masukkan nidn" />
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3">
                            <label for="birthday">Birthday</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input class="form-control" name="tanggal_lahir" type="date"
                                    value="{{ old('tanggal_lahir') ?? ($pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('Y-m-d') : '') }}"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender">NUPTK</label>
                            <input class="form-control" id="nuptk" type="number" name="NUPTK"
                                value="{{ old('NUPTK') ?? $pegawai->NUPTK }}" placeholder="masukan nuptk" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" type="email"
                                    value="{{ auth()->user()->email }}" placeholder="name@uis.ac.id" readonly />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="phone">Nomor Whatsapp</label>
                                <input class="form-control" id="no_wa" name="no_wa" type="number"
                                    value="{{ old('no_wa') ?? $pegawai->no_wa }}" placeholder="masukkan nomor whatsapp" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <select name="agama" class="form-control" id="agama">
                                    <option value="Islam"
                                        {{ (old('agama') ?? $pegawai->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen"
                                        {{ (old('agama') ?? $pegawai->agama) == 'Kristen' ? 'selected' : '' }}>Kristen
                                    </option>
                                    <option value="Hindu"
                                        {{ (old('agama') ?? $pegawai->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha"
                                        {{ (old('agama') ?? $pegawai->agama) == 'Buddha' ? 'selected' : '' }}>Buddha
                                    </option>
                                    <option value="Konghucu"
                                        {{ (old('agama') ?? $pegawai->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h2 class="h5 my-4">Location</h2>
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3">{{ old('alamat') ?? $pegawai->alamat }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">
                            Save all
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow border-0 text-center p-0">
                            <div class="profile-cover rounded-top"
                                data-background="{{ asset('volt/assets/img/profile-cover.jpg') }}"></div>
                            <div class="card-body pb-5">
                                <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : asset('volt/assets/img/team/avatar-grey.png') }}"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Foto Profil" />
                                <h5 class="h3">{{ Auth::user()->name }}</h5>
                                <p class="fw-normal">{{ Auth::user()->unitKegiatan?->unit_kegiatan ?? 'Universitas Ibnu Sina' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-body border-0 shadow mb-4">
                            <h2 class="h5 mb-4">Pilih foto profil</h2>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <!-- Avatar -->
                                    <img class="rounded avatar-xl" id="preview-foto"
                                        src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : asset('volt/assets/img/team/avatar-grey.png') }}"
                                        alt="preview" />
                                </div>
                                <div class="file-field">
                                    <div class="d-flex justify-content-xl-center ms-xl-3">
                                        <div class="d-flex">
                                            <label for="foto" style="cursor: pointer;"
                                                class="d-flex align-items-center">
                                                <svg class="icon text-gray-500 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="d-md-block text-left">
                                                    <div class="fw-normal text-dark mb-1">Pilih Gambar</div>
                                                    <div class="text-gray small">JPG, GIF, atau PNG. Maks 2MB.</div>
                                                </div>
                                                <input type="file" name="foto" id="foto" accept="image/*"
                                                    class="d-none" onchange="previewImage(this)" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-foto').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
