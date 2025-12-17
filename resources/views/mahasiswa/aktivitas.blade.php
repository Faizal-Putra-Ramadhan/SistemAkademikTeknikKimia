<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    @vite('resources/css/app.css')
     @vite('resources/js/app.js')

     <style>
      .back-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
        }
      .pengumuman-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }
        .pengumuman-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .pengumuman-header h2 {
            color: #333;
            margin: 0;
        }
        .pengumuman-date {
            color: #999;
            font-size: 0.85rem;
        }
        .pengumuman-author {
            color: #667eea;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .pengumuman-content {
            color: #666;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .empty-state p {
            color: #666;
            font-size: 1.1rem;
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

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <div class="min-h-full">
        <x-mahasiswa.navbar :labs="$labs" :user="$user"></x-mahasiswa.navbar>

        <x-mahasiswa.header>Riwayat Aktivitas</x-mahasiswa.header>
    </div>

    <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
       

        
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

        <!-- Risk Assessment -->
<div class="section">
    <h2>⚠️ Risk Assessment</h2>

    @if($riskAssessments->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Judul / Topik</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riskAssessments as $ra)
            <tr>
                <td>{{ $ra->topik_judul }}</td>
                <td>{{ $ra->jenis_ra }}</td>
                <td>
                    @php
                        $statusClass = match($ra->status) {
                            'disetujui' => 'status-disetujui',
                            'ditolak' => 'status-ditolak',
                            default => 'status-menunggu',
                        };
                    @endphp

                    <span class="status {{ $statusClass }}">
                        {{ $ra->getStatusLabel() }}
                    </span>
                </td>
                <td>{{ $ra->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">
            Belum ada pengajuan Risk Assessment.
        </div>
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
  </main>
</body>
</html>