<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa - LAB TEKIM UAD</title>
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
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            max-width: 1200px;
            width: 100%;
        }

        .register-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 70px;
            height: 70px;
            margin-bottom: 15px;
        }

        .logo-container h3 {
            color: #333;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-container p {
            color: #666;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            z-index: 10;
        }

        .toggle-password:hover {
            color: #4b5563;
        }

        .error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
        }

        .helper-text {
            color: #6b7280;
            font-size: 12px;
            margin-top: 5px;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
        }

        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert svg {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .info-box {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .info-box .title {
            display: flex;
            align-items: center;
            color: #1e40af;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .info-box svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        .info-box ul {
            color: #1e40af;
            font-size: 12px;
            margin-left: 26px;
        }

        .info-box li {
            margin-bottom: 5px;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        /* Info Section */
        .info-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .info-header {
            margin-bottom: 30px;
        }

        .info-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .info-header p {
            color: #666;
            font-size: 14px;
        }

        .feature-card {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .feature-card .icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .feature-card .icon svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .feature-card h3 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .feature-card p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            opacity: 0.9;
        }

        .process-timeline {
            margin-top: 30px;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 20px;
        }

        .timeline-number {
            width: 32px;
            height: 32px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            margin-right: 15px;
        }

        .timeline-content h4 {
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-content p {
            color: #666;
            font-size: 12px;
            line-height: 1.5;
        }

        /* Scrollbar */
        .register-section::-webkit-scrollbar {
            width: 6px;
        }

        .register-section::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .register-section::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .register-section {
                max-height: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Register Section -->
        <div class="register-section">
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
     <h3>Registrasi Mahasiswa</h3>
                <p>Sistem RegLab - Universitas Ahmad Dahlan</p>
            </div>

            @if(session('success'))
                <div class="alert success">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert error">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('registrasi.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="nama"
                            name="nama" 
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap Anda"
                            required
                        >
                    </div>
                    @error('nama')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <div class="input-wrapper">
                        <input 
                            type="tel" 
                            id="phone"
                            name="phone" 
                            value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            required
                        >
                    </div>
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="email@example.com"
                            required
                        >
                    </div>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <p class="helper-text">Gunakan email aktif untuk menerima verifikasi</p>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            placeholder="Minimal 8 karakter"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" class="toggle-password" data-target="password" title="Lihat/Sembunyi Password">
                            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            placeholder="Ulangi password"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" class="toggle-password" data-target="password_confirmation" title="Lihat/Sembunyi Password">
                            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="info-box">
                    <div class="title">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Catatan Penting:
                    </div>
                    <ul>
                        <li>Link verifikasi akan dikirim ke email Anda</li>
                        <li>Link berlaku selama 24 jam</li>
                        <li>User ID akan diberikan setelah verifikasi</li>
                    </ul>
                </div>

                <button type="submit" class="btn-register">
                    📧 Daftar & Kirim Verifikasi
                </button>
            </form>

            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-header">
                <h2>🎓 Bergabung dengan Lab TEKIM</h2>
                <p>Dapatkan akses penuh ke fasilitas laboratorium</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3>Akses Aman & Terverifikasi</h3>
                <p>Semua akun mahasiswa diverifikasi melalui email untuk memastikan keamanan data dan akses yang terkontrol.</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3>Peminjaman Alat & Ruangan</h3>
                <p>Ajukan peminjaman alat laboratorium dan ruangan praktikum secara online dengan proses persetujuan yang cepat.</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3>Tracking Aktivitas</h3>
                <p>Pantau semua aktivitas Anda di laboratorium, dari peminjaman hingga pengajuan penelitian.</p>
            </div>

            <div class="process-timeline">
                <h3 style="color: #333; font-size: 16px; margin-bottom: 20px; font-weight: 600;">Proses Registrasi:</h3>
                
                <div class="timeline-item">
                    <div class="timeline-number">1</div>
                    <div class="timeline-content">
                        <h4>Isi Formulir Registrasi</h4>
                        <p>Lengkapi data diri dengan informasi yang valid</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-number">2</div>
                    <div class="timeline-content">
                        <h4>Verifikasi Email</h4>
                        <p>Klik link verifikasi yang dikirim ke email Anda</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-number">3</div>
                    <div class="timeline-content">
                        <h4>Dapatkan User ID</h4>
                        <p>User ID akan diberikan setelah verifikasi berhasil</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-number">4</div>
                    <div class="timeline-content">
                        <h4>Login & Mulai</h4>
                        <p>Gunakan User ID dan password untuk mengakses sistem</p>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['mahasiswa'] }}{{ $stats['mahasiswa'] >= 100 ? '+' : '' }}</div>
                    <div class="stat-label">Mahasiswa Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['alat_lab'] }}</div>
                    <div class="stat-label">Alat Lab</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['lab_aktif'] }}</div>
                    <div class="stat-label">Lab Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('.eye-icon');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m5.882-5.882A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-4.012 4.904m-4.904-4.904a3 3 0 11-4.243-4.243M3 3l18 18" />
                        `;
                    } else {
                        input.type = 'password';
                        icon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        `;
                    }
                });
            });
        });
    </script>
</body>
</html>