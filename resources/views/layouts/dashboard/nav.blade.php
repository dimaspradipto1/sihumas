<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark p-0 mb-4">
    <div class="card border-0 shadow-sm w-100 bg-white" style="border-radius: 0.75rem;">
        <div class="card-body py-2 px-3 px-md-4">
            <div class="d-flex justify-content-between align-items-center w-100" id="navbarSupportedContent">
                <!-- Search Halaman -->
                <div class="d-flex align-items-center">
                    <div class="position-relative page-search-wrapper" style="width: 260px; max-width: 100%;">
                        <form id="pageSearchForm" onsubmit="return false;" class="m-0">
                            <div class="input-group input-group-merge page-search-group">
                                <span class="input-group-text bg-gray-100 border-0 text-gray-500 ps-3 pe-2" id="search-addon" style="border-radius: 2rem 0 0 2rem;">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" class="form-control bg-gray-100 border-0 ps-1 text-gray-800 shadow-none" id="pageSearchInput"
                                    placeholder="Cari halaman..." aria-label="Cari halaman" aria-describedby="search-addon"
                                    autocomplete="off" style="border-radius: 0 2rem 2rem 0; font-size: 0.85rem; height: 38px;">
                            </div>
                        </form>

                        <!-- Autocomplete dropdown -->
                        <div id="pageSearchResults" class="dropdown-menu shadow-lg border-0 mt-1 py-1 w-100"
                            style="display: none; position: absolute; top: 100%; left: 0; z-index: 1050; border-radius: 0.5rem; max-height: 320px; overflow-y: auto;">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Notifications & User profile dropdown -->
                <ul class="navbar-nav align-items-center mb-0">
                    @php
                        $notif = session('pengajuan_notif', []);
                        $user = auth()->user();

                        // Scope mengikuti role yang login
                        $scope = null;

                        if (!empty($user->is_admin) || !empty($user->is_staffbaak)) {
                            $scope = 'admin';
                        } elseif (!empty($user->is_feb)) {
                            $scope = 'feb';
                        } elseif (!empty($user->is_fst)) {
                            $scope = 'fst';
                        } elseif (!empty($user->is_fikes)) {
                            $scope = 'fikes';
                        } elseif (!empty($user->is_rektorat)) {
                            $scope = 'rektorat';
                        }

                        $pending = $scope ? data_get($notif, "$scope.pending", 0) : 0;
                        $diterima = $scope ? data_get($notif, "$scope.diterima", 0) : 0;
                        $ditolak = $scope ? data_get($notif, "$scope.ditolak", 0) : 0;

                        $totalNotif = $pending + $diterima + $ditolak;
                        $hasNotifications = $totalNotif > 0;

                        // Label judul sesuai scope
                        $scopeLabel = match ($scope) {
                            'admin' => 'All Faculties',
                            'feb' => 'FEB',
                            'fst' => 'FST',
                            'fikes' => 'FIKES',
                            'rektorat' => 'Rektorat',
                            default => '',
                        };
                    @endphp

                    {{-- Notification Bell --}}
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link text-gray-700 notification-bell-btn dropdown-toggle"
                            data-unread-notifications="{{ $hasNotifications ? 'true' : 'false' }}" href="#"
                            role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"
                            title="Notifikasi Pengajuan">
                            <svg style="width: 19px; height: 19px; display: block; margin: 0 auto;" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                                </path>
                            </svg>

                            @if ($totalNotif > 0)
                                <span class="bell-badge-fixed">{{ $totalNotif }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end mt-2 py-0 shadow-sm border-0">
                            <div class="list-group list-group-flush">
                                <a href="#" class="text-center text-primary fw-bold border-bottom border-light py-3">
                                    Notifications
                                </a>

                                {{-- PENDING --}}
                                @if ($pending > 0)
                                    <a href="{{ route('kotak-masuk-pengajuan.index', ['status' => 'pending']) }}"
                                        class="list-group-item list-group-item-action border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col ps-0 ms-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="h6 mb-0 text-small">
                                                            Pengajuan Pending {{ $scopeLabel }}
                                                        </h4>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-warning">{{ $pending }} Pengajuan</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                {{-- DITERIMA --}}
                                @if ($diterima > 0)
                                    <a href="{{ route('kotak-masuk-pengajuan.index', ['status' => 'diterima']) }}"
                                        class="list-group-item list-group-item-action border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col ps-0 ms-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="h6 mb-0 text-small">
                                                            Pengajuan Diterima {{ $scopeLabel }}
                                                        </h4>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-success">{{ $diterima }} Pengajuan</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                {{-- DITOLAK --}}
                                @if ($ditolak > 0)
                                    <a href="{{ route('kotak-masuk-pengajuan.index', ['status' => 'ditolak']) }}"
                                        class="list-group-item list-group-item-action border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col ps-0 ms-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="h6 mb-0 text-small">
                                                            Pengajuan Ditolak {{ $scopeLabel }}
                                                        </h4>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-danger">{{ $ditolak }} Pengajuan</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                {{-- Jika tidak ada notif --}}
                                @if (!$hasNotifications)
                                    <div class="list-group-item border-bottom">
                                        <div class="text-center text-muted py-3">
                                            Tidak ada notifikasi terbaru
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('kotak-masuk-pengajuan.index') }}"
                                    class="dropdown-item text-center fw-bold rounded-bottom py-3">
                                    <svg class="icon icon-xxs text-gray-400 me-1" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Lihat Semua Pemberitahuan
                                </a>
                            </div>
                        </div>
                    </li>

                    {{-- User Profile Pill Dropdown --}}
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link user-profile-pill dropdown-toggle p-1 ps-2 pe-3 d-flex align-items-center text-decoration-none"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $pegawai = Auth::user()->pegawai;
                            @endphp
                            <img class="rounded-circle me-2" alt="{{ Auth::user()->name }}"
                                src="{{ $pegawai && $pegawai->foto ? asset('storage/' . $pegawai->foto) : asset('volt/assets/img/team/avatar-grey.png') }}"
                                style="width: 32px; height: 32px; object-fit: cover;">
                            <div class="d-none d-md-block text-start me-1">
                                <span class="fw-bold text-gray-900 text-capitalize d-block text-truncate"
                                    style="font-size: 0.825rem; max-width: 170px; line-height: 1.2;">
                                    {{ Auth::user()->name }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">
                                    @if (!empty(Auth::user()->is_admin))
                                        Admin Humas
                                    @elseif (!empty(Auth::user()->is_rektorat))
                                        Rektorat
                                    @elseif (!empty(Auth::user()->is_feb))
                                        Fakultas FEB
                                    @elseif (!empty(Auth::user()->is_fst))
                                        Fakultas FST
                                    @elseif (!empty(Auth::user()->is_fikes))
                                        Fakultas FIKES
                                    @else
                                        Pegawai
                                    @endif
                                </small>
                            </div>
                            <svg class="icon icon-xxs text-gray-500 ms-1 d-none d-md-inline" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>

                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1 shadow-sm border-0">
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('pegawai.index', Auth::user()->id) }}">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Data Pegawai
                            </a>
                            <a class="dropdown-item d-flex align-items-center"
                                href="https://api.whatsapp.com/send?phone=6289523712219&text=Hello!%20I%20would%20like%20to%20get%20in%20touch%20with%20you."
                                target="_blank" rel="noopener noreferrer">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Messages
                            </a>

                            <a class="dropdown-item d-flex align-items-center"
                                href="https://docs.google.com/forms/d/e/1FAIpQLScBzk64NtsOkLRN6ncMx9s1_f8owD2DZnItnl3s6H_-_uZRWQ/viewform"
                                target="_blank">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                                    </path>
                                </svg>
                                Feedback
                            </a>
                            <div role="separator" class="dropdown-divider my-1"></div>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}">
                                <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Hilangkan panah default ::after pada dropdown di header */
    .notification-bell-btn.dropdown-toggle::after,
    .user-profile-pill.dropdown-toggle::after {
        display: none !important;
    }

    /* Tombol lonceng notifikasi melingkar */
    .notification-bell-btn {
        position: relative !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 40px !important;
        height: 40px !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 50% !important;
        background-color: #F3F4F6 !important;
        transition: background-color 0.2s ease !important;
        text-align: center !important;
        line-height: 1 !important;
    }

    .notification-bell-btn:hover {
        background-color: #E5E7EB !important;
    }

    /* Badge notifikasi presisi di atas pojok tombol */
    .bell-badge-fixed {
        position: absolute !important;
        top: -6px !important;
        right: -4px !important;
        min-width: 20px !important;
        height: 20px !important;
        padding: 0 5px !important;
        background-color: #f0ad4e !important;
        color: #ffffff !important;
        border-radius: 999px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        z-index: 15 !important;
        box-shadow: 0 0 0 2px #ffffff !important;
    }

    /* Pill profil pengguna */
    .user-profile-pill {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 50rem;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .user-profile-pill:hover,
    .user-profile-pill[aria-expanded="true"] {
        background: #F3F4F6;
        border-color: #D1D5DB;
    }

    /* Page Search Bar styling */
    .page-search-wrapper {
        transition: width 0.2s ease;
    }

    .page-search-group .input-group-text,
    .page-search-group .form-control {
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .page-search-group:focus-within .input-group-text,
    .page-search-group:focus-within .form-control {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 1px #2563EB !important;
    }

    .page-search-item.active,
    .page-search-item:hover {
        background-color: #F3F4F6 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('pageSearchInput');
    const searchResults = document.getElementById('pageSearchResults');
    if (!searchInput || !searchResults) return;

    const pages = [
        { title: 'Dashboard', url: '{{ route("dashboard") }}', icon: '📊', category: 'Utama' },
        { title: 'Pengajuan Kegiatan', url: '{{ route("pengajuan.index") }}', icon: '📝', category: 'Kegiatan' },
        @if(Auth::user()->is_admin)
        { title: 'Kotak Masuk Pengajuan', url: '{{ route("kotak-masuk-pengajuan.index") }}', icon: '📥', category: 'Kegiatan' },
        @endif
        { title: 'Pelaporan Kegiatan', url: '{{ route("publikasi.index") }}', icon: '📑', category: 'Kegiatan' },
        @if(Auth::user()->is_admin)
        { title: 'Tahun Akademik', url: '{{ route("tahun-akademik.index") }}', icon: '📅', category: 'Master Data' },
        { title: 'Unit Kegiatan', url: '{{ route("unit-kegiatan.index") }}', icon: '🏢', category: 'Master Data' },
        { title: 'Rekap Kegiatan', url: '{{ route("laporan-publikasi.index") }}', icon: '📁', category: 'Laporan' },
        { title: 'Pengguna', url: '{{ route("users.index") }}', icon: '👥', category: 'Pengaturan' },
        { title: 'Pengaturan WhatsApp', url: '{{ route("settings.index") }}', icon: '💬', category: 'Pengaturan' },
        { title: 'Backup Database & Storage', url: '{{ route("backup.index") }}', icon: '💾', category: 'Pengaturan' },
        @endif
        { title: 'Data Pegawai / Profil', url: '{{ route("pegawai.index", Auth::user()->id) }}', icon: '👤', category: 'Profil' }
    ];

    let activeIndex = -1;
    let filteredPages = [];

    function renderResults(query) {
        query = query.trim().toLowerCase();
        if (!query) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            activeIndex = -1;
            filteredPages = [];
            return;
        }

        filteredPages = pages.filter(p =>
            p.title.toLowerCase().includes(query) ||
            p.category.toLowerCase().includes(query)
        );

        if (filteredPages.length === 0) {
            searchResults.innerHTML = '<div class="px-3 py-2 text-muted text-center" style="font-size: 0.8rem;">Halaman tidak ditemukan</div>';
        } else {
            searchResults.innerHTML = filteredPages.map((page, index) => `
                <a href="${page.url}" class="dropdown-item d-flex align-items-center py-2 px-3 page-search-item ${index === 0 ? 'active' : ''}" data-index="${index}">
                    <span class="me-2" style="font-size: 1rem;">${page.icon}</span>
                    <div class="d-flex flex-column text-truncate">
                        <span class="fw-semibold text-gray-800" style="font-size: 0.84rem;">${page.title}</span>
                        <small class="text-muted" style="font-size: 0.72rem;">${page.category}</small>
                    </div>
                </a>
            `).join('');
            activeIndex = 0;
        }

        searchResults.style.display = 'block';
    }

    searchInput.addEventListener('input', function () {
        renderResults(this.value);
    });

    searchInput.addEventListener('focus', function () {
        if (this.value.trim().length > 0) {
            renderResults(this.value);
        }
    });

    searchInput.addEventListener('keydown', function (e) {
        const items = searchResults.querySelectorAll('.page-search-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && filteredPages[activeIndex]) {
                window.location.href = filteredPages[activeIndex].url;
            }
        } else if (e.key === 'Escape') {
            searchResults.style.display = 'none';
        }
    });

    function updateActiveItem(items) {
        items.forEach((item, idx) => {
            if (idx === activeIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
