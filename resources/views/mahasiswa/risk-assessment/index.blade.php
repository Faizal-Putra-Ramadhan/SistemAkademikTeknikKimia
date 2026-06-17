@extends('layouts.app')
@section('title', 'Risk Assessment')
@section('page-title', 'Daftar Risk Assessment Saya')

@push('styles')
<style>
/* PAGE LAYOUT */
        .page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* BREADCRUMB */
        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb span {
            color: #6b7280;
        }

        /* STATS OVERVIEW */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #667eea;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* RA CARD */
        .ra-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
        }
        .ra-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
        }
        .ra-card.expired {
            border-left-color: #ef4444;
            background: linear-gradient(to right, rgba(239,68,68,0.03), transparent);
        }
        .ra-card.hampir-expired {
            border-left-color: #f59e0b;
            background: linear-gradient(to right, rgba(245,158,11,0.03), transparent);
        }
        
        .ra-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        .ra-title {
            color: #1f2937;
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            flex: 1;
            line-height: 1.4;
        }
        .status-badge {
            padding: 0.625rem 1.25rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-menunggu_dosen { background: #fef3c7; color: #92400e; }
        .status-menunggu_safety_officer { background: #dbeafe; color: #1e40af; }
        .status-menunggu_kepala_lab { background: #e0e7ff; color: #3730a3; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        
        .ra-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
            padding: 1.5rem 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        .meta-label {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5rem;
        }
        .meta-value {
            color: #374151;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .ra-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #5568d3 100%);
            color: white;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #5568d3 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            font-size: 1rem;
        }
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            padding: 1.25rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left-color: #10b981;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left-color: #f59e0b;
        }
        
        .expired-notice {
            background: linear-gradient(to right, rgba(239,68,68,0.1), transparent);
            border: 2px solid #ef4444;
            padding: 1.25rem;
            border-radius: 10px;
            margin-top: 1rem;
        }
        .expired-notice strong {
            color: #991b1b;
        }
        
        .hampir-expired-notice {
            background: linear-gradient(to right, rgba(245,158,11,0.1), transparent);
            border: 2px solid #f59e0b;
            padding: 1.25rem;
            border-radius: 10px;
            margin-top: 1rem;
        }
        .hampir-expired-notice strong {
            color: #92400e;
        }
        
        .perpanjangan-info {
            background: linear-gradient(to right, rgba(59,130,246,0.1), transparent);
            border-left: 4px solid #3b82f6;
            padding: 1.25rem;
            border-radius: 10px;
            margin-top: 1rem;
        }
        .perpanjangan-info strong {
            color: #1e40af;
        }
        
        .catatan-section {
            background: linear-gradient(to right, rgba(245,158,11,0.05), transparent);
            border-left: 4px solid #f59e0b;
            padding: 1.25rem;
            margin-top: 1rem;
            border-radius: 10px;
        }
        .catatan-section strong {
            color: #92400e;
        }
        .catatan-section p {
            margin: 0.5rem 0;
            color: #78350f;
        }

        .empty-state {
            background: white;
            padding: 3rem 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .empty-state p {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* PAGINATION */
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
</style>
@endpush

@section('content')
    <!-- BREADCRUMB -->
                <div class="breadcrumb">
                    <span>📊</span>
                    <span>Dashboard</span>
                    <span>/</span>
                    <span>Risk Assessment</span>
                </div>
    
                <!-- ALERTS -->
                @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span>
                    <div>{{ session('success') }}</div>
                </div>
                @endif
    
                @if(session('error'))
                <div class="alert alert-danger">
                    <span>❌</span>
                    <div>{{ session('error') }}</div>
                </div>
                @endif
    
                <!-- STATS OVERVIEW -->
                @if($riskAssessments->count() > 0)
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-number">{{ $riskAssessments->where('status', 'draft')->count() }}</div>
                        <div class="stat-label">📝 Draft</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $riskAssessments->where('status', 'disetujui')->count() }}</div>
                        <div class="stat-label">✅ Disetujui</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $riskAssessments->where('status', 'ditolak')->count() }}</div>
                        <div class="stat-label">❌ Ditolak</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $riskAssessments->where('status', 'menunggu_dosen')->count() }}</div>
                        <div class="stat-label">⏳ Menunggu Review</div>
                    </div>
                </div>
                @endif
    
                <!-- CREATE BUTTON -->
                @if($labs->first())
                    <a href="{{ route('mahasiswa.risk-assessment.create', $labs->first()->id) }}" class="btn-create">
                        Buat Risk Assessment Baru
                    </a>
                @else
                    <button class="btn-create" disabled style="opacity:0.6; cursor:not-allowed;">
                        Tidak ada lab tersedia
                    </button>
                @endif
    
                <!-- TAB MENU -->
                <div style="display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #e5e7eb;">
                    <button onclick="switchTab('daftar-ra')" id="tab-btn-daftar-ra" 
                            style="padding: 1rem 1.5rem; background: none; border: none; font-size: 1rem; font-weight: 600; color: #667eea; border-bottom: 3px solid #667eea; cursor: pointer; transition: all 0.3s;">
                        📋 Daftar Risk Assessment
                    </button>
                    <button onclick="switchTab('pilih-jadwal')" id="tab-btn-pilih-jadwal"
                            style="padding: 1rem 1.5rem; background: none; border: none; font-size: 1rem; font-weight: 600; color: #9ca3af; cursor: pointer; transition: all 0.3s;">
                        📅 Pilih Jadwal Wawancara
                    </button>
                </div>
    
                <!-- TAB CONTENT: DAFTAR RA -->
                <div id="tab-content-daftar-ra" style="display: block;">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅</span>
                        <div>{{ session('success') }}</div>
                    </div>
                    @endif
    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <span>❌</span>
                        <div>{{ session('error') }}</div>
                    </div>
                    @endif
    
                <!-- PENDING SCHEDULES WARNING -->
                @php
                    $pendingSchedules = collect();
                    foreach($riskAssessments as $ra) {
                        if($ra->jadwal_wawancara_options && !$ra->jadwal_wawancara_dipilih_at) {
                            $pendingSchedules->push($ra);
                        }
                    }
                @endphp
                
                @if($pendingSchedules->count() > 0)
                <div class="alert" style="background: #eef2ff; border-left-color: #667eea; color: #3730a3; margin-bottom: 2rem;">
                    <span style="font-size: 1.5rem;">⏰</span>
                    <div>
                        <strong>Jadwal Wawancara Menunggu Pilihan Anda</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.95rem;">
                            Ada {{ $pendingSchedules->count() }} Risk Assessment dengan jadwal wawancara yang perlu Anda pilih. 
                            <a href="#" onclick="switchTab('pilih-jadwal'); return false;" style="color: #667eea; font-weight: 600; text-decoration: underline;">Pilih sekarang →</a>
                        </p>
                    </div>
                </div>
                @endif
    
                @if($riskAssessments->count() > 0)
                    @foreach($riskAssessments as $ra)
                    <div class="ra-card {{ !$ra->isMasihBerlaku() ? 'expired' : ($ra->isHampirExpired() ? 'hampir-expired' : '') }}">
                        <div class="ra-header">
                            <h3 class="ra-title">{{ $ra->topik_judul }}</h3>
                            <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                {{ $ra->getStatusLabel() }}
                            </span>
                        </div>
    
                        <div class="ra-meta">
                            <div class="meta-item">
                                <span class="meta-label">🏫 Laboratorium</span>
                                <span class="meta-value">{{ $ra->daftarLab->Nama_Laboratorium }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">📋 Jenis</span>
                                <span class="meta-value">{{ $ra->jenis_ra }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">👨‍🏫 Dosen Pembimbing</span>
                                <span class="meta-value">{{ $ra->dosen_pembimbing_nama }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">📅 Tanggal Dibuat</span>
                                <span class="meta-value">{{ $ra->created_at->format('d M Y') }}</span>
                            </div>
                            @if($ra->kategori_resiko_dosen)
                            <div class="meta-item">
                                <span class="meta-label">⚠️ Kategori Resiko</span>
                                <span class="meta-value">{{ $ra->getKategoriResikoLabel() }}</span>
                            </div>
                            @endif
                            @if($ra->status === 'disetujui' && $ra->batas_waktu_peminjaman)
                            <div class="meta-item">
                                <span class="meta-label">⏰ Batas Waktu Peminjaman</span>
                                <span class="meta-value" style="color: {{ !$ra->isMasihBerlaku() ? '#ef4444' : ($ra->isHampirExpired() ? '#f59e0b' : '#065f46') }}">
                                    {{ $ra->getBatasWaktuPeminjamanFormatted() }}
                                    <br>
                                    <small>({{ $ra->getSisaWaktuPeminjaman() }})</small>
                                </span>
                            </div>
                            @endif
                        </div>
    
                        {{-- Notice jika sudah expired --}}
                        @if($ra->status === 'disetujui' && !$ra->isMasihBerlaku() && !$ra->hasPendingPerpanjangan())
                        <div class="expired-notice">
                            <strong style="color: #991b1b;">⚠️ Batas Waktu Peminjaman Sudah Berakhir!</strong>
                            <p style="margin-top: 0.5rem; color: #7f1d1d;">
                                Batas waktu peminjaman alat untuk Risk Assessment ini sudah melewati batas. Anda dapat mengajukan perpanjangan jika masih memerlukan akses laboratorium.
                            </p>
                        </div>
                        @endif
    
                        {{-- Notice jika hampir expired --}}
                        @if($ra->status === 'disetujui' && $ra->isHampirExpired() && !$ra->hasPendingPerpanjangan())
                        <div class="hampir-expired-notice">
                            <strong style="color: #92400e;">⏰ Batas Waktu Peminjaman Hampir Berakhir!</strong>
                            <p style="margin-top: 0.5rem; color: #78350f;">
                                Batas waktu peminjaman akan berakhir dalam {{ $ra->getSisaWaktuPeminjaman() }}. Segera ajukan perpanjangan jika masih memerlukan akses laboratorium.
                            </p>
                        </div>
                        @endif
    
                        {{-- Info pengajuan perpanjangan --}}
                        @if($ra->hasPendingPerpanjangan())
                        <div class="perpanjangan-info">
                            <strong style="color: #1e40af;">📝 Pengajuan Perpanjangan</strong>
                            <p style="margin-top: 0.5rem; color: #1e3a8a;">
                                Status: Menunggu persetujuan Kaprodi<br>
                                Durasi diminta: {{ $ra->durasi_perpanjangan_diminta }} bulan<br>
                                Diajukan: {{ $ra->tanggal_pengajuan_perpanjangan->format('d M Y, H:i') }}
                            </p>
                        </div>
                        @endif
    
                        @if($ra->catatan_dosen || $ra->catatan_safety_officer || $ra->catatan_kepala_lab)
                        <div class="catatan-section">
                            <strong>📝 Catatan Reviewer:</strong>
                            <div style="margin-top: 0.75rem;">
                                @if($ra->catatan_dosen)
                                    <p><strong>👨‍🏫 Dosen:</strong> {{ $ra->catatan_dosen }}</p>
                                @endif
                                @if($ra->catatan_safety_officer)
                                    <p><strong>🛡️ Safety Officer:</strong> {{ $ra->catatan_safety_officer }}</p>
                                @endif
                                @if($ra->catatan_kepala_lab)
                                    <p><strong>🏫 Kepala Lab:</strong> {{ $ra->catatan_kepala_lab }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
    
                        @if($ra->jadwal_wawancara)
                        <div class="perpanjangan-info">
                            <strong>📅 Jadwal Wawancara dengan Safety Officer</strong>
                            <p style="margin-top: 0.75rem; margin-bottom: 0;">
                                📍 <strong>{{ \Carbon\Carbon::parse($ra->jadwal_wawancara)->format('d M Y, H:i') }} WIB</strong>
                                @if($ra->tempat_wawancara)
                                    <br>📌 Tempat: {{ $ra->tempat_wawancara }}
                                @endif
                            </p>
                        </div>
                        @elseif($ra->jadwal_wawancara_options && !$ra->jadwal_wawancara_dipilih_at)
                        <div class="alert" style="background: #fef3c7; border-left-color: #f59e0b; color: #92400e; margin-top: 1rem; border-left: 4px solid #f59e0b;">
                            <span>⏰</span>
                            <div>
                                <strong>Jadwal Wawancara Menunggu Pilihan Anda</strong>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.95rem;">
                                    Safety Officer telah menyediakan {{ count($ra->jadwal_wawancara_options) }} opsi jadwal wawancara.
                                    <a href="{{ route('mahasiswa.risk-assessment.pending-schedules') }}" style="color: #92400e; font-weight: 600; text-decoration: underline;">Pilih sekarang →</a>
                                </p>
                            </div>
                        </div>
                        @endif
    
                        <div class="ra-actions">
                            <a href="{{ route('mahasiswa.risk-assessment.show', $ra->id) }}" class="btn btn-primary">
                                👁️ Lihat Detail
                            </a>
                            
                            @if($ra->status === 'draft')
                            <a href="{{ route('mahasiswa.risk-assessment.edit', $ra->id) }}" class="btn btn-secondary">
                                ✏️ Edit
                            </a>
                            @endif
    
                            {{-- Tombol Ajukan ke Kaprodi --}}
                        @if($ra->status === 'disetujui' && $ra->bisaAjukanKeKaprodi())
                        <form action="{{ route('mahasiswa.risk-assessment.ajukan-kaprodi', $ra->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan Risk Assessment ini ke Kaprodi untuk persetujuan final?')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                📤 Ajukan ke Kaprodi
                            </button>
                        </form>
                        @endif
    
                            @if($ra->status === 'disetujui')
                            <a href="{{ route('mahasiswa.risk-assessment.download-pdf', $ra->id) }}" class="btn btn-success">
                                📄 Download PDF
                            </a>
    
                            {{-- Tombol Ajukan Perpanjangan --}}
                            @if($ra->bisaAjukanPerpanjangan())
                            <a href="{{ route('mahasiswa.risk-assessment.perpanjangan', $ra->id) }}" class="btn btn-warning">
                                🔄 Ajukan Perpanjangan
                            </a>
                            @endif
    
                            {{-- Tombol Batalkan Perpanjangan --}}
                            @if($ra->hasPendingPerpanjangan())
                            <form action="{{ route('mahasiswa.risk-assessment.perpanjangan.batalkan', $ra->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan perpanjangan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    ❌ Batalkan Perpanjangan
                                </button>
                            </form>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
    
                    <!-- Pagination -->
                    <div style="margin-top: 2rem;">
                        {{ $riskAssessments->links() }}
                    </div>
                @else
                <div class="empty-state">
                    <p>Anda belum memiliki Risk Assessment.</p>
                    @if($labs->first())
                        <a href="{{ route('mahasiswa.risk-assessment.create', $labs->first()->id) }}" class="btn-create">
                            Buat Risk Assessment Pertama
                        </a>
                    @else
                        <button class="btn-create" disabled style="opacity:0.6; cursor:not-allowed;">
                            Tidak ada lab tersedia
                        </button>
                    @endif
                </div>
                @endif
                </div>
                <!-- END TAB CONTENT: DAFTAR RA -->
    
                <!-- TAB CONTENT: PILIH JADWAL WAWANCARA -->
                <div id="tab-content-pilih-jadwal" style="display: none;">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅</span>
                        <div>{{ session('success') }}</div>
                    </div>
                    @endif
    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <span>❌</span>
                        <div>{{ session('error') }}</div>
                    </div>
                    @endif
    
                    @if($pendingSchedules->count() > 0)
                        @foreach($pendingSchedules as $ra)
                        <div class="ra-card" style="border-left-color: #f59e0b; background: linear-gradient(to right, rgba(245,158,11,0.03), transparent);">
                            <div class="ra-header">
                                <h3 class="ra-title">📅 Pilih Jadwal Wawancara: {{ $ra->topik_judul }}</h3>
                            </div>
    
                            <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #f59e0b;">
                                <strong style="color: #92400e;">⏰ Safety Officer telah menyediakan {{ count($ra->jadwal_wawancara_options) }} opsi jadwal wawancara</strong>
                            </div>
    
                            <form action="{{ route('mahasiswa.risk-assessment.select-schedule', $ra->id) }}" method="POST" id="form-select-schedule-{{ $ra->id }}" style="margin-bottom: 1.5rem;">
                                @csrf
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                                    @foreach($ra->jadwal_wawancara_options as $index => $option)
                                        <label style="display: block; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; cursor: pointer; transition: all 0.3s;" 
                                               onclick="selectScheduleOption(this, {{ $ra->id }}, {{ $index }})"
                                               id="schedule-option-{{ $ra->id }}-{{ $index }}">
                                            
                                            <input type="radio" name="schedule_index" value="{{ $index }}" 
                                                   style="position: absolute; top: 1rem; right: 1rem; width: 20px; height: 20px; cursor: pointer;">
    
                                            <div>
                                                <div style="font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">🕐 Opsi {{ $index + 1 }}</div>
                                                <table style="width: 100%; font-size: 0.9rem;">
                                                    <tr>
                                                        <td style="color: #6b7280; padding: 4px 0;">📅 Tanggal:</td>
                                                        <td style="color: #1f2937; font-weight: 600; padding: 4px 0;">{{ \Carbon\Carbon::parse($option['jadwal'])->format('l, d M Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color: #6b7280; padding: 4px 0;">⏰ Jam:</td>
                                                        <td style="color: #1f2937; font-weight: 600; padding: 4px 0;">{{ $option['waktu'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color: #6b7280; padding: 4px 0;">📍 Lokasi:</td>
                                                        <td style="color: #1f2937; font-weight: 600; padding: 4px 0;">{{ $option['tempat'] }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
    
                                @if ($errors->has('schedule_index'))
                                <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #ef4444;">
                                    ❌ {{ $errors->first('schedule_index') }}
                                </div>
                                @endif
    
                                <div style="display: flex; gap: 1rem;">
                                    <button type="submit" id="btn-confirm-{{ $ra->id }}" disabled
                                            style="padding: 0.75rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; opacity: 0.5; transition: all 0.3s;">
                                        ✅ Konfirmasi Jadwal
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <p>Tidak ada jadwal wawancara yang menunggu pilihan Anda.</p>
                        </div>
                    @endif
                </div>
                <!-- END TAB CONTENT: PILIH JADWAL WAWANCARA -->
@endsection

@push('scripts')
<script>
// Switch tab function
function switchTab(tabName) {
    // Hide all tabs
    document.getElementById('tab-content-daftar-ra').style.display = 'none';
    document.getElementById('tab-content-pilih-jadwal').style.display = 'none';
    
    // Remove active style from all buttons
    document.getElementById('tab-btn-daftar-ra').style.color = '#9ca3af';
    document.getElementById('tab-btn-daftar-ra').style.borderBottomColor = 'transparent';
    document.getElementById('tab-btn-pilih-jadwal').style.color = '#9ca3af';
    document.getElementById('tab-btn-pilih-jadwal').style.borderBottomColor = 'transparent';
    
    // Show selected tab
    if (tabName === 'daftar-ra') {
        document.getElementById('tab-content-daftar-ra').style.display = 'block';
        document.getElementById('tab-btn-daftar-ra').style.color = '#667eea';
        document.getElementById('tab-btn-daftar-ra').style.borderBottomColor = '#667eea';
    } else if (tabName === 'pilih-jadwal') {
        document.getElementById('tab-content-pilih-jadwal').style.display = 'block';
        document.getElementById('tab-btn-pilih-jadwal').style.color = '#667eea';
        document.getElementById('tab-btn-pilih-jadwal').style.borderBottomColor = '#667eea';
    }
}

// Select schedule option function - update card styling and enable button
function selectScheduleOption(element, raId, index) {
    // Find the form
    const form = document.getElementById(`form-select-schedule-${raId}`);
    
    // Update radio button
    const radio = form.querySelector(`input[name="schedule_index"][value="${index}"]`);
    radio.checked = true;

    // Update card styling - remove selected from all, add to current
    form.querySelectorAll('label').forEach(label => {
        label.style.borderColor = '#e5e7eb';
        label.style.background = '#f9fafb';
    });
    element.style.borderColor = '#10b981';
    element.style.background = '#ecfdf5';

    // Enable submit button
    const submitBtn = document.getElementById(`btn-confirm-${raId}`);
    submitBtn.disabled = false;
    submitBtn.style.opacity = '1';
    submitBtn.style.cursor = 'pointer';
}

// Initialize on page load - restore styling for pre-selected options
document.addEventListener('DOMContentLoaded', function() {
    @foreach($pendingSchedules as $ra)
        const form{{ $ra->id }} = document.getElementById(`form-select-schedule-{{ $ra->id }}`);
        if (form{{ $ra->id }}) {
            const checkedRadio{{ $ra->id }} = form{{ $ra->id }}.querySelector('input[name="schedule_index"]:checked');
            if (checkedRadio{{ $ra->id }}) {
                const index = checkedRadio{{ $ra->id }}.value;
                const label = document.getElementById(`schedule-option-{{ $ra->id }}-${index}`);
                if (label) {
                    label.style.borderColor = '#10b981';
                    label.style.background = '#ecfdf5';
                }
                const btn = document.getElementById(`btn-confirm-{{ $ra->id }}`);
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                }
            }
        }
    @endforeach
});
</script>
@endpush
