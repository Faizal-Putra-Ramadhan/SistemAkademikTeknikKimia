@extends('layouts.app')
@section('title', 'Review Risk Assessment')
@section('page-title', 'Review Risk Assessment')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background-color: #f4f7f6; color: #333; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #eee; font-weight: bold; border-radius: 10px 10px 0 0 !important; }
        .info-label { color: #888; font-size: 0.85rem; margin-bottom: 0; }
        .info-value { font-weight: 600; color: #2d3436; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; text-transform: uppercase; }
        .timeline-item { border-left: 2px solid #e9ecef; padding-left: 20px; position: relative; padding-bottom: 20px; }
        .timeline-item::before { content: ''; position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #28a745; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
        <header class="mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('kaprodi.risk-assessment.index') }}" class="btn btn-outline-secondary btn-sm mr-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="h3 mb-0">Review Risk Assessment #{{ $riskAssessment->id }}</h1>
                <span class="badge badge-{{ $riskAssessment->getStatusBadgeClass() }} badge-lg ml-auto">
                    {{ $riskAssessment->getStatusLabel() }}
                </span>
            </div>
        </header>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <i class="fas fa-user-graduate text-primary mr-2"></i> Informasi Mahasiswa
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr><td class="text-muted" width="40%">Nama</td><td>: <strong>{{ $riskAssessment->nama }}</strong></td></tr>
                                    <tr><td class="text-muted">NIM</td><td>: {{ $riskAssessment->nim }}</td></tr>
                                    <tr><td class="text-muted">Kontak</td><td>: {{ $riskAssessment->no_kontak ?? '-' }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6 border-left">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr><td class="text-muted" width="40%">Lab</td><td>: {{ $riskAssessment->daftarLab->Nama_Laboratorium ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">Jenis RA</td><td>: <span class="badge badge-info">{{ $riskAssessment->jenis_ra }}</span></td></tr>
                                    <tr><td class="text-muted">Diajukan</td><td>: {{ $riskAssessment->created_at->format('d M Y H:i') }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <i class="fas fa-flask text-info mr-2"></i> Topik Penelitian
                    </div>
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">{{ $riskAssessment->topik_judul }}</h5>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <i class="fas fa-flask-vial text-warning mr-2"></i> Bahan Kimia & Bahaya
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Bahan</th>
                                        <th>Sifat Bahaya</th>
                                        <th>MSDS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riskAssessment->bahanKimias as $bahan)
                                    <tr>
                                        <td class="align-middle"><strong>{{ $bahan->nama_bahan }}</strong></td>
                                        <td>
                                            @if($bahan->explosive) <span class="badge badge-danger">Explosive</span> @endif
                                            @if($bahan->flammable) <span class="badge badge-warning">Flammable</span> @endif
                                            @if($bahan->toxic) <span class="badge badge-dark">Toxic</span> @endif
                                            @if($bahan->corrosive) <span class="badge badge-orange" style="background-color: #fd7e14; color:white;">Corrosive</span> @endif
                                            @if($bahan->irritant) <span class="badge badge-info">Irritant</span> @endif
                                            @if($bahan->oxidizing) <span class="badge badge-primary">Oxidizing</span> @endif
                                            @if($bahan->lain_lain) <div class="small mt-1 text-muted">{{ $bahan->lain_lain }}</div> @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($bahan->msds_file)
                                                <a href="{{ route('msds.show', $bahan->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">📄 Lihat MSDS</a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data bahan kimia</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white border-bottom text-dark">
                        <i class="fas fa-history mr-2"></i> Riwayat Persetujuan
                    </div>
                    <div class="card-body">
                        <div class="media mb-4">
                            <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                            <div class="media-body">
                                <h6 class="mt-0 font-weight-bold">Dosen Pembimbing</h6>
                                <p class="mb-1 text-dark">{{ $riskAssessment->dosenPembimbing->Nama ?? '-' }}</p>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ optional($riskAssessment->tanggal_persetujuan_dosen)->format('d M Y H:i') }}</small>
                                <div class="mt-2">
                                    <span class="badge badge-{{ $riskAssessment->kategori_resiko_dosen == 'tinggi' ? 'danger' : 'warning' }}">
                                        Risiko: {{ ucfirst($riskAssessment->kategori_resiko_dosen) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="media mb-4">
                            <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                            <div class="media-body">
                                <h6 class="mt-0 font-weight-bold">Safety Officer</h6>
                                <p class="mb-1 text-dark">{{ $riskAssessment->safetyOfficer->Nama ?? '-' }}</p>
                                @if($riskAssessment->jadwal_wawancara)
                                    <div class="small bg-light p-2 border rounded">
                                        <strong>Wawancara:</strong> {{ $riskAssessment->jadwal_wawancara->format('d M Y H:i') }}<br>
                                        <strong>Tempat:</strong> {{ $riskAssessment->tempat_wawancara ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="media">
                            <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                            <div class="media-body">
                                <h6 class="mt-0 font-weight-bold">Kepala Laboratorium</h6>
                                <p class="mb-1 text-dark">{{ $riskAssessment->kepalaLab->Nama ?? '-' }}</p>
                                @if($riskAssessment->catatan_kepala_lab)
                                    <div class="small font-italic text-muted">"{{ $riskAssessment->catatan_kepala_lab }}"</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($riskAssessment->status == 'menunggu_kaprodi')
                <div class="sidebar-sticky">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-gavel mr-2"></i> Keputusan Kaprodi
                        </div>
                        <div class="card-body">
                            
                            <form action="{{ route('kaprodi.risk-assessment.approve', $riskAssessment->id) }}" method="POST" id="approvalForm">
                                @csrf
                               
                                
                                <div class="form-group">
                                    <label class="font-weight-bold small uppercase">Keputusan <span class="text-danger">*</span></label>
                                    <select name="persetujuan" id="persetujuan" class="form-control form-control-lg" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="setuju">✓ Setujui</option>
                                        <option value="tolak">✗ Tolak</option>
                                    </select>
                                </div>

                                <div id="durasiGroup" >
                                    <div class="form-group">
                                        <label class="font-weight-bold small">Durasi Akses Peminjaman <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="durasi_batas_peminjaman" id="durasi_batas_peminjaman" class="form-control" min="1" max="12" value="4">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Bulan</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="previewTanggal" class="alert alert-info py-2 px-3 small" >
                                        <i class="fas fa-calendar-alt mr-1"></i> Berlaku hingga:<br>
                                        <strong id="tanggalBatas"></strong>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold small">Catatan (Opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Pastikan menggunakan APD lengkap..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Keputusan
                                </button>
                                
                                <button type="button" class="btn btn-link btn-block text-warning font-weight-bold mt-2" data-toggle="modal" data-target="#revisiModal">
                                    <i class="fas fa-edit mr-1"></i> Minta Revisi Mahasiswa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>RA Telah Diproses</h5>
                        <p class="text-muted small">Risk Assessment ini sudah memiliki keputusan final dan tidak dapat diubah kembali.</p>
                        <hr>
                        <div class="text-left small">
                            <strong>Status Akhir:</strong> {{ $riskAssessment->getStatusLabel() }}<br>
                            <strong>Tanggal:</strong> {{ optional($riskAssessment->tanggal_persetujuan_kaprodi)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="revisiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('kaprodi.risk-assessment.request-revision', $riskAssessment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Instruksi Revisi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Apa yang perlu diperbaiki? <span class="text-danger">*</span></label>
                            <textarea name="catatan_revisi" class="form-control" rows="5" required placeholder="Sebutkan bagian yang salah atau data yang kurang lengkap..."></textarea>
                        </div>
                        <p class="small text-muted"><i class="fas fa-info-circle mr-1"></i> Status akan kembali ke <strong>Draft</strong> dan mahasiswa akan menerima notifikasi.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning font-weight-bold">Kirim ke Mahasiswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        const persetujuanSelect = document.getElementById('persetujuan');
        const durasiGroup = document.getElementById('durasiGroup');
        const durasiInput = document.getElementById('durasi_batas_peminjaman');
        const previewTanggal = document.getElementById('previewTanggal');
        const tanggalBatas = document.getElementById('tanggalBatas');
        const approvalForm = document.getElementById('approvalForm');
        
        function updatePreviewTanggal() {
            const durasi = parseInt(durasiInput.value);
            if (durasi >= 1 && durasi <= 12) {
                const date = new Date();
                date.setMonth(date.getMonth() + durasi);
                
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                tanggalBatas.textContent = date.toLocaleDateString('id-ID', options);
                previewTanggal.style.display = 'block';
            } else {
                previewTanggal.style.display = 'none';
            }
        }

        if(persetujuanSelect) {
            persetujuanSelect.addEventListener('change', function() {
                if (this.value === 'setuju') {
                    durasiGroup.style.display = 'block';
                    durasiInput.required = true;
                    updatePreviewTanggal();
                } else {
                    durasiGroup.style.display = 'none';
                    durasiInput.required = false;
                    previewTanggal.style.display = 'none';
                }
            });

            durasiInput.addEventListener('input', updatePreviewTanggal);

            approvalForm.addEventListener('submit', function(e) {
                const keputusan = persetujuanSelect.value;
                let message = '';
                
                if (keputusan === 'setuju') {
                    message = `Setujui Risk Assessment ini untuk durasi ${durasiInput.value} bulan?`;
                } else if (keputusan === 'tolak') {
                    message = 'Anda yakin ingin MENOLAK Risk Assessment ini?';
                }
                
                if (message && !confirm(message)) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush
