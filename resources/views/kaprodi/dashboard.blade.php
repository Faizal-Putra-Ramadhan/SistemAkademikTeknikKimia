@extends('layouts.app')
@section('title', 'Dashboard Kaprodi')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .so-page { max-width: 1200px; margin: 0 auto; }

    .so-welcome {
        background: linear-gradient(135deg, #b45309 0%, #d97706 60%, #fbbf24 100%);
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

    .so-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1024px) { .so-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .so-stats { grid-template-columns: 1fr; } }

    .so-stat {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .so-stat:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .so-stat.highlight { border-color: #f59e0b; background: #fffbeb; }

    .so-stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .so-stat-icon.amber  { background: #fef3c7; color: #d97706; }
    .so-stat-icon.green  { background: #d1fae5; color: #059669; }
    .so-stat-icon.red    { background: #fee2e2; color: #dc2626; }
    .so-stat-icon.blue   { background: #dbeafe; color: #2563eb; }

    .so-stat-info p { font-size: 13px; color: #6b7280; margin-bottom: 2px; }
    .so-stat-info h3 { font-size: 26px; font-weight: 700; color: #1f2937; line-height: 1.1; }
    .so-stat-link {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; font-weight: 500; color: #d97706;
        text-decoration: none; margin-top: 6px; transition: color 0.15s;
    }
    .so-stat-link:hover { color: #92400e; }

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
        border-color: var(--ac, #d97706);
        box-shadow: 0 0 0 1px var(--ac, #d97706), 0 4px 16px rgba(0,0,0,0.05);
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

    .so-action.act-blue .so-action-icon    { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }
    .so-action.act-emerald .so-action-icon { background: #ecfdf5; color: #059669; }
    .so-action.act-emerald:hover .so-action-icon { background: #059669; }
    .so-action.act-amber .so-action-icon   { background: #fffbeb; color: #d97706; }
    .so-action.act-amber:hover .so-action-icon { background: #d97706; }
    .so-action.act-purple .so-action-icon  { background: #f5f3ff; color: #7c3aed; }
    .so-action.act-purple:hover .so-action-icon { background: #7c3aed; }

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
        justify-content: space-between;
    }
    .so-card-head-left { display: flex; align-items: center; gap: 10px; }
    .so-card-head i { color: #6b7280; font-size: 15px; }
    .so-card-head h3 { font-size: 15px; font-weight: 700; color: #1f2937; }
    .so-card-body { padding: 0; }

    .topic-cell { max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

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
        <h2><i class="fas fa-user-shield" style="margin-right:8px;opacity:0.7;"></i> Selamat Datang, {{ Auth::user()->Nama ?? Auth::user()->name }}</h2>
        <p>Panel persetujuan Risk Assessment — Program Studi Teknik Kimia</p>
    </div>

    {{-- Statistics --}}
    <div class="so-stats">
        <div class="so-stat highlight">
            <div class="so-stat-icon amber"><i class="fas fa-clock"></i></div>
            <div class="so-stat-info">
                <p>Menunggu Persetujuan</p>
                <h3>{{ $statistics['menunggu'] }}</h3>
                <a href="{{ route('kaprodi.risk-assessment.index') }}" class="so-stat-link">
                    Lihat Detail <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="so-stat-info">
                <p>Disetujui Bulan Ini</p>
                <h3>{{ $statistics['disetujui_bulan_ini'] }}</h3>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="so-stat-info">
                <p>Ditolak Bulan Ini</p>
                <h3>{{ $statistics['ditolak_bulan_ini'] }}</h3>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon blue"><i class="fas fa-chart-bar"></i></div>
            <div class="so-stat-info">
                <p>Total Tahun Ini</p>
                <h3>{{ $statistics['total_tahun_ini'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Recent Approvals Table --}}
    <div class="so-card">
        <div class="so-card-head">
            <div class="so-card-head-left">
                <i class="fas fa-clipboard-list"></i>
                <h3>Persetujuan Terbaru</h3>
            </div>
            <span class="badge badge-primary">{{ $recentApprovals->count() }} Data</span>
        </div>
        <div class="so-card-body">
            @if($recentApprovals->count() > 0)
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mahasiswa</th>
                            <th>Penelitian</th>
                            <th>Laboratorium</th>
                            <th>Durasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApprovals as $ra)
                        <tr>
                            <td style="white-space:nowrap;">{{ $ra->tanggal_persetujuan_kaprodi ? $ra->tanggal_persetujuan_kaprodi->format('d M Y') : '-' }}</td>
                            <td>
                                <div style="font-weight:600; color:#111827;">{{ $ra->nama }}</div>
                                <div style="font-size:12px; color:#9ca3af; margin-top:1px;">{{ $ra->nim }}</div>
                            </td>
                            <td>
                                <div class="topic-cell" title="{{ $ra->topik_judul }}">{{ $ra->topik_judul }}</div>
                            </td>
                            <td>{{ $ra->daftarLab->Nama_Laboratorium ?? '-' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $ra->durasi_batas_peminjaman }} Bulan</span>
                            </td>
                            <td>
                                <span class="badge badge-success">Disetujui</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="so-empty">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data persetujuan terbaru</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <h3 class="so-section-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    <div class="so-actions">
        <a href="{{ route('kaprodi.risk-assessment.index') }}" class="so-action act-blue" style="--ac:#2563eb;">
            <div class="so-action-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4>Review Risk Assessment</h4>
            <p>Tinjau dan setujui pengajuan RA mahasiswa</p>
        </a>
        <a href="{{ route('kaprodi.risk-assessment.report') }}" class="so-action act-emerald" style="--ac:#059669;">
            <div class="so-action-icon"><i class="fas fa-file-alt"></i></div>
            <h4>Lihat Laporan</h4>
            <p>Akses rekapitulasi dan data lengkap RA</p>
        </a>
        <a href="{{ route('kaprodi.pengumuman.index') }}" class="so-action act-amber" style="--ac:#d97706;">
            <div class="so-action-icon"><i class="fas fa-bullhorn"></i></div>
            <h4>Kelola Pengumuman</h4>
            <p>Buat atau kelola informasi untuk mahasiswa</p>
        </a>
        <a href="{{ route('kaprodi.perpanjangan.index') }}" class="so-action act-purple" style="--ac:#7c3aed;">
            <div class="so-action-icon"><i class="fas fa-calendar-plus"></i></div>
            <h4>Perpanjangan RA</h4>
            <p>Kelola pengajuan perpanjangan Risk Assessment</p>
        </a>
    </div>

</div>
@endsection
