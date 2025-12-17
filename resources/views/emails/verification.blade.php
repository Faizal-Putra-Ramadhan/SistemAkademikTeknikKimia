{{-- resources/views/emails/verification.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333;
            margin-top: 0;
        }
        .email-body p {
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 15px 40px;
            background: #667eea;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .verify-button:hover {
            background: #5568d3;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎓 Sistem RegLab UAD</h1>
            <p>Verifikasi Email Registrasi</p>
        </div>
        
        <div class="email-body">
            <h2>Halo, {{ $nama }}! 👋</h2>
            
            <p>Terima kasih telah mendaftar di Sistem Registrasi Laboratorium Universitas Ahmad Dahlan.</p>
            
            <p>Untuk menyelesaikan proses registrasi Anda sebagai <strong>Mahasiswa</strong>, silakan verifikasi email Anda dengan klik tombol di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✅ Verifikasi Email Saya
                </a>
            </div>
            
            <div class="info-box">
                <p><strong>⚠️ Penting:</strong></p>
                <p>• Link verifikasi ini berlaku selama <strong>24 jam</strong></p>
                <p>• Setelah verifikasi berhasil, Anda akan mendapatkan User ID untuk login</p>
                <p>• Jika Anda tidak merasa mendaftar, abaikan email ini</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #999;">
                Jika tombol tidak berfungsi, copy dan paste link berikut ke browser Anda:<br>
                <a href="{{ $verificationUrl }}" style="color: #667eea; word-break: break-all;">{{ $verificationUrl }}</a>
            </p>
        </div>
        
        <div class="email-footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; 2024 Universitas Ahmad Dahlan. All rights reserved.</p>
        </div>
    </div>
</body>
</html>