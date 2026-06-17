<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informasi Akun - Sistem RegLab UAD</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .info-box {
            background-color: #f0f8ff;
            border-left: 4px solid #003366;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box p {
            margin: 8px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background-color: #003366;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Sistem RegLab UAD</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $nama }}</strong>,</p>
            
            <p>Akun Anda telah berhasil dibuat di Sistem RegLab (Regulasi Laboratorium) UAD. Berikut adalah informasi akun Anda:</p>
            
            <div class="info-box">
                <p><strong>📋 Informasi Login</strong></p>
                <p><strong>User ID:</strong> {{ $userId }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
            </div>
            
            <div class="warning">
                <p><strong>⚠️ Penting:</strong> Silakan ubah password Anda setelah login pertama kali untuk keamanan akun.</p>
            </div>
            
            <center>
                <a href="{{ $loginUrl }}" class="button">Masuk ke Sistem RegLab</a>
            </center>
            
            <p style="margin-top: 30px; font-size: 12px; color: #666;">
                Jika Anda tidak melakukan pendaftaran ini, silakan abaikan email ini.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem RegLab UAD. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
