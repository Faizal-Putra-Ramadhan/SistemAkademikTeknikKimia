@extends('layouts.app')

@section('title', 'Review Risk Assessment')
@section('page-title', 'Review Risk Assessment')

@push('styles')
<style>
    .detail-section {
        margin-bottom: 1.5rem;
    }
    .detail-section h3 {
        color: #333;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #0d6efd;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    .detail-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .detail-value {
        color: #374151;
        font-size: 1rem;
    }
    .approval-form {
        border: 3px solid #0d6efd;
    }
    .radio-group {
        display: flex;
        gap: 1rem;
        margin-top: 0.75rem;
    }
    .radio-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }
    .radio-item:hover {
        border-color: #0d6efd;
        background-color: #f8f9ff;
    }
    .radio-item input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-right: 0.75rem;
    }
    .radio-item.success:hover {
        border-color: #10b981;
        background-color: #d1fae5;
    }
    .radio-item.danger:hover {
        border-color: #ef4444;
        background-color: #fee2e2;
    }
    .approval-timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0;
        width: 2px;
        height: 100%;
        background: #e5e7eb;
    }
    .timeline-dot {
        position: absolute;
        left: -2.5rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e5e7eb;
    }
    .timeline-dot.active { background: #10b981; box-shadow: 0 0 0 2px #10b981; }
    .timeline-dot.pending { background: #fbbf24; box-shadow: 0 0 0 2px #fbbf24; }
</style>
@endpush

@section('content')
    @if($riskAssessment->status !== 'menunggu_kepala_lab')
    <div class="alert-box warning">
        ⚠️ <strong>Perhatian:</strong> Risk Assessment ini sudah diproses sebelumnya.
    </div>
    @endif

    <!-- Header dengan Info Mahasiswa -->
    <div class="card detail-section">
        <div class="card-body">
            <h2 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin-bottom: 1rem;">
                {{ $riskAssessment->topik_judul }}
            </h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">👤 Mahasiswa</span>
                    <span class="detail-value">{{ $riskAssessment->nama }} ({{ $riskAssessment->nim }})</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">🏫 Laboratorium</span>
                    <span class="detail-value">{{ $riskAssessment->daftarLab->nama_lab }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">📋 Jenis</span>
                    <span class="detail-value">{{ $riskAssessment->jenis_ra }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">📅 Tanggal Diajukan</span>
                    <span class="detail-value">{{ $riskAssessment->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Timeline -->
    <div class="card detail-section">
        <div class="card-body">
            <h3>📊 Riwayat Persetujuan</h3>
            
            <div class="approval-timeline">
                <!-- Dosen Pembimbing -->
                <div class="timeline-item">
                    <div class="timeline-dot active"></div>
                    <div>
                        <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            👨‍🏫 Dosen Pembimbing
                        </h4>
                        <p style="color: #6b7280; font-size: 0.9rem;">{{ $riskAssessment->dosen_pembimbing_nama }}</p>
                        <p style="margin-top: 0.5rem;">
                            Status: <strong style="color: #10b981;">Disetujui ✅</strong>
                        </p>
                        <p style="margin-top: 0.25rem;">
                            Kategori Resiko: 
                            <span class="badge {{ $riskAssessment->kategori_resiko_dosen == 'tinggi' ? 'badge-danger' : ($riskAssessment->kategori_resiko_dosen == 'sedang' ? 'badge-warning' : 'badge-success') }}">
                                {{ $riskAssessment->getKategoriResikoLabel() }}
                            </span>
                        </p>
                        @if($riskAssessment->catatan_dosen)
                        <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #0d6efd; border-radius: 4px;">
                            <strong>Catatan:</strong><br>
                            {{ $riskAssessment->catatan_dosen }}
                        </div>
                        @endif
                        <p style="margin-top: 0.5rem; color: #9ca3af; font-size: 0.85rem;">
                            {{ $riskAssessment->tanggal_persetujuan_dosen->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Safety Officer -->
                <div class="timeline-item">
                    <div class="timeline-dot active"></div>
                    <div>
                        <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            🛡️ Safety Officer
                        </h4>
                        <p style="color: #6b7280; font-size: 0.9rem;">{{ $riskAssessment->safety_officer_nama }}</p>
                        
                        @if($riskAssessment->jadwal_wawancara)
                        <div style="margin-top: 0.75rem; padding: 0.75rem; background: #dbeafe; border-left: 3px solid #3b82f6; border-radius: 4px;">
                            <strong>📅 Jadwal Wawancara:</strong><br>
                            {{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('d M Y, H:i') }} WIB
                        </div>
                        @endif

                        <p style="margin-top: 0.5rem;">
                            Status: <strong style="color: #10b981;">Disetujui ✅</strong>
                        </p>
                        @if($riskAssessment->catatan_safety_officer)
                        <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #0d6efd; border-radius: 4px;">
                            <strong>Catatan:</strong><br>
                            {{ $riskAssessment->catatan_safety_officer }}
                        </div>
                        @endif
                        <p style="margin-top: 0.5rem; color: #9ca3af; font-size: 0.85rem;">
                            {{ $riskAssessment->tanggal_persetujuan_safety_officer->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Kepala Lab -->
                <div class="timeline-item">
                    <div class="timeline-dot pending"></div>
                    <div>
                        <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            🏛️ Kepala Laboratorium (Anda)
                        </h4>
                        <p style="margin-top: 0.5rem; color: #fbbf24;">
                            ⏳ Menunggu keputusan Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Lengkap Risk Assessment -->
    <div class="card detail-section">
        <div class="card-body">
            <h3>⚗️ Bahan Kimia yang Digunakan</h3>
            @foreach($riskAssessment->bahanKimias as $index => $bahan)
            <div style="border: 2px solid #e5e7eb; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                <h4 style="color: #0d6efd; margin-bottom: 1rem; font-weight: 600;">
                    Bahan #{{ $index + 1 }}: {{ $bahan->nama_bahan }}
                </h4>
                <div>
                    @if($bahan->explosive) <span class="badge badge-danger">☢️ Explosive</span> @endif
                    @if($bahan->flammable) <span class="badge badge-warning">🔥 Flammable</span> @endif
                    @if($bahan->toxic) <span class="badge badge-danger">☠️ Toxic</span> @endif
                    @if($bahan->corrosive) <span class="badge badge-warning">⚗️ Corrosive</span> @endif
                    @if($bahan->irritant) <span class="badge badge-info">⚠️ Irritant</span> @endif
                    @if($bahan->oxidizing) <span class="badge badge-info">💨 Oxidizing</span> @endif
                    @if($bahan->lain_lain) <span class="badge badge-info">{{ $bahan->lain_lain }}</span> @endif
                </div>
                @if($bahan->msds_file)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('msds.show', $bahan->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
                        📄 Lihat/Download MSDS
                    </a>
                </div>
                @endif
            </div>
            @endforeach
            
            <div class="detail-item" style="margin-top: 1.5rem;">
                <span class="detail-label">Kategori Tingkat Hazard Bahan</span>
                <span class="badge {{ $riskAssessment->kategoriHazardBahan->kategori == 'sangat_hazardous' || $riskAssessment->kategoriHazardBahan->kategori == 'hazardous' ? 'badge-danger' : ($riskAssessment->kategoriHazardBahan->kategori == 'moderat' ? 'badge-warning' : 'badge-success') }}">
                    {{ $riskAssessment->kategoriHazardBahan->getKategoriLabel() }}
                </span>
            </div>
        </div>
    </div>

    <div class="card detail-section">
        <div class="card-body">
            <h3>🔧 Peralatan & Kondisi Operasi</h3>
            <div>
                @if($riskAssessment->peralatanOperasi->tekanan_tinggi)
                    <span class="badge badge-warning">⚡ Tekanan Tinggi</span>
                @endif
                @if($riskAssessment->peralatanOperasi->suhu_tinggi)
                    <span class="badge badge-danger">🌡️ Suhu Tinggi</span>
                @endif
                @if($riskAssessment->peralatanOperasi->nyala_api)
                    <span class="badge badge-danger">🔥 Nyala Api</span>
                @endif
                @if($riskAssessment->peralatanOperasi->peralatan_berputar)
                    <span class="badge badge-info">⚙️ Peralatan Berputar</span>
                @endif
            </div>

            <div class="detail-grid" style="margin-top: 1.5rem;">
                @if($riskAssessment->peralatanOperasi->temperatur_maksimum)
                <div class="detail-item">
                    <span class="detail-label">Temperatur Maksimum</span>
                    <span class="detail-value">{{ $riskAssessment->peralatanOperasi->temperatur_maksimum }} °C</span>
                </div>
                @endif

                @if($riskAssessment->peralatanOperasi->tekanan_maksimum)
                <div class="detail-item">
                    <span class="detail-label">Tekanan Maksimum</span>
                    <span class="detail-value">{{ $riskAssessment->peralatanOperasi->tekanan_maksimum }} atm</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Approval -->
    @if($riskAssessment->status === 'menunggu_kepala_lab')
    <div class="card approval-form">
        <div class="card-body">
            <h3 style="color: #333; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center;">
                🔐 Final Approval - Kepala Laboratorium
            </h3>

            <form action="{{ route('kepala-lab.risk-assessment.approve', $riskAssessment->id) }}" method="POST" id="approvalForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" style="font-size: 1.1rem;">Keputusan Final *</label>
                    <div class="radio-group">
                        <label class="radio-item success">
                            <input type="radio" name="persetujuan" value="setuju" required>
                            <span style="font-size: 1.1rem; font-weight: 600;">✅ Setuju / Disetujui</span>
                        </label>
                        <label class="radio-item danger">
                            <input type="radio" name="persetujuan" value="tolak" required>
                            <span style="font-size: 1.1rem; font-weight: 600;">❌ Tolak / Ditolak</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="4" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                    <button type="submit" class="btn btn-success">
                        ✅ Submit Keputusan
                    </button>
                    <a href="{{ route('kepala-lab.risk-assessment.index') }}" class="btn btn-secondary">
                        ❌ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
document.getElementById('approvalForm')?.addEventListener('submit', function(e) {
    const persetujuan = document.querySelector('input[name="persetujuan"]:checked');
    
    if (!persetujuan) {
        e.preventDefault();
        alert('Mohon pilih keputusan (Setuju/Tolak)');
        return false;
    }
    
    const isApprove = persetujuan.value === 'setuju';
    const message = isApprove 
        ? 'Apakah Anda yakin MENYETUJUI Risk Assessment ini? Mahasiswa akan dapat melakukan penelitian/praktikum.' 
        : 'Apakah Anda yakin MENOLAK Risk Assessment ini?';
    
    if (!confirm(message)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush