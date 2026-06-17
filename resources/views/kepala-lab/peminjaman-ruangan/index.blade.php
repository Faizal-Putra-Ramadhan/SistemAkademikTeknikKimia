@extends('layouts.app')

@section('title', 'Peminjaman Ruangan')
@section('page-title', 'Peminjaman Ruangan')

@push('styles')
<style>
    .tab-navigation {
        display: flex;
        gap: 2rem;
        margin: 0 0 1.5rem 0;
        border-bottom: 2px solid #e5e7eb;
    }
    .tab-button {
        padding: 1rem 1.5rem;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        position: relative;
        bottom: -2px;
        transition: all 0.3s;
    }
    .tab-button.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
    }
    .tab-button:hover {
        color: #0d6efd;
    }
    .rental-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        border-left: 4px solid #0d6efd;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .rental-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .rental-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .rental-title {
        color: #333;
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0;
    }
    .rental-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    .info-item {
        display: flex;
        flex-direction: column;
    }
    .info-label {
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #1f2937;
        font-weight: 600;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
    <div style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">📋 Persetujuan Peminjaman Ruangan</h2>
    </div>

    <!-- Tabs -->
    <div class="tab-navigation">
        <button class="tab-button active" onclick="showTab('waiting')">
            ⏳ Menunggu Persetujuan ({{ $peminjamanMenunggu->total() ?? 0 }})
        </button>
        <button class="tab-button" onclick="showTab('processed')">
            ✅ Sudah Diproses ({{ $peminjamanDiproses->total() ?? 0 }})
        </button>
    </div>

    <!-- Waiting for Approval Tab -->
    <div id="waiting-tab" class="tab-content">
        @forelse ($peminjamanMenunggu as $peminjaman)
            <div class="rental-card">
                <div class="rental-header">
                    <div>
                        <h3 class="rental-title">{{ $peminjaman->nama_ruangan ?? 'Ruangan' }}</h3>
                    </div>
                    <span class="badge {{ $peminjaman->status == 'menunggu_kepala_lab' ? 'badge-warning' : ($peminjaman->status == 'disetujui_laboran' ? 'badge-info' : 'badge-secondary') }}">
                        @switch($peminjaman->status)
                            @case('menunggu_kepala_lab')
                                ⏳ Menunggu Persetujuan
                                @break
                            @case('disetujui_laboran')
                                📋 Disetujui Laboran
                                @break
                            @default
                                {{ $peminjaman->status }}
                        @endswitch
                    </span>
                </div>

                <div class="rental-info">
                    <div class="info-item">
                        <span class="info-label">👤 Peminjam</span>
                        <span class="info-value">{{ $peminjaman->user_nama ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Tanggal Peminjaman</span>
                        <span class="info-value">{{ $peminjaman->tanggal ? date('d/m/Y', strtotime($peminjaman->tanggal)) : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">⏰ Jam</span>
                        <span class="info-value">{{ $peminjaman->jam_mulai ?? 'N/A' }} - {{ $peminjaman->jam_selesai ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📝 Keperluan</span>
                        <span class="info-value">{{ $peminjaman->keperluan ?? 'N/A' }}</span>
                    </div>
                </div>

                @if ($peminjaman->catatan_laboran)
                    <div class="info-item" style="margin-bottom: 1rem;">
                        <span class="info-label">📌 Catatan Laboran</span>
                        <span class="info-value">{{ $peminjaman->catatan_laboran }}</span>
                    </div>
                @endif

                <div class="action-buttons">
                    <a href="{{ route('kepala-lab.peminjaman-ruangan.show', $peminjaman->id) }}" class="btn btn-primary btn-sm">
                        👁️ Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Tidak ada peminjaman ruangan yang menunggu persetujuan Anda</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($peminjamanMenunggu->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $peminjamanMenunggu->links() }}
            </div>
        @endif
    </div>

    <!-- Processed Tab -->
    <div id="processed-tab" class="tab-content" style="display: none;">
        @forelse ($peminjamanDiproses as $peminjaman)
            <div class="rental-card">
                <div class="rental-header">
                    <div>
                        <h3 class="rental-title">{{ $peminjaman->nama_ruangan ?? 'Ruangan' }}</h3>
                    </div>
                    <span class="badge {{ in_array($peminjaman->status, ['disetujui', 'disetujui_final', 'dikembalikan']) ? 'badge-success' : ($peminjaman->status == 'ditolak' ? 'badge-danger' : 'badge-secondary') }}">
                        @switch($peminjaman->status)
                            @case('disetujui')
                            @case('disetujui_final')
                                ✅ Disetujui
                                @break
                            @case('dikembalikan')
                                🔄 Selesai
                                @break
                            @case('ditolak')
                                ❌ Ditolak
                                @break
                            @default
                                {{ $peminjaman->status }}
                        @endswitch
                    </span>
                </div>

                <div class="rental-info">
                    <div class="info-item">
                        <span class="info-label">👤 Peminjam</span>
                        <span class="info-value">{{ $peminjaman->user_nama ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Tanggal Peminjaman</span>
                        <span class="info-value">{{ $peminjaman->tanggal ? date('d/m/Y', strtotime($peminjaman->tanggal)) : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">⏰ Jam</span>
                        <span class="info-value">{{ $peminjaman->jam_mulai ?? 'N/A' }} - {{ $peminjaman->jam_selesai ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📝 Keperluan</span>
                        <span class="info-value">{{ $peminjaman->keperluan ?? 'N/A' }}</span>
                    </div>
                </div>

                @if ($peminjaman->catatan_kepala_lab)
                    <div class="info-item" style="margin-bottom: 1rem;">
                        <span class="info-label">📌 Catatan Kepala Lab</span>
                        <span class="info-value">{{ $peminjaman->catatan_kepala_lab }}</span>
                    </div>
                @endif

                <div class="action-buttons">
                    <a href="{{ route('kepala-lab.peminjaman-ruangan.show', $peminjaman->id) }}" class="btn btn-primary btn-sm">
                        👁️ Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Tidak ada peminjaman ruangan yang sudah diproses</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($peminjamanDiproses->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $peminjamanDiproses->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function showTab(tabName) {
        document.getElementById('waiting-tab').style.display = 'none';
        document.getElementById('processed-tab').style.display = 'none';

        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });

        document.getElementById(tabName + '-tab').style.display = 'block';
        event.target.classList.add('active');
    }
</script>
@endpush
