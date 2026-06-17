@extends('layouts.app')

@section('title', 'Dashboard Laboran')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .so-page { max-width: 1200px; margin: 0 auto; }

    .so-welcome {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 60%, #60a5fa 100%);
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
        grid-template-columns: repeat(3, 1fr);
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
    .so-stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .so-stat-icon.blue   { background: #dbeafe; color: #2563eb; }

    .so-stat-info p { font-size: 13px; color: #6b7280; margin-bottom: 2px; }
    .so-stat-info h3 { font-size: 26px; font-weight: 700; color: #1f2937; line-height: 1.1; }

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
        position: relative;
    }
    .so-action:hover {
        border-color: var(--ac, #3b82f6);
        box-shadow: 0 0 0 1px var(--ac, #3b82f6), 0 4px 16px rgba(0,0,0,0.05);
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

    .so-action.act-blue .so-action-icon     { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }
    .so-action.act-purple .so-action-icon   { background: #f5f3ff; color: #7c3aed; }
    .so-action.act-purple:hover .so-action-icon { background: #7c3aed; }
    .so-action.act-amber .so-action-icon    { background: #fffbeb; color: #d97706; }
    .so-action.act-amber:hover .so-action-icon { background: #d97706; }
    .so-action.act-emerald .so-action-icon  { background: #ecfdf5; color: #059669; }
    .so-action.act-emerald:hover .so-action-icon { background: #059669; }
    .so-action.act-rose .so-action-icon     { background: #fff1f2; color: #e11d48; }
    .so-action.act-rose:hover .so-action-icon { background: #e11d48; }
    .so-action.act-teal .so-action-icon     { background: #f0fdfa; color: #0d9488; }
    .so-action.act-teal:hover .so-action-icon { background: #0d9488; }

    .so-action h4 { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .so-action p  { font-size: 12.5px; color: #6b7280; line-height: 1.5; }

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
        <h2><i class="fas fa-flask" style="margin-right:8px;opacity:0.7;"></i> Selamat datang, {{ $user->Nama }}</h2>
        <p>{{ $daftarLab->Nama_Laboratorium ?? ($laboran->Laboratorium ?? 'Laboratorium') }} &bull; {{ $user->Email }}</p>
    </div>

    {{-- Statistics --}}
    <div class="so-stats">
        <div class="so-stat highlight">
            <div class="so-stat-icon amber"><i class="fas fa-calendar-check"></i></div>
            <div class="so-stat-info">
                <p>Peminjaman Ruangan Menunggu</p>
                <h3>{{ $peminjamanRuanganMenunggu }}</h3>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon purple"><i class="fas fa-box-open"></i></div>
            <div class="so-stat-info">
                <p>Peminjaman Alat Menunggu</p>
                <h3>{{ $peminjamanAlatMenunggu }}</h3>
            </div>
        </div>
        <div class="so-stat">
            <div class="so-stat-icon blue"><i class="fas fa-bullhorn"></i></div>
            <div class="so-stat-info">
                <p>Total Pengumuman Aktif</p>
                <h3>{{ $totalPengumuman }}</h3>
            </div>
        </div>
    </div>

    

</div>
@endsection