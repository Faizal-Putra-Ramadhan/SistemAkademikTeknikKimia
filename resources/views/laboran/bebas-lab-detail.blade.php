@extends('layouts.app')

@section('title', 'Detail Bebas Lab')
@section('page-title', 'Detail Bebas Lab')

@push('styles')
<style>
    .detail-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.5rem 1.25rem 2.5rem;
    }
    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.95rem;
        border-radius: 999px;
        background: #1f2937;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
    }
    .alert {
        padding: 0.9rem 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .alert-success {
        background: #e7f6ef;
        color: #0f5132;
        border: 1px solid #bfe7d3;
    }
    .alert-error {
        background: #fdecea;
        color: #7a271a;
        border: 1px solid #f9c2bb;
    }
    .panel {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .panel-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.35rem 0.6rem;
        border-radius: 999px;
    }
    .status-badge.success {
        background: #e7f6ef;
        color: #0f5132;
    }
    .status-badge.warn {
        background: #fff4d6;
        color: #8a5800;
    }
    .panel-body {
        padding: 1.5rem;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }
    .meta-item {
        padding: 0.85rem 1rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
    }
    .meta-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.3rem;
        font-weight: 700;
    }
    .meta-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.75rem;
    }
    .list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.5rem;
    }
    .list-item {
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        border: 1px solid #eef2f7;
        background: #fff;
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .list-muted {
        color: #64748b;
        font-size: 0.85rem;
    }
    .empty-state {
        padding: 1rem;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        border: 1px dashed #dbe3ef;
        font-size: 0.9rem;
    }
    .stat-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .stat-card {
        padding: 0.9rem 1rem;
        border-radius: 12px;
        background: #0f172a;
        color: #fff;
    }
    .stat-card strong {
        font-size: 1.3rem;
        display: block;
    }
    .action-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    .hint {
        font-size: 0.85rem;
        color: #b42318;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
    <div class="detail-page">
        <div class="top-row">
            <a href="{{ route('laboran.bebas-lab', $lab->id) }}" class="back-link">&larr; Kembali</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @php
            $isApproved = $approval && $approval->status === 'disetujui';
        @endphp

        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Pengajuan Bebas Lab</h2>
                    <div class="list-muted">Ringkasan pengajuan dan status persetujuan laboran</div>
                </div>
                <div>
                    @if($isApproved)
                        <span class="status-badge success">Disetujui</span>
                    @else
                        <span class="status-badge warn">Menunggu</span>
                    @endif
                </div>
            </div>
            <div class="panel-body">
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-label">Laboratorium</div>
                        <div class="meta-value">{{ $lab->Nama_Laboratorium }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Nama Pemohon</div>
                        <div class="meta-value">{{ $bebasLabRequest->user_nama }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Tanggal Pengajuan</div>
                        <div class="meta-value">{{ $bebasLabRequest->created_at?->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Risk Assessment</div>
                        <div class="meta-value">
                            @if($bebasLabRequest->riskAssessment)
                                {{ $bebasLabRequest->riskAssessment->id_ra ?? '-' }} - {{ $bebasLabRequest->riskAssessment->topik_judul }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    @if($isApproved)
                        <div class="meta-item">
                            <div class="meta-label">Disetujui Pada</div>
                            <div class="meta-value">{{ $approval->approved_at?->format('d/m/Y H:i') ?? '-' }}</div>
                        </div>
                    @endif
                </div>

                <div class="stat-row">
                    <div class="stat-card">
                        <div>Peminjaman Alat Aktif</div>
                        <strong>{{ $pendingAlatNow }}</strong>
                    </div>
                    <div class="stat-card">
                        <div>Peminjaman Ruangan Aktif</div>
                        <strong>{{ $pendingRuanganNow }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Peminjaman Alat</h3>
            </div>
            <div class="panel-body">
                @if($alatList->isEmpty())
                    <div class="empty-state">Tidak ada peminjaman alat.</div>
                @else
                    <ul class="list">
                        @foreach($alatList as $alat)
                            <li class="list-item">
                                <span>{{ $alat->alatLab?->nama_alat ?? '-' }}</span>
                                <span class="list-muted">Status: {{ $alat->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Peminjaman Ruangan</h3>
            </div>
            <div class="panel-body">
                @if($ruanganList->isEmpty())
                    <div class="empty-state">Tidak ada peminjaman ruangan.</div>
                @else
                    <ul class="list">
                        @foreach($ruanganList as $ruangan)
                            <li class="list-item">
                                <span>{{ $ruangan->tanggal?->format('d/m/Y') ?? '-' }} {{ $ruangan->jam_mulai }}-{{ $ruangan->jam_selesai }}</span>
                                <span class="list-muted">Status: {{ $ruangan->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="action-bar">
            <a href="{{ route('laboran.bebas-lab', $lab->id) }}" class="btn btn-secondary">
                Kembali
            </a>

            @if($bebasLabRequest->is_active && $bebasLabRequest->isFullyApproved() && !$bebasLabRequest->hasPeminjamanAktif())
                <a href="{{ route('laboran.bebas-lab.download', [$lab->id, $bebasLabRequest->id]) }}" class="btn btn-success" style="background-color: #059669; color: white;">
                    Download Surat Bebas Lab
                </a>
            @endif

            @if(!$isApproved)
                <form action="{{ route('laboran.bebas-lab.approve', [$lab->id, $bebasLabRequest->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-primary" @if($pendingNow > 0) disabled @endif>
                        Setujui Bebas Lab
                    </button>
                </form>
                @if($pendingNow > 0)
                    <span class="hint">Masih ada peminjaman alat atau ruangan yang aktif.</span>
                @endif
            @endif
        </div>
    </div>
@endsection
