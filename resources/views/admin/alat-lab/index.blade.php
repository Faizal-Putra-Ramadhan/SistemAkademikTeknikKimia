<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Alat Lab - Admin</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Kelola Alat / Aset Laboratorium</div>
                    <a href="{{ route('admin.alat-lab.create') }}" class="btn-tambah">
                        + Tambah Alat Baru
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
                            <th>Foto</th>
                            <th>Nama Alat</th>
                            <th>Laboratorium</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alats as $i => $alat)
                        <tr>
                            <td>{{ $alats->firstItem() + $i }}</td>
                            <td>
                                @if($alat->foto)
                                    <img src="{{ asset('storage/' . $alat->foto) }}" 
                                         style="width:60px;height:60px;object-fit:cover;border-radius:8px;"
                                         alt="{{ $alat->nama_alat }}">
                                @else
                                    <div style="width:60px;height:60px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999;font-size:12px;">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $alat->nama_alat }}</strong></td>
                            <td>{{ $alat->daftarLab->Nama_Laboratorium }}</td>
                            <td>
                                <span style="padding:5px 10px;background:#{{ $alat->jumlah_tersedia > 0 ? 'd4edda;color:#155724' : 'f8d7da;color:#721c24' }};border-radius:4px;">
                                    {{ $alat->jumlah_tersedia }}
                                </span>
                            </td>
                            <td>{{ Str::limit($alat->deskripsi, 80) }}</td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('admin.alat-lab.edit', $alat) }}" 
                                       style="background:#ffc107;color:white;padding:6px 12px;border-radius:4px;font-size:13px;text-decoration:none;">
                                       Edit
                                    </a>
                                    <form action="{{ route('admin.alat-lab.destroy', $alat) }}" method="POST" 
                                          onsubmit="return confirm('Yakin hapus alat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                style="background:#dc3545;color:white;padding:6px 12px;border:none;border-radius:4px;font-size:13px;cursor:pointer;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:50px;color:#666;">
                                Belum ada alat laboratorium. 
                                <a href="{{ route('admin.alat-lab.create') }}">Tambah sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top:20px;">
                    {{ $alats->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>