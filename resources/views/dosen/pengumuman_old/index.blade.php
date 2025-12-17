<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            color: #667eea;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .back-btn, .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .back-btn {
            background: #6c757d;
            color: white;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn:hover, .back-btn:hover {
            opacity: 0.8;
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
        .pengumuman-grid {
            display: grid;
            gap: 1rem;
        }
        .pengumuman-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .pengumuman-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .pengumuman-title {
            flex: 1;
        }
        .pengumuman-title h3 {
            color: #333;
            margin-bottom: 0.25rem;
        }
        .pengumuman-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: #666;
        }
        .pengumuman-content {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .pengumuman-actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-publish {
            background: #d4edda;
            color: #155724;
        }
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            text-align: center;
        }
        .empty-state p {
            color: #666;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📢 Kelola Pengumuman</h1>
    </div>

    <div class="container">
        <div class="header">
            <a href="{{ route('dosen.dashboard') }}" class="back-btn">← Kembali</a>
            <a href="{{ route('dosen.pengumuman.create') }}" class="btn btn-primary">+ Buat Pengumuman</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($pengumuman->count() > 0)
        <div class="pengumuman-grid">
            @foreach($pengumuman as $item)
            <div class="pengumuman-card">
                <div class="pengumuman-header">
                    <div class="pengumuman-title">
                        <h3>{{ $item->judul }}</h3>
                        <div class="pengumuman-meta">
                            <span>👤 {{ $item->author }}</span>
                            <span>📅 {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</span>
                            <span class="status-badge status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pengumuman-content">
                    {{ Str::limit($item->isi, 200) }}
                </div>
                
                @if($item->author === $user->Nama)
                <div class="pengumuman-actions">
                    <a href="{{ route('dosen.pengumuman.edit', $item->id) }}" class="btn-sm btn-warning">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('dosen.pengumuman.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>Belum ada pengumuman.</p>
            <a href="{{ route('dosen.pengumuman.create') }}" class="btn btn-primary">Buat Pengumuman Pertama</a>
        </div>
        @endif
    </div>
</body>
</html>