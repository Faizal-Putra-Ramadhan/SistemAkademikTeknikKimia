@extends('layouts.app')

@section('title', 'Dashboard Kepala Lab')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .so-page { max-width: 1200px; margin: 0 auto; }

    .so-welcome {
        background: linear-gradient(135deg, #7e22ce 0%, #a855f7 60%, #c084fc 100%);
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
        border-color: var(--ac, #a855f7);
        box-shadow: 0 0 0 1px var(--ac, #a855f7), 0 4px 16px rgba(0,0,0,0.05);
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

    .so-action.act-purple .so-action-icon  { background: #f5f3ff; color: #7c3aed; }
    .so-action.act-purple:hover .so-action-icon { background: #7c3aed; }
    .so-action.act-emerald .so-action-icon { background: #ecfdf5; color: #059669; }
    .so-action.act-emerald:hover .so-action-icon { background: #059669; }
    .so-action.act-amber .so-action-icon   { background: #fffbeb; color: #d97706; }
    .so-action.act-amber:hover .so-action-icon { background: #d97706; }
    .so-action.act-blue .so-action-icon    { background: #eff6ff; color: #2563eb; }
    .so-action.act-blue:hover .so-action-icon { background: #2563eb; }

    .so-action h4 { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .so-action p  { font-size: 12.5px; color: #6b7280; line-height: 1.5; }

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
        <h2><i class="fas fa-user-tie" style="margin-right:8px;opacity:0.7;"></i> Selamat Datang, {{ $user->Nama }}</h2>
        <p>Sistem Manajemen Risk Assessment Laboratorium — Kepala Laboratorium</p>
    </div>

    {{-- Statistics --}}
    <h3 class="so-section-title"><i class="fas fa-chart-line"></i> Statistik Risk Assessment</h3>
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="so-action" style="padding: 20px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; color: #6b7280; font-size: 13px;">Menunggu Review</h4>
                    <h3 style="margin: 8px 0 0; font-size: 24px;">{{ $statistics['menunggu'] }}</h3>
                </div>
                <div style="background: #fff7ed; color: #d97706; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="so-action" style="padding: 20px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; color: #6b7280; font-size: 13px;">Disetujui Bulan Ini</h4>
                    <h3 style="margin: 8px 0 0; font-size: 24px;">{{ $statistics['disetujui_bulan_ini'] }}</h3>
                </div>
                <div style="background: #ecfdf5; color: #059669; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="so-action" style="padding: 20px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; color: #6b7280; font-size: 13px;">Total Tahun Ini</h4>
                    <h3 style="margin: 8px 0 0; font-size: 24px;">{{ $statistics['total_tahun_ini'] }}</h3>
                </div>
                <div style="background: #eff6ff; color: #2563eb; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
    </div>

    <h3 class="so-section-title"><i class="fas fa-door-open"></i> Statistik Peminjaman Ruangan</h3>
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="so-action" style="padding: 20px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; color: #6b7280; font-size: 13px;">Menunggu Persetujuan</h4>
                    <h3 style="margin: 8px 0 0; font-size: 24px;">{{ $statistics['ruangan_menunggu'] }}</h3>
                </div>
                <div style="background: #fff1f2; color: #e11d48; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="so-action" style="padding: 20px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; color: #6b7280; font-size: 13px;">Disetujui Bulan Ini</h4>
                    <h3 style="margin: 8px 0 0; font-size: 24px;">{{ $statistics['ruangan_disetujui_bulan_ini'] }}</h3>
                </div>
                <div style="background: #f0fdf4; color: #16a34a; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-door-closed"></i>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 24px; margin-top: 32px;">
        {{-- Recent RA --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 class="so-section-title" style="margin-bottom: 0;"><i class="fas fa-history"></i> Riwayat Risk Assessment Terkini</h3>
                <a href="{{ route('kepala-lab.risk-assessment.report') }}" style="color: #7c3aed; font-size: 13px; font-weight: 600; text-decoration: none;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="so-action" style="padding: 0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                    <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <tr>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Mahasiswa</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Judul</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Status</th>
                            <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #4b5563;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentApprovals as $ra)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px 16px;">{{ $ra->user->Nama }}</td>
                            <td style="padding: 12px 16px;">{{ Str::limit($ra->topik_judul, 40) }}</td>
                            <td style="padding: 12px 16px;">
                                <span style="background: #ecfdf5; color: #065f46; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600;">Disetujui</span>
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" style="color: #7c3aed; text-decoration: none;"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 32px; text-align: center; color: #9ca3af;">Belum ada riwayat persetujuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Room Borrowing --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 class="so-section-title" style="margin-bottom: 0;"><i class="fas fa-history"></i> Riwayat Peminjaman Ruangan Terkini</h3>
                <a href="{{ route('kepala-lab.peminjaman-ruangan.report') }}" style="color: #2563eb; font-size: 13px; font-weight: 600; text-decoration: none;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="so-action" style="padding: 0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                    <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <tr>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Peminjam</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Lab</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #4b5563;">Status</th>
                            <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #4b5563;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRoomBorrowings as $room)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px 16px;">{{ $room->user_nama }}</td>
                            <td style="padding: 12px 16px;">{{ $room->daftarLab->Nama_Laboratorium }}</td>
                            <td style="padding: 12px 16px;">
                                @if(in_array($room->status, ['disetujui', 'disetujui_final']))
                                    <span style="background: #ecfdf5; color: #065f46; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600;">Disetujui</span>
                                @elseif($room->status === 'dikembalikan')
                                    <span style="background: #eff6ff; color: #1e40af; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600;">Selesai</span>
                                @else
                                    <span style="background: #fef2f2; color: #991b1b; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600;">Ditolak</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <a href="{{ route('kepala-lab.peminjaman-ruangan.show', $room->id) }}" style="color: #2563eb; text-decoration: none;"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 32px; text-align: center; color: #9ca3af;">Belum ada riwayat peminjaman ruangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <h3 class="so-section-title" style="margin-top: 32px;"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    <div class="so-actions">
        <a href="{{ route('kepala-lab.risk-assessment.index') }}" class="so-action act-purple" style="--ac:#7c3aed;">
            <div class="so-action-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4>Persetujuan RA</h4>
            <p>Tinjau pengajuan Risk Assessment</p>
        </a>
        <a href="{{ route('kepala-lab.peminjaman-ruangan.index') }}" class="so-action act-blue" style="--ac:#2563eb;">
            <div class="so-action-icon"><i class="fas fa-door-open"></i></div>
            <h4>Persetujuan Ruangan</h4>
            <p>Tinjau pengajuan peminjaman ruangan</p>
        </a>
        <a href="{{ route('kepala-lab.risk-assessment.report') }}" class="so-action act-emerald" style="--ac:#059669;">
            <div class="so-action-icon"><i class="fas fa-file-alt"></i></div>
            <h4>Laporan RA</h4>
            <p>Akses rekap data Risk Assessment</p>
        </a>
        <a href="{{ route('kepala-lab.peminjaman-ruangan.report') }}" class="so-action act-blue" style="--ac:#2563eb;">
            <div class="so-action-icon"><i class="fas fa-history"></i></div>
            <h4>Laporan Ruangan</h4>
            <p>Riwayat peminjaman ruangan lab</p>
        </a>
    </div>

</div>
@endsection
