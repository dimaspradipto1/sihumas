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
                  <input type="text" name="no_wa" value="{{old('no_wa')}}" class="form-control" id="no_wa" placeholder="Contoh: 6285363572448">
                </div>

                <div class="mb-4">
                  <label for="email" class="text-uppercase">fakultas</label>
                  <select name="fakultas" class="form-control" id="fakultas">
                    <option >PILIH</option>
                    <option >==================</option>
                    <option value="UNIVERSITAS IBNU SINA">UNIVERSITAS IBNU SINA</option>
                    <option value="REKTORAT">REKTORAT</option>
                    <option value="FAKULTAS EKONOMI DAN BISNIS">FAKULTAS EKONOMI DAN BISNIS</option>
                    <option value="FAKULTAS SAINS DAN TEKNOLOGI">FAKULTAS SAINS DAN TEKNOLOGI</option>
                    <option value="FAKULTAS ILMU KESEHATAN">FAKULTAS ILMU KESEHATAN</option>
                  </select>
                </div>

                <div class="mb-4">
                  <label for="password" class="text-uppercase">password</label>
                  <input type="password" class="form-control" name="password" value="{{old('password')}}">
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
