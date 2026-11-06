<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIHUMAS — Sistem Informasi Humas &amp; Publikasi | Universitas Ibnu Sina</title>
    <meta name="description" content="Sistem Informasi Humas Universitas Ibnu Sina - Layanan Pengajuan Liputan, Dokumentasi dan Peminjaman Alat">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('volt/assets/img/favicon/uis.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --uis-green: #056839;
            --uis-green-light: #0d8b4f;
            --uis-gold: #c69214;
            --uis-gold-light: #e5af32;
            --navy-dark: #07111e;
            --white: #ffffff;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--white);
            background-color: var(--navy-dark);
            overflow-x: hidden;
        }

        /* ===== FULLSCREEN UIS BACKGROUND ===== */
        .login-page {
            position: relative;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        /* Background Kampus UIS */
        .bg-uis {
            position: fixed;
            inset: 0;
            background: url('{{ asset('volt/assets/img/uis.png') }}') center center / cover no-repeat;
            z-index: 1;
            transform: scale(1.02);
        }

        /* Overlay gelap & elegan agar kampus tetap tampak jelas namun kartu login kontras */
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at center, rgba(7, 17, 30, 0.72) 0%, rgba(5, 11, 20, 0.88) 100%);
            backdrop-filter: blur(2px);
            z-index: 2;
        }

        /* ===== CENTERED LOGIN CARD ===== */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: rgba(9, 20, 36, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-top: 1px solid rgba(198, 146, 20, 0.45);
            border-radius: 20px;
            padding: 40px 36px 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.05);
            animation: cardFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardFadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo & Header Institusi */
        .header-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 82px;
            height: 82px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(198, 146, 20, 0.4);
            padding: 6px;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .inst-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--uis-gold-light);
            margin-bottom: 4px;
        }

        .app-title {
            font-family: 'Cinzel', serif;
            font-size: 1.85rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--white);
            line-height: 1.2;
        }

        .app-sub {
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-300);
            margin-top: 4px;
        }

        .dept-tag {
            display: inline-block;
            margin-top: 8px;
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 12px;
            border-radius: 50px;
            background: rgba(5, 104, 57, 0.35);
            border: 1px solid rgba(13, 139, 79, 0.5);
            color: #6ee7b7;
        }

        /* Alert Error */
        .alert-error-box {
            display: none;
            align-items: center;
            gap: 10px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 12.5px;
            color: #fca5a5;
        }
        .alert-error-box i {
            color: #ef4444;
            font-size: 14px;
        }

        /* Input Fields */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--slate-300);
            margin-bottom: 7px;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control-custom {
            width: 100%;
            background: rgba(7, 15, 27, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 12px 42px 12px 42px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--white);
            outline: none;
            transition: all 0.25s ease;
        }

        .form-control-custom::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        .form-control-custom:focus {
            background: rgba(7, 15, 27, 0.9);
            border-color: var(--uis-gold);
            box-shadow: 0 0 0 3px rgba(198, 146, 20, 0.2);
        }

        .form-control-custom:focus + .input-icon,
        .input-container:focus-within .input-icon {
            color: var(--uis-gold-light);
        }

        /* Password Toggle */
        .btn-toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--slate-400);
            font-size: 14px;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }
        .btn-toggle-pw:hover {
            color: var(--uis-gold-light);
        }

        /* Row bantuan & remember */
        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 12px;
        }

        .remember-wrap {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            color: var(--slate-300);
            user-select: none;
        }
        .remember-wrap input[type="checkbox"] {
            accent-color: var(--uis-gold);
            cursor: pointer;
            width: 15px;
            height: 15px;
        }

        .link-help {
            color: var(--uis-gold-light);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .link-help:hover {
            text-decoration: underline;
            color: var(--white);
        }

        /* Submit Button */
        .btn-login-submit {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--uis-gold) 0%, var(--uis-gold-light) 100%);
            border: none;
            border-radius: 10px;
            color: #07111e;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 18px rgba(198, 146, 20, 0.35);
            transition: all 0.25s ease;
        }

        .btn-login-submit:hover {
            background: linear-gradient(135deg, var(--uis-gold-light) 0%, #ffd066 100%);
            box-shadow: 0 6px 24px rgba(198, 146, 20, 0.5);
            transform: translateY(-1px);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        /* Divider & Footer */
        .card-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
            margin: 24px 0 16px;
        }

        .card-footer-info {
            text-align: center;
            font-size: 11px;
            color: var(--slate-400);
            line-height: 1.5;
        }
        .card-footer-info strong {
            color: var(--slate-300);
        }

        /* Loading Spinner */
        .loading-cover {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(5, 11, 20, 0.8);
            backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
        }
        .spinner {
            width: 44px;
            height: 44px;
            border: 3.5px solid rgba(198, 146, 20, 0.2);
            border-top-color: var(--uis-gold-light);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .loading-cover span {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--uis-gold-light);
            letter-spacing: 0.8px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }
            .app-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

<!-- Loading Overlay -->
<div class="loading-cover" id="loadingCover">
    <div class="spinner"></div>
    <span>Memverifikasi Akun...</span>
</div>

<!-- Background Kampus UIS -->
<div class="bg-uis"></div>
<div class="bg-overlay"></div>

<!-- Centered Layout -->
<div class="login-page">

    <div class="login-card">

        <!-- Logo & Header Institusi -->
        <div class="header-brand">
            <div class="logo-box">
                <img 
                    src="{{ asset('volt/assets/img/logouis.png') }}" 
                    alt="Logo UIS" 
                    onerror="this.src='https://assets.siakadcloud.com/uploads/uis/logoaplikasi/156.jpg'"
                >
            </div>
            <div class="inst-title">UNIVERSITAS IBNU SINA</div>
            <h1 class="app-title">SIHUMAS</h1>
            <p class="app-sub">Sistem Informasi Humas &amp; Protokoler</p>
            <div class="dept-tag">
                <i class="fa-solid fa-camera me-1"></i> Layanan Liputan &amp; Publikasi
            </div>
        </div>

        <!-- Alert Error -->
        <div class="alert-error-box" id="loginAlertError">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="loginAlertText">Email atau password salah.</span>
        </div>

        <!-- Form Login -->
        <form action="{{ route('loginproses') }}" method="POST" id="formLogin">
            @csrf

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const alertBox = document.getElementById('loginAlertError');
                        const alertText = document.getElementById('loginAlertText');
                        if (alertBox && alertText) {
                            alertBox.style.display = 'flex';
                            alertText.textContent = '{{ $errors->first() }}';
                        }
                    });
                </script>
            @endif

            <!-- Email -->
            <div class="form-group">
                <label for="inputEmail" class="form-label">Email</label>
                <div class="input-container">
                    <input 
                        type="email" 
                        id="inputEmail" 
                        name="login" 
                        value="{{ old('login') }}" 
                        class="form-control-custom" 
                        placeholder="nama@uis.ac.id" 
                        required 
                        autocomplete="email" 
                        autofocus
                    >
                    <i class="fa-solid fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="inputPassword" class="form-label">Kata Sandi</label>
                <div class="input-container">
                    <input 
                        type="password" 
                        id="inputPassword" 
                        name="password" 
                        class="form-control-custom" 
                        placeholder="Masukkan kata sandi" 
                        required 
                        autocomplete="current-password"
                    >
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="btn-toggle-pw" id="btnTogglePw" aria-label="Lihat kata sandi">
                        <i class="fa-solid fa-eye" id="iconTogglePw"></i>
                    </button>
                </div>
            </div>

            <!-- Options -->
            <div class="form-meta">
                <label class="remember-wrap">
                    <input type="checkbox" name="remember" id="chkRemember">
                    <span>Ingat saya</span>
                </label>
                <a 
                    href="https://wa.me/6282283736481?text=Halo%20Admin%20Humas%20UIS,%20saya%20butuh%20bantuan%20login%20ke%20SIHUMAS." 
                    target="_blank" 
                    rel="noopener" 
                    class="link-help"
                >
                    <i class="fa-brands fa-whatsapp me-1"></i> Bantuan Login
                </a>
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="btn-login-submit" id="btnSubmit">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Masuk ke Sistem</span>
            </button>
        </form>

        <div class="card-divider"></div>

        <!-- Footer -->
        <div class="card-footer-info">
            &copy; {{ date('Y') }} <strong>Universitas Ibnu Sina</strong><br>
            Biro Hubungan Masyarakat, Protokoler &amp; Publikasi
        </div>

    </div>

</div>

<!-- Scripts -->
<script>
    // Toggle Password Visibility
    const pwInput = document.getElementById('inputPassword');
    const toggleBtn = document.getElementById('btnTogglePw');
    const toggleIcon = document.getElementById('iconTogglePw');

    if (toggleBtn && pwInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Submit Loading
    const form = document.getElementById('formLogin');
    const loadingCover = document.getElementById('loadingCover');
    const btnSubmit = document.getElementById('btnSubmit');

    if (form) {
        form.addEventListener('submit', function () {
            if (loadingCover) loadingCover.style.display = 'flex';
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Memverifikasi...</span>';
            }
        });
    }

    // Enter Key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
            if (form && !loadingCover.style.display.includes('flex')) {
                form.requestSubmit ? form.requestSubmit() : form.submit();
            }
        }
    });
</script>

<!-- SweetAlert2 Alert Integration -->
@include('vendor.sweetalert.alert')

</body>
</html>