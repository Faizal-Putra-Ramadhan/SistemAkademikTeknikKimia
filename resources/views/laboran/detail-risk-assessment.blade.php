@extends('layouts.app')

@section('title', 'Detail Risk Assessment')
@section('page-title', 'Detail Risk Assessment')

@push('styles')
<style>
    .detail-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.5rem 1.25rem 2.5rem;
    }
    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        background: #1f2937;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
    }
    .status-pill {
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #e2e8f0;
        color: #1f2937;
    }
    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .section-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
    }
    .section-subtitle {
        margin: 0.35rem 0 0;
        font-size: 0.9rem;
        color: #64748b;
    }
    .section-body {
        padding: 1.5rem;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }
    .meta-item {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 0.9rem 1rem;
    }
    .meta-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.35rem;
        font-weight: 700;
    }
    .meta-value {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }
    .info-item label {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }
    .info-item p {
        margin: 0;
        font-weight: 600;
        color: #0f172a;
    }
    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #e2e8f0;
        color: #1f2937;
    }
    .tag.success {
        background: #e7f6ef;
        color: #0f5132;
    }
    .tag.warn {
        background: #fff4d6;
        color: #8a5800;
    }
    .tag.danger {
        background: #fdecea;
        color: #7a271a;
    }
    .tag.info {
        background: #e0f2fe;
        color: #0c4a6e;
    }
    .chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .check-list {
        display: grid;
        gap: 0.65rem;
    }
    .check-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 0.9rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
    }
    .check-label {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.9rem;
    }
    .check-value {
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
    }
    .check-value.yes {
        background: #e7f6ef;
        color: #0f5132;
    }
    .check-value.no {
        background: #e2e8f0;
        color: #475569;
    }
    .approval-list {
        display: grid;
        gap: 1rem;
    }
    .approval-card {
        padding: 1rem 1.2rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        display: grid;
        gap: 0.5rem;
    }
    .approval-top {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    .approval-title {
        font-weight: 700;
        color: #111827;
    }
    .approval-meta {
        color: #64748b;
        font-size: 0.85rem;
    }
    .info-box {
        padding: 1rem 1.2rem;
        border-radius: 12px;
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        color: #0c4a6e;
        font-size: 0.9rem;
    }
    .action-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
    @php
        $statusConfig = [
            'draft' => ['class' => 'tag', 'label' => 'Draft'],
            'menunggu_dosen' => ['class' => 'tag warn', 'label' => 'Menunggu Dosen'],
            'menunggu_safety_officer' => ['class' => 'tag info', 'label' => 'Menunggu Safety Officer'],
            'menunggu_kepala_lab' => ['class' => 'tag warn', 'label' => 'Menunggu Kepala Lab'],
            'disetujui' => ['class' => 'tag success', 'label' => 'Disetujui'],
            'ditolak' => ['class' => 'tag danger', 'label' => 'Ditolak'],
        ];
        $config = $statusConfig[$riskAssessment->status] ?? ['class' => 'tag', 'label' => $riskAssessment->status];
    @endphp

    <div class="detail-page">
        <div class="top-row">
            <a href="{{ route('laboran.risk-assessment', $riskAssessment->daftar_lab_id) }}" class="back-link">&larr; Kembali ke Daftar Risk Assessment</a>
            <span class="status-pill">{{ $config['label'] }}</span>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div>
                    <h1 class="section-title">Risk Assessment Form</h1>
                    <p class="section-subtitle">Formulir Penakaran Resiko untuk Kerja Laboratorium</p>
                </div>
                <span class="{{ $config['class'] }}">{{ $config['label'] }}</span>
            </div>
            <div class="section-body">
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-label">Laboratorium</div>
                        <div class="meta-value">{{ $riskAssessment->daftarLab->Nama_Laboratorium }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Tanggal Dibuat</div>
                        <div class="meta-value">{{ $riskAssessment->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Kode RA</div>
                        <div class="meta-value">{{ $riskAssessment->id_ra ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">1. Data Mahasiswa</h2>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nama</label>
                        <p>{{ $riskAssessment->nama }}</p>
                    </div>
                    <div class="info-item">
                        <label>NIM</label>
                        <p>{{ $riskAssessment->nim }}</p>
                    </div>
                    <div class="info-item">
                        <label>No. Kontak</label>
                        <p>{{ $riskAssessment->no_kontak ?? '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Alamat Kontak</label>
                        <p>{{ $riskAssessment->alamat_kontak ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">2. Jenis Risk Assessment</h2>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Jenis RA</label>
                        <p>{{ $riskAssessment->jenis_ra }}</p>
                    </div>
                    <div class="info-item">
                        <label>Topik/Judul</label>
                        <p>{{ $riskAssessment->topik_judul ?? '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Dosen Pembimbing</label>
                        <p>{{ $riskAssessment->dosen_pembimbing_nama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($riskAssessment->bahanKimias && $riskAssessment->bahanKimias->count() > 0)
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">3. Material atau Bahan Kimia</h2>
                </div>
                <div class="section-body">
                    @foreach($riskAssessment->bahanKimias as $index => $bahan)
                        <div class="section-card" style="box-shadow:none;border:1px solid #eef2f7;">
                            <div class="section-body">
                                <div class="meta-label">Bahan #{{ $index + 1 }}</div>
                                <div class="meta-value">{{ $bahan->nama_bahan }}</div>
                                <div class="chip-row">
                                    @if($bahan->explosive)
                                        <span class="tag danger">Explosive</span>
                                    @endif
                                    @if($bahan->flammable)
                                        <span class="tag warn">Flammable</span>
                                    @endif
                                    @if($bahan->toxic)
                                        <span class="tag danger">Toxic</span>
                                    @endif
                                    @if($bahan->corrosive)
                                        <span class="tag warn">Corrosive</span>
                                    @endif
                                    @if($bahan->irritant)
                                        <span class="tag danger">Irritant</span>
                                    @endif
                                    @if($bahan->oxidizing)
                                        <span class="tag info">Oxidizing</span>
                                    @endif
                                </div>
                                @if($bahan->lain_lain)
                                    <p class="section-subtitle" style="margin-top:0.6rem;"><strong>Lain-lain:</strong> {{ $bahan->lain_lain }}</p>
                                @endif
                                @if($bahan->msds_file)
                                    <div style="margin-top:0.75rem;">
                                        <a href="{{ route('msds.show', $bahan->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#0d6efd;color:#fff;border-radius:6px;text-decoration:none;font-size:0.875rem;">
                                            📄 Lihat/Download MSDS
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($riskAssessment->kategoriHazardBahan)
                        <div class="info-box">
                            <strong>Kategori Hazard Bahan:</strong>
                            {{ ucfirst(str_replace('_', ' ', $riskAssessment->kategoriHazardBahan->kategori)) }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($riskAssessment->peralatanOperasi)
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">4. Peralatan dan Kondisi Operasi</h2>
                </div>
                <div class="section-body">
                    <div class="check-list">
                        <div class="check-item">
                            <span class="check-label">Menggunakan tekanan tinggi</span>
                            <span class="check-value {{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'yes' : 'no' }}">{{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Menggunakan suhu tinggi</span>
                            <span class="check-value {{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'yes' : 'no' }}">{{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Menggunakan nyala api</span>
                            <span class="check-value {{ $riskAssessment->peralatanOperasi->nyala_api ? 'yes' : 'no' }}">{{ $riskAssessment->peralatanOperasi->nyala_api ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Menggunakan peralatan berputar</span>
                            <span class="check-value {{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'yes' : 'no' }}">{{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'Ya' : 'Tidak' }}</span>
                        </div>
                    </div>

                    <div class="info-grid" style="margin-top:1rem;">
                        <div class="info-item">
                            <label>Temperatur Maksimum</label>
                            <p>{{ $riskAssessment->peralatanOperasi->temperatur_maksimum ? $riskAssessment->peralatanOperasi->temperatur_maksimum . ' C' : '-' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Tekanan Maksimum</label>
                            <p>{{ $riskAssessment->peralatanOperasi->tekanan_maksimum ? $riskAssessment->peralatanOperasi->tekanan_maksimum . ' atm' : '-' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Kategori Hazard Peralatan</label>
                            <p>{{ $riskAssessment->peralatanOperasi->kategori_hazard ? ucfirst(str_replace('_', ' ', $riskAssessment->peralatanOperasi->kategori_hazard)) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($riskAssessment->pelakuKerja)
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">5. Pelaku Kerja Laboratorium</h2>
                </div>
                <div class="section-body">
                    <div class="check-list">
                        <div class="check-item">
                            <span class="check-label">Menyadari faktor manusia dalam kecelakaan kerja</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Memahami bahaya terhadap diri sendiri</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->memahami_bahaya_diri ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->memahami_bahaya_diri ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Memahami bahaya terhadap orang lain</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Memahami bahaya terhadap lingkungan</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Memahami bahaya dari peralatan</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="check-item">
                            <span class="check-label">Paham tindakan saat kecelakaan</span>
                            <span class="check-value {{ $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? 'yes' : 'no' }}">{{ $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? 'Ya' : 'Tidak' }}</span>
                        </div>
                    </div>

                    <div class="info-grid" style="margin-top:1rem;">
                        <div class="info-item">
                            <label>Penilaian Keterampilan Diri</label>
                            <p>{{ $riskAssessment->pelakuKerja->penilaian_keterampilan ? ucfirst(str_replace('_', ' ', $riskAssessment->pelakuKerja->penilaian_keterampilan)) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">Status Persetujuan</h2>
            </div>
            <div class="section-body">
                <div class="approval-list">
                    <div class="approval-card">
                        <div class="approval-top">
                            <div>
                                <div class="approval-title">Dosen Pembimbing</div>
                                <div class="approval-meta">{{ $riskAssessment->dosen_pembimbing_nama ?? 'Belum ditentukan' }}</div>
                            </div>
                            <div>
                                @if($riskAssessment->persetujuan_dosen === true || $riskAssessment->persetujuan_dosen == 1)
                                    <span class="tag success">Disetujui</span>
                                    @if($riskAssessment->tanggal_persetujuan_dosen)
                                        <div class="approval-meta">{{ $riskAssessment->tanggal_persetujuan_dosen->format('d/m/Y H:i') }}</div>
                                    @endif
                                @elseif($riskAssessment->persetujuan_dosen === false || $riskAssessment->persetujuan_dosen == 0)
                                    <span class="tag danger">Ditolak</span>
                                @else
                                    <span class="tag warn">Menunggu</span>
                                @endif
                            </div>
                        </div>
                        @if($riskAssessment->kategori_resiko_dosen)
                            <div class="approval-meta">Kategori Resiko: {{ ucfirst($riskAssessment->kategori_resiko_dosen) }}</div>
                        @endif
                        @if($riskAssessment->catatan_dosen)
                            <div class="approval-meta">Catatan: {{ $riskAssessment->catatan_dosen }}</div>
                        @endif
                    </div>

                    <div class="approval-card">
                        <div class="approval-top">
                            <div>
                                <div class="approval-title">Safety Officer</div>
                                <div class="approval-meta">{{ $riskAssessment->safety_officer_nama ?? 'Belum ditentukan' }}</div>
                            </div>
                            <div>
                                @if($riskAssessment->persetujuan_safety_officer === true || $riskAssessment->persetujuan_safety_officer == 1)
                                    <span class="tag success">Disetujui</span>
                                    @if($riskAssessment->tanggal_persetujuan_safety_officer)
                                        <div class="approval-meta">{{ $riskAssessment->tanggal_persetujuan_safety_officer->format('d/m/Y H:i') }}</div>
                                    @endif
                                @elseif($riskAssessment->persetujuan_safety_officer === false || $riskAssessment->persetujuan_safety_officer == 0)
                                    <span class="tag danger">Ditolak</span>
                                @else
                                    <span class="tag warn">Menunggu</span>
                                @endif
                            </div>
                        </div>
                        @if($riskAssessment->jadwal_wawancara)
                            <div class="approval-meta">Jadwal Wawancara: {{ $riskAssessment->jadwal_wawancara->format('d/m/Y H:i') }}</div>
                        @endif
                        @if($riskAssessment->catatan_safety_officer)
                            <div class="approval-meta">Catatan: {{ $riskAssessment->catatan_safety_officer }}</div>
                        @endif
                    </div>

                    <div class="approval-card">
                        <div class="approval-top">
                            <div>
                                <div class="approval-title">Kepala Laboratorium</div>
                                <div class="approval-meta">{{ $riskAssessment->daftarLab->Kepala_Labolatorium ?? 'Belum ditentukan' }}</div>
                            </div>
                            <div>
                                @if(!is_null($riskAssessment->persetujuan_kepala_lab) && ($riskAssessment->persetujuan_kepala_lab === true || $riskAssessment->persetujuan_kepala_lab == 1))
                                    <span class="tag success">Disetujui</span>
                                    @if($riskAssessment->tanggal_persetujuan_kepala_lab)
                                        <div class="approval-meta">{{ $riskAssessment->tanggal_persetujuan_kepala_lab->format('d/m/Y H:i') }}</div>
                                    @endif
                                @elseif(!is_null($riskAssessment->persetujuan_kepala_lab) && ($riskAssessment->persetujuan_kepala_lab === false || $riskAssessment->persetujuan_kepala_lab == 0))
                                    <span class="tag danger">Ditolak</span>
                                @else
                                    <span class="tag warn">Menunggu</span>
                                @endif
                            </div>
                        </div>
                        @if($riskAssessment->catatan_kepala_lab)
                            <div class="approval-meta">Catatan: {{ $riskAssessment->catatan_kepala_lab }}</div>
                        @endif
                    </div>

                    <div class="approval-card">
                        <div class="approval-top">
                            <div>
                                <div class="approval-title">Kaprodi</div>
                                <div class="approval-meta">{{ $riskAssessment->kaprodi_nama ?? 'Belum ditentukan' }}</div>
                            </div>
                            <div>
                                @if(!is_null($riskAssessment->persetujuan_kaprodi) && ($riskAssessment->persetujuan_kaprodi === true || $riskAssessment->persetujuan_kaprodi == 1))
                                    <span class="tag success">Disetujui</span>
                                    @if($riskAssessment->tanggal_persetujuan_kaprodi)
                                        <div class="approval-meta">{{ $riskAssessment->tanggal_persetujuan_kaprodi->format('d/m/Y H:i') }}</div>
                                    @endif
                                @elseif(!is_null($riskAssessment->persetujuan_kaprodi) && ($riskAssessment->persetujuan_kaprodi === false || $riskAssessment->persetujuan_kaprodi == 0))
                                    <span class="tag danger">Ditolak</span>
                                @else
                                    <span class="tag warn">Menunggu</span>
                                @endif
                            </div>
                        </div>
                        @if($riskAssessment->catatan_kaprodi)
                            <div class="approval-meta">Catatan: {{ $riskAssessment->catatan_kaprodi }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box" style="margin-bottom:1.25rem;">
            Anda hanya dapat melihat Risk Assessment ini. Persetujuan dilakukan oleh Dosen Pembimbing,
            Safety Officer, dan Kepala Laboratorium.
        </div>

        <div class="action-row">
            <a href="{{ route('laboran.risk-assessment.download', $riskAssessment->id) }}" class="btn btn-primary">
                Download Laporan
            </a>
        </div>
    </div>
@endsection
