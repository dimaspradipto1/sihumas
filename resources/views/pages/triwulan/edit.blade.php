@extends('layouts.dashboard.template')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('triwulan.index') }}">Triwulan (TW)</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Triwulan</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between w-100 flex-wrap mb-3">
            <h1 class="h4 text-capitalize">Edit Data Triwulan (TW)</h1>
            <a href="{{ route('triwulan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow">
            <div class="card-body">
                <form action="{{ route('triwulan.update', $triwulan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                    @endphp

                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="kode" class="form-label fw-bold">Kode TW <span class="text-danger">*</span></label>
                            <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
                                value="{{ old('kode', $triwulan->kode) }}" required>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="nama" class="form-label fw-bold">Nama Periode TW <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $triwulan->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="bulan_mulai" class="form-label fw-bold">Bulan Mulai <span class="text-danger">*</span></label>
                            <select name="bulan_mulai" id="bulan_mulai" class="form-select @error('bulan_mulai') is-invalid @enderror" required>
                                @foreach ($namaBulan as $num => $label)
                                    <option value="{{ $num }}" {{ old('bulan_mulai', $triwulan->bulan_mulai) == $num ? 'selected' : '' }}>{{ $num }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('bulan_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="bulan_selesai" class="form-label fw-bold">Bulan Selesai <span class="text-danger">*</span></label>
                            <select name="bulan_selesai" id="bulan_selesai" class="form-select @error('bulan_selesai') is-invalid @enderror" required>
                                @foreach ($namaBulan as $num => $label)
                                    <option value="{{ $num }}" {{ old('bulan_selesai', $triwulan->bulan_selesai) == $num ? 'selected' : '' }}>{{ $num }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('bulan_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $triwulan->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Jadikan TW Aktif</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('triwulan.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
