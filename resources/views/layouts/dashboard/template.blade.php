<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Dashboard - SIHUMAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Dashboard - Humas">
    <meta name="author" content="Themesberg">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://demo.themesberg.com/volt-pro">
    <meta property="og:title" content="Volt - Free Bootstrap 5 Dashboard">
    <meta property="og:description"
        content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.themesberg.com/volt-pro">
    <meta property="twitter:title" content="Volt - Free Bootstrap 5 Dashboard">
    <meta property="twitter:description"
        content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('volt/assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('volt/assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('volt/assets/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('volt/assets/img/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('volt/assets/img/favicon/favicon-16x16.png') }}" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Notyf -->
    <link type="text/css" href="{{ asset('volt/vendor/notyf/notyf.min.css') }}" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="{{ asset('volt/css/volt.css') }}" rel="stylesheet">

    {{--  datatables CSS  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <style>
        /* Pastikan scroll horizontal DataTables selalu sinkron antara header dan body */
        .dataTables_wrapper .dataTables_scrollHead,
        .dt-container .dt-scroll-head {
            overflow: hidden !important;
            position: relative !important;
            border: 0 !important;
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_scrollBody,
        .dt-container .dt-scroll-body {
            overflow-x: auto !important;
            margin-bottom: 0.5rem;
            width: 100% !important;
        }

        /* Hilangkan pembatasan lebar 100% dari Bootstrap pada DataTables scrollX agar kolom selaras */
        .dt-container .dt-scroll-head table,
        .dt-container .dt-scroll-body table,
        .dataTables_wrapper .dataTables_scrollHead table,
        .dataTables_wrapper .dataTables_scrollBody table {
            width: max-content !important;
            min-width: 100% !important;
            margin-bottom: 0 !important;
        }

        .dt-container .dt-scroll-headInner,
        .dataTables_wrapper .dataTables_scrollHeadInner {
            box-sizing: content-box !important;
        }

        /* Hindari container pembungkus card membuat scrollbar di bawah pagination */
        .card-body > .table-responsive {
            overflow-x: visible !important;
        }

        /* Styling Select2 agar serasi dengan tema Volt Dashboard */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px !important;
            border-color: #d1d5db !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #1f2937 !important;
            box-shadow: 0 0 0 0.2rem rgba(31, 41, 55, 0.15) !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #1f2937 !important;
            font-size: 0.875rem !important;
            padding-left: 0.5rem !important;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            font-size: 0.875rem !important;
            z-index: 9999 !important;
        }
        .select2-container--bootstrap-5 .select2-search__field {
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
            font-size: 0.875rem !important;
            padding: 0.4rem 0.75rem !important;
        }
        .select2-container--bootstrap-5 .select2-search__field:focus {
            border-color: #1f2937 !important;
            box-shadow: 0 0 0 0.2rem rgba(31, 41, 55, 0.15) !important;
        }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        .select2-container--bootstrap-5 .select2-results__option--selected {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            font-weight: 600 !important;
        }

        /* Transisi & Mode Hidden Sidebar */
        .sidebar {
            transition: transform 0.3s ease-in-out, margin-left 0.3s ease-in-out !important;
        }
        .content {
            transition: margin-left 0.3s ease-in-out !important;
        }
        @media (min-width: 992px) {
            html.sidebar-hidden #sidebarMenu,
            body.sidebar-hidden #sidebarMenu {
                transform: translateX(-100%) !important;
            }
            html.sidebar-hidden .content,
            body.sidebar-hidden .content {
                margin-left: 0 !important;
            }
        }
        #sidebarToggle {
            transition: all 0.2s ease-in-out;
        }
        #sidebarToggle:hover {
            background-color: #e5e7eb !important;
            color: #111827 !important;
        }
    </style>

    <script>
        if (window.innerWidth >= 992 && localStorage.getItem('sidebar_hidden') === 'true') {
            document.documentElement.classList.add('sidebar-hidden');
        }
    </script>

    @stack('style')
</head>

