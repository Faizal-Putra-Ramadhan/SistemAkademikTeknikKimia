<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laboratorium</title>
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
        }
        .navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            color: #667eea;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .back-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #667eea;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .header h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #666;
        }
        .labs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .lab-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .lab-card:hover {
            transform: translateY(-5px);
        }
        .lab-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        .lab-info {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .lab-info strong {
            color: #333;
        }
        .lab-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .btn {
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
            transition: opacity 0.3s;
            font-weight: 600;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🔬 Daftar Laboratorium</h1>
    </div>

    <div class="container">
        <a href="{{ route('dosen.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

        <div class="header">
            <h2>Pilih Laboratorium</h2>
            <p>Pilih laboratorium untuk melakukan peminjaman ruangan atau alat.</p>
        </div>

        <div class="labs-grid">
            @forelse($labs as $lab)
            <div class="lab-card">
                <h3>{{ $lab->Nama_Laboratorium }}</h3>
                <div class="lab-info">
                    <strong>Kepala Lab:</strong> {{ $lab->Kepala_Labolatorium }}
                </div>
                <div class="lab-info">
                    <strong>Admin:</strong> {{ $lab->Admin_Laboratorium }}
                </div>
                <div class="lab-info">
                    <strong>Safety Officer:</strong> {{ $lab->Safety_Officer }}
                </div>
                <div class="lab-info">
                    <strong>Email:</strong> {{ $lab->email_lab }}
                </div>
                
                <div class="lab-actions">
                    <a href="{{ route('dosen.pinjam-ruangan', $lab->id) }}" class="btn btn-primary">
                        📅 Pinjam Ruangan
                    </a>
                    <a href="{{ route('dosen.pinjam-alat', $lab->id) }}" class="btn btn-success">
                        🔧 Pinjam Alat
                    </a>
                </div>
            </div>
            @empty
            <p style="color: white; text-align: center; grid-column: 1 / -1; font-size: 1.2rem;">
                Belum ada laboratorium tersedia.
            </p>
            @endforelse
        </div>
    </div>
</body>
</html>