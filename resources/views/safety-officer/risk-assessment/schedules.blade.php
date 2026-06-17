@extends('layouts.app')
@section('title', 'Kelola Jadwal')
@section('page-title', 'Kelola Jadwal Wawancara')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== Schedules Page ===== */
    .sch-page { max-width: 1000px; margin: 0 auto; }

    /* Header */
    .sch-header { margin-bottom: 24px; }
    .sch-header h1 {
        font-size: 22px; font-weight: 700; color: #111827;
        display: flex; align-items: center; gap: 10px; margin-bottom: 4px;
    }
    .sch-header h1 i { color: #2563eb; font-size: 20px; }
    .sch-header p { font-size: 14px; color: #6b7280; }

    /* Section title */
    .sch-section-title {
        font-size: 15px; font-weight: 700; color: #1f2937;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
    }
    .sch-section-title i { font-size: 14px; }
    .sch-section-title .count {
        background: #dbeafe; color: #1d4ed8;
        font-size: 11px; font-weight: 700;
        padding: 2px 8px; border-radius: 10px;
    }

    /* Card */
    .sch-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 12px; overflow: hidden; margin-bottom: 28px;
    }

    /* Schedule item */
    .sch-item {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.15s;
    }
    .sch-item:last-child { border-bottom: none; }
    .sch-item:hover { background: #f9fafb; }

    .sch-item-top {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 16px;
        margin-bottom: 14px;
    }

    /* Calendar block */
    .sch-cal {
        display: flex; align-items: center; gap: 14px;
        flex: 1; min-width: 0;
    }
    .sch-cal-icon {
        width: 52px; height: 52px;
        background: #eff6ff; color: #2563eb;
        border-radius: 12px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sch-cal-icon .day { font-size: 18px; font-weight: 800; line-height: 1; }
    .sch-cal-icon .mon { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .sch-cal-info {}
    .sch-cal-date { font-size: 14px; font-weight: 600; color: #111827; }
    .sch-cal-time { font-size: 13px; color: #6b7280; margin-top: 2px; }
    .sch-cal-time i { margin-right: 4px; font-size: 11px; color: #9ca3af; }

    /* Urgency pill */
    .sch-urgency {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 11.5px; font-weight: 600; white-space: nowrap;
        flex-shrink: 0;
    }
    .sch-urgency.soon { background: #fee2e2; color: #991b1b; }
    .sch-urgency.normal { background: #dbeafe; color: #1e40af; }

    .sch-urgency-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: currentColor; opacity: 0.6;
    }

    /* Details grid */
    .sch-details {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 8px 20px;
        padding: 14px 18px;
        background: #f8fafc; border-radius: 10px;
    }
    @media (max-width: 640px) { .sch-details { grid-template-columns: 1fr; } }

    .sch-detail {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: 13px; color: #374151;
    }
    .sch-detail i { color: #9ca3af; font-size: 12px; margin-top: 2px; flex-shrink: 0; }
    .sch-detail strong { font-weight: 600; color: #111827; }
    .sch-detail.span-2 { grid-column: 1 / -1; }

    /* Action button inside item */
    .sch-action-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px;
        font-size: 12.5px; font-weight: 600;
        background: #fff; color: #374151;
        border: 1px solid #d1d5db;
        text-decoration: none;
        transition: all 0.15s;
        margin-top: 12px;
    }
    .sch-action-btn:hover { background: #f3f4f6; border-color: #9ca3af; }
    .sch-action-btn i { font-size: 12px; }

    /* Badge (history) */
    .sch-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11.5px; font-weight: 600;
    }
    .sch-badge.success { background: #d1fae5; color: #065f46; }
    .sch-badge.danger  { background: #fee2e2; color: #991b1b; }
    .sch-badge.pending { background: #fef3c7; color: #92400e; }

    /* History item (compact) */
    .sch-hist-item {
        display: flex; align-items: center;
        justify-content: space-between; gap: 16px;
        padding: 14px 24px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.15s;
    }
    .sch-hist-item:last-child { border-bottom: none; }
    .sch-hist-item:hover { background: #f9fafb; }

    .sch-hist-left { flex: 1; min-width: 0; }
    .sch-hist-name { font-size: 13.5px; font-weight: 600; color: #111827; }
    .sch-hist-topic {
        font-size: 12.5px; color: #6b7280; margin-top: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 400px;
    }
    .sch-hist-place {
        font-size: 12px; color: #9ca3af; margin-top: 2px;
        display: flex; align-items: center; gap: 4px;
    }
    .sch-hist-place i { font-size: 10px; }

    .sch-hist-right {
        display: flex; flex-direction: column;
        align-items: flex-end; gap: 4px; flex-shrink: 0;
    }
    .sch-hist-date { font-size: 12.5px; color: #6b7280; }

    /* Empty */
    .sch-empty {
        text-align: center; padding: 40px 20px; color: #9ca3af;
    }
    .sch-empty i { font-size: 40px; margin-bottom: 10px; display: block; opacity: 0.35; }
    .sch-empty h4 { font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
    .sch-empty p { font-size: 13px; }

    /* Pagination */
    .sch-pagination { margin-top: 14px; }
</style>
@endpush

@section('content')
<div class="sch-page">

    {{-- Header --}}
    <div class="sch-header">
        <h1><i class="fas fa-calendar-alt"></i> Jadwal Wawancara</h1>
        <p>Kelola jadwal wawancara Risk Assessment dengan mahasiswa</p>
    </div>

    {{-- ======= UPCOMING ======= --}}
    <h3 class="sch-section-title" style="color:#2563eb;">
        <i class="fas fa-clock"></i> Jadwal Mendatang
        @if($upcomingSchedules->total() > 0)
            <span class="count">{{ $upcomingSchedules->total() }}</span>
        @endif
    </h3>

    <div class="sch-card">
        @forelse($upcomingSchedules as $schedule)
        <div class="sch-item">
            {{-- Top: Calendar + Urgency --}}
            <div class="sch-item-top">
                <div class="sch-cal">
                    <div class="sch-cal-icon">
                        <span class="day">{{ $schedule->jadwal_wawancara->format('d') }}</span>
                        <span class="mon">{{ $schedule->jadwal_wawancara->format('M') }}</span>
                    </div>
                    <div class="sch-cal-info">
                        <div class="sch-cal-date">{{ $schedule->jadwal_wawancara->format('l, d F Y') }}</div>
                        <div class="sch-cal-time">
                            <i class="fas fa-clock"></i> {{ $schedule->jadwal_wawancara->format('H:i') }} WIB
                        </div>
                    </div>
                </div>

                @php
                    $diffHours = now()->diffInHours($schedule->jadwal_wawancara, false);
                @endphp
                @if($diffHours <= 24 && $diffHours > 0)
                    <span class="sch-urgency soon">
                        <span class="sch-urgency-dot"></span>
                        Segera ({{ $schedule->jadwal_wawancara->diffForHumans() }})
                    </span>
                @else
                    <span class="sch-urgency normal">
                        {{ $schedule->jadwal_wawancara->diffForHumans() }}
                    </span>
                @endif
            </div>

            {{-- Details --}}
            <div class="sch-details">
                <div class="sch-detail">
                    <i class="fas fa-user-graduate"></i>
                    <div><strong>{{ $schedule->nama }}</strong> &middot; {{ $schedule->nim }}</div>
                </div>
                <div class="sch-detail">
                    <i class="fas fa-flask"></i>
                    <div>{{ $schedule->daftarLab->Nama_Laboratorium }}</div>
                </div>
                <div class="sch-detail span-2">
                    <i class="fas fa-book"></i>
                    <div><strong>Topik:</strong> {{ $schedule->topik_judul }}</div>
                </div>
                @if($schedule->tempat_wawancara)
                <div class="sch-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><strong>Tempat:</strong> {{ $schedule->tempat_wawancara }}</div>
                </div>
                @endif
                @if($schedule->catatan_safety_officer)
                <div class="sch-detail span-2">
                    <i class="fas fa-sticky-note"></i>
                    <div><strong>Catatan:</strong> {{ $schedule->catatan_safety_officer }}</div>
                </div>
                @endif
            </div>

            <a href="{{ route('safety-officer.risk-assessment.show', $schedule->id) }}" class="sch-action-btn">
                <i class="fas fa-arrow-right"></i> Lihat Detail RA
            </a>
        </div>
        @empty
        <div class="sch-empty">
            <i class="fas fa-calendar-check"></i>
            <h4>Tidak ada jadwal mendatang</h4>
            <p>Belum ada wawancara yang dijadwalkan.</p>
        </div>
        @endforelse
    </div>

    @if($upcomingSchedules->hasPages())
    <div class="sch-pagination">{{ $upcomingSchedules->links() }}</div>
    @endif

    {{-- ======= HISTORY ======= --}}
    <h3 class="sch-section-title" style="color:#6b7280; margin-top: 8px;">
        <i class="fas fa-history"></i> Riwayat Wawancara
        @if($pastSchedules->total() > 0)
            <span class="count" style="background:#f3f4f6;color:#374151;">{{ $pastSchedules->total() }}</span>
        @endif
    </h3>

    <div class="sch-card">
        @forelse($pastSchedules as $schedule)
        <div class="sch-hist-item">
            <div class="sch-hist-left">
                <div class="sch-hist-name">{{ $schedule->nama }}</div>
                <div class="sch-hist-topic" title="{{ $schedule->topik_judul }}">{{ $schedule->topik_judul }}</div>
                @if($schedule->tempat_wawancara)
                <div class="sch-hist-place"><i class="fas fa-map-marker-alt"></i> {{ $schedule->tempat_wawancara }}</div>
                @endif
            </div>
            <div class="sch-hist-right">
                <div class="sch-hist-date">{{ $schedule->jadwal_wawancara->format('d M Y, H:i') }}</div>
                @if($schedule->persetujuan_safety_officer === true)
                    <span class="sch-badge success"><i class="fas fa-check-circle"></i> Disetujui</span>
                @elseif($schedule->persetujuan_safety_officer === false)
                    <span class="sch-badge danger"><i class="fas fa-times-circle"></i> Ditolak</span>
                @else
                    <span class="sch-badge pending"><i class="fas fa-clock"></i> Belum Review</span>
                @endif
            </div>
        </div>
        @empty
        <div class="sch-empty">
            <i class="fas fa-history"></i>
            <p>Tidak ada riwayat wawancara.</p>
        </div>
        @endforelse
    </div>

    @if($pastSchedules->hasPages())
    <div class="sch-pagination">{{ $pastSchedules->links() }}</div>
    @endif

</div>
@endsection