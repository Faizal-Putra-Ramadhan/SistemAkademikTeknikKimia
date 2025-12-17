<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Risk Assessment - Dosen</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    
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
            flex-direction: column;
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
        <x-dosen.navbar :labs="$labs" :user="$user" />
        <x-dosen.header>Review Risk Assessment</x-dosen.header>
        
        <main>
            <div class="max-w-7xl mx-auto px-6 py-6">
                
                @if($riskAssessment->status !== 'menunggu_dosen')
                <div class="alert alert-warning">
                    ⚠️ <strong>Perhatian:</strong> Risk Assessment ini sudah diproses sebelumnya.
                </div>
                @endif

                <!-- Header Info -->
                <div class="detail-section">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin-bottom: 1rem;">
                        {{ $riskAssessment->topik_judul }}
                    </h2>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">👤 Mahasiswa</span>
                            <span class="detail-value">{{ $riskAssessment->nama }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">🎓 NIM</span>
                            <span class="detail-value">{{ $riskAssessment->nim }}</span>
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
                            <span class="detail-label">📞 Kontak</span>
                            <span class="detail-value">{{ $riskAssessment->no_kontak }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">📅 Tanggal Diajukan</span>
                            <span class="detail-value">{{ $riskAssessment->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bahan Kimia -->
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
                        @if($bahan->msds_file)
                        <div style="margin-top: 1rem;">
                            <a href="{{ Storage::url($bahan->msds_file) }}" target="_blank" style="color: #667eea; font-weight: 500;">
                                📄 Lihat MSDS
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    
                    <div class="detail-item" style="margin-top: 1.5rem;">
                        <span class="detail-label">Kategori Tingkat Hazard Bahan</span>
                        <span class="badge badge-{{ $riskAssessment->kategoriHazardBahan->kategori == 'sangat_hazardous' || $riskAssessment->kategoriHazardBahan->kategori == 'hazardous' ? 'danger' : ($riskAssessment->kategoriHazardBahan->kategori == 'moderat' ? 'warning' : 'success') }}">
                            {{ $riskAssessment->kategoriHazardBahan->getKategoriLabel() }}
                        </span>
                    </div>
                </div>

                <!-- Peralatan -->
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

                        <div class="detail-item">
                            <span class="detail-label">Kategori Hazard Peralatan</span>
                            <span class="badge badge-{{ $riskAssessment->peralatanOperasi->kategori_hazard == 'sangat_hazardous' || $riskAssessment->peralatanOperasi->kategori_hazard == 'hazardous' ? 'danger' : ($riskAssessment->peralatanOperasi->kategori_hazard == 'moderat' ? 'warning' : 'success') }}">
                                {{ $riskAssessment->peralatanOperasi->getKategoriHazardLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pelaku Kerja -->
                <div class="detail-section">
                    <h3>👤 Pemahaman Mahasiswa</h3>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <span class="detail-label">Pemahaman Keselamatan:</span>
                        <div style="margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                            <div>{!! $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? '✅' : '❌' !!} Menyadari faktor manusia dalam kecelakaan</div>
                            <div>{!! $riskAssessment->pelakuKerja->memahami_bahaya_diri ? '✅' : '❌' !!} Memahami bahaya terhadap diri sendiri</div>
                            <div>{!! $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? '✅' : '❌' !!} Memahami bahaya terhadap orang lain</div>
                            <div>{!! $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? '✅' : '❌' !!} Memahami bahaya terhadap lingkungan</div>
                            <div>{!! $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? '✅' : '❌' !!} Memahami bahaya peralatan</div>
                            <div>{!! $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? '✅' : '❌' !!} Paham tindakan kecelakaan</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Penilaian Keterampilan Diri</span>
                        <span class="badge badge-info">
                            {{ $riskAssessment->pelakuKerja->getPenilaianKeterampilanLabel() }}
                        </span>
                    </div>
                </div>

                <!-- Form Approval -->
                @if($riskAssessment->status === 'menunggu_dosen')
                <div class="approval-form">
                    <h3 style="color: #333; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center;">
                        📋 Form Persetujuan Dosen Pembimbing
                    </h3>

                    <form action="{{ route('dosen.risk-assessment.approve', $riskAssessment->id) }}" method="POST" id="approvalForm">
                        @csrf

                        <div class="form-group">
                            <label>Kategori Resiko *</label>
                            <div class="radio-group">
                                <label class="radio-item" style="border-color: #fee2e2;">
                                    <input type="radio" name="kategori_resiko" value="tinggi" required>
                                    <span style="font-size: 1rem; font-weight: 600;">🔴 Beresiko Tinggi</span>
                                </label>
                                <label class="radio-item" style="border-color: #fef3c7;">
                                    <input type="radio" name="kategori_resiko" value="sedang" required>
                                    <span style="font-size: 1rem; font-weight: 600;">🟡 Beresiko Sedang</span>
                                </label>
                                <label class="radio-item" style="border-color: #d1fae5;">
                                    <input type="radio" name="kategori_resiko" value="rendah" required>
                                    <span style="font-size: 1rem; font-weight: 600;">🟢 Beresiko Rendah</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keputusan *</label>
                            <div class="radio-group">
                                <label class="radio-item" style="border-color: #d1fae5;">
                                    <input type="radio" name="persetujuan" value="setuju" required>
                                    <span style="font-size: 1.1rem; font-weight: 600;">✅ Setuju / Disetujui</span>
                                </label>
                                <label class="radio-item" style="border-color: #fee2e2;">
                                    <input type="radio" name="persetujuan" value="tolak" required>
                                    <span style="font-size: 1.1rem; font-weight: 600;">❌ Tolak / Ditolak</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Tambahkan catatan untuk mahasiswa jika diperlukan..."></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                            <button type="submit" class="btn btn-success">
                                ✅ Submit Keputusan
                            </button>
                            <a href="{{ route('dosen.risk-assessment.index') }}" class="btn btn-secondary">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
                @else
                <!-- Keputusan yang sudah dibuat -->
                <div class="detail-section" style="border: 3px solid #667eea;">
                    <h3>📋 Keputusan Dosen Pembimbing</h3>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Kategori Resiko</span>
                            <span class="badge badge-{{ $riskAssessment->kategori_resiko_dosen == 'tinggi' ? 'danger' : ($riskAssessment->kategori_resiko_dosen == 'sedang' ? 'warning' : 'success') }}">
                                {{ $riskAssessment->getKategoriResikoLabel() }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Keputusan</span>
                            <span style="font-size: 1.1rem; font-weight: 600; color: {{ $riskAssessment->persetujuan_dosen ? '#10b981' : '#ef4444' }};">
                                {{ $riskAssessment->persetujuan_dosen ? '✅ Disetujui' : '❌ Ditolak' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Keputusan</span>
                            <span class="detail-value">{{ $riskAssessment->tanggal_persetujuan_dosen->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    @if($riskAssessment->catatan_dosen)
                    <div style="margin-top: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 6px; border-left: 3px solid #667eea;">
                        <strong style="color: #374151;">📝 Catatan:</strong>
                        <div style="margin-top: 0.5rem; color: #4b5563;">
                            {{ $riskAssessment->catatan_dosen }}
                        </div>
                    </div>
                    @endif
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('dosen.risk-assessment.index') }}" class="btn btn-secondary">
                        ← Kembali ke Daftar
                    </a>
                </div>
                @endif

            </div>
        </main>
    </div>
    
    <script>
    document.getElementById('approvalForm')?.addEventListener('submit', function(e) {
        const kategori = document.querySelector('input[name="kategori_resiko"]:checked');
        const persetujuan = document.querySelector('input[name="persetujuan"]:checked');
        
        if (!kategori || !persetujuan) {
            e.preventDefault();
            alert('Mohon lengkapi kategori resiko dan keputusan');
            return false;
        }
        
        const isApprove = persetujuan.value === 'setuju';
        const message = isApprove 
            ? 'Apakah Anda yakin MENYETUJUI Risk Assessment ini?' 
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