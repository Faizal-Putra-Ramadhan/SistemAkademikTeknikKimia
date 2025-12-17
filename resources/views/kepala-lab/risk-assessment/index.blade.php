<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Assessment - Kepala Laboratorium</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

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
        }
        .stat-card.menunggu { border-left-color: #fbbf24; }
        .stat-card.disetujui { border-left-color: #10b981; }
        .stat-card.ditolak { border-left-color: #ef4444; }
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
            font-size: 1.15rem;
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
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .tab:hover {
            color: #667eea;
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
        .priority-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .priority-tinggi { background: #fee2e2; color: #991b1b; }
        .priority-sedang { background: #fef3c7; color: #92400e; }
        .priority-rendah { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">

    <x-kepala-lab.navbar :labs="$labs" :user="$user" />
    <x-kepala-lab.header>Dashboard</x-kepala-lab.header>
    <!-- Navbar akan disesuaikan dengan navbar kepala lab Anda -->
    

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card menunggu">
                    <div class="stat-value">{{ $riskAssessments->total() }}</div>
                    <div class="stat-label">⏳ Menunggu Review</div>
                </div>
                <div class="stat-card disetujui">
                    <div class="stat-value">
                        {{ App\Models\RiskAssessment::where('status', 'disetujui')
                            ->whereMonth('tanggal_persetujuan_kepala_lab', now()->month)
                            ->count() }}
                    </div>
                    <div class="stat-label">✅ Disetujui Bulan Ini</div>
                </div>
                <div class="stat-card ditolak">
                    <div class="stat-value">
                        {{ App\Models\RiskAssessment::where('status', 'ditolak')
                            ->whereMonth('updated_at', now()->month)
                            ->count() }}
                    </div>
                    <div class="stat-label">❌ Ditolak Bulan Ini</div>
                </div>
                <div class="stat-card total">
                    <div class="stat-value">
                        {{ App\Models\RiskAssessment::whereYear('created_at', now()->year)->count() }}
                    </div>
                    <div class="stat-label">📊 Total Tahun Ini</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="showTab('menunggu')">
                    Menunggu Review ({{ $riskAssessments->total() }})
                </button>
                <button class="tab" onclick="showTab('riwayat')">
                    Riwayat
                </button>
            </div>

            <!-- Tab Content: Menunggu -->
            <div id="tab-menunggu" class="tab-content">
                @if($riskAssessments->count() > 0)
                    @foreach($riskAssessments as $ra)
                    <div class="ra-card">
                        <div class="ra-header">
                            <div style="flex: 1;">
                                <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                                    {{ $ra->user->Nama }} ({{ $ra->nim }})
                                </p>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                                <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                    {{ $ra->getStatusLabel() }}
                                </span>
                                @if($ra->kategori_resiko_dosen)
                                <span class="priority-badge priority-{{ $ra->kategori_resiko_dosen }}">
                                    {{ $ra->getKategoriResikoLabel() }}
                                </span>
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
                                <span class="meta-label">👨‍🏫 Dosen Pembimbing</span>
                                <span class="meta-value">{{ $ra->dosen_pembimbing_nama }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">🛡️ Safety Officer</span>
                                <span class="meta-value">{{ $ra->safety_officer_nama ?? '-' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">📅 Tanggal Diajukan</span>
                                <span class="meta-value">{{ $ra->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if($ra->catatan_safety_officer)
                        <div style="margin-top: 1rem; padding: 1rem; background: #dbeafe; border-radius: 6px; border-left: 3px solid #3b82f6;">
                            <strong style="color: #1e40af;">📝 Catatan Safety Officer:</strong>
                            <div style="margin-top: 0.5rem; color: #1e3a8a;">
                                {{ $ra->catatan_safety_officer }}
                            </div>
                        </div>
                        @endif

                        <div class="ra-actions">
                            <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary">
                                👁️ Review & Approve
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div style="margin-top: 2rem;">
                        {{ $riskAssessments->links() }}
                    </div>
                @else
                <div class="empty-state">
                    <p>Tidak ada Risk Assessment yang menunggu review.</p>
                </div>
                @endif
            </div>

            <!-- Tab Content: Riwayat -->
            <div id="tab-riwayat" class="tab-content" style="display: none;">
                @if($riwayat->count() > 0)
                    @foreach($riwayat as $ra)
                    <div class="ra-card">
                        <div class="ra-header">
                            <div style="flex: 1;">
                                <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                                    {{ $ra->user->Nama }} ({{ $ra->nim }})
                                </p>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                                <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                    {{ $ra->getStatusLabel() }}
                                </span>
                                @if($ra->kategori_resiko_dosen)
                                <span class="priority-badge priority-{{ $ra->kategori_resiko_dosen }}">
                                    {{ $ra->getKategoriResikoLabel() }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="ra-meta">
                            <div class="meta-item">
                                <span class="meta-label">🏫 Laboratorium</span>
                                <span class="meta-value">{{ $ra->daftarLab->nama_lab }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">👨‍🏫 Dosen Pembimbing</span>
                                <span class="meta-value">{{ $ra->dosen_pembimbing_nama }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">📅 Tanggal Keputusan</span>
                                <span class="meta-value">
                                    {{ $ra->tanggal_persetujuan_kepala_lab ? $ra->tanggal_persetujuan_kepala_lab->format('d M Y') : '-' }}
                                </span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">✅ Keputusan</span>
                                <span class="meta-value">
                                    @if($ra->persetujuan_kepala_lab === true)
                                        <span style="color: #10b981;">Disetujui</span>
                                    @elseif($ra->persetujuan_kepala_lab === false)
                                        <span style="color: #ef4444;">Ditolak</span>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($ra->catatan_kepala_lab)
                        <div style="margin-top: 1rem; padding: 1rem; background: #f3f4f6; border-radius: 6px; border-left: 3px solid #667eea;">
                            <strong style="color: #374151;">📝 Catatan Anda:</strong>
                            <div style="margin-top: 0.5rem; color: #4b5563;">
                                {{ $ra->catatan_kepala_lab }}
                            </div>
                        </div>
                        @endif

                        <div class="ra-actions">
                            <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary">
                                👁️ Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div style="margin-top: 2rem;">
                        {{ $riwayat->links() }}
                    </div>
                @else
                <div class="empty-state">
                    <p>Belum ada riwayat review.</p>
                </div>
                @endif
            </div>

        </div>
    </main>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('tab-' + tabName).style.display = 'block';
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>