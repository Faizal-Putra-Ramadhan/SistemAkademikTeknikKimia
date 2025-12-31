<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Risk Assessment</title>
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
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-draft { background: #e5e7eb; color: #4b5563; }
        .status-menunggu_dosen { background: #fef3c7; color: #92400e; }
        .status-menunggu_safety_officer { background: #dbeafe; color: #1e40af; }
        .status-menunggu_kepala_lab { background: #e0e7ff; color: #3730a3; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        
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
        .timeline-dot.rejected { background: #ef4444; box-shadow: 0 0 0 2px #ef4444; }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .signature-box {
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            padding: 1rem;
            max-width: 400px;
        }
        .signature-box img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">
    <x-mahasiswa.navbar :labs="[$riskAssessment->daftarLab]" :user="$riskAssessment->user"></x-mahasiswa.navbar>
    <x-mahasiswa.header>Detail Risk Assessment</x-mahasiswa.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            <!-- Header dengan Status -->
            <div class="detail-section">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h2 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin: 0;">
                            {{ $riskAssessment->topik_judul }}
                        </h2>
                        <p style="color: #6b7280; margin-top: 0.5rem;">
                            Dibuat: {{ $riskAssessment->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="status-badge status-{{ str_replace(' ', '_', $riskAssessment->status) }}">
                        {{ $riskAssessment->getStatusLabel() }}
                    </span>
                </div>
            </div>

            <!-- Data Mahasiswa -->
            <div class="detail-section">
                <h3>📋 Data Mahasiswa</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nama</span>
                        <span class="detail-value">{{ $riskAssessment->nama }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIM</span>
                        <span class="detail-value">{{ $riskAssessment->nim }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">No. Kontak</span>
                        <span class="detail-value">{{ $riskAssessment->no_kontak }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Laboratorium</span>
                        <span class="detail-value">{{ $riskAssessment->daftarLab->nama_lab }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jenis Risk Assessment</span>
                        <span class="detail-value">{{ $riskAssessment->jenis_ra }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dosen Pembimbing</span>
                        <span class="detail-value">{{ $riskAssessment->dosen_pembimbing_nama }}</span>
                    </div>
                </div>
                <div class="detail-item" style="margin-top: 1rem;">
                    <span class="detail-label">Alamat Kontak</span>
                    <span class="detail-value">{{ $riskAssessment->alamat_kontak }}</span>
                </div>
            </div>

            <!-- Bahan Kimia -->
            <div class="detail-section">
                <h3>⚗️ Bahan Kimia yang Digunakan</h3>
                @foreach($riskAssessment->bahanKimias as $index => $bahan)
                <div style="border: 2px solid #e5e7eb; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 style="color: #667eea; margin-bottom: 1rem;">Bahan #{{ $index + 1 }}: {{ $bahan->nama_bahan }}</h4>
                    
                    <div class="detail-item" style="margin-bottom: 1rem;">
                        <span class="detail-label">Sifat Bahan</span>
                        <div>
                            @if($bahan->explosive)
                                <span class="badge badge-danger">☢️ Explosive</span>
                            @endif
                            @if($bahan->flammable)
                                <span class="badge badge-warning">🔥 Flammable</span>
                            @endif
                            @if($bahan->toxic)
                                <span class="badge badge-danger">☠️ Toxic</span>
                            @endif
                            @if($bahan->corrosive)
                                <span class="badge badge-warning">⚗️ Corrosive</span>
                            @endif
                            @if($bahan->irritant)
                                <span class="badge badge-info">⚠️ Irritant</span>
                            @endif
                            @if($bahan->oxidizing)
                                <span class="badge badge-info">💨 Oxidizing</span>
                            @endif
                            @if($bahan->lain_lain)
                                <span class="badge badge-info">{{ $bahan->lain_lain }}</span>
                            @endif
                        </div>
                    </div>

                    @if($bahan->msds_file)
                    <div class="detail-item">
                        <span class="detail-label">MSDS</span>
                        <a href="{{ Storage::url($bahan->msds_file) }}" target="_blank" class="btn btn-secondary" style="display: inline-block; padding: 0.5rem 1rem; font-size: 0.9rem;">
                            📄 Download MSDS
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach

                <div class="detail-item" style="margin-top: 1.5rem;">
                    <span class="detail-label">Kategori Tingkat Hazard Bahan</span>
                    <span class="detail-value">
                        @php
                            $kategori = $riskAssessment->kategoriHazardBahan->kategori ?? null;
                            $badgeClass = match($kategori) {
                                'sangat_hazardous' => 'badge-danger',
                                'hazardous' => 'badge-warning',
                                'moderat' => 'badge-info',
                                'tidak_hazardous' => 'badge-success',
                                default => 'badge-info'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $riskAssessment->kategoriHazardBahan->getKategoriLabel() }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Peralatan & Kondisi Operasi -->
            <div class="detail-section">
                <h3>🔧 Peralatan & Kondisi Operasi</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Kondisi Operasi</span>
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
                    </div>
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
                        <span class="detail-value">
                            @php
                                $kategoriPeralatan = $riskAssessment->peralatanOperasi->kategori_hazard;
                                $badgeClass = match($kategoriPeralatan) {
                                    'sangat_hazardous' => 'badge-danger',
                                    'hazardous' => 'badge-warning',
                                    'moderat' => 'badge-info',
                                    'tidak_hazardous' => 'badge-success',
                                    default => 'badge-info'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $riskAssessment->peralatanOperasi->getKategoriHazardLabel() }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pelaku Kerja -->
            <div class="detail-section">
                <h3>👤 Pelaku Kerja Laboratorium</h3>
                
                <div class="detail-item" style="margin-bottom: 1.5rem;">
                    <span class="detail-label">Pemahaman Keselamatan</span>
                    <div style="margin-top: 0.5rem;">
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? '✅' : '❌' !!}
                            Menyadari faktor manusia dalam kecelakaan kerja
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->memahami_bahaya_diri ? '✅' : '❌' !!}
                            Memahami bahaya terhadap diri sendiri
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? '✅' : '❌' !!}
                            Memahami bahaya terhadap orang lain
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? '✅' : '❌' !!}
                            Memahami bahaya terhadap lingkungan
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? '✅' : '❌' !!}
                            Memahami bahaya peralatan
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            {!! $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? '✅' : '❌' !!}
                            Paham tindakan jika terjadi kecelakaan
                        </div>
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Penilaian Keterampilan Diri</span>
                    <span class="detail-value">
                        <span class="badge badge-info">
                            {{ $riskAssessment->pelakuKerja->getPenilaianKeterampilanLabel() }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Pernyataan Mahasiswa -->
            @if($riskAssessment->pernyataanMahasiswa)
            <div class="detail-section">
                <h3>✍️ Pernyataan Mahasiswa</h3>
                
                <div class="detail-item" style="margin-bottom: 1rem;">
                    <p style="color: #374151; line-height: 1.6;">
                        {!! $riskAssessment->pernyataanMahasiswa->setuju_bertanggung_jawab ? '✅' : '❌' !!}
                        Saya memahami tentang apa yang akan saya lakukan dalam percobaan tersebut dan bertanggung jawab terhadap keselamatan jalannya percobaan.
                    </p>
                </div>

                @if($riskAssessment->pernyataanMahasiswa->tanda_tangan)
                <div class="detail-item">
                    <span class="detail-label">Tanda Tangan Digital</span>
                    <div class="signature-box">
                        <img src="{{ $riskAssessment->pernyataanMahasiswa->tanda_tangan }}" alt="Tanda Tangan">
                    </div>
                </div>
                @endif

                <div class="detail-item" style="margin-top: 1rem;">
                    <span class="detail-label">Tanggal Pernyataan</span>
                    <span class="detail-value">
                        {{ $riskAssessment->pernyataanMahasiswa->tanggal_pernyataan->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
            @endif

            <!-- Timeline Approval -->
            <div class="detail-section">
                <h3>📊 Status Persetujuan</h3>
                
                <div class="approval-timeline">
                    <!-- Dosen Pembimbing -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $riskAssessment->persetujuan_dosen === true ? 'active' : ($riskAssessment->persetujuan_dosen === false ? 'rejected' : 'pending') }}"></div>
                        <div>
                            <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                👨‍🏫 Dosen Pembimbing
                            </h4>
                            <p style="color: #6b7280; font-size: 0.9rem;">
                                {{ $riskAssessment->dosen_pembimbing_nama }}
                            </p>
                            @if($riskAssessment->persetujuan_dosen !== null)
                                <p style="margin-top: 0.5rem;">
                                    Status: <strong style="color: {{ $riskAssessment->persetujuan_dosen ? '#10b981' : '#ef4444' }}">
                                        {{ $riskAssessment->persetujuan_dosen ? 'Disetujui' : 'Ditolak' }}
                                    </strong>
                                </p>
                                @if($riskAssessment->kategori_resiko_dosen)
                                    <p style="margin-top: 0.25rem;">
                                        Kategori Resiko: <strong>{{ $riskAssessment->getKategoriResikoLabel() }}</strong>
                                    </p>
                                @endif
                                @if($riskAssessment->catatan_dosen)
                                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #667eea; border-radius: 4px;">
                                        <strong>Catatan:</strong><br>
                                        {{ $riskAssessment->catatan_dosen }}
                                    </div>
                                @endif
                                <p style="margin-top: 0.5rem; color: #9ca3af; font-size: 0.85rem;">
                                    {{ $riskAssessment->tanggal_persetujuan_dosen->format('d M Y, H:i') }}
                                </p>
                            @else
                                <p style="margin-top: 0.5rem; color: #fbbf24;">
                                    ⏳ Menunggu review
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Safety Officer -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $riskAssessment->persetujuan_safety_officer === true ? 'active' : ($riskAssessment->persetujuan_safety_officer === false ? 'rejected' : 'pending') }}"></div>
                        <div>
                            <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                🛡️ Safety Officer
                            </h4>
                            @if($riskAssessment->safety_officer_nama)
                                <p style="color: #6b7280; font-size: 0.9rem;">
                                    {{ $riskAssessment->safety_officer_nama }}
                                </p>
                            @endif

                            @if($riskAssessment->safetyOfficer?->Phone)
                                <p style="color: #6b7280; font-size: 0.9rem;">
                                    No Telp: {{ $riskAssessment->safetyOfficer->Phone }}
                                </p>
                            @endif
                            
                            @if($riskAssessment->jadwal_wawancara)
                                <div style="margin-top: 0.75rem; padding: 0.75rem; background: #dbeafe; border-left: 3px solid #3b82f6; border-radius: 4px;">
                                    <strong>📅 Jadwal Wawancara:</strong><br>
                                    {{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('d M Y, H:i') }} WIB
                                </div>
                            @endif

                            @if($riskAssessment->persetujuan_safety_officer !== null)
                                <p style="margin-top: 0.5rem;">
                                    Status: <strong style="color: {{ $riskAssessment->persetujuan_safety_officer ? '#10b981' : '#ef4444' }}">
                                        {{ $riskAssessment->persetujuan_safety_officer ? 'Disetujui' : 'Ditolak' }}
                                    </strong>
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
                            @elseif($riskAssessment->status === 'menunggu_safety_officer')
                                <p style="margin-top: 0.5rem; color: #fbbf24;">
                                    ⏳ Menunggu review
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Kepala Lab -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $riskAssessment->persetujuan_kepala_lab === true ? 'active' : ($riskAssessment->persetujuan_kepala_lab === false ? 'rejected' : 'pending') }}"></div>
                        <div>
                            <h4 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                🏛️ Kepala Laboratorium
                            </h4>
                            @if($riskAssessment->kepalaLab)
                                <p style="color: #6b7280; font-size: 0.9rem;">
                                    {{ $riskAssessment->kepalaLab->Nama }}
                                </p>
                            @endif
                            
                            @if($riskAssessment->persetujuan_kepala_lab !== null)
                                <p style="margin-top: 0.5rem;">
                                    Status: <strong style="color: {{ $riskAssessment->persetujuan_kepala_lab ? '#10b981' : '#ef4444' }}">
                                        {{ $riskAssessment->persetujuan_kepala_lab ? 'Disetujui ✅' : 'Ditolak ❌' }}
                                    </strong>
                                </p>
                                @if($riskAssessment->catatan_kepala_lab)
                                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border-left: 3px solid #667eea; border-radius: 4px;">
                                        <strong>Catatan:</strong><br>
                                        {{ $riskAssessment->catatan_kepala_lab }}
                                    </div>
                                @endif
                                <p style="margin-top: 0.5rem; color: #9ca3af; font-size: 0.85rem;">
                                    {{ $riskAssessment->tanggal_persetujuan_kepala_lab->format('d M Y, H:i') }}
                                </p>
                            @elseif($riskAssessment->status === 'menunggu_kepala_lab')
                                <p style="margin-top: 0.5rem; color: #fbbf24;">
                                    ⏳ Menunggu review
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('mahasiswa.risk-assessment.index') }}" class="btn btn-secondary">
                    ← Kembali
                </a>
                
                @if($riskAssessment->status === 'draft')
                <a href="{{ route('mahasiswa.risk-assessment.edit', $riskAssessment->id) }}" class="btn btn-primary">
                    ✏️ Edit
                </a>
                @endif

                @if($riskAssessment->status === 'disetujui')
                <a href="{{ route('mahasiswa.risk-assessment.download-pdf', $riskAssessment->id) }}" class="btn btn-success">
                    📄 Download PDF
                </a>
                @endif
            </div>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>