<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laboratorium - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')

    <style>
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
         .btn-edit {
            background-color: #ffc107;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
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
                    <div class="section-title">Daftar Laboratorium</div>
                    <a href="{{ route('daftar-lab.create') }}" class="btn-tambah">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Tambah Laboratorium
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
                            <th>Nama Laboratorium</th>
                            <th>Kepala Lab</th>
                            <th>Admin Lab</th>
                            <th>Safety Officer</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_labs as $i => $lab)
                        <tr>
                            <td>{{ $daftar_labs->firstItem() + $i }}</td>
                            <td>{{ $lab->Nama_Laboratorium }}</td>
                            <td>{{ $lab->Kepala_Labolatorium }}</td>
                            <td>{{ $lab->Admin_Laboratorium }}</td>
                            <td>{{ $lab->Safety_Officer }}</td>
                            <td>{{ $lab->email_lab }}</td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('daftar-lab.edit', $lab) }}" style="background:#ffc107;color:white;padding:6px 12px;border-radius:4px;font-size:13px; " class="btn-edit">Edit</a>
                                    <form action="{{ route('daftar-lab.destroy', $lab) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:#dc3545;color:white;padding:6px 12px;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:30px;color:#666;">Belum ada data laboratorium</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top:20px;">{{ $daftar_labs->links() }}</div>
            </div>
        </div>
    </div>
</body>
</html>