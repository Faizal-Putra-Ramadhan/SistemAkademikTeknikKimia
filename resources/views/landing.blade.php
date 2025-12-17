<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Laboratorium - Teknik UAD</title>
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
        }
        .container {
            max-width: 800px;
            padding: 2rem;
        }
        .card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .feature {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        .feature h3 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .feature p {
            color: #666;
            font-size: 0.9rem;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔬 Sistem Manajemen Laboratorium</h1>
            <p class="subtitle">Fakultas Teknik - Universitas Ahmad Dahlan</p>
            
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📦</div>
                    <h3>Peminjaman Alat</h3>
                    <p>Kelola peminjaman alat laboratorium dengan mudah</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🏢</div>
                    <h3>Booking Ruangan</h3>
                    <p>Reservasi ruangan lab untuk kegiatan praktikum</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🔬</div>
                    <h3>Pengajuan Penelitian</h3>
                    <p>Ajukan proposal penelitian di laboratorium</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📢</div>
                    <h3>Pengumuman</h3>
                    <p>Dapatkan update terbaru dari laboratorium</p>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('login') }}" class="btn btn-primary">🔐 Login</a>
                <a href="{{ route('registrasi') }}" class="btn btn-secondary">📝 Registrasi</a>
            </div>
        </div>
    </div>
</body>
</html>