@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .so-page { max-width: 1200px; margin: 0 auto; }

    .so-welcome {
        background: linear-gradient(135deg, #0e7490 0%, #06b6d4 60%, #22d3ee 100%);
        border-radius: 14px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .so-welcome::after {
        content: '';
        position: absolute;
        top: -50px; right: -30px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .so-welcome::before {
        content: '';
        position: absolute;
        bottom: -30px; right: 60px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .so-welcome h2 { font-size: 22px; font-weight: 700; margin-bottom: 4px; position: relative; }
    .so-welcome p  { font-size: 14px; opacity: 0.85; position: relative; }

    .so-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .so-section-title i { color: #d97706; font-size: 15px; }

    .so-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .so-actions { grid-template-columns: 1fr; } }

    .so-action {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    .so-action:hover {
        border-color: var(--ac, #06b6d4);
        box-shadow: 0 0 0 1px var(--ac, #06b6d4), 0 4px 16px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }

    .so-action-icon {
        width: 50px; height: 50px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
        transition: all 0.2s;
    }
    .so-action:hover .so-action-icon { color: #fff !important; }

    .so-action.act-cyan .so-action-icon    { background: #ecfeff; color: #0891b2; }
    .so-action.act-cyan:hover .so-action-icon { background: #0891b2; }
    .so-action.act-blue .so-action-icon    { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }
    .so-action.act-amber .so-action-icon   { background: #fffbeb; color: #d97706; }
    .so-action.act-amber:hover .so-action-icon { background: #d97706; }

    .so-action h4 { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .so-action p  { font-size: 12.5px; color: #6b7280; line-height: 1.5; }

    .so-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .so-card-head {
        padding: 18px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .so-card-head i { color: #6b7280; font-size: 15px; }
    .so-card-head h3 { font-size: 15px; font-weight: 700; color: #1f2937; }
    .so-card-body { padding: 0; }

    .so-empty {
        text-align: center;
        padding: 32px 20px;
        color: #9ca3af;
    }
    .so-empty i { font-size: 36px; margin-bottom: 10px; display: block; opacity: 0.4; }
    .so-empty p { font-size: 13px; }

    .so-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border-radius: 10px;
        margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
    }
    .so-alert.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .so-alert i { font-size: 16px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="so-page">

    {{-- Alert --}}
    @if(session('success'))
    <div class="so-alert success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Welcome Banner --}}
    <div class="so-welcome">
        <h2><i class="fas fa-chalkboard-teacher" style="margin-right:8px;opacity:0.7;"></i> Dashboard Dosen</h2>
        <p>Kelola pengajuan, peminjaman, dan Risk Assessment mahasiswa Anda</p>
    </div>

    {{-- Quick Actions --}}
    <h3 class="so-section-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    <div class="so-actions">
        <a href="{{ route('dosen.risk-assessment.index') }}" class="so-action act-cyan" style="--ac:#0891b2;">
            <div class="so-action-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4>Review Risk Assessment</h4>
            <p>Tinjau RA mahasiswa bimbingan Anda</p>
        </a>
        <a href="{{ route('dosen.lab') }}" class="so-action act-blue" style="--ac:#2563eb;">
            <div class="so-action-icon"><i class="fas fa-flask"></i></div>
            <h4>Daftar Laboratorium</h4>
            <p>Lihat dan pinjam ruangan atau alat lab</p>
        </a>
        <a href="{{ route('dosen.pengumuman.index') }}" class="so-action act-amber" style="--ac:#d97706;">
            <div class="so-action-icon"><i class="fas fa-bullhorn"></i></div>
            <h4>Kelola Pengumuman</h4>
            <p>Buat dan kelola pengumuman</p>
        </a>
    </div>

    {{-- Risk Assessment Terbaru --}}
    <div class="so-card">
        <div class="so-card-head">
            <i class="fas fa-clipboard-list"></i>
            <h3>Risk Assessment Terbaru</h3>
        </div>
        <div class="so-card-body">
            @if($riskAssessments->count())
            <div class="table-wrapper">
                <table class="data-table">
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
                                    $badgeClass = match($ra->status) {
                                        'disetujui' => 'badge-success',
                                        'ditolak' => 'badge-danger',
                                        default => 'badge-warning'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $ra->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('dosen.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="so-empty">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Belum ada Risk Assessment</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Peminjaman Ruangan --}}
    <div class="so-card">
        <div class="so-card-head">
            <i class="fas fa-door-open"></i>
            <h3>Peminjaman Ruangan</h3>
        </div>
        <div class="so-card-body">
            @if($peminjamanRuangan->count())
            <div class="table-wrapper">
                <table class="data-table">
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
                                @php
                                    $roomBadgeClass = match($item->status) {
                                        'disetujui' => 'badge-success',
                                        'disetujui_laboran', 'menunggu_kepala_lab' => 'badge-info',
                                        'ditolak' => 'badge-danger',
                                        'dikembalikan' => 'badge-secondary',
                                        default => 'badge-warning'
                                    };

                                    $roomStatusLabel = match($item->status) {
                                        'menunggu' => '⏳ Menunggu Laboran',
                                        'disetujui_laboran', 'menunggu_kepala_lab' => '📋 Menunggu Kepala Lab',
                                        'disetujui' => '✅ Disetujui',
                                        'dikembalikan' => '📥 Dikembalikan',
                                        'ditolak' => '❌ Ditolak',
                                        default => ucfirst($item->status)
                                    };
                                @endphp
                                <span class="badge {{ $roomBadgeClass }}">
                                    {{ $roomStatusLabel }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="so-empty">
                    <i class="fas fa-door-open"></i>
                    <p>Belum ada peminjaman ruangan</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Peminjaman Alat --}}
    <div class="so-card">
        <div class="so-card-head">
            <i class="fas fa-tools"></i>
            <h3>Peminjaman Alat</h3>
        </div>
        <div class="so-card-body">
            @if($peminjamanAlat->count())
            <div class="table-wrapper">
                <table class="data-table">
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
                                @php
                                    $alatBadgeClass = match($item->status) {
                                        'disetujui' => 'badge-success',
                                        'ditolak' => 'badge-danger',
                                        'dikembalikan' => 'badge-secondary',
                                        default => 'badge-warning'
                                    };

                                    $alatStatusLabel = match($item->status) {
                                        'menunggu' => '⏳ Menunggu Laboran',
                                        'disetujui' => '✅ Disetujui',
                                        'dikembalikan' => '📥 Dikembalikan',
                                        'ditolak' => '❌ Ditolak',
                                        default => ucfirst($item->status)
                                    };
                                @endphp
                                <span class="badge {{ $alatBadgeClass }}">
                                    {{ $alatStatusLabel }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="so-empty">
                    <i class="fas fa-tools"></i>
                    <p>Belum ada peminjaman alat</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
