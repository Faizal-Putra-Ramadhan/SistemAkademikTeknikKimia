<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Risk Assessment Saya</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        .ra-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .ra-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .ra-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .ra-title {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-draft { background: #e5e7eb; color: #4b5563; }
        .status-menunggu_dosen { background: #fef3c7; color: #92400e; }
        .status-menunggu_safety_officer { background: #dbeafe; color: #1e40af; }
        .status-menunggu_kepala_lab { background: #e0e7ff; color: #3730a3; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        
        .ra-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        .meta-label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .meta-value {
            color: #374151;
            font-weight: 500;
        }
        .ra-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-create {
            background: #667eea;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1.5rem;
            transition: background 0.2s;
        }
        .btn-create:hover {
            background: #5568d3;
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
            margin-bottom: 1.5rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">
    <x-mahasiswa.navbar :labs="$labs" :user="$user"></x-mahasiswa.navbar>
    <x-mahasiswa.header>Daftar Risk Assessment Saya</x-mahasiswa.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
            @endif

            <a href="{{ route('mahasiswa.risk-assessment.create', $labs->first()->id) }}" class="btn-create">
                ➕ Buat Risk Assessment Baru
            </a>

            @if($riskAssessments->count() > 0)
                @foreach($riskAssessments as $ra)
                <div class="ra-card">
                    <div class="ra-header">
                        <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                        <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                            {{ $ra->getStatusLabel() }}
                        </span>
                    </div>

                    <div class="ra-meta">
                        <div class="meta-item">
                            <span class="meta-label">🏫 Laboratorium</span>
                            <span class="meta-value">{{ $ra->daftarLab->nama_lab }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">📋 Jenis</span>
                            <span class="meta-value">{{ $ra->jenis_ra }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">👨‍🏫 Dosen Pembimbing</span>
                            <span class="meta-value">{{ $ra->dosen_pembimbing_nama }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">📅 Tanggal Dibuat</span>
                            <span class="meta-value">{{ $ra->created_at->format('d M Y') }}</span>
                        </div>
                        @if($ra->kategori_resiko_dosen)
                        <div class="meta-item">
                            <span class="meta-label">⚠️ Kategori Resiko</span>
                            <span class="meta-value">{{ $ra->getKategoriResikoLabel() }}</span>
                        </div>
                        @endif
                    </div>

                    @if($ra->catatan_dosen || $ra->catatan_safety_officer || $ra->catatan_kepala_lab)
                    <div style="margin-top: 1rem; padding: 1rem; background: #fef3c7; border-radius: 6px; border-left: 3px solid #f59e0b;">
                        <strong style="color: #92400e;">📝 Catatan:</strong>
                        <div style="margin-top: 0.5rem; color: #78350f;">
                            @if($ra->catatan_dosen)
                                <p><strong>Dosen:</strong> {{ $ra->catatan_dosen }}</p>
                            @endif
                            @if($ra->catatan_safety_officer)
                                <p><strong>Safety Officer:</strong> {{ $ra->catatan_safety_officer }}</p>
                            @endif
                            @if($ra->catatan_kepala_lab)
                                <p><strong>Kepala Lab:</strong> {{ $ra->catatan_kepala_lab }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($ra->jadwal_wawancara)
                    <div style="margin-top: 1rem; padding: 1rem; background: #dbeafe; border-radius: 6px; border-left: 3px solid #3b82f6;">
                        <strong style="color: #1e40af;">📅 Jadwal Wawancara dengan Safety Officer:</strong>
                        <div style="margin-top: 0.5rem; color: #1e3a8a;">
                            {{ \Carbon\Carbon::parse($ra->jadwal_wawancara)->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                    @endif

                    <div class="ra-actions">
                        <a href="{{ route('mahasiswa.risk-assessment.show', $ra->id) }}" class="btn btn-primary">
                            👁️ Lihat Detail
                        </a>
                        
                        @if($ra->status === 'draft')
                        <a href="{{ route('mahasiswa.risk-assessment.edit', $ra->id) }}" class="btn btn-secondary">
                            ✏️ Edit
                        </a>
                        @endif

                        @if($ra->status === 'disetujui')
                        <!-- <a href="{{ route('mahasiswa.risk-assessment.download-pdf', $ra->id) }}" class="btn btn-success">
                            📄 Download PDF
                        </a> -->
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div style="margin-top: 2rem;">
                    {{ $riskAssessments->links() }}
                </div>
            @else
            <div class="empty-state">
                <p>Anda belum memiliki Risk Assessment.</p>
                <a href="{{ route('mahasiswa.risk-assessment.create', $labs->first()->id) }}" class="btn-create">
                    ➕ Buat Risk Assessment Pertama
                </a>
            </div>
            @endif

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>