<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')

    <style>
        .btn-edit {
            background-color: #ffc107;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-tambah {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-tambah:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Kelola Pengumuman</div>
                    <a href="{{ route('pengumuman.create') }}" class="btn-tambah">
                        Buat Pengumuman Baru
                    </a>
                </div>

                @if(session('success'))
                    <div style="background:#d4edda;color:#155724;padding:12px;border-radius:4px;margin:15px 0;">
                        {{ session('success') }}
                    </div>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengumuman as $i => $p)
                        <tr>
                            <td>{{ $pengumuman->firstItem() + $i }}</td>
                            <td>{{ Str::limit($p->judul, 50) }}</td>
                            <td>
                                <span style="padding:4px 10px;border-radius:4px;font-size:12px;
                                    background:{{ $p->status == 'publish' ? '#d4edda;color:#155724' : '#fff3cd;color:#856404' }}">
                                    {{ $p->status == 'publish' ? 'Publish' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('pengumuman.edit', $p) }}" style="background:#ffc107;color:white;padding:6px 12px;border-radius:4px;font-size:13px;">Edit</a>
                                    <form action="{{ route('pengumuman.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:#dc3545;color:white;padding:6px 12px;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:40px;color:#666;">Belum ada pengumuman</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top:20px;">{{ $pengumuman->links() }}</div>
            </div>
        </div>
    </div>
</body>
</html>