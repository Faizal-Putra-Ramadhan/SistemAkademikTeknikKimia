<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Assessment - Dosen</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    
    <style>
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
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .stat-card.pending { border-left-color: #f59e0b; }
        .stat-card.approved { border-left-color: #10b981; }
        .stat-card.rejected { border-left-color: #ef4444; }
        .stat-card.total { border-left-color: #667eea; }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .ra-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            border-left: 4px solid #f59e0b;
            transition: all 0.2s;
        }
        .ra-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .ra-card.history {
            border-left-color: #6b7280;
        }
        
        .ra-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .ra-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .ra-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
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
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-menunggu_dosen { background: #fef3c7; color: #92400e; }
        .status-menunggu_safety_officer { background: #dbeafe; color: #1e40af; }
        .status-menunggu_kepala_lab { background: #e0e7ff; color: #3730a3; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        
        .risk-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
            display: inline-block;
        }
        .risk-tinggi { background: #fee2e2; color: #991b1b; }
        .risk-sedang { background: #fef3c7; color: #92400e; }
        .risk-rendah { background: #d1fae5; color: #065f46; }
        
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .section-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .section-header {
            padding: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-body {
            padding: 1.5rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <x-dosen.navbar :labs="$labs" :user="$user" />
        <x-dosen.header>Risk Assessment Management</x-dosen.header>
        
        <main>
            <div class="max-w-7xl mx-auto px-6 py-6">
                
                @if(session('success'))
                <div style="padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 6px; margin-bottom: 1.5rem; border-left: 4px solid #10b981;">
                    ✅ {{ session('success') }}
                </div>
                @endif

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card pending">
                        <div class="stat-value">{{ $riskAssessments->total() }}</div>
                        <div class="stat-label">⏳ Menunggu Review</div>
                    </div>
                    <div class="stat-card approved">
                        <div class="stat-value">
                            {{ App\Models\RiskAssessment::where('dosen_pembimbing_id', Auth::user()->id)
                                ->where('persetujuan_dosen', true)
                                ->count() }}
                        </div>
                        <div class="stat-label">✅ Disetujui</div>
                    </div>
                    <div class="stat-card rejected">
                        <div class="stat-value">
                            {{ App\Models\RiskAssessment::where('dosen_pembimbing_id', Auth::user()->id)
                                ->where('persetujuan_dosen', false)
                                ->count() }}
                        </div>
                        <div class="stat-label">❌ Ditolak</div>
                    </div>
                    <div class="stat-card total">
                        <div class="stat-value">
                            {{ App\Models\RiskAssessment::where('dosen_pembimbing_id', Auth::user()->id)->count() }}
                        </div>
                        <div class="stat-label">📊 Total Pengajuan</div>
                    </div>
                </div>

                <!-- Menunggu Persetujuan Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <span>⏳</span>
                            <span>Menunggu Persetujuan Anda</span>
                        </h2>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.5rem;">
                            Risk Assessment yang perlu direview dan disetujui
                        </p>
                    </div>
                    <div class="section-body">
                        @if($riskAssessments->count() > 0)
                            @foreach($riskAssessments as $ra)
                            <div class="ra-card">
                                <div class="ra-header">
                                    <div style="flex: 1;">
                                        <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                                        <p style="color: #6b7280; font-size: 0.9rem;">
                                            Jenis: <strong>{{ $ra->jenis_ra }}</strong>
                                        </p>
                                    </div>
                                    <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                        Menunggu Review
                                    </span>
                                </div>

                                <div class="ra-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">👤 Mahasiswa</span>
                                        <span class="meta-value">{{ $ra->nama }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">🎓 NIM</span>
                                        <span class="meta-value">{{ $ra->nim }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">🏫 Laboratorium</span>
                                        <span class="meta-value">{{ $ra->daftarLab->nama_lab }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">📅 Tanggal Diajukan</span>
                                        <span class="meta-value">{{ $ra->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div style="padding-top: 1rem; border-top: 1px solid #e5e7eb; display: flex; gap: 0.75rem;">
                                    <a href="{{ route('dosen.risk-assessment.show', $ra->id) }}" class="btn btn-primary">
                                        👁️ Review & Approve
                                    </a>
                                </div>
                            </div>
                            @endforeach

                            <!-- Pagination -->
                            <div style="margin-top: 1.5rem;">
                                {{ $riskAssessments->links() }}
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">📭</div>
                                <p style="font-size: 1.1rem; font-weight: 500;">Tidak ada Risk Assessment yang menunggu review</p>
                                <p style="margin-top: 0.5rem;">Semua pengajuan sudah diproses</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Riwayat Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <span>📚</span>
                            <span>Riwayat Risk Assessment</span>
                        </h2>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.5rem;">
                            Daftar Risk Assessment yang sudah Anda review
                        </p>
                    </div>
                    <div class="section-body">
                        @if($riwayat->count() > 0)
                            @foreach($riwayat as $ra)
                            <div class="ra-card history">
                                <div class="ra-header">
                                    <div style="flex: 1;">
                                        <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                                        <p style="color: #6b7280; font-size: 0.9rem;">
                                            {{ $ra->nama }} ({{ $ra->nim }})
                                        </p>
                                        @if($ra->kategori_resiko_dosen)
                                        <span class="risk-badge risk-{{ $ra->kategori_resiko_dosen }}">
                                            {{ ucfirst($ra->kategori_resiko_dosen) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                            {{ $ra->getStatusLabel() }}
                                        </span>
                                        @if($ra->persetujuan_dosen !== null)
                                        <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                                            @if($ra->persetujuan_dosen)
                                                <span style="color: #10b981; font-weight: 600;">✅ Anda Setujui</span>
                                            @else
                                                <span style="color: #ef4444; font-weight: 600;">❌ Anda Tolak</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
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
                                        <span class="meta-label">📅 Tanggal Review</span>
                                        <span class="meta-value">
                                            {{ $ra->tanggal_persetujuan_dosen ? $ra->tanggal_persetujuan_dosen->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">⚡ Status Saat Ini</span>
                                        <span class="meta-value">
                                            @switch($ra->status)
                                                @case('menunggu_safety_officer')
                                                    Di Safety Officer
                                                    @break
                                                @case('menunggu_kepala_lab')
                                                    Di Kepala Lab
                                                    @break
                                                @case('disetujui')
                                                    <span style="color: #10b981;">Disetujui ✅</span>
                                                    @break
                                                @case('ditolak')
                                                    <span style="color: #ef4444;">Ditolak ❌</span>
                                                    @break
                                                @default
                                                    {{ $ra->status }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                @if($ra->catatan_dosen)
                                <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 6px; border-left: 3px solid #667eea;">
                                    <strong style="color: #374151;">📝 Catatan Anda:</strong>
                                    <div style="margin-top: 0.5rem; color: #4b5563;">
                                        {{ $ra->catatan_dosen }}
                                    </div>
                                </div>
                                @endif

                                <div style="padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                    <a href="{{ route('dosen.risk-assessment.show', $ra->id) }}" class="btn btn-secondary">
                                        👁️ Lihat Detail
                                    </a>
                                </div>
                            </div>
                            @endforeach

                            <!-- Pagination -->
                            <div style="margin-top: 1.5rem;">
                                {{ $riwayat->links() }}
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">📂</div>
                                <p style="font-size: 1.1rem; font-weight: 500;">Belum ada riwayat review</p>
                                <p style="margin-top: 0.5rem;">Risk Assessment yang sudah Anda review akan muncul di sini</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>