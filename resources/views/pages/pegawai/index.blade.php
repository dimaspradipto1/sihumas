@extends('layouts.dashboard.template')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning text-white">Edit Profile</a>
            <a href="{{ route('pegawai.index') }}" class="btn btn-danger">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">General information</h2>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="user_id">nama pegawai</label>
                                <select class="form-select" id="user_id" name="user_id" disabled>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ ($pegawai->user_id ?? auth()->id()) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="last_name">NUP</label>
                                <input class="form-control" id="nup" type="number" value="{{ $pegawai->NUP ?? '' }}"
                                    placeholder="masukkan nup" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="tempat_lahir">tempat lahir</label>
                                <input class="form-control" id="tempat_lahir" type="text"
                                    value="{{ $pegawai->tempat_lahir ?? '' }}" placeholder="masukkan tempat lahir"
                                    readonly />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="last_name">NIDN</label>
                                <input class="form-control" id="nidn" type="number" value="{{ $pegawai->NIDN ?? '' }}"
                                    placeholder="masukkan nidn" readonly />
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
                                <input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="text"
                                    value="{{ $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d/m/Y') : '' }}"
                                    readonly required />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender">NUPTK</label>
                            <input class="form-control" id="nuptk" type="number" name="nuptk"
                                value="{{ $pegawai->NUPTK ?? '' }}" placeholder="masukan nuptk" readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email"
                                    value="{{ auth()->user()->email }}" placeholder="name@uis.ac.id" readonly />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="phone">Nomor Whatsapp</label>
                                <input class="form-control" id="no_wa" name="no_wa" type="number"
                                    value="{{ $pegawai->no_wa ?? '' }}" placeholder="masukkan nomor whatsapp" readonly />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <select name="agama" class="form-control" id="agama" disabled>
                                    <option value="Islam" {{ ($pegawai->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam
                                    </option>
                                    <option value="Kristen" {{ ($pegawai->agama ?? '') == 'Kristen' ? 'selected' : '' }}>
                                        Kristen</option>
                                    <option value="Hindu" {{ ($pegawai->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu
                                    </option>
                                    <option value="Buddha" {{ ($pegawai->agama ?? '') == 'Buddha' ? 'selected' : '' }}>
                                        Buddha</option>
                                    <option value="Konghucu" {{ ($pegawai->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>
                                        Konghucu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h2 class="h5 my-4">Location</h2>
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3" readonly>{{ $pegawai->alamat ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow border-0 text-center p-0">
                        <div class="profile-cover rounded-top" data-background="volt/assets/img/profile-cover.jpg"></div>
                        <div class="card-body pb-5">
                            <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : asset('volt/assets/img/team/avatar-grey.png') }}"
                                class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait" />
                            <h5 class="h3">{{ Auth::user()->name }}</h5>
                            <p class="fw-normal">{{ Auth::user()->unitKegiatan?->unit_kegiatan ?? 'Universitas Ibnu Sina' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
