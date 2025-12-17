<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Saya - {{ $lab->Nama_Laboratorium }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
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
            background: #6c757d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
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
        .status-dikembalikan {
            background: #d1ecf1;
            color: #0c5460;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 10px;
            height: 10px;
            background: #667eea;
            border-radius: 50%;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -1.25rem;
            top: 0.75rem;
            width: 2px;
            height: calc(100% - 0.5rem);
            background: #ddd;
        }
        .timeline-item:last-child::after {
            display: none;
        }
        .timeline-time {
            color: #999;
            font-size: 0.85rem;
        }
        .timeline-content {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 Aktivitas Saya - {{ $lab->Nama_Laboratorium }}</h1>
    </div>

    <div class="container">
        <a href="{{ route('mahasiswa.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Peminjaman Ruangan -->
        <div class="section">
            <h2>📅 Peminjaman Ruangan</h2>
            @if($peminjamanRuangan->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanRuangan as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                        <td>{{ $item->keperluan }}</td>
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
            <div class="empty-state">Belum ada peminjaman ruangan.</div>
            @endif
        </div>

        <!-- Peminjaman Alat -->
        <div class="section">
            <h2>🔧 Peminjaman Alat</h2>
            @if($peminjamanAlat->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nama Alat</th>
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
            <div class="empty-state">Belum ada peminjaman alat.</div>
            @endif
        </div>

        <!-- Pengajuan Penelitian -->
        <div class="section">
            <h2>🔬 Pengajuan Penelitian</h2>
            @if($pengajuanPenelitian->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanPenelitian as $item)
                    <tr>
                        <td>{{ $item->judul_penelitian }}</td>
                        <td>{{ $item->dosen_pembimbing }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        </td>
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
            <div class="empty-state">Belum ada pengajuan penelitian.</div>
            @endif
        </div>

        <!-- Timeline Aktivitas -->
        <div class="section">
            <h2>📜 Riwayat Aktivitas</h2>
            @if($aktivitas->count() > 0)
            <div class="timeline">
                @foreach($aktivitas as $item)
                <div class="timeline-item">
                    <div class="timeline-time">
                        {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                    </div>
                    <div class="timeline-content">
                        <strong>{{ $item->jenis_aktivitas }}</strong>
                        <p style="margin-top: 0.5rem; color: #666;">{{ $item->keterangan }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">Belum ada aktivitas tercatat.</div>
            @endif
        </div>
    </div>
</body>
</html>