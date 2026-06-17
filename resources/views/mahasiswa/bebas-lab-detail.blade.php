@extends('layouts.app')
@section('title', 'Detail Bebas Lab')
@section('page-title', 'Detail Bebas Lab')

@push('styles')
<style>
    .card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin-bottom: 1.5rem;
    }
    .card-header h2 {
        color: #333;
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }
</style>
@endpush

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Ringkasan</h2>
        <a href="{{ route('mahasiswa.bebas-lab.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @php
        $isApproved = $bebasLabRequest->isFullyApproved();
        $isActive = $bebasLabRequest->is_active && $bebasLabRequest->isMasihBerlaku() && !$bebasLabRequest->hasPeminjamanAktif();
        $canDownload = $isApproved && $isActive;
    @endphp

    <div class="card">
        <div class="card-header">
            <h2>Informasi Bebas Lab</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><span class="font-semibold">Tanggal Pengajuan:</span> {{ $bebasLabRequest->created_at ? $bebasLabRequest->created_at->format('d/m/Y H:i') : '-' }}</div>
            <div><span class="font-semibold">Status:</span> {{ ucfirst($bebasLabRequest->status) }}</div>
            <div><span class="font-semibold">Aktif:</span> {{ $bebasLabRequest->is_active ? 'Aktif' : 'Tidak Aktif' }}</div>
            <div>
                <span class="font-semibold">Berlaku:</span>
                {{ $bebasLabRequest->tanggal_berlaku_dari ? $bebasLabRequest->tanggal_berlaku_dari->format('d/m/Y') : '-' }}
                s/d
                {{ $bebasLabRequest->tanggal_berlaku_sampai ? $bebasLabRequest->tanggal_berlaku_sampai->format('d/m/Y') : '-' }}
            </div>
            <div class="md:col-span-2">
                <span class="font-semibold">Risk Assessment:</span>
                {{ $bebasLabRequest->riskAssessment?->id_ra ?? $bebasLabRequest->risk_assessment_id ?? '-' }}
                - {{ $bebasLabRequest->riskAssessment?->topik_judul ?? '-' }}
            </div>
            <div class="md:col-span-2">
                <span class="font-semibold">Laboratorium:</span>
                {{ $bebasLabRequest->riskAssessment?->daftarLab?->Nama_Laboratorium ?? '-' }}
            </div>
        </div>

        <div class="mt-4">
            @if($canDownload)
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 16px; text-align: center;">
                    <p style="font-size: 14px; color: #065f46; margin-bottom: 10px; font-weight: 600;">
                        ✅ Semua laboran telah menyetujui. Laporan siap diunduh!
                    </p>
                    <a href="{{ route('mahasiswa.bebas-lab.download', $bebasLabRequest->id) }}" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; font-size: 14px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Laporan Bebas Lab
                    </a>
                </div>
            @else
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px;">
                    <p style="font-size: 13px; color: #92400e; margin: 0;">
                        ⏳ Download belum tersedia — menunggu semua laboran menyetujui atau bebas lab belum aktif.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-left-color: #16a34a;">
            <h2>Persetujuan Laboran</h2>
        </div>
        @if($bebasLabRequest->approvals->isNotEmpty())
            <ul class="text-sm text-gray-700 space-y-2">
                @foreach($bebasLabRequest->approvals as $approval)
                    <li>
                        {{ $approval->lab?->Nama_Laboratorium ?? '-' }} - {{ $approval->laboran_nama ?? '-' }}
                        ({{ $approval->status === 'disetujui' ? 'Disetujui' : 'Menunggu' }})
                        @if($approval->approved_at)
                            - {{ $approval->approved_at->format('d/m/Y H:i') }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-sm text-gray-500">Belum ada data persetujuan.</div>
        @endif
    </div>

    <div class="card">
        <div class="card-header" style="border-left-color: #0ea5e9;">
            <h2>Peminjaman Alat Terkait</h2>
        </div>
        @if($peminjamanAlats->isNotEmpty())
            <ul class="text-sm text-gray-700 space-y-2">
                @foreach($peminjamanAlats as $alat)
                    <li>
                        {{ $alat->alatLab?->nama_alat ?? '-' }}
                        ({{ $alat->tanggal_pinjam ? $alat->tanggal_pinjam->format('d/m/Y') : '-' }})
                        - {{ ucfirst($alat->status) }}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-sm text-gray-500">Tidak ada peminjaman alat untuk Risk Assessment ini.</div>
        @endif
    </div>

    <div class="card">
        <div class="card-header" style="border-left-color: #f59e0b;">
            <h2>Peminjaman Ruangan Terkait</h2>
        </div>
        @if($peminjamanRuangans->isNotEmpty())
            <ul class="text-sm text-gray-700 space-y-2">
                @foreach($peminjamanRuangans as $ruangan)
                    <li>
                        {{ $ruangan->daftarLab?->Nama_Laboratorium ?? '-' }}
                        ({{ $ruangan->tanggal ? $ruangan->tanggal->format('d/m/Y') : '-' }}
                        {{ $ruangan->jam_mulai ?? '' }}-{{ $ruangan->jam_selesai ?? '' }})
                        - {{ ucfirst($ruangan->status) }}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-sm text-gray-500">Tidak ada peminjaman ruangan untuk Risk Assessment ini.</div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('mahasiswa.bebas-lab.index') }}" class="btn btn-secondary">
            Kembali ke Bebas Lab
        </a>
    </div>
@endsection
