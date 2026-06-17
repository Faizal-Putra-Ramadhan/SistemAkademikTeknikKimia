@extends('layouts.app')

@section('title', 'Risk Assessment')
@section('page-title', 'Risk Assessment')

@push('styles')
<style>
    .ra-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 1.25rem 2.5rem;
    }
    .ra-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .ra-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .ra-subtitle {
        margin: 0.35rem 0 0;
        color: #64748b;
        font-size: 0.95rem;
    }
    .ra-stats {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .ra-stat {
        background: #0f172a;
        color: #fff;
        padding: 0.6rem 0.9rem;
        border-radius: 12px;
        min-width: 120px;
    }
    .ra-stat span {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.75;
    }
    .ra-stat strong {
        font-size: 1.2rem;
    }
    .ra-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .ra-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding: 1rem 1.5rem 0.75rem;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
    }
    .filter-tab {
        border: none;
        background: #fff;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-weight: 600;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .filter-tab.active {
        background: #0f6fff;
        border-color: #0f6fff;
        color: #fff;
    }
    .ra-content {
        padding: 1.5rem;
    }
    .ra-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
    }
    .ra-table {
        width: 100%;
        border-collapse: collapse;
    }
    .ra-table th {
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid #eef2f7;
    }
    .ra-table td {
        padding: 0.85rem 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
        color: #0f172a;
        font-size: 0.9rem;
    }
    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .deadline-badge.expired {
        background: #fee2e2;
        color: #991b1b;
    }
    .deadline-badge.hampir-expired {
        background: #fef3c7;
        color: #92400e;
    }
    .deadline-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    .deadline-sisa {
        display: block;
        font-size: 0.7rem;
        font-weight: 500;
        margin-top: 2px;
        opacity: 0.85;
    }
</style>
@endpush

