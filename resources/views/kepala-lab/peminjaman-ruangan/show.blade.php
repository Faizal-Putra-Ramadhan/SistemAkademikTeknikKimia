@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@push('styles')
<style>
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .info-item {
        display: flex;
        flex-direction: column;
    }
    .info-label {
        color: #6b7280;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    .info-value {
        color: #1f2937;
        font-size: 1rem;
        font-weight: 600;
    }
    .text-area {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.9rem;
        min-height: 100px;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .approval-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #0d6efd;
    }
    .approval-info {
        background: #e0e7ff;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        color: #3730a3;
        font-size: 0.9rem;
    }
    .approval-info strong {
        display: block;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700;">📋 Detail Peminjaman Ruangan</h2>
        <a href="{{ route('kepala-lab.peminjaman-ruangan.index') }}" class="btn btn-secondary">
            ← Kembali ke Daftar
        </a>
    </div>

    <!-- Main Information -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 700; color: #1f2937; margin: 0;">
                        {{ $peminjaman->nama_ruangan ?? 'Ruangan' }}
                    </h1>
                </div>
                <span class="badge {{ $peminjaman->status == 'menunggu_kepala_lab' ? 'badge-warning' : ($peminjaman->status == 'disetujui_laboran' ? 'badge-info' : ($peminjaman->status == 'disetujui' ? 'badge-success' : ($peminjaman->status == 'ditolak' ? 'badge-danger' : 'badge-secondary'))) }}">
                    @switch($peminjaman->status)
                        @case('menunggu_kepala_lab')
                            ⏳ Menunggu Persetujuan
                            @break
                        @case('disetujui_laboran')
                            📋 Disetujui Laboran
                            @break
                        @case('disetujui')
                            ✅ Disetujui
                            @break
                        @case('ditolak')
                            ❌ Ditolak
                            @break
                        @default
                            {{ $peminjaman->status }}
                    @endswitch
                </span>
            </div>

            <div class="section-title">📝 Informasi Peminjaman</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">👤 Nama Peminjam</span>
                    <span class="info-value">{{ $peminjaman->user_nama ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">📧 Email</span>
                    <span class="info-value">{{ $peminjaman->user->Email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">🏢 Laboratorium</span>
                    <span class="info-value">{{ $peminjaman->daftarLab->Nama_Laboratorium ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">📅 Tanggal Peminjaman</span>
                    <span class="info-value">{{ $peminjaman->tanggal ? date('d/m/Y', strtotime($peminjaman->tanggal)) : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">⏰ Jam Mulai</span>
                    <span class="info-value">{{ $peminjaman->jam_mulai ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">⏰ Jam Selesai</span>
                    <span class="info-value">{{ $peminjaman->jam_selesai ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-item">
                <span class="info-label">📝 Keperluan Peminjaman</span>
                <span class="info-value">{{ $peminjaman->keperluan ?? 'Tidak ada deskripsi' }}</span>
            </div>
        </div>
    </div>

    <!-- Laboran Approval Info -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div class="section-title">📋 Persetujuan Laboran</div>
            
            @if ($peminjaman->laboran)
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">✍️ Laboran</span>
                        <span class="info-value">{{ $peminjaman->laboran->Nama ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">✅ Status Persetujuan</span>
                        <span class="info-value" style="color: {{ $peminjaman->persetujuan_laboran ? '#10b981' : '#ef4444' }};">
                            {{ $peminjaman->persetujuan_laboran ? '✅ Disetujui' : '❌ Ditolak / Belum Diproses' }}
                        </span>
                    </div>
                    @if ($peminjaman->tanggal_persetujuan_laboran)
                        <div class="info-item">
                            <span class="info-label">📅 Tanggal Persetujuan</span>
                            <span class="info-value">{{ date('d/m/Y H:i', strtotime($peminjaman->tanggal_persetujuan_laboran)) }}</span>
                        </div>
                    @endif
                </div>

                @if ($peminjaman->catatan_laboran)
                    <div class="info-item" style="margin-top: 1rem;">
                        <span class="info-label">💬 Catatan Laboran</span>
                        <span class="info-value">{{ $peminjaman->catatan_laboran }}</span>
                    </div>
                @endif
            @else
                <div class="approval-info">
                    ⏳ Menunggu persetujuan dari Laboran
                </div>
            @endif
        </div>
    </div>

    <!-- Approval Section for Kepala Lab -->
    @if (in_array($peminjaman->status, ['disetujui_laboran', 'menunggu_kepala_lab']))
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <div class="section-title">✅ Persetujuan Kepala Laboratorium</div>

                <div class="approval-section">
                    <div class="approval-info">
                        🔔 Silakan review informasi peminjaman di atas dan memberikan keputusan Anda
                    </div>

                    <!-- Approve Form -->
                    <form action="{{ route('kepala-lab.peminjaman-ruangan.approve', $peminjaman->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">💭 Catatan (Opsional)</label>
                            <textarea name="catatan" class="text-area" placeholder="Tuliskan catatan tambahan jika diperlukan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Anda yakin ingin menyetujui peminjaman ini?')">
                            ✅ Setujui Peminjaman
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form action="{{ route('kepala-lab.peminjaman-ruangan.reject', $peminjaman->id) }}" method="POST" style="display: inline; margin-left: 1rem;">
                        @csrf
                        <div class="form-group" style="display: none;">
                            <textarea id="reject-reason" name="catatan" class="text-area" placeholder="Alasan penolakan (wajib diisi)..." style="display: none;"></textarea>
                        </div>
                        <button type="button" class="btn btn-danger" onclick="showRejectForm()">
                            ❌ Tolak Peminjaman
                        </button>
                    </form>
                </div>

                <!-- Hidden Reject Form -->
                <div id="reject-form" style="display: none; margin-top: 2rem; padding: 1.5rem; background: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444;">
                    <div class="form-group">
                        <label class="form-label" style="color: #991b1b;">⚠️ Alasan Penolakan (Wajib Diisi)</label>
                        <textarea id="reject-reason-input" class="text-area" style="border-color: #fecaca;" placeholder="Tuliskan alasan mengapa peminjaman ditolak..."></textarea>
                    </div>
                    <div class="action-buttons">
                        <form id="reject-submit-form" action="{{ route('kepala-lab.peminjaman-ruangan.reject', $peminjaman->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="catatan" id="catatan-input">
                            <button type="button" class="btn btn-danger" onclick="submitReject()">
                                ✅ Konfirmasi Penolakan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideRejectForm()" style="margin-left: 0.5rem;">
                                ❌ Batal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($peminjaman->status == 'disetujui')
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <div class="section-title">✅ Sudah Disetujui</div>
                <div class="approval-info" style="background: #d1fae5; color: #065f46;">
                    <strong>✅ Status: DISETUJUI</strong>
                    Peminjaman ruangan telah disetujui oleh Kepala Laboratorium.
                    @if ($peminjaman->catatan_kepala_lab)
                        <strong style="display: block; margin-top: 0.5rem;">Catatan:</strong>
                        {{ $peminjaman->catatan_kepala_lab }}
                    @endif
                </div>
            </div>
        </div>
    @elseif ($peminjaman->status == 'ditolak')
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <div class="section-title">❌ Sudah Ditolak</div>
                <div class="approval-info" style="background: #fee2e2; color: #991b1b;">
                    <strong>❌ Status: DITOLAK</strong>
                    Peminjaman ruangan telah ditolak oleh Kepala Laboratorium.
                    @if ($peminjaman->catatan_kepala_lab)
                        <strong style="display: block; margin-top: 0.5rem;">Alasan:</strong>
                        {{ $peminjaman->catatan_kepala_lab }}
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function showRejectForm() {
        document.getElementById('reject-form').style.display = 'block';
        document.querySelector('.approval-section').style.display = 'none';
    }

    function hideRejectForm() {
        document.getElementById('reject-form').style.display = 'none';
        document.querySelector('.approval-section').style.display = 'block';
    }

    function submitReject() {
        const reason = document.getElementById('reject-reason-input').value;
        if (!reason.trim()) {
            alert('Alasan penolakan wajib diisi!');
            return;
        }
        document.getElementById('catatan-input').value = reason;
        document.getElementById('reject-submit-form').submit();
    }
</script>
@endpush
