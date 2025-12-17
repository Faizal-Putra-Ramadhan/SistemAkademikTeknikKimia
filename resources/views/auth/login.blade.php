<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LAB TEKIM UAD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #a8d8ea 0%,  #F5F7FC 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

        .main-container {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            max-width: 1200px;
            width: 100%;
        }

        .login-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .logo-container h3 {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-container p {
            color: #0066cc;
            font-size: 14px;
            font-weight: 500;
        }

        .logo-container .subtitle {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #667eea;
        }

        .input-wrapper .icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: #5568d3;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            background: #fee;
            color: #c33;
            font-size: 14px;
            border-left: 4px solid #c33;
        }

        .error {
            color: #c33;
            font-size: 13px;
            margin-top: 5px;
        }

        /* Announcement Section */
        .announcement-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-height: 600px;
            overflow-y: auto;
        }

        .announcement-header {
            margin-bottom: 25px;
        }

        .announcement-header h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .announcement-header p {
            color: #666;
            font-size: 13px;
        }

        .announcement-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }

        .announcement-card.bahasa {
            border-left-color: #4299e1;
        }

        .announcement-card.pengumuman {
            border-left-color: #ed8936;
        }

        .announcement-card.pendaftaran {
            border-left-color: #48bb78;
        }

        .announcement-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .badge-bahasa {
            background: #bee3f8;
            color: #2c5282;
        }

        .badge-pengumuman {
            background: #fbd38d;
            color: #7c2d12;
        }

        .badge-pendaftaran {
            background: #c6f6d5;
            color: #22543d;
        }

        .announcement-card h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .announcement-card p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .announcement-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .announcement-date {
            color: #999;
            font-size: 12px;
        }

        .btn-detail {
            background: none;
            border: 1px solid #667eea;
            color: #667eea;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-detail:hover {
            background: #667eea;
            color: white;
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 12px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Scrollbar */
        .announcement-section::-webkit-scrollbar {
            width: 6px;
        }

        .announcement-section::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .announcement-section::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .announcement-section::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Login Section -->
        <div class="login-section">
            <div class="logo-container">
                <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}"
     alt="Logo UAD"
     style="
        width: 110px;
        height: 110px;
        object-fit: contain;
        object-position: center;
        display: block;
        margin: 0 auto;
     ">

                <h3>Teknik Kimia</h3>
                <p>Selamat Datang di LAB TEKIM UAD</p>
                <p class="subtitle">Portal informasi laboratorium untuk civitas akademika Teknik Kimia</p>
            </div>

            @if ($errors->any())
                <div class="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}" required autofocus>
                        <span class="icon">📧</span>
                    </div>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
                        <span class="icon">🔒</span>
                    </div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="forgot-password">
                    <a href="#">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <div class="register-link">
                Belum Punya Akun? <a href="{{ route('registrasi') }}">Daftar di sini</a>
            </div>
        </div>

        <!-- Announcement Section -->
        <div class="announcement-section">
            <div class="announcement-header">
                <h2>Informasi & Pengumuman</h2>
                <p>Pengumuman resmi dari Laboratorium TEKIM UAD</p>
            </div>

            @forelse($pengumuman ?? [] as $item)
                <div class="announcement-card {{ strtolower($item->status ?? 'pengumuman') }}">
                    <span class="announcement-badge badge-{{ strtolower($item->status ?? 'pengumuman') }}">
                        {{ $item->status ?? 'Pengumuman' }}
                    </span>
                    <h3>{{ $item->judul }}</h3>
                    <p>{{ Str::limit($item->isi, 150) }}</p>
                    <div class="announcement-meta">
                        <span class="announcement-date">
                            {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </span>
                        <!-- <button class="btn-detail" onclick="alert('{{ addslashes($item->isi) }}')">Detail →</button> -->
                    </div>
                </div>
            @empty
                <div class="announcement-card">
                    <span class="announcement-badge badge-pengumuman">Pengumuman</span>
                    <h3>Belum ada pengumuman</h3>
                    <p>Saat ini belum ada pengumuman terbaru dari laboratorium.</p>
                </div>
            @endforelse

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Mahasiswa</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">25</div>
                    <div class="stat-label">Dosen</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Praktikum</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Lab Aktif</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>