@section('content')
    <div class="ra-page">
        <div class="ra-header">
            <div>
                <h1 class="ra-title">Risk Assessment</h1>
                <p class="ra-subtitle">{{ $lab->Nama_Laboratorium }}</p>
            </div>
            @php
                $approvedRAs = $riskAssessments->where('status', 'disetujui');
                $expiredCount = $approvedRAs->filter(fn($ra) => $ra->batas_waktu_peminjaman && !$ra->isMasihBerlaku())->count();
                $hampirExpiredCount = $approvedRAs->filter(fn($ra) => $ra->batas_waktu_peminjaman && $ra->isMasihBerlaku() && $ra->isHampirExpired())->count();
            @endphp
            <div class="ra-stats">
                <div class="ra-stat">
                    <span>Total</span>
                    <strong>{{ $riskAssessments->count() }}</strong>
                </div>
                <div class="ra-stat">
                    <span>Menunggu</span>
                    <strong>{{ $riskAssessments->whereIn('status', ['menunggu_dosen', 'menunggu_safety_officer', 'menunggu_kepala_lab'])->count() }}</strong>
                </div>
                <div class="ra-stat">
                    <span>Disetujui</span>
                    <strong>{{ $approvedRAs->count() }}</strong>
                </div>
                @if($hampirExpiredCount > 0)
                <div class="ra-stat" style="background: #92400e;">
                    <span>Hampir Expired</span>
                    <strong>{{ $hampirExpiredCount }}</strong>
                </div>
                @endif
                @if($expiredCount > 0)
                <div class="ra-stat" style="background: #991b1b;">
                    <span>Expired</span>
                    <strong>{{ $expiredCount }}</strong>
                </div>
                @endif
            </div>
        </div>

        @if($hampirExpiredCount > 0 || $expiredCount > 0)
        <div style="background: linear-gradient(to right, #fef3c7, #fef9c3); border: 1px solid #f59e0b; border-left: 5px solid #f59e0b; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 12px;">
            <span style="font-size: 1.5rem; flex-shrink: 0;">⚠️</span>
            <div>
                <strong style="color: #92400e; font-size: 0.95rem;">Perhatian: Ada Risk Assessment yang perlu ditindaklanjuti</strong>
                <p style="margin: 4px 0 0; font-size: 0.875rem; color: #78350f;">
                    @if($hampirExpiredCount > 0)
                        <strong>{{ $hampirExpiredCount }}</strong> RA hampir melewati batas waktu peminjaman.
                    @endif
                    @if($expiredCount > 0)
                        <strong>{{ $expiredCount }}</strong> RA sudah expired.
                    @endif
                    Ingatkan mahasiswa untuk mengajukan perpanjangan.
                </p>
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
        @endif

        <div class="ra-card">
            <div class="ra-tabs">
                <button onclick="filterStatus('all')" class="filter-tab active" data-status="all">
                    Semua ({{ $riskAssessments->count() }})
                </button>
                <button onclick="filterStatus('draft')" class="filter-tab" data-status="draft">
                    Draft ({{ $riskAssessments->where('status', 'draft')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_dosen')" class="filter-tab" data-status="menunggu_dosen">
                    Menunggu Dosen ({{ $riskAssessments->where('status', 'menunggu_dosen')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_safety_officer')" class="filter-tab" data-status="menunggu_safety_officer">
                    Menunggu SO ({{ $riskAssessments->where('status', 'menunggu_safety_officer')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_kepala_lab')" class="filter-tab" data-status="menunggu_kepala_lab">
                    Menunggu Kepala Lab ({{ $riskAssessments->where('status', 'menunggu_kepala_lab')->count() }})
                </button>
                <button onclick="filterStatus('disetujui')" class="filter-tab" data-status="disetujui">
                    Disetujui ({{ $riskAssessments->where('status', 'disetujui')->count() }})
                </button>
                <button onclick="filterStatus('ditolak')" class="filter-tab" data-status="ditolak">
                    Ditolak ({{ $riskAssessments->where('status', 'ditolak')->count() }})
                </button>
            </div>

            <div class="ra-content">
                @if($riskAssessments->isEmpty())
                    <div class="ra-empty">
                        <h3 class="text-sm font-semibold text-gray-900">Belum ada Risk Assessment</h3>
                        <p class="text-sm">Belum ada mahasiswa yang mengajukan Risk Assessment untuk lab ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="ra-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode RA</th>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jenis RA</th>
                                    <th>Topik/Judul</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Status</th>
                                    <th>Batas Berlaku</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskAssessments as $index => $ra)
                                    <tr class="ra-row" data-status="{{ $ra->status }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($ra->id_ra)
                                                <span class="badge badge-info">{{ $ra->id_ra }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-sm font-medium text-gray-900">{{ $ra->nama }}</div>
                                        </td>
                                        <td>{{ $ra->nim }}</td>
                                        <td>
                                            <span class="badge
                                                @if($ra->jenis_ra === 'Penelitian') badge-info
                                                @elseif($ra->jenis_ra === 'Praktikum') badge-purple
                                                @else badge-secondary
                                                @endif">
                                                {{ $ra->jenis_ra }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $ra->topik_judul ?? '-' }}</div>
                                        </td>
                                        <td>
                                            {{ $ra->dosen_pembimbing_nama ?? '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'draft' => ['class' => 'badge-secondary', 'label' => 'Draft'],
                                                    'menunggu_dosen' => ['class' => 'badge-warning', 'label' => 'Menunggu Dosen'],
                                                    'menunggu_safety_officer' => ['class' => 'badge-info', 'label' => 'Menunggu SO'],
                                                    'menunggu_kepala_lab' => ['class' => 'badge-purple', 'label' => 'Menunggu Kepala Lab'],
                                                    'disetujui' => ['class' => 'badge-success', 'label' => 'Disetujui'],
                                                    'ditolak' => ['class' => 'badge-danger', 'label' => 'Ditolak'],
                                                ];
                                                $config = $statusConfig[$ra->status] ?? ['class' => 'badge-secondary', 'label' => $ra->status];
                                            @endphp
                                            <span class="badge {{ $config['class'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ra->status === 'disetujui' && $ra->batas_waktu_peminjaman)
                                                @if(!$ra->isMasihBerlaku())
                                                    <span class="deadline-badge expired">⚠️ Expired</span>
                                                    <span class="deadline-sisa" style="color: #991b1b;">{{ $ra->getBatasWaktuPeminjamanFormatted() }}</span>
                                                @elseif($ra->isHampirExpired())
                                                    <span class="deadline-badge hampir-expired">⏰ Hampir Habis</span>
                                                    <span class="deadline-sisa" style="color: #92400e;">{{ $ra->getBatasWaktuPeminjamanFormatted() }} ({{ $ra->getSisaWaktuPeminjaman() }})</span>
                                                @else
                                                    <span class="deadline-badge active">✅ Berlaku</span>
                                                    <span class="deadline-sisa" style="color: #065f46;">s.d. {{ $ra->getBatasWaktuPeminjamanFormatted() }} ({{ $ra->getSisaWaktuPeminjaman() }})</span>
                                                @endif
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $ra->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('laboran.risk-assessment.detail', $ra->id) }}" class="btn btn-info btn-sm">
                                                    Lihat Detail
                                                </a>
                                                @if($ra->persetujuan_kaprodi)
                                                    <form action="{{ route('laboran.risk-assessment.send-notification', $ra->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm" title="Kirim pengingat perpanjangan">
                                                            Spam Email
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function filterStatus(status) {
    const rows = document.querySelectorAll('.ra-row');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update active tab
    tabs.forEach(tab => {
        if (tab.dataset.status === status) {
            tab.classList.add('active', 'border-blue-500', 'text-blue-600');
            tab.classList.remove('border-transparent', 'text-gray-600');
        } else {
            tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-600');
        }
    });
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Set initial active state
document.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('.filter-tab[data-status="all"]');
    if (firstTab) {
        firstTab.classList.add('active');
    }
});
</script>
@endpush