@extends('layouts.app')
@section('title', 'Bebas Lab')
@section('page-title', 'Bebas Lab')

@push('styles')
<style>
    .progress-track {
        width: 100%;
        background: #e5e7eb;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.5s ease-in-out;
    }
    .progress-fill.green { background: #059669; }
    .progress-fill.blue { background: var(--color-primary, #667eea); }

    .approval-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        margin-bottom: 12px;
    }
    .approval-item.approved {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .approval-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .approval-icon.green { background: #059669; color: #fff; }
    .approval-icon.yellow { background: #d97706; color: #fff; }
    .approval-icon svg { width: 20px; height: 20px; }
    .approval-content { flex: 1; min-width: 0; }
    .approval-content h4 { font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
    .approval-meta { font-size: 12.5px; color: #6b7280; display: flex; align-items: center; gap: 6px; margin-bottom: 2px; }
    .approval-meta svg { width: 14px; height: 14px; flex-shrink: 0; }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .empty-state-icon svg { width: 32px; height: 32px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 8px; }
    .empty-state p { font-size: 13.5px; color: #6b7280; margin-bottom: 20px; }

    .info-panel {
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid;
        margin-bottom: 16px;
    }
    .info-panel.blue { background: #eff6ff; border-color: #3b82f6; }
    .info-panel.green { background: #f0fdf4; border-color: #22c55e; }
    .info-panel.red { background: #fef2f2; border-color: #ef4444; }
    .info-panel h4 { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
    .info-panel p { font-size: 13px; line-height: 1.6; color: #4b5563; margin: 0; }
    .info-panel.blue h4 { color: #1e40af; }
    .info-panel.blue p { color: #1e3a5f; }
    .info-panel.green h4 { color: #166534; }
    .info-panel.green p { color: #14532d; }
    .info-panel.red h4 { color: #991b1b; }
    .info-panel.red p { color: #7f1d1d; }

    .select-ra {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13.5px;
        color: #1f2937;
        background: #fff;
    }
    .select-ra:focus {
        outline: none;
        border-color: var(--color-primary, #667eea);
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }
</style>
@endpush

@section('content')
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-box alert-success" style="margin-bottom: 16px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-danger" style="margin-bottom: 16px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Form Pengajuan Baru --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2>Ajukan Bebas Lab Baru</h2>
        </div>
        <div class="card-body">
            @if($riskAssessments->isEmpty())
                <div class="empty-state" style="padding: 24px 20px;">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @if($bebasLabRequests->isEmpty())
                        <h3>Belum Ada Risk Assessment</h3>
                        <p>Tidak ada Risk Assessment yang tersedia untuk pengajuan Bebas Lab baru.</p>
                    @else
                        <p style="color: #6b7280; font-size: 13px;">Semua Risk Assessment yang disetujui sudah memiliki pengajuan Bebas Lab aktif.</p>
                    @endif
                </div>
            @else
                <form action="{{ route('peneliti-eksternal.bebas-lab.store') }}" method="POST" style="max-width: 540px; text-align: left;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151;">Pilih Risk Assessment:</label>
                        <select name="risk_assessment_id" id="risk_assessment_id" required class="select-ra">
                            <option value="">-- Pilih Risk Assessment yang Disetujui --</option>
                            @foreach($riskAssessments as $ra)
                                <option value="{{ $ra->id }}">
                                    {{ $ra->id_ra ?? $ra->id }} - {{ $ra->topik_judul }} ({{ $ra->daftarLab->Nama_Laboratorium ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <p style="margin-top: 6px; font-size: 12px; color: #6b7280;">Pilih Risk Assessment yang telah disetujui untuk mengajukan pembebasan lab</p>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 10px 20px;">
                        Ajukan Bebas Lab Sekarang
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Status Pengajuan Card(s) --}}
    @if($bebasLabRequests->isEmpty())
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h2>Status Pengajuan Bebas Lab</h2>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Pengajuan Aktif</h3>
                    <p>Anda belum memiliki pengajuan Bebas Lab yang aktif. Gunakan form di atas untuk mengajukan.</p>
                </div>
            </div>
        </div>
    @else
        @foreach($bebasLabRequests as $bebasLabRequest)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h2>Bebas Lab — {{ $bebasLabRequest->riskAssessment?->id_ra ?? 'RA-'.$bebasLabRequest->risk_assessment_id }}</h2>
            </div>
            <div class="card-body">
                @php
                    $totalApprovals = $bebasLabRequest->approvals->count();
                    $approvedCount = $bebasLabRequest->approvals->where('status', 'disetujui')->count();
                    $isApproved = $bebasLabRequest->isFullyApproved();
                    $isActive = $bebasLabRequest->is_active && $bebasLabRequest->isMasihBerlaku() && !$bebasLabRequest->hasPeminjamanAktif();
                    $canDownload = $isApproved && $isActive;
                    $progressPercentage = $totalApprovals > 0 ? ($approvedCount / $totalApprovals) * 100 : 0;
                @endphp

                {{-- Risk Assessment Info --}}
                <div class="info-panel blue">
                    <h4>📄 Risk Assessment Terkait</h4>
                    <p>
                        <strong>{{ $bebasLabRequest->riskAssessment?->id_ra ?? '-' }}</strong> 
                        — {{ $bebasLabRequest->riskAssessment?->topik_judul ?? '-' }}
                        ({{ $bebasLabRequest->riskAssessment?->daftarLab?->Nama_Laboratorium ?? '-' }})
                    </p>
                </div>

                {{-- Progress Bar --}}
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 13px; font-weight: 600; color: #374151;">Progress Persetujuan</span>
                        <span class="badge badge-primary">{{ $approvedCount }}/{{ $totalApprovals }} Laboran</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill {{ $progressPercentage >= 100 ? 'green' : 'blue' }}" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <p style="font-size: 11.5px; color: #9ca3af; margin-top: 4px;">{{ number_format($progressPercentage, 1) }}% selesai</p>
                </div>

                {{-- Status Badge --}}
                <div style="margin-bottom: 16px;">
                    @if($isApproved && $isActive)
                        <span class="badge badge-success" style="padding: 6px 14px; font-size: 13px;">✅ Disetujui & Aktif</span>
                    @elseif($isApproved && !$isActive)
                        <span class="badge badge-danger" style="padding: 6px 14px; font-size: 13px;">❌ Tidak Aktif</span>
                    @else
                        <span class="badge badge-warning" style="padding: 6px 14px; font-size: 13px;">⏳ Menunggu Persetujuan</span>
                    @endif
                </div>

                {{-- Action Section --}}
                @if($canDownload)
                    <div class="info-panel green" style="text-align: center;">
                        <h4 style="font-size: 16px; margin-bottom: 8px;">🎉 Pengajuan Disetujui!</h4>
                        <p style="margin-bottom: 16px;">Semua laboran telah menyetujui pengajuan Anda. Laporan siap diunduh.</p>
                        <a href="{{ route('peneliti-eksternal.bebas-lab.download', $bebasLabRequest->id) }}" class="btn btn-success" style="padding: 10px 24px;">
                            Download Laporan Bebas Lab
                        </a>
                    </div>
                @elseif($isApproved && !$isActive)
                    <div class="info-panel red">
                        <h4>⚠️ Status Tidak Aktif</h4>
                        <p>Bebas Lab Anda sudah tidak aktif karena ada peminjaman baru atau sudah melewati masa berlaku.</p>
                    </div>
                @else
                    <div class="info-panel blue">
                        <h4>ℹ️ Menunggu Persetujuan</h4>
                        <p>Pengajuan Anda sedang dalam proses review oleh laboran. Laporan akan dapat diunduh setelah <strong>SEMUA laboran</strong> menyetujui.</p>
                        <p style="margin-top: 8px;"><strong>Progress:</strong> {{ $approvedCount }} dari {{ $totalApprovals }} laboran telah menyetujui.</p>
                    </div>
                @endif

                {{-- Detail Persetujuan Laboran --}}
                <div style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h3 style="font-size: 15px; font-weight: 600; color: #374151; margin: 0;">Detail Persetujuan Laboran</h3>
                        @php
                            $totalLabs = $bebasLabRequest->approvals->count();
                            $approvedLabs = $bebasLabRequest->approvals->where('status', 'disetujui')->count();
                        @endphp
                        <span class="badge badge-secondary">{{ $approvedLabs }}/{{ $totalLabs }} Disetujui</span>
                    </div>

                    @if($bebasLabRequest->status === 'disetujui' && $bebasLabRequest->tanggal_berlaku_sampai)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
                            @if($bebasLabRequest->isMasihBerlaku())
                                <span class="badge badge-success" style="padding: 5px 12px;">
                                    ✅ Berlaku sampai {{ $bebasLabRequest->tanggal_berlaku_sampai->format('d M Y') }}
                                </span>
                            @else
                                <span class="badge badge-danger" style="padding: 5px 12px;">
                                    ❌ Sudah expired ({{ $bebasLabRequest->tanggal_berlaku_sampai->format('d M Y') }})
                                </span>
                            @endif
                            @if(!$bebasLabRequest->is_active)
                                <span class="badge" style="padding: 5px 12px; background: #374151; color: #fff;">
                                    Tidak aktif (ada peminjaman baru)
                                </span>
                            @endif
                        </div>
                    @endif

                    @foreach($bebasLabRequest->approvals as $approval)
                        <div class="approval-item {{ $approval->status === 'disetujui' ? 'approved' : '' }}">
                            <div class="approval-icon {{ $approval->status === 'disetujui' ? 'green' : 'yellow' }}">
                                @if($approval->status === 'disetujui')
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="approval-content">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                    <div>
                                        <h4>{{ $approval->lab?->Nama_Laboratorium ?? '-' }}</h4>
                                        <div class="approval-meta">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $approval->laboran_nama ?? '-' }}
                                        </div>
                                        @if($approval->approved_at)
                                            <div class="approval-meta">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $approval->approved_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($approval->status === 'disetujui')
                                            <span class="badge badge-success" style="padding: 5px 12px;">✅ Disetujui</span>
                                        @else
                                            <span class="badge badge-warning" style="padding: 5px 12px;">⏳ Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- Riwayat Peminjaman Alat Section --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2>Riwayat Peminjaman Alat</h2>
        </div>
        <div class="card-body" style="{{ $peminjamanAlats->isEmpty() ? '' : 'padding: 0;' }}">
            @if($peminjamanAlats->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Peminjaman</h3>
                    <p>Anda belum memiliki riwayat peminjaman alat laboratorium.</p>
                </div>
            @else
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Laboratorium</th>
                                <th>Nama Alat</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamanAlats as $alat)
                                <tr>
                                    <td>{{ $alat->alatLab?->daftarLab?->Nama_Laboratorium ?? '-' }}</td>
                                    <td><strong>{{ $alat->alatLab?->nama_alat ?? '-' }}</strong></td>
                                    <td>{{ $alat->tanggal_pinjam ? $alat->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $alat->tanggal_kembali ? $alat->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @php
                                            $statusBadges = [
                                                'disetujui' => 'badge-success',
                                                'menunggu' => 'badge-warning',
                                                'ditolak' => 'badge-danger',
                                                'selesai' => 'badge-info',
                                                'dikembalikan' => 'badge-info',
                                            ];
                                            $badgeClass = $statusBadges[strtolower($alat->status)] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($alat->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
