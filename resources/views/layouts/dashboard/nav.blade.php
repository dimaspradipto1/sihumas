<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark p-0 mb-4" style="overflow: visible !important;">
    <div class="card border-0 shadow-sm w-100 bg-white" style="border-radius: 0.75rem; overflow: visible !important;">
        <div class="card-body py-2 px-3 px-md-4" style="overflow: visible !important;">
            <div class="d-flex justify-content-between align-items-center w-100" id="navbarSupportedContent">
                <!-- Left Side: Hamburger & Search Halaman -->
                <div class="d-flex align-items-center">
                    <!-- Hamburger Toggle Button -->
                    <button id="sidebarToggle" type="button" class="btn btn-sm text-gray-700 me-2 me-md-3 d-flex align-items-center justify-content-center border-0 shadow-none"
                        style="width: 38px; height: 38px; border-radius: 0.5rem; background-color: #f3f4f6; cursor: pointer;"
                        title="Sembunyikan / Tampilkan Menu Sidebar"
                        aria-label="Toggle Sidebar">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Search Halaman -->
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
                        $user = auth()->user();
                        $bellData = $bellNotif ?? ['total' => 0, 'pending' => 0, 'diterima' => 0, 'ditolak' => 0, 'recent' => collect()];
                        $recentNotifs = $bellData['recent'] ?? collect();
                        $totalNotif = (int) ($bellData['total'] ?? 0);
                        $hasNotifications = $totalNotif > 0 || $recentNotifs->isNotEmpty();
                        $notifAllRoute = (!empty($user->is_admin) || !empty($user->is_humas))
                            ? route('kotak-masuk-pengajuan.index')
                            : route('pengajuan.index');
                    @endphp

                    {{-- Notification Bell --}}
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link text-gray-700 notification-bell-btn dropdown-toggle"
                            data-unread-notifications="{{ $totalNotif > 0 ? 'true' : 'false' }}" href="#"
                            role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"
                            title="Notifikasi Pengajuan & Informasi Terbaru">
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

                        <div class="dropdown-menu dropdown-menu-end mt-2 py-0 shadow-lg border notif-dropdown-menu"
                            style="width: 380px !important; min-width: 320px !important; max-width: calc(100vw - 24px) !important; right: 0 !important; left: auto !important; overflow: hidden !important; border-radius: 0.65rem !important;">
                            <div class="list-group list-group-flush w-100">
                                <div class="d-flex justify-content-between align-items-center gap-2 px-3 py-2 border-bottom border-light w-100" style="box-sizing: border-box;">
                                    <span class="text-primary fw-bold text-uppercase text-truncate" style="font-size: 11px; min-width: 0;">Notifikasi &amp; Info Terbaru</span>
                                    @if (($bellData['pending'] ?? 0) > 0)
                                        <span class="badge bg-warning flex-shrink-0 px-2 py-1" style="font-size: 11px; color: #1f2937 !important; white-space: nowrap;">{{ $bellData['pending'] }} Pending</span>
                                    @endif
                                </div>

                                @if ($recentNotifs->isNotEmpty())
                                    @foreach ($recentNotifs as $item)
                                        @php
                                            $itemUrl = !empty($user->is_admin)
                                                ? route('pengajuan.edit', $item->id)
                                                : route('pengajuan.show', $item->id);
                                            $statusBadge = match ($item->status) {
                                                'diterima' => '<span class="badge bg-success px-2 py-1" style="font-size: 11px; color: #ffffff !important; white-space: nowrap;">Diterima</span>',
                                                'ditolak' => '<span class="badge bg-danger px-2 py-1" style="font-size: 11px; color: #ffffff !important; white-space: nowrap;">Ditolak</span>',
                                                default => '<span class="badge bg-warning px-2 py-1" style="font-size: 11px; color: #1f2937 !important; white-space: nowrap;">Pending</span>',
                                            };
                                        @endphp
                                        <a href="{{ $itemUrl }}" class="list-group-item list-group-item-action border-bottom py-2 px-3 w-100" style="box-sizing: border-box;">
                                            <div class="d-flex justify-content-between align-items-center gap-2 w-100" style="min-width: 0;">
                                                <div class="flex-grow-1" style="min-width: 0; overflow: hidden;">
                                                    <div class="fw-bold text-gray-900 text-truncate" style="font-size: 11px;">{{ $item->nama_kegiatan }}</div>
                                                    <div class="text-muted text-truncate" style="font-size: 11px;">
                                                        {{ $item->unit_kegiatan ?: ($item->user?->name ?? '-') }}
                                                        @if (!empty($item->link_desain))
                                                            • <span class="text-info fw-semibold">Link Desain Tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-end flex-shrink-0" style="white-space: nowrap;">
                                                    {!! $statusBadge !!}
                                                    <div class="text-muted mt-1" style="font-size: 11px;">
                                                        {{ $item->updated_at ? $item->updated_at->locale('id')->diffForHumans() : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="list-group-item border-bottom">
                                        <div class="text-center text-muted py-3" style="font-size: 11px;">
                                            Tidak ada notifikasi terbaru
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ $notifAllRoute }}"
                                    class="dropdown-item text-center fw-bold rounded-bottom py-2" style="font-size: 11px;">
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
                                <span class="fw-bold text-gray-900 text-capitalize d-block text-nowrap"
                                    style="font-size: 0.825rem; line-height: 1.2;">
                                    {{ Auth::user()->name }}
                                </span>
                                <small class="text-muted d-block text-nowrap" style="font-size: 0.7rem; line-height: 1.1;">
                                    {{ Auth::user()->role_label }}
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
    /* Pastikan navbar dan seluruh dropdown-nya tidak terpotong */
    .navbar-dashboard,
    .navbar-dashboard .card,
    .navbar-dashboard .card-body,
    .navbar-dashboard .nav-item.dropdown {
        overflow: visible !important;
    }

    .navbar-dashboard .dropdown-menu {
        z-index: 1060 !important;
    }

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
        @if(!Auth::user()->is_humas || Auth::user()->is_admin)
        { title: 'Pengajuan Kegiatan', url: '{{ route("pengajuan.index") }}', icon: '📝', category: 'Kegiatan' },
        @endif
        @if(Auth::user()->is_admin || Auth::user()->is_humas)
        { title: 'Kotak Masuk Pengajuan', url: '{{ route("kotak-masuk-pengajuan.index") }}', icon: '📥', category: 'Kegiatan' },
        @endif
        { title: 'Pelaporan Kegiatan', url: '{{ route("publikasi.index") }}', icon: '📑', category: 'Kegiatan' },
        @if(Auth::user()->is_admin)
        { title: 'Rekap Kegiatan', url: '{{ route("laporan-publikasi.index") }}', icon: '📁', category: 'Laporan' },
        @endif
        @if(Auth::user()->is_admin)
        { title: 'Tahun Akademik', url: '{{ route("tahun-akademik.index") }}', icon: '📅', category: 'Master Data' },
        { title: 'Unit Kegiatan', url: '{{ route("unit-kegiatan.index") }}', icon: '🏢', category: 'Master Data' },
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
