<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Penelitian</title>
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
            max-width: 900px;
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
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .card h2 {
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }
        .info-grid {
            display: grid;
            gap: 1rem;
        }
        .info-item {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .deskripsi-box {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            margin-top: 0.5rem;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
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
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🔬 Detail Pengajuan Penelitian</h1>
    </div>

    <div class="container">
        <a href="{{ route('dosen.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="card">
            <h2>Informasi Pengajuan</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status status-{{ $pengajuan->status }}">
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nama Mahasiswa:</div>
                    <div class="info-value">{{ $pengajuan->user_nama }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Judul Penelitian:</div>
                    <div class="info-value"><strong>{{ $pengajuan->judul_penelitian }}</strong></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Laboratorium:</div>
                    <div class="info-value">{{ $pengajuan->daftarLab->Nama_Laboratorium }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Dosen Pembimbing:</div>
                    <div class="info-value">{{ $pengajuan->dosen_pembimbing }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tanggal Mulai:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d F Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tanggal Selesai:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Durasi Penelitian:</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_selesai)) }} hari
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Diajukan pada:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y, H:i') }}</div>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <div class="info-label" style="margin-bottom: 0.5rem;">Deskripsi Penelitian:</div>
                <div class="deskripsi-box">{{ $pengajuan->deskripsi }}</div>
            </div>

            @if($pengajuan->status === 'menunggu')
            <div class="action-buttons">
                <form action="{{ route('dosen.pengajuan.setujui', $pengajuan->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan penelitian ini?')">
                        ✓ Setujui Pengajuan
                    </button>
                </form>
                
                <form action="{{ route('dosen.pengajuan.tolak', $pengajuan->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan penelitian ini?')">
                        ✗ Tolak Pengajuan
                    </button>
                </form>
            </div>
            @else
            <div class="action-buttons">
                <button class="btn" disabled>
                    Pengajuan sudah {{ $pengajuan->status }}
                </button>
            </div>
            @endif
        </div>
    </div>
</body>
</html>