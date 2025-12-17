<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laboran - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
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
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
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
                    <div class="section-title">Daftar Laboran Laboratorium</div>
                    <a href="{{ route('tambah-laboran.create') }}" class="btn-tambah">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Tambah Laboran
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Laboratorium</th>
                            <th>Nama Laboran</th>
                            <th>UserID</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_laborans as $index => $laboran)
                            <tr>
                                <td>{{ $daftar_laborans->firstItem() + $index }}</td>
                                <td>{{ $laboran->Laboratorium }}</td>
                                <td>{{ $laboran->Nama_Laboran }}</td>
                                <td>{{ $laboran->UserID }}</td>
                                <td>{{ $laboran->Phone }}</td>
                                <td>{{ $laboran->Email }}</td>
                                <td>
                                    <span class="role-badge">{{ $laboran->Role_User }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('tambah-laboran.edit', $laboran->id) }}" class="btn-edit">Edit</a>
                                        <form action="{{ route('tambah-laboran.destroy', $laboran->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center;">Belum ada data laboran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $daftar_laborans->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
