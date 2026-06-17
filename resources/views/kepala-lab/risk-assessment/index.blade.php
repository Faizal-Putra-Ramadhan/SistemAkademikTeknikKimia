@extends('layouts.app')

@section('title', 'Risk Assessment')
@section('page-title', 'Risk Assessment')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .ra-card {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #0d6efd;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ra-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        color: #0d6efd;
        border-bottom-color: #0d6efd;
    }
    .tab:hover {
        color: #0d6efd;
    }
    .empty-state {
        padding: 3rem;
        text-align: center;
    }
    .empty-state p {
        color: #666;
        font-size: 1.1rem;
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
@endpush

@section('content')
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-info">
                <p>⏳ Menunggu Review</p>
                <h3>{{ $riskAssessments->total() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-info">
                <p>✅ Disetujui Bulan Ini</p>
                <h3>{{ App\Models\RiskAssessment::where('status', 'disetujui')->whereMonth('tanggal_persetujuan_kepala_lab', now()->month)->count() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-info">
                <p>❌ Ditolak Bulan Ini</p>
                <h3>{{ App\Models\RiskAssessment::where('status', 'ditolak')->whereMonth('updated_at', now()->month)->count() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            </div>
            <div class="stat-info">
                <p>📊 Total Tahun Ini</p>
                <h3>{{ App\Models\RiskAssessment::whereYear('created_at', now()->year)->count() }}</h3>
            </div>
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
            <div class="card ra-card">
                <div class="ra-header">
                    <div style="flex: 1;">
                        <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                            {{ $ra->user->Nama }} ({{ $ra->nim }})
                        </p>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                        <span class="badge badge-info">
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
                        <span class="meta-value">{{ $ra->daftarLab->Nama_Laboratorium }}</span>
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
                    <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm">
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
        <div class="card empty-state">
            <p>Tidak ada Risk Assessment yang menunggu review.</p>
        </div>
        @endif
    </div>

    <!-- Tab Content: Riwayat -->
    <div id="tab-riwayat" class="tab-content" style="display: none;">
        @if($riwayat->count() > 0)
            @foreach($riwayat as $ra)
            <div class="card ra-card">
                <div class="ra-header">
                    <div style="flex: 1;">
                        <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                            {{ $ra->user->Nama }} ({{ $ra->nim }})
                        </p>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                        <span class="badge {{ $ra->status == 'disetujui' ? 'badge-success' : ($ra->status == 'ditolak' ? 'badge-danger' : 'badge-secondary') }}">
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
                        <span class="meta-value">{{ $ra->daftarLab->Nama_Laboratorium }}</span>
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
                <div style="margin-top: 1rem; padding: 1rem; background: #f3f4f6; border-radius: 6px; border-left: 3px solid #0d6efd;">
                    <strong style="color: #374151;">📝 Catatan Anda:</strong>
                    <div style="margin-top: 0.5rem; color: #4b5563;">
                        {{ $ra->catatan_kepala_lab }}
                    </div>
                </div>
                @endif

                <div class="ra-actions">
                    <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm">
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
        <div class="card empty-state">
            <p>Belum ada riwayat review.</p>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.getElementById('tab-' + tabName).style.display = 'block';
    event.target.classList.add('active');
}
</script>
@endpush