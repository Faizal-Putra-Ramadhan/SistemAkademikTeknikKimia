<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            color: #667eea;
            font-size: 1.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            padding: 0.5rem 1.5rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .welcome-card h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .welcome-card p {
            color: #666;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .menu-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .menu-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .menu-card h3 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .menu-card p {
            color: #666;
            font-size: 0.9rem;
        }
        .labs-section h2 {
            color: white;
            margin-bottom: 1rem;
            text-align: center;
        }
        .labs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            margin-top: 1rem;
        }
        .btn {
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 0.85rem;
            transition: opacity 0.3s;
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
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🎓 Portal Mahasiswa</h1>
        <div class="user-info">
            <span>Halo, <strong>{{ $user->Nama }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Selamat Datang di Sistem Manajemen Laboratorium</h2>
            <p>Pilih laboratorium untuk mengakses layanan peminjaman alat, ruangan, dan pengajuan penelitian.</p>
        </div>

        <div class="menu-grid">
            <a href="{{ route('mahasiswa.pengumuman') }}" class="menu-card">
                <div class="icon">📢</div>
                <h3>Pengumuman</h3>
                <p>Lihat pengumuman terbaru</p>
            </a>
        </div>

        <div class="labs-section">
            <h2>Daftar Laboratorium</h2>
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
                        <strong>Email:</strong> {{ $lab->email_lab }}
                    </div>
                    
                    <div class="lab-actions">
                        <a href="{{ route('mahasiswa.lab.detail', $lab->id) }}" class="btn btn-primary">
                            Lihat Alat
                        </a>
                        <a href="{{ route('mahasiswa.pinjam-ruangan', $lab->id) }}" class="btn btn-success">
                            Pinjam Ruangan
                        </a>
                        <a href="{{ route('mahasiswa.pinjam-alat', $lab->id) }}" class="btn btn-info">
                            Pinjam Alat
                        </a>
                        <a href="{{ route('mahasiswa.pengajuan-penelitian', $lab->id) }}" class="btn btn-warning">
                            Ajukan Penelitian
                        </a>
                    </div>
                    <a href="{{ route('mahasiswa.aktivitas', $lab->id) }}" class="btn btn-primary" style="margin-top: 0.5rem; display: block;">
                        Lihat Aktivitas Saya
                    </a>
                </div>
                @empty
                <p style="color: white; text-align: center; grid-column: 1 / -1;">
                    Belum ada laboratorium tersedia.
                </p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>