<body>

    <!-- navbar -->
    <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
        <a class="navbar-brand me-lg-5" href="{{ route('dashboard') }}">
            <img class="navbar-brand-dark" src="{{ asset('volt/assets/img/favicon/uis.png') }}" alt="camera" />
            <img class="navbar-brand-light" src="{{ asset('volt/assets/img/brand/dark.svg') }}" alt="camera" />
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <!-- end navbar -->

    @include('layouts.dashboard.sidebar')

    <main class="content">

        <!-- navbar -->
        @include('layouts.dashboard.nav')
        @sweetAlert

        @yield('content')

        @include('layouts.dashboard.footer')
    </main>

    {{--  fontawesome  --}}
    <script src="https://kit.fontawesome.com/63b8672806.js" crossorigin="anonymous"></script>

    <!-- Core -->
    <script src="{{ asset('volt/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('volt/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('volt/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

    <!-- Slider -->
    <script src="{{ asset('volt/vendor/nouislider/dist/nouislider.min.js') }}"></script>

    <!-- Smooth scroll -->
    <script src="{{ asset('volt/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('volt/vendor/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('volt/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>


    <!-- Datepicker -->
    <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Sweet Alerts 2 -->
    <script src="{{ asset('volt/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    <!-- Vanilla JS Datepicker -->
    <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Notyf -->
    <script src="{{ asset('volt/vendor/notyf/notyf.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('volt/vendor/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Volt JS -->
    <script src="{{ asset('volt/assets/js/volt.js') }}"></script>


    {{-- datatables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
    {{-- ckeditor --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.16.2/ckeditor.js"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
    @stack('style')
    <script>
        $(document).ready(function() {
            // Sinkronisasi horizontal scroll antara tbody dan thead pada DataTables
            $(document).on('scroll', '.dataTables_scrollBody, .dt-scroll-body', function() {
                var scrollLeft = $(this).scrollLeft();
                var head = $(this).closest('.dataTables_scroll, .dt-scroll')
                                  .find('.dataTables_scrollHead, .dt-scroll-head');
                head.scrollLeft(scrollLeft);
            });

            // Sinkronisasi jika scrolling digerakkan dari area thead
            $(document).on('scroll', '.dataTables_scrollHead, .dt-scroll-head', function() {
                var scrollLeft = $(this).scrollLeft();
                var body = $(this).closest('.dataTables_scroll, .dt-scroll')
                                  .find('.dataTables_scrollBody, .dt-scroll-body');
                body.scrollLeft(scrollLeft);
            });

            // Otomatis sesuaikan ukuran kolom saat DataTables selesai di-draw atau init
            $(document).on('init.dt draw.dt', function(e, settings) {
                if (window.$ && $.fn.dataTable) {
                    setTimeout(function() {
                        var api = new $.fn.dataTable.Api(settings);
                        api.columns.adjust();
                    }, 50);
                }
            });

            // Sesuaikan kolom saat resize browser
            $(window).on('resize', function() {
                if (window.$ && $.fn.dataTable) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 992 && localStorage.getItem('sidebar_hidden') === 'true') {
                document.body.classList.add('sidebar-hidden');
            }

            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebarMenu = document.getElementById('sidebarMenu');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.innerWidth >= 992) {
                        document.body.classList.toggle('sidebar-hidden');
                        document.documentElement.classList.toggle('sidebar-hidden');
                        const isHidden = document.body.classList.contains('sidebar-hidden');
                        localStorage.setItem('sidebar_hidden', isHidden ? 'true' : 'false');

                        // Sesuaikan kolom datatables jika ada tabel di halaman
                        if (window.$ && $.fn.dataTable) {
                            setTimeout(function() {
                                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                            }, 310);
                        }
                    } else {
                        if (sidebarMenu) {
                            if (window.bootstrap && bootstrap.Collapse) {
                                const bsCollapse = bootstrap.Collapse.getInstance(sidebarMenu) || new bootstrap.Collapse(sidebarMenu, { toggle: false });
                                bsCollapse.toggle();
                            } else {
                                sidebarMenu.classList.toggle('show');
                            }
                        }
                    }
                });
            }
        });
    </script>


</body>

</html>
