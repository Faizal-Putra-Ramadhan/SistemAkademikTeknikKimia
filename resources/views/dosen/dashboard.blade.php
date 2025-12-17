<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')

    <style>
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

  <div class="min-h-full">

    <x-dosen.navbar :labs="$labs" :user="$user" />

    <x-dosen.header>Dashboard</x-dosen.header>

    <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="section">
    <h2>📋 Risk Assessment Terbaru</h2>

    @if($riskAssessments->count())
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Mahasiswa</th>
                <th>Lab</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riskAssessments as $ra)
            <tr>
                <td>{{ $ra->topik_judul }}</td>
                <td>{{ $ra->nama }}</td>
                <td>{{ $ra->daftarLab->Nama_Laboratorium ?? '-' }}</td>
                <td>
                    @php
                        $statusClass = match($ra->status) {
                            'disetujui' => 'status-disetujui',
                            'ditolak' => 'status-ditolak',
                            default => 'status-menunggu'
                        };
                    @endphp
                    <span class="status {{ $statusClass }}">
                        {{ $ra->getStatusLabel() }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('dosen.risk-assessment.show', $ra->id) }}"
                       class="btn btn-primary">
                        Lihat
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">Belum ada Risk Assessment</div>
    @endif
</div>

<div class="section">
    <h2>📅 Peminjaman Ruangan</h2>

    @if($peminjamanRuangan->count())
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
        <div class="empty-state">Belum ada peminjaman ruangan</div>
    @endif
</div>

    <div class="section">
    <h2>🔧 Peminjaman Alat</h2>

    @if($peminjamanAlat->count())
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
                <td>{{ $item->alatLab->nama_alat ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    {{ $item->tanggal_kembali
                        ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y')
                        : '-' }}
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
        <div class="empty-state">Belum ada peminjaman alat</div>
    @endif
</div>

    </div>
  </main>

  </div>
  

  {{-- Pindahkan ke paling bawah, SETELAH konten --}}
  {{-- Pindahkan semua skrip ke paling bawah, SEBELUM </body> --}}
  
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

</body>
</html>
