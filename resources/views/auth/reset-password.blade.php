<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LAB TEKIM UAD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8d8ea 0%, #F5F7FC 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
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
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-container p {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.6;
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
            border-color: #1e3a8a;
        }

        .input-wrapper .icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
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

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #1e3a8a;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .back-link a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .info-box p {
            color: #856404;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="Logo UAD">
            <h3>Reset Password</h3>
            <p>Buat password baru untuk akun Anda</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="info-box">
            <p><strong> Perhatian:</strong><br>
            Password harus minimal 6 karakter dan kombinasi huruf serta angka untuk keamanan akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" value="{{ request()->email ?? old('email') }}" required autofocus>
                    <span class="icon"></span>
                </div>
                @error('email')
                    <div style="color: #c33; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required oninput="checkPasswordStrength()">
                    <button type="button" id="togglePassword" class="toggle-password" title="Lihat/Sembunyi Password">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div id="passwordStrength" class="password-strength"></div>
                @error('password')
                    <div style="color: #c33; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" required>
                    <button type="button" id="togglePasswordConfirmation" class="toggle-password" title="Lihat/Sembunyi Password">
                        <svg id="eyeIconConfirmation" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <div style="color: #c33; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>

        <div class="back-link">
            Kembali ke <a href="{{ route('login') }}">Halaman Login</a>
        </div>
    </div>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;
            
            if (strength <= 2) {
                strengthDiv.innerHTML = '<span class="strength-weak"> Password lemah</span>';
            } else if (strength <= 3) {
                strengthDiv.innerHTML = '<span class="strength-medium"> Password sedang</span>';
            } else {
                strengthDiv.innerHTML = '<span class="strength-strong"> Password kuat</span>';
            }
        }

        // Validasi konfirmasi password
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
        });

        // Toggle tampil/sembunyi password (ikon mata)
        const eyeOpenHtml = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
        const eyeClosedHtml = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m5.882-5.882A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-4.012 4.904m-4.904-4.904a3 3 0 11-4.243-4.243M3 3l18 18" />
        `;

        function setupTogglePassword(toggleId, inputId, iconId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            toggle.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.innerHTML = type === 'password' ? eyeOpenHtml : eyeClosedHtml;
            });
        }

        setupTogglePassword('togglePassword', 'password', 'eyeIcon');
        setupTogglePassword('togglePasswordConfirmation', 'password_confirmation', 'eyeIconConfirmation');
    </script>
</body>
</html>