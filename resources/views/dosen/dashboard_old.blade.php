<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
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
        }
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            font-size: 3rem;
        }
        .stat-info h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-info p {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .menu-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s;
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .menu-card .icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        .menu-card h3 {
            color: #333;
        }
        .section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .section h2 {
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }
        .status-disetujui {
            background: #d4edda;
            color: #155724;
        }
        .status-ditolak {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>👨‍🏫 Portal Dosen</h1>
        <div class="user-info">
            <span>Halo, <strong>{{ $user->Nama }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3>Total Pengajuan</h3>
                    <p>{{ $totalPengajuan }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3>Menunggu Persetujuan</h3>
                    <p>{{ $menungguPersetujuan }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3>Disetujui</h3>
                    <p>{{ $disetujui }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <h3>Ditolak</h3>
                    <p>{{ $ditolak }}</p>
                </div>
            </div>
        </div>

        <!-- Menu Utama -->
        <div class="menu-grid">
            <a href="{{ route('dosen.lab') }}" class="menu-card">
                <div class="icon">🔬</div>
                <h3>Pinjam Ruangan/Alat</h3>
            </a>
            <a href="{{ route('dosen.pengumuman.index') }}" class="menu-card">
                <div class="icon">📢</div>
                <h3>Kelola Pengumuman</h3>
            </a>
            <a href="{{ route('dosen.profil') }}" class="menu-card">
                <div class="icon">👤</div>
                <h3>Profil Saya</h3>
            </a>
        </div>

        <!-- Pengajuan Penelitian -->
        <div class="section">
            <h2>🔬 Pengajuan Penelitian yang Perlu Disetujui</h2>
            @if($pengajuanPenelitian->where('status', 'menunggu')->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul Penelitian</th>
                        <th>Lab</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanPenelitian->where('status', 'menunggu') as $item)
                    <tr>
                        <td>{{ $item->user_nama }}</td>
                        <td>{{ $item->judul_penelitian }}</td>
                        <td>{{ $item->daftarLab->Nama_Laboratorium }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="status status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('dosen.pengajuan.detail', $item->id) }}" class="btn btn-primary">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                Tidak ada pengajuan penelitian yang perlu disetujui.
            </div>
            @endif
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="section">
            <h2>📜 Riwayat Pengajuan Penelitian</h2>
            @if($pengajuanPenelitian->whereIn('status', ['disetujui', 'ditolak'])->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul Penelitian</th>
                        <th>Lab</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanPenelitian->whereIn('status', ['disetujui', 'ditolak']) as $item)
                    <tr>
                        <td>{{ $item->user_nama }}</td>
                        <td>{{ $item->judul_penelitian }}</td>
                        <td>{{ $item->daftarLab->Nama_Laboratorium }}</td>
                        <td>
                            <span class="status status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('dosen.pengajuan.detail', $item->id) }}" class="btn btn-primary">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                Belum ada riwayat pengajuan penelitian.
            </div>
            @endif
        </div>

        <!-- Peminjaman Saya -->
        <div class="section">
            <h2>📅 Peminjaman Ruangan Saya</h2>
            @if($peminjamanRuangan->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Lab</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanRuangan as $item)
                    <tr>
                        <td>{{ $item->daftarLab->Nama_Laboratorium }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                        <td>
                            <span class="status status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                Belum ada peminjaman ruangan.
            </div>
            @endif
        </div>

        <!-- Peminjaman Alat Saya -->
        <div class="section">
            <h2>🔧 Peminjaman Alat Saya</h2>
            @if($peminjamanAlat->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanAlat as $item)
                    <tr>
                        <td>{{ $item->alatLab->nama_alat }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="status status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                Belum ada peminjaman alat.
            </div>
            @endif
        </div>
    </div>
</body>
</html>