<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - LAB TEKIM UAD</title>
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
            border-color: #667eea;
        }

        .input-wrapper .icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn-submit {
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

        .btn-submit:hover {
            background: #5568d3;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .info-box p {
            color: #0c5460;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
        }

        .info-box ul {
            margin-top: 10px;
            padding-left: 20px;
            color: #0c5460;
            font-size: 13px;
        }

        .info-box li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="Logo UAD">
            <h3>Lupa Password</h3>
            <p>Masukkan email Anda untuk menerima link reset password</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="info-box">
            <p><strong>ℹ️ Informasi:</strong></p>
            <ul>
                <li>Link reset password akan dikirim ke email Anda</li>
                <li>Link berlaku selama 1 jam</li>
                <li>Cek folder spam jika email tidak masuk</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Terdaftar</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                    <span class="icon">📧</span>
                </div>
                @error('email')
                    <div style="color: #c33; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Kirim Link Reset Password</button>
        </form>

        <div class="back-link">
            Ingat password Anda? <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </div>
</body>
</html>