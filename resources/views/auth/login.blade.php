<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lab Tekkim UAD</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 24px;
            max-width: 1100px;
            width: 100%;
        }

        /* Login Card */
        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 40px 36px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .brand h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1d29;
            margin-bottom: 4px;
        }

        .brand p {
            font-size: 13px;
            color: #0d6efd;
            font-weight: 500;
        }

        .brand .sub {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
            font-weight: 400;
        }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }

        .input-field {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .input-field:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        }

        .input-field::placeholder {
            color: #9ca3af;
        }

        .form-link {
            text-align: right;
            margin-bottom: 20px;
        }

        .form-link a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .form-link a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #0b5ed7;
        }

        .register-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .register-text a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        .alert-error {
            padding: 10px 14px;
            margin-bottom: 18px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 13px;
            border: 1px solid #fecaca;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .captcha-box {
            margin-bottom: 18px;
        }

        .captcha-box .g-recaptcha {
            transform: scale(0.95);
            transform-origin: left top;
        }

        .captcha-note {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            display: flex;
            align-items: center;
            background: none;
            border: none;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #1f2937;
        }

        /* Announcement Card */
        .announcement-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            max-height: 580px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .announcement-card::-webkit-scrollbar { width: 5px; }
        .announcement-card::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

        .ann-header {
            margin-bottom: 20px;
        }

        .ann-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1d29;
            margin-bottom: 4px;
        }

        .ann-header p {
            font-size: 13px;
            color: #6b7280;
        }

        .ann-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            border-left: 4px solid #0d6efd;
        }

        .ann-item .ann-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
            background: #dbeafe;
            color: #1e40af;
        }

        .ann-item h3 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .ann-item p {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .ann-date {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: auto;
            padding-top: 16px;
        }

        .stat-box {
            background: #f0f2f5;
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
        }

        .stat-box .num {
            font-size: 22px;
            font-weight: 700;
            color: #0d6efd;
        }

        .stat-box .lbl {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
            margin-top: 2px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Card -->
        <div class="login-card">
            <div class="brand">
                <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="Logo UAD">
                <h2>Teknik Kimia</h2>
                <p>Selamat Datang di Lab Tekkim UAD</p>
                <p class="sub">Portal informasi laboratorium untuk civitas akademika</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="input-field" placeholder="Masukkan password" required style="padding-right: 40px;">
                        <button type="button" id="togglePassword" class="toggle-password" title="Lihat/Sembunyi Password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-link">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>

               

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <div class="register-text">
                Belum punya akun? <a href="{{ route('registrasi') }}">Daftar di sini</a>
            </div>
        </div>

        <!-- Announcement Card -->
        <div class="announcement-card">
            <div class="ann-header">
                <h2>Informasi & Pengumuman</h2>
                <p>Pengumuman resmi dari Laboratorium Tekkim UAD</p>
            </div>

            @forelse($pengumuman ?? [] as $item)
                <div class="ann-item">
                    <span class="ann-badge">{{ $item->status ?? 'Pengumuman' }}</span>
                    <h3>{{ $item->judul }}</h3>
                    <p>{{ Str::limit($item->isi, 150) }}</p>
                    <span class="ann-date">
                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </div>
            @empty
                <div class="ann-item">
                    <span class="ann-badge">Pengumuman</span>
                    <h3>Belum ada pengumuman</h3>
                    <p>Saat ini belum ada pengumuman terbaru dari laboratorium.</p>
                </div>
            @endforelse

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-box">
                    <div class="num">{{ $stats['mahasiswa'] }}{{ $stats['mahasiswa'] >= 100 ? '+' : '' }}</div>
                    <div class="lbl">Mahasiswa</div>
                </div>
                <div class="stat-box">
                    <div class="num">{{ $stats['dosen'] }}</div>
                    <div class="lbl">Dosen</div>
                </div>
                <div class="stat-box">
                    <div class="num">{{ $stats['alat_lab'] }}</div>
                    <div class="lbl">Alat Lab</div>
                </div>
                <div class="stat-box">
                    <div class="num">{{ $stats['lab_aktif'] }}</div>
                    <div class="lbl">Lab Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function (e) {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the icon
                if (type === 'password') {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    `;
                } else {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m5.882-5.882A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-4.012 4.904m-4.904-4.904a3 3 0 11-4.243-4.243M3 3l18 18" />
                    `;
                }
            });
        });
    </script>
</body>
</html>
