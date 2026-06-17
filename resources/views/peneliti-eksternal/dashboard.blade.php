@extends('layouts.app')
@section('title', 'Dashboard Peneliti')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .so-page { max-width: 1200px; margin: 0 auto; }

    .so-welcome {
        background: linear-gradient(135deg, #7c2d12 0%, #c2410c 60%, #f97316 100%);
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
    .so-section-title i { color: #c2410c; font-size: 15px; }

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
        border-color: var(--ac, #c2410c);
        box-shadow: 0 0 0 1px var(--ac, #c2410c), 0 4px 16px rgba(0,0,0,0.05);
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

    .so-action.act-orange .so-action-icon  { background: #fff7ed; color: #c2410c; }
    .so-action.act-orange:hover .so-action-icon { background: #c2410c; }
    .so-action.act-blue .so-action-icon    { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }
    .so-action.act-emerald .so-action-icon { background: #ecfdf5; color: #059669; }
    .so-action.act-emerald:hover .so-action-icon { background: #059669; }
    .so-action.act-amber .so-action-icon   { background: #fffbeb; color: #d97706; }
    .so-action.act-amber:hover .so-action-icon { background: #d97706; }
    .so-action.act-rose .so-action-icon    { background: #fff1f2; color: #e11d48; }
    .so-action.act-rose:hover .so-action-icon { background: #e11d48; }
    .so-action.act-teal .so-action-icon    { background: #f0fdfa; color: #0d9488; }
    .so-action.act-teal:hover .so-action-icon { background: #0d9488; }

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
    .so-card-body { padding: 20px 24px; }

    .pn-item {
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .pn-item:last-child { border-bottom: none; }
    .pn-item-title { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 6px; }
    .pn-item-meta {
        display: flex; align-items: center; gap: 14px;
        font-size: 12.5px; color: #6b7280; margin-bottom: 8px;
    }
    .pn-item-meta i { font-size: 11px; color: #9ca3af; }
    .pn-item-content {
        font-size: 13.5px; color: #4b5563; line-height: 1.6;
        display: -webkit-box; -webkit-line-clamp: 3;
        -webkit-box-orient: vertical; overflow: hidden;
    }

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
        <h2><i class="fas fa-microscope" style="margin-right:8px;opacity:0.7;"></i> Dashboard Peneliti Eksternal</h2>
        <p>Selamat datang! Kelola kegiatan penelitian dan laboratorium Anda dari sini</p>
    </div>

    {{-- Quick Actions --}}
    <h3 class="so-section-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    <div class="so-actions">
        <a href="{{ route('peneliti-eksternal.dashboard') }}" class="so-action act-orange" style="--ac:#c2410c;">
            <div class="so-action-icon"><i class="fas fa-flask"></i></div>
            <h4>Daftar Laboratorium</h4>
            <p>Lihat laboratorium yang tersedia</p>
        </a>
        <a href="{{ route('peneliti-eksternal.risk-assessment.index') }}" class="so-action act-emerald" style="--ac:#059669;">
            <div class="so-action-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4>Risk Assessment</h4>
            <p>Buat dan kelola Risk Assessment Anda</p>
        </a>
        <a href="{{ route('peneliti-eksternal.bebas-lab') }}" class="so-action act-rose" style="--ac:#e11d48;">
            <div class="so-action-icon"><i class="fas fa-file-circle-check"></i></div>
            <h4>Bebas Lab</h4>
            <p>Ajukan surat bebas laboratorium</p>
        </a>
    </div>

    {{-- Pengumuman --}}
    <div class="so-card">
        <div class="so-card-head">
            <i class="fas fa-bullhorn"></i>
            <h3>Pengumuman Terbaru</h3>
        </div>
        <div class="so-card-body">
            @forelse($pengumuman as $item)
            <div class="pn-item">
                <div class="pn-item-title">{{ $item->judul }}</div>
                <div class="pn-item-meta">
                    <span><i class="fas fa-user"></i> {{ $item->author }}</span>
                    <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="pn-item-content">{{ $item->isi }}</div>
            </div>
            @empty
            <div class="so-empty">
                <i class="fas fa-bullhorn"></i>
                <p>Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
