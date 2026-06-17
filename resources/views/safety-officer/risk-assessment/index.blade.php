@extends('layouts.app')
@section('title', 'Review Risk Assessment')
@section('page-title', 'Review Risk Assessment')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== Review RA Page ===== */
    .ra-page { max-width: 1200px; margin: 0 auto; }

    /* Alert */
    .ra-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border-radius: 10px;
        margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
    }
    .ra-alert.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .ra-alert i { font-size: 16px; flex-shrink: 0; }

    /* Header */
    .ra-header { margin-bottom: 24px; }
    .ra-header h1 { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .ra-header p  { font-size: 14px; color: #6b7280; }

    /* Tabs */
    .ra-tabs {
        display: flex; gap: 4px;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 20px;
    }
    .ra-tab {
        padding: 10px 20px;
        font-size: 13.5px; font-weight: 600;
        color: #6b7280;
        border: none; background: none;
        cursor: pointer;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -2px;
        transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .ra-tab:hover { color: #374151; }
    .ra-tab.active { color: #059669; border-bottom-color: #059669; }
    .ra-tab-count {
        background: #f3f4f6; color: #6b7280;
        font-size: 11px; font-weight: 700;
        padding: 2px 8px; border-radius: 10px;
    }
    .ra-tab.active .ra-tab-count { background: #d1fae5; color: #065f46; }

    /* Card container */
    .ra-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    /* List items */
    .ra-item {
        display: block;
        padding: 18px 24px;
        border-bottom: 1px solid #f3f4f6;
        text-decoration: none;
        transition: background 0.15s;
    }
    .ra-item:last-child { border-bottom: none; }
    .ra-item:hover { background: #f9fafb; }

    .ra-item-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 10px;
    }

    .ra-item-title {
        font-size: 14.5px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    .ra-item-student {
        font-size: 13px;
        color: #6b7280;
        display: flex; align-items: center; gap: 6px;
    }
    .ra-item-student i { font-size: 12px; color: #9ca3af; }

    .ra-item-meta {
        display: flex; flex-wrap: wrap; gap: 14px;
        margin-top: 8px;
    }
    .ra-meta-tag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; color: #6b7280;
    }
    .ra-meta-tag i { font-size: 11px; color: #9ca3af; }

    /* Badges */
    .ra-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11.5px; font-weight: 600;
        white-space: nowrap;
    }
    .ra-badge.pending  { background: #fef3c7; color: #92400e; }
    .ra-badge.success  { background: #d1fae5; color: #065f46; }
    .ra-badge.danger   { background: #fee2e2; color: #991b1b; }
    .ra-badge.info     { background: #dbeafe; color: #1e40af; }
    .ra-badge.default  { background: #f3f4f6; color: #374151; }

    .ra-risk {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 6px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.3px;
    }
    .ra-risk.tinggi { background: #fee2e2; color: #991b1b; }
    .ra-risk.sedang { background: #fef3c7; color: #92400e; }
    .ra-risk.rendah { background: #d1fae5; color: #065f46; }

    .ra-time {
        font-size: 12px; color: #9ca3af;
        display: flex; align-items: center; gap: 5px;
        margin-top: 4px;
    }
    .ra-time i { font-size: 11px; }

    /* Right side of item */
    .ra-item-right {
        display: flex; flex-direction: column;
        align-items: flex-end; gap: 6px;
        flex-shrink: 0;
    }

    /* Empty state */
    .ra-empty {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
    }
    .ra-empty i { font-size: 42px; margin-bottom: 12px; display: block; opacity: 0.35; }
    .ra-empty h4 { font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
    .ra-empty p  { font-size: 13px; }

    /* Pagination */
    .ra-pagination { margin-top: 16px; }

    /* Tab content */
    .ra-tab-content { display: none; }
    .ra-tab-content.active { display: block; }
</style>
@endpush

@section('content')
<div class="ra-page">

    {{-- Alert --}}
    @if(session('success'))
    <div class="ra-alert success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="ra-header">
        <h1><i class="fas fa-clipboard-check" style="margin-right:8px;color:#059669;"></i> Review Risk Assessment</h1>
        <p>Tinjau dan setujui Risk Assessment dari mahasiswa</p>
    </div>

    {{-- Tabs --}}
    <div class="ra-tabs">
        <button class="ra-tab active" onclick="switchTab('pending', this)">
            <i class="fas fa-clock"></i> Menunggu Review
            @if($riskAssessments->total() > 0)
                <span class="ra-tab-count">{{ $riskAssessments->total() }}</span>
            @endif
        </button>
        <button class="ra-tab" onclick="switchTab('history', this)">
            <i class="fas fa-history"></i> Riwayat
            @if($riwayat->total() > 0)
                <span class="ra-tab-count">{{ $riwayat->total() }}</span>
            @endif
        </button>
    </div>

    {{-- Tab: Pending --}}
    <div class="ra-tab-content active" id="tab-pending">
        <div class="ra-card">
            @forelse($riskAssessments as $ra)
            <a href="{{ route('safety-officer.risk-assessment.show', $ra->id) }}" class="ra-item">
                <div class="ra-item-top">
                    <div style="flex:1;min-width:0;">
                        <div class="ra-item-title">{{ $ra->topik_judul }}</div>
                        <div class="ra-item-student">
                            <i class="fas fa-user-graduate"></i>
                            {{ $ra->nama }} &middot; {{ $ra->nim }}
                        </div>
                    </div>
                    <div class="ra-item-right">
                        <span class="ra-badge pending"><i class="fas fa-clock"></i> Menunggu Review</span>
                        @if($ra->kategori_resiko_dosen)
                        <span class="ra-risk {{ $ra->kategori_resiko_dosen }}">
                            <i class="fas fa-exclamation-triangle"></i> Risiko {{ ucfirst($ra->kategori_resiko_dosen) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="ra-item-meta">
                    <span class="ra-meta-tag"><i class="fas fa-flask"></i> {{ $ra->daftarLab->Nama_Laboratorium }}</span>
                    <span class="ra-meta-tag"><i class="fas fa-tag"></i> {{ $ra->jenis_ra }}</span>
                    <span class="ra-meta-tag"><i class="fas fa-chalkboard-teacher"></i> {{ $ra->dosenPembimbing->Nama ?? '-' }}</span>
                    <span class="ra-meta-tag"><i class="fas fa-calendar-alt"></i> {{ $ra->created_at->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <div class="ra-empty">
                <i class="fas fa-clipboard-check"></i>
                <h4>Tidak ada Risk Assessment</h4>
                <p>Belum ada RA yang menunggu review Anda.</p>
            </div>
            @endforelse
        </div>

        @if($riskAssessments->hasPages())
        <div class="ra-pagination">
            {{ $riskAssessments->links() }}
        </div>
        @endif
    </div>

    {{-- Tab: History --}}
    <div class="ra-tab-content" id="tab-history">
        <div class="ra-card">
            @forelse($riwayat as $ra)
            <a href="{{ route('safety-officer.risk-assessment.show', $ra->id) }}" class="ra-item">
                <div class="ra-item-top">
                    <div style="flex:1;min-width:0;">
                        <div class="ra-item-title">{{ $ra->topik_judul }}</div>
                        <div class="ra-item-student">
                            <i class="fas fa-user-graduate"></i>
                            {{ $ra->nama }} &middot; {{ $ra->nim }}
                        </div>
                    </div>
                    <div class="ra-item-right">
                        @if($ra->status === 'menunggu_kepala_lab')
                            <span class="ra-badge info"><i class="fas fa-hourglass-half"></i> Menunggu Kepala Lab</span>
                        @elseif($ra->status === 'disetujui')
                            <span class="ra-badge success"><i class="fas fa-check-circle"></i> Disetujui</span>
                        @elseif($ra->status === 'ditolak')
                            <span class="ra-badge danger"><i class="fas fa-times-circle"></i> Ditolak</span>
                        @else
                            <span class="ra-badge default">{{ ucfirst(str_replace('_', ' ', $ra->status)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="ra-item-meta">
                    <span class="ra-meta-tag"><i class="fas fa-flask"></i> {{ $ra->daftarLab->Nama_Laboratorium }}</span>
                    @if($ra->tanggal_persetujuan_safety_officer)
                    <span class="ra-meta-tag"><i class="fas fa-calendar-check"></i> Direview: {{ $ra->tanggal_persetujuan_safety_officer->format('d M Y') }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="ra-empty">
                <i class="fas fa-history"></i>
                <h4>Belum ada riwayat</h4>
                <p>Belum ada riwayat review Risk Assessment.</p>
            </div>
            @endforelse
        </div>

        @if($riwayat->hasPages())
        <div class="ra-pagination">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('.ra-tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.ra-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush