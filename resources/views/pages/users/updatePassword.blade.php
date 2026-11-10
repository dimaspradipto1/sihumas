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
        <h1 class="h4">Forms Update Password</h1>
        <div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
          <form action="{{route('users.updatePassword', $users->id)}}" method="POST">
            @csrf
            @method('PUT')

          <div class="card-body">
            <div class="row mb-4">
              <div class="col-12">

                <div class="mb-4">
                  <label for="new_password" class="text-uppercase">Password Baru</label>
                  <div class="input-group">
                    <input type="password" name="new_password" value="{{old('new_password')}}" class="form-control" id="new_password" placeholder="Masukkan password baru">
                    <button class="btn btn-outline-gray-400" type="button" onclick="togglePasswordVisibility('new_password', 'iconNewPassword')" title="Lihat/Sembunyikan Password">
                      <i class="fa-solid fa-eye" id="iconNewPassword"></i>
                    </button>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="new_password_confirmation" class="text-uppercase">Konfirmasi Password Baru</label>
                  <div class="input-group">
                    <input type="password" name="new_password_confirmation" value="{{old('new_password_confirmation')}}" class="form-control" id="new_password_confirmation" placeholder="Ulangi password baru">
                    <button class="btn btn-outline-gray-400" type="button" onclick="togglePasswordVisibility('new_password_confirmation', 'iconConfirmPassword')" title="Lihat/Sembunyikan Password">
                      <i class="fa-solid fa-eye" id="iconConfirmPassword"></i>
                    </button>
                  </div>
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
              UPDATE PASSWORD
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
