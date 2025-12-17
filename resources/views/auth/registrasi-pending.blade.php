<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - LAB TEKIM UAD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            max-width: 500px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
        }

        .icon-wrapper {
            display: inline-block;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            padding: 25px;
            border-radius: 50%;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .icon-wrapper svg {
            width: 60px;
            height: 60px;
            color: white;
        }

        h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .email-display {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 15px 25px;
            border-radius: 12px;
            margin: 20px 0 30px;
            border-left: 4px solid #3b82f6;
        }

        .email-display p {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .email-display strong {
            color: #1e3a8a;
            font-size: 18px;
            font-weight: 700;
        }

        .steps-container {
            background: #f9fafb;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            text-align: left;
        }

        .steps-title {
            color: #374151;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            background: #667eea;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            margin-right: 15px;
        }

        .step-content {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
            padding-top: 4px;
        }

        .warning-box {
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .warning-box svg {
            width: 20px;
            height: 20px;
            color: #d97706;
            margin-right: 10px;
        }

        .warning-box span {
            color: #92400e;
            font-size: 13px;
            font-weight: 500;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }

        .resend-section p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .btn-resend {
            width: 100%;
            padding: 14px;
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-resend:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #374151;
        }

        @media (max-width: 568px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1>📧 Cek Email Anda!</h1>

        <div class="email-display">
            <p>Kami telah mengirim link verifikasi ke:</p>
            <strong>{{ session('email') }}</strong>
        </div>

        <div class="steps-container">
            <p class="steps-title">Langkah selanjutnya:</p>
            
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    Buka email Anda (cek inbox atau folder spam)
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    Klik link verifikasi dalam email
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    Dapatkan User ID Anda untuk login
                </div>
            </div>
        </div>

        <div class="warning-box">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Link verifikasi berlaku <strong>24 jam</strong></span>
        </div>

        <div class="divider"></div>

        <div class="resend-section">
            <p>Tidak menerima email?</p>
            <form action="{{ route('registrasi.resend') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">
                <button type="submit" class="btn-resend">
                    🔄 Kirim Ulang Email
                </button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            ← Kembali ke Login
        </a>
    </div>
</body>
</html>