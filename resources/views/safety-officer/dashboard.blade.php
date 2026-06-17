@extends('layouts.app')
@section('title', 'Dashboard Safety Officer')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== Safety Officer Dashboard ===== */
    .so-page { max-width: 1200px; margin: 0 auto; }

    /* Welcome Banner */
    .so-welcome {
        background: linear-gradient(135deg, #065f46 0%, #059669 60%, #34d399 100%);
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

    /* Stats Grid */
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
    .so-stat-icon.blue   { background: #dbeafe; color: #2563eb; }
    .so-stat-icon.green  { background: #d1fae5; color: #059669; }
    .so-stat-icon.red    { background: #fee2e2; color: #dc2626; }

    .so-stat-info p { font-size: 13px; color: #6b7280; margin-bottom: 2px; }
    .so-stat-info h3 { font-size: 26px; font-weight: 700; color: #1f2937; line-height: 1.1; }

    .so-stat-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 500;
        color: #2563eb;
        text-decoration: none;
        margin-top: 6px;
        transition: color 0.15s;
    }
    .so-stat-link:hover { color: #1d4ed8; text-decoration: underline; }

    /* Card */
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
    .so-card-body { padding: 20px 24px; }

    /* Quick Actions */
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
        position: relative;
    }
    .so-action:hover {
        border-color: var(--ac, #059669);
        box-shadow: 0 0 0 1px var(--ac, #059669), 0 4px 16px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .so-action.disabled {
        opacity: 0.55;
        pointer-events: none;
    }
    .so-action.disabled::after {
        content: 'Segera Hadir';
        position: absolute;
        top: 12px; right: 12px;
        font-size: 10px;
        font-weight: 600;
        background: #f3f4f6;
        color: #9ca3af;
        padding: 2px 8px;
        border-radius: 8px;
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

    .so-action.act-emerald .so-action-icon  { background: #ecfdf5; color: #059669; }
    .so-action.act-emerald:hover .so-action-icon { background: #059669; }
    .so-action.act-blue .so-action-icon     { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }
    .so-action.act-slate .so-action-icon    { background: #f1f5f9; color: #64748b; }

    .so-action h4 { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .so-action p  { font-size: 12.5px; color: #6b7280; line-height: 1.5; }

    /* Lab List */
    .so-lab-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .so-lab-item:last-child { border-bottom: none; }

    .so-lab-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .so-lab-info { flex: 1; min-width: 0; }
    .so-lab-info h4 { font-size: 13.5px; font-weight: 600; color: #1f2937; margin-bottom: 3px; }
    .so-lab-meta { display: flex; flex-wrap: wrap; gap: 12px; }
    .so-lab-meta span {
        font-size: 12px;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .so-lab-meta i { font-size: 11px; color: #9ca3af; }

    /* Section Title */
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

    /* Empty */
    .so-empty {
        text-align: center;
        padding: 32px 20px;
        color: #9ca3af;
    }
    .so-empty i { font-size: 36px; margin-bottom: 10px; display: block; opacity: 0.4; }
    .so-empty p { font-size: 13px; }

    /* Alert */
    .so-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13.5px;
        font-weight: 500;
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
        <h2><i class="fas fa-shield-alt" style="margin-right:8px;opacity:0.7;"></i> Safety Officer Dashboard</h2>
        <p>Kelola dan review Risk Assessment untuk keselamatan laboratorium</p>
    </div>

    {{-- Statistics --}}
    <div class="so-stats">
        <div class="so-stat highlight">
            <div class="so-stat-icon amber"><i class="fas fa-clock"></i></div>
            <div class="so-stat-info">
                <p>Menunggu Review</p>
                <h3>{{ $pending }}</h3>
                <a href="{{ route('safety-officer.risk-assessment.index') }}" class="so-stat-link">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon blue"><i class="fas fa-calendar-check"></i></div>
            <div class="so-stat-info">
                <p>Jadwal Wawancara</p>
                <h3>{{ $scheduled }}</h3>
                <a href="{{ route('safety-officer.risk-assessment.schedules') }}" class="so-stat-link">
                    Lihat jadwal <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="so-stat-info">
                <p>Disetujui</p>
                <h3>{{ $approved }}</h3>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="so-stat-info">
                <p>Ditolak</p>
                <h3>{{ $rejected }}</h3>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <h3 class="so-section-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    <div class="so-actions">
        <a href="{{ route('safety-officer.risk-assessment.index') }}" class="so-action act-emerald" style="--ac:#059669;">
            <div class="so-action-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4>Review Risk Assessment</h4>
            <p>Tinjau RA yang menunggu persetujuan</p>
        </a>
        <a href="{{ route('safety-officer.risk-assessment.schedules') }}" class="so-action act-blue" style="--ac:#2563eb;">
            <div class="so-action-icon"><i class="fas fa-calendar-alt"></i></div>
            <h4>Kelola Jadwal Wawancara</h4>
            <p>Lihat dan atur jadwal wawancara mahasiswa</p>
        </a>
        <div class="so-action act-slate disabled">
            <div class="so-action-icon"><i class="fas fa-file-alt"></i></div>
            <h4>Laporan Keselamatan</h4>
            <p>Rekap data keselamatan laboratorium</p>
        </div>
    </div>

    {{-- Labs --}}
    <div class="so-card">
        <div class="so-card-head">
            <i class="fas fa-flask"></i>
            <h3>Semua Laboratorium</h3>
        </div>
        <div class="so-card-body">
            <p class="so-lab-meta" style="margin-bottom: 16px; font-size: 13px; color: #6b7280;">
                <i class="fas fa-info-circle"></i> Satu Safety Officer untuk seluruh laboratorium. Anda menangani Risk Assessment dari semua lab.
            </p>
            @forelse($labs as $lab)
            <div class="so-lab-item">
                <div class="so-lab-icon"><i class="fas fa-flask"></i></div>
                <div class="so-lab-info">
                    <h4>{{ $lab->Nama_Laboratorium }}</h4>
                    <div class="so-lab-meta">
                        <span><i class="fas fa-user-tie"></i> {{ $lab->Kepala_Labolatorium }}</span>
                        <span><i class="fas fa-user-cog"></i> {{ $lab->Admin_Laboratorium }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="so-empty">
                <i class="fas fa-flask"></i>
                <p>Belum ada data laboratorium</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection