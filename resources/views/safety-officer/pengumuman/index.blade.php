@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== Pengumuman Index ===== */
    .pn-page { max-width: 1000px; margin: 0 auto; }

    /* Header */
    .pn-header {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 16px;
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .pn-header-text h1 {
        font-size: 22px; font-weight: 700; color: #111827;
        display: flex; align-items: center; gap: 10px; margin-bottom: 4px;
    }
    .pn-header-text h1 i { color: #2563eb; font-size: 20px; }
    .pn-header-text p { font-size: 14px; color: #6b7280; }

    .pn-create-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 10px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; font-size: 13.5px; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        transition: all 0.2s;
    }
    .pn-create-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,0.35); }
    .pn-create-btn i { font-size: 13px; }

    /* Alert */
    .pn-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 10px;
        margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
    }
    .pn-alert.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .pn-alert i { font-size: 16px; }

    /* Card container */
    .pn-list { display: flex; flex-direction: column; gap: 14px; }

    /* Card */
    .pn-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; overflow: hidden;
        transition: all 0.2s;
    }
    .pn-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 16px rgba(0,0,0,0.06); }

    .pn-card-inner { padding: 22px 24px; }

    /* Top row: title + badge */
    .pn-card-top {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 14px;
        margin-bottom: 10px;
    }
    .pn-card-title {
        font-size: 16px; font-weight: 700; color: #111827;
        line-height: 1.35; flex: 1;
    }

    .pn-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 11px; border-radius: 20px;
        font-size: 11.5px; font-weight: 600; white-space: nowrap;
        flex-shrink: 0;
    }
    .pn-badge.publish { background: #dcfce7; color: #166534; }
    .pn-badge.draft   { background: #fef9c3; color: #854d0e; }

    /* Meta info */
    .pn-meta {
        display: flex; align-items: center; gap: 16px;
        margin-bottom: 14px; flex-wrap: wrap;
    }
    .pn-meta-item {
        display: flex; align-items: center; gap: 6px;
        font-size: 12.5px; color: #6b7280;
    }
    .pn-meta-item i { font-size: 12px; color: #9ca3af; }

    /* Content preview */
    .pn-content {
        font-size: 13.5px; color: #4b5563; line-height: 1.65;
        display: -webkit-box; -webkit-line-clamp: 3;
        -webkit-box-orient: vertical; overflow: hidden;
        margin-bottom: 16px;
    }

    /* Actions row */
    .pn-actions {
        display: flex; align-items: center; gap: 8px;
        padding-top: 14px; border-top: 1px solid #f3f4f6;
    }
    .pn-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px;
        font-size: 12.5px; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        transition: all 0.15s;
    }
    .pn-btn.edit {
        background: #fef3c7; color: #92400e;
        border: 1px solid #fde68a;
    }
    .pn-btn.edit:hover { background: #fde68a; }
    .pn-btn.delete {
        background: #fee2e2; color: #991b1b;
        border: 1px solid #fecaca;
    }
    .pn-btn.delete:hover { background: #fecaca; }

    /* Accent stripe */
    .pn-stripe {
        height: 4px; border-radius: 4px 4px 0 0;
    }
    .pn-stripe.publish { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .pn-stripe.draft   { background: linear-gradient(90deg, #eab308, #ca8a04); }

    /* Empty */
    .pn-empty {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; padding: 60px 24px;
        text-align: center;
    }
    .pn-empty i { font-size: 48px; color: #d1d5db; margin-bottom: 14px; display: block; }
    .pn-empty h4 { font-size: 15px; font-weight: 600; color: #6b7280; margin-bottom: 6px; }
    .pn-empty p { font-size: 13px; color: #9ca3af; margin-bottom: 20px; }
</style>
@endpush

@section('content')
<div class="pn-page">

    {{-- Header --}}
    <div class="pn-header">
        <div class="pn-header-text">
            <h1><i class="fas fa-bullhorn"></i> Pengumuman</h1>
            <p>Daftar pengumuman terbaru untuk seluruh pengguna laboratorium</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="pn-alert success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- List --}}
    @if($pengumuman->count() > 0)
    <div class="pn-list">
        @foreach($pengumuman as $item)
        <div class="pn-card">
            <div class="pn-stripe {{ $item->status }}"></div>
            <div class="pn-card-inner">
                <div class="pn-card-top">
                    <span class="pn-card-title">{{ $item->judul }}</span>
                    <span class="pn-badge {{ $item->status }}">
                        @if($item->status === 'publish')
                            <i class="fas fa-globe"></i> Publish
                        @else
                            <i class="fas fa-file-pen"></i> Draft
                        @endif
                    </span>
                </div>

                <div class="pn-meta">
                    <span class="pn-meta-item">
                        <i class="fas fa-user"></i> {{ $item->author }}
                    </span>
                    <span class="pn-meta-item">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                    </span>
                </div>

                <div class="pn-content">{{ $item->isi }}</div>

            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="pn-empty">
        <i class="fas fa-bullhorn"></i>
        <h4>Belum ada pengumuman</h4>
        <p>Pengumuman terbaru akan muncul di sini.</p>
    </div>
    @endif

</div>
@endsection