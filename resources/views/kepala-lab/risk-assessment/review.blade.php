<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Risk Assessment - Kepala Lab</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        .detail-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .detail-section h3 {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
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
        .badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        
        .approval-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-top: 2rem;
            border: 3px solid #667eea;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
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
            border-color: #667eea;
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
        .form-control {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn {
            padding: 0.875rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
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
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">
    <x-kepala-lab.navbar :labs="$labs" :user="$user" />
    <x-kepala-lab.header>Dashboard</x-kepala-lab.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            @if($riskAssessment->status !== 'menunggu_kepala_lab')
            <div class="alert alert-warning">
                ⚠️ <strong>Perhatian:</strong> Risk Assessment ini sudah diproses sebelumnya.
            </div>
            @endif

            <!-- Header dengan Info Mahasiswa -->
            <div class="detail-section">
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

            <!-- Approval Timeline -->
            <div class="detail-section">
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
                                <span class="badge badge-{{ $riskAssessment->kategori_resiko_dosen == 'tinggi' ? 'danger' : ($riskAssessment->kategori_resiko_dosen == 'sedang' ? 'warning' : 'success') }}">
                                    {{ $riskAssessment->getKategoriResikoLabel() }}
                                </span>
                            </p>
                            @if($riskAssessment->catatan_dosen)
                            <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #667eea; border-radius: 4px;">
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
                            <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #667eea; border-radius: 4px;">
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

            <!-- Detail Lengkap Risk Assessment -->
            <div class="detail-section">
                <h3>⚗️ Bahan Kimia yang Digunakan</h3>
                @foreach($riskAssessment->bahanKimias as $index => $bahan)
                <div style="border: 2px solid #e5e7eb; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 style="color: #667eea; margin-bottom: 1rem; font-weight: 600;">
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
                </div>
                @endforeach
                
                <div class="detail-item" style="margin-top: 1.5rem;">
                    <span class="detail-label">Kategori Tingkat Hazard Bahan</span>
                    <span class="badge badge-{{ $riskAssessment->kategoriHazardBahan->kategori == 'sangat_hazardous' || $riskAssessment->kategoriHazardBahan->kategori == 'hazardous' ? 'danger' : ($riskAssessment->kategoriHazardBahan->kategori == 'moderat' ? 'warning' : 'success') }}">
                        {{ $riskAssessment->kategoriHazardBahan->getKategoriLabel() }}
                    </span>
                </div>
            </div>

            <div class="detail-section">
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

            <!-- Form Approval -->
            @if($riskAssessment->status === 'menunggu_kepala_lab')
            <div class="approval-form">
                <h3 style="color: #333; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center;">
                    🔐 Final Approval - Kepala Laboratorium
                </h3>

                <form action="{{ route('kepala-lab.risk-assessment.approve', $riskAssessment->id) }}" method="POST" id="approvalForm">
                    @csrf

                    <div class="form-group">
                        <label>Keputusan Final *</label>
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
                        <label>Catatan (Opsional)</label>
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
            @endif

        </div>
    </main>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>