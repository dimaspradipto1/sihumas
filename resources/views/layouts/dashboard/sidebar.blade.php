  <style>
      #sidebarMenu, 
      #sidebarMenu .sidebar-inner, 
      #sidebarMenu .simplebar-content,
      #sidebarMenu .simplebar-content-wrapper,
      #sidebarMenu .simplebar-mask {
          overflow-x: hidden !important;
      }
      #sidebarMenu .simplebar-track.simplebar-horizontal {
          display: none !important;
          opacity: 0 !important;
          visibility: hidden !important;
      }
  </style>
  <!-- sidebar mobile -->
  <nav id="sidebarMenu" class="sidebar d-lg-block bg-primary text-white collapse" data-simplebar>
      <div class="sidebar-inner px-4 pt-3">
          <div data-aos="fade-up" data-aos-duration="1000"
              class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
              <div class="d-flex align-items-center">
                  <div class="avatar-lg me-4">
                      <img src="{{ asset('volt/assets/img/team/avatar-grey.png') }}"
                          class="card-img-top rounded-circle border-white" alt="users">
                  </div>
                  <div class="d-block">
                      <h2 class="h5 mb-3">{{ Auth::user()->name }}</h2>
                      <a href="{{ route('logout') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                          <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                              </path>
                          </svg>
                          Sign Out
                      </a>
                  </div>
              </div>
              <div class="collapse-close d-md-none">
                  <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                      aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                      <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                      </svg>
                  </a>
              </div>
          </div>
          <ul class="nav flex-column pt-3 pt-md-0">
              <li class="nav-item active">
                  <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center">
                      <span class="sidebar-icon">
                          <img src="{{ asset('volt/assets/img/logouis.png') }}" height="30" width="30"
                              alt="Volt Logo">
                      </span>
                      <span class="mt-1 ms-1 sidebar-text">SIHUMAS</span>
                  </a>
              </li>
              <li role="separator" class="dropdown-divider mt-4 mb-3 bs-white-rgb"></li>

              <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <a href="{{ route('dashboard') }}" class="nav-link">
                      <span class="sidebar-icon">
                          <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                              xmlns="http://www.w3.org/2000/svg">
                              <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                              <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                          </svg>
                      </span>
                      <span class="sidebar-text">Dashboard</span>
                  </a>
              </li>

              @php
                  $notif = session('pengajuan_notif', []);
                  $user = auth()->user();

                  // Tentukan scope sesuai role login
                  $scope = null;

                  if (!empty($user->is_admin)) {
                      $scope = 'admin';
                  } elseif (!empty($user->is_humas)) {
                      $scope = null;
                  } elseif (!empty($user->is_feb)) {
                      $scope = 'feb';
                  } elseif (!empty($user->is_fst)) {
                      $scope = 'fst';
                  } elseif (!empty($user->is_fikes)) {
                      $scope = 'fikes';
                  } elseif (!empty($user->is_rektorat)) {
                      $scope = 'rektorat';
                  } else {
                      $scope = 'user';
                  }

                  $pending = $scope ? data_get($notif, "$scope.pending", 0) : 0;
                  $diterima = $scope ? data_get($notif, "$scope.diterima", 0) : 0;
                  $ditolak = $scope ? data_get($notif, "$scope.ditolak", 0) : 0;
              @endphp

              @if (!Auth::user()->is_humas || Auth::user()->is_admin)
              <li class="nav-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                  <a href="{{ route('pengajuan.index') }}" class="nav-link nav-with-badge d-flex justify-content-between align-items-center gap-2">
                      <span class="d-flex align-items-center text-truncate">
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd"
                                      d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                      clip-rule="evenodd"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize">Pengajuan Kegiatan</span>
                      </span>

                      {{-- BADGE: sejajar di dalam menu agar tidak overflow --}}
                      @if (!request()->routeIs('pengajuan.*') && ($pending > 0 || $diterima > 0 || $ditolak > 0))
                          <div class="badge-group flex-shrink-0">
                              @if ($pending > 0)
                                  <span class="badge-dot badge-pending" title="Pending">{{ $pending }}</span>
                              @endif

                              @if ($diterima > 0)
                                  <span class="badge-dot badge-diterima" title="Diterima">{{ $diterima }}</span>
                              @endif

                              @if ($ditolak > 0)
                                  <span class="badge-dot badge-ditolak" title="Ditolak">{{ $ditolak }}</span>
                              @endif
                          </div>
                      @endif
                  </a>
              </li>
              @endif

              <style>
                  .nav-with-badge {
                      position: relative;
                  }

                  /* Container badge rapi di kanan dalam area nav-link */
                  .badge-group {
                      display: inline-flex;
                      align-items: center;
                      gap: 4px;
                  }

                  /* Badge bulat kecil */
                  .badge-dot {
                      min-width: 18px;
                      height: 18px;
                      border-radius: 999px;
                      font-size: 11px;
                      display: inline-flex;
                      align-items: center;
                      justify-content: center;
                      color: #fff;
                      font-weight: 700;
                      line-height: 1;
                      padding: 0 5px;
                  }

                  .badge-pending {
                      background: #f0ad4e;
                  }

                  .badge-diterima {
                      background: #5cb85c;
                  }

                  .badge-ditolak {
                      background: #d9534f;
                  }
              </style>

              @if (Auth::user()->is_admin || Auth::user()->is_humas)
                  <li class="nav-item {{ request()->routeIs('kotak-masuk-pengajuan.*') ? 'active' : '' }}">
                      <a href="{{ route('kotak-masuk-pengajuan.index') }}" class="nav-link d-flex justify-content-between align-items-center gap-2">
                          <span class="d-flex align-items-start">
                              <span class="sidebar-icon mt-1">
                                  <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd"
                                          d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z"
                                          clip-rule="evenodd"></path>
                                  </svg>
                              </span>
                              <span>
                                  <span class="sidebar-text text-capitalize d-block">Kotak Masuk</span>
                                  <span class="sidebar-subtext text-capitalize d-block" style="font-size: 0.75rem; opacity: 0.85;">
                                      Pengajuan kegiatan
                                  </span>
                              </span>
                          </span>

                          @if (!request()->routeIs('kotak-masuk-pengajuan.*') && ($notifikasi_pengajuan_admin ?? 0) > 0)
                              <span class="badge-kotak-masuk flex-shrink-0"
                                  style="
                                      background: #dc3545;
                                      color: #ffffff;
                                      border-radius: 999px;
                                      min-width: 20px;
                                      height: 20px;
                                      padding: 0 6px;
                                      display: inline-flex;
                                      justify-content: center;
                                      align-items: center;
                                      font-size: 11px;
                                      font-weight: 700;
                                      line-height: 1;
                                  ">
                                  {{ $notifikasi_pengajuan_admin }}
                              </span>
                          @endif
                      </a>
                  </li>
              @endif

              <li class="nav-item {{ request()->routeIs('publikasi.*') ? 'active' : '' }}">
                  <a href="{{ route('publikasi.index') }}" class="nav-link d-flex justify-content-between">
                      <span>
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd"
                                      d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                      clip-rule="evenodd"></path>
                                  <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize">pelaporan kegiatan</span>
                      </span>
                      @if (Auth::user()->is_admin)
                          @php
                              $pendingPublikasiCount = 0;
                              try {
                                  if (\Illuminate\Support\Facades\Schema::hasColumn('publikasis', 'status_validasi')) {
                                      $pendingPublikasiCount = \App\Models\Publikasi::where('status_validasi', 'menunggu_validasi')->count();
                                  }
                              } catch (\Throwable $e) {
                                  $pendingPublikasiCount = 0;
                              }
                          @endphp
                          @if ($pendingPublikasiCount > 0)
                              <span class="badge badge-sm bg-warning text-dark font-weight-bold">{{ $pendingPublikasiCount }}</span>
                          @endif
                      @endif
                  </a>
              </li>

              @if (Auth::user()->is_admin)
                  <li class="nav-item {{ request()->routeIs('laporan-publikasi.*') ? 'active' : '' }}">
                      <a href="{{ route('laporan-publikasi.index') }}"
                          class="nav-link d-flex justify-content-between">
                          <span>
                              <span class="sidebar-icon">
                                  <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                      <path fill-rule="evenodd"
                                          d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                          clip-rule="evenodd"></path>
                                  </svg>
                              </span>
                              <span class="sidebar-text text-capitalize">rekap kegiatan</span>
                          </span>
                      </a>
                  </li>
              @endif

              @if (Auth::user()->is_admin)
                  <li class="nav-item {{ request()->routeIs('tahun-akademik.*') ? 'active' : '' }}">
                      <a href="{{ route('tahun-akademik.index') }}" class="nav-link">
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd"
                                      d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                      clip-rule="evenodd"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize">tahun akademik</span>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->routeIs('unit-kegiatan.*') ? 'active' : '' }}">
                      <a href="{{ route('unit-kegiatan.index') }}" class="nav-link">
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path
                                      d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                  </path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize">Unit Kegiatan</span>
                      </a>
                  </li>
              @endif

              @if (Auth::user()->is_admin)
                  <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                      <a href="{{ route('users.index') }}" class="nav-link d-flex justify-content-between">
                          <span>
                              <span class="sidebar-icon">
                                  <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                      </path>
                                  </svg>
                              </span>
                              <span class="sidebar-text text-capitalize">pengguna</span>
                          </span>
                      </a>
                  </li>
              @endif

              @if (Auth::user()->is_admin)
                  <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                      <a href="{{ route('settings.index') }}" class="nav-link">
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd"
                                      d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                      clip-rule="evenodd"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize">Pengaturan WA</span>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                      <a href="{{ route('backup.index') }}" class="nav-link d-flex align-items-center">
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"></path>
                                  <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"></path>
                                  <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text text-capitalize" style="white-space: normal; line-height: 1.25;">Backup Database &amp; Storage</span>
                      </a>
                  </li>
              @endif

              <li role="separator" class="dropdown-divider mt-4 mb-3 bs-white-rgb"></li>
          </ul>
      </div>
  </nav>
  <!-- end sidebar -->
