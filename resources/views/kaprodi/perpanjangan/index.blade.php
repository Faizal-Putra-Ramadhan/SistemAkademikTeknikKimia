@extends('layouts.app')
@section('title', 'Perpanjangan RA')
@section('page-title', 'Perpanjangan Risk Assessment')

@push('styles')
<style>
.section-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .section-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
        }
        .ra-card {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #f59e0b;
            transition: all 0.2s;
        }
        .ra-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateX(4px);
        }
        .ra-card.riwayat {
            border-left-color: #6b7280;
            background: white;
        }
        .ra-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .ra-title {
            color: #111827;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        .ra-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #374151;
            font-weight: 500;
        }
        .alasan-box {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
            margin-top: 1rem;
        }
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .tab:hover {
            color: #667eea;
        }
</style>
@endpush

@section('content')
@if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
            @endif

            {{-- Tabs --}}
            <div class="tabs">
                <div class="tab active" onclick="showTab('pending')">
                    📋 Menunggu Persetujuan ({{ $pengajuanPerpanjangan->total() }})
                </div>
                <div class="tab" onclick="showTab('riwayat')">
                    📚 Riwayat ({{ $riwayatPerpanjangan->total() }})
                </div>
            </div>

            {{-- Tab Content: Pending --}}
            <div id="tab-pending" class="tab-content">
                <div class="section-card">
                    <h3 class="section-title">⏳ Pengajuan Perpanjangan Menunggu Persetujuan</h3>

                    @if($pengajuanPerpanjangan->count() > 0)
                        @foreach($pengajuanPerpanjangan as $ra)
                        <div class="ra-card">
                            <div class="ra-header">
                                <div>
                                    <h4 class="ra-title">{{ $ra->topik_judul }}</h4>
                                    <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                                        oleh {{ $ra->nama }} ({{ $ra->nim }})
                                    </p>
                                </div>
                                <span class="badge badge-pending">
                                    ⏳ Menunggu Review
                                </span>
                            </div>

                            <div class="ra-info">
                                <div class="info-item">
                                    <span class="info-label">🏫 Laboratorium</span>
                                    <span class="info-value">{{ $ra->daftarLab->Nama_Laboratorium }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">📅 Diajukan</span>
                                    <span class="info-value">{{ $ra->tanggal_pengajuan_perpanjangan->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">⏰ Batas Waktu Saat Ini</span>
                                    <span class="info-value" style="color: #ef4444;">
                                        {{ $ra->getBatasWaktuPeminjamanFormatted() }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">🔄 Durasi Diminta</span>
                                    <span class="info-value">{{ $ra->durasi_perpanjangan_diminta }} Bulan</span>
                                </div>
                                @if($ra->jumlah_perpanjangan > 0)
                                <div class="info-item">
                                    <span class="info-label">📊 Riwayat Perpanjangan</span>
                                    <span class="info-value">{{ $ra->jumlah_perpanjangan }} kali</span>
                                </div>
                                @endif
                            </div>

                            <div class="alasan-box">
                                <strong style="color: #1e40af;">📝 Alasan Perpanjangan:</strong>
                                <p style="margin-top: 0.5rem; color: #374151; line-height: 1.6;">
                                    {{ $ra->alasan_perpanjangan }}
                                </p>
                            </div>

                            <div style="margin-top: 1.5rem;">
                                <a href="{{ route('kaprodi.perpanjangan.show', $ra->id) }}" class="btn btn-primary">
                                    👁️ Review & Proses
                                </a>
                            </div>
                        </div>
                        @endforeach

                        <div style="margin-top: 2rem;">
                            {{ $pengajuanPerpanjangan->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <p style="font-size: 1.1rem;">Tidak ada pengajuan perpanjangan yang menunggu persetujuan.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tab Content: Riwayat --}}
            <div id="tab-riwayat" class="tab-content" style="display: none;">
                <div class="section-card">
                    <h3 class="section-title">📚 Riwayat Perpanjangan</h3>

                    @if($riwayatPerpanjangan->count() > 0)
                        @foreach($riwayatPerpanjangan as $ra)
                        <div class="ra-card riwayat">
                            <div class="ra-header">
                                <div>
                                    <h4 class="ra-title">{{ $ra->topik_judul }}</h4>
                                    <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">
                                        oleh {{ $ra->nama }} ({{ $ra->nim }})
                                    </p>
                                </div>
                                <span class="badge {{ $ra->persetujuan_perpanjangan_kaprodi ? 'badge-approved' : 'badge-rejected' }}">
                                    {{ $ra->persetujuan_perpanjangan_kaprodi ? '✅ Disetujui' : '❌ Ditolak' }}
                                </span>
                            </div>

                            <div class="ra-info">
                                <div class="info-item">
                                    <span class="info-label">📅 Diproses</span>
                                    <span class="info-value">{{ $ra->tanggal_persetujuan_perpanjangan->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">🔄 Durasi Diminta</span>
                                    <span class="info-value">{{ $ra->durasi_perpanjangan_diminta }} Bulan</span>
                                </div>
                                @if($ra->persetujuan_perpanjangan_kaprodi)
                                <div class="info-item">
                                    <span class="info-label">✅ Durasi Disetujui</span>
                                    <span class="info-value">{{ $ra->durasi_perpanjangan_disetujui }} Bulan</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">⏰ Batas Waktu Baru</span>
                                    <span class="info-value" style="color: #10b981;">
                                        {{ $ra->getBatasWaktuPeminjamanFormatted() }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            @if($ra->catatan_perpanjangan_kaprodi)
                            <div class="alasan-box">
                                <strong style="color: #1e40af;">💬 Catatan Kaprodi:</strong>
                                <p style="margin-top: 0.5rem; color: #374151;">
                                    {{ $ra->catatan_perpanjangan_kaprodi }}
                                </p>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <div style="margin-top: 2rem;">
                            {{ $riwayatPerpanjangan->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <p style="font-size: 1.1rem;">Belum ada riwayat perpanjangan.</p>
                        </div>
                    @endif
                </div>
            </div>
@endsection

@push('scripts')
<script>
function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.style.display = 'none';
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabName).style.display = 'block';
        
        // Add active class to selected tab
        event.target.classList.add('active');
    }
</script>
@endpush
