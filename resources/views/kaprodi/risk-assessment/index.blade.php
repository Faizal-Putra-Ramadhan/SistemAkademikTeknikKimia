@extends('layouts.app')
@section('title', 'Persetujuan Risk Assessment')
@section('page-title', 'Manajemen Risk Assessment')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .nav-tabs { border-bottom: 2px solid #e5e7eb; }
        .nav-tabs .nav-link { border: none; color: #6b7280; font-weight: 600; padding: 1rem 1.5rem; }
        .nav-tabs .nav-link.active { color: #4f46e5; border-bottom: 2px solid #4f46e5; background: transparent; }
        .table thead th { background-color: #f9fafb; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #4b5563; border-top: none; }
        .badge-lg { font-size: 0.85rem; padding: 0.5em 0.75em; border-radius: 6px; }
</style>
@endpush

@section('content')
@if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <ul class="nav nav-tabs mb-4" id="raTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                        Menunggu Persetujuan
                        @if($riskAssessments->total() > 0)
                            <span class="ml-2 badge badge-pill badge-primary">{{ $riskAssessments->total() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="riwayat-tab" data-toggle="tab" href="#riwayat" role="tab">
                        Riwayat Proses
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="raTabsContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    @if($riskAssessments->count() > 0)
                        <div class="card bg-white">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="pl-4">No</th>
                                            <th>Mahasiswa & Judul</th>
                                            <th>Laboratorium</th>
                                            <th>Kategori Risiko</th>
                                            <th>Validasi Sebelumnya</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        @foreach($riskAssessments as $index => $ra)
                                            <tr>
                                                <td class="pl-4 font-weight-bold text-gray-500">{{ $riskAssessments->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="font-weight-bold text-gray-900">{{ $ra->nama }}</div>
                                                    <div class="text-xs text-gray-500 mb-2">{{ $ra->nim }}</div>
                                                    <div class="text-sm text-indigo-600 font-italic text-truncate" style="max-width: 250px;" title="{{ $ra->topik_judul }}">
                                                        "{{ $ra->topik_judul }}"
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm text-gray-700">{{ $ra->daftarLab->Nama_Laboratorium ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $resiko = strtolower($ra->kategori_resiko_dosen);
                                                        $badgeClass = $resiko == 'tinggi' ? 'danger' : ($resiko == 'sedang' ? 'warning' : 'success');
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeClass }} badge-lg px-3">
                                                        {{ ucfirst($ra->kategori_resiko_dosen ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        <small class="text-success"><i class="fas fa-check-circle"></i> Dosen Pembimbing</small>
                                                        <small class="text-success"><i class="fas fa-check-circle"></i> Safety Officer</small>
                                                        <small class="text-success"><i class="fas fa-check-circle"></i> Kepala Lab</small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('kaprodi.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                                        <i class="fas fa-search mr-1"></i> Review
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-white border-top-0 py-4">
                                {{ $riskAssessments->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                            <h5 class="text-gray-400 font-medium">Belum ada pengajuan masuk</h5>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="riwayat" role="tabpanel">
                    @if($riwayat->count() > 0)
                        <div class="card bg-white">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="pl-4">Tanggal Selesai</th>
                                            <th>Mahasiswa</th>
                                            <th>Durasi Peminjaman</th>
                                            <th>Status Akhir</th>
                                            <th class="text-center">Catatan</th>
                                            <th class="text-right pr-4">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayat as $ra)
                                            <tr>
                                                <td class="pl-4 text-sm text-gray-600">
                                                    {{ $ra->tanggal_persetujuan_kaprodi ? $ra->tanggal_persetujuan_kaprodi->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $ra->nama }}</div>
                                                    <div class="text-xs text-gray-400">{{ $ra->nim }}</div>
                                                </td>
                                                <td>
                                                    @if($ra->status == 'disetujui')
                                                        <span class="text-indigo-700 font-weight-bold">{{ $ra->durasi_batas_peminjaman }} Bulan</span>
                                                        <div class="text-xs text-gray-400">s/d {{ $ra->getBatasWaktuPeminjamanFormatted() }}</div>
                                                    @else
                                                        <span class="text-gray-300">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $ra->status == 'disetujui' ? 'success' : 'danger' }} px-3">
                                                        {{ $ra->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($ra->catatan_kaprodi)
                                                        <button class="btn btn-link btn-sm text-gray-500" data-toggle="modal" data-target="#catatanModal{{ $ra->id }}">
                                                            <i class="fas fa-comment-dots fa-lg"></i>
                                                        </button>
                                                    @else
                                                        <span class="text-gray-300">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-right pr-4">
                                                    <a href="{{ route('kaprodi.risk-assessment.show', $ra->id) }}" class="btn btn-outline-secondary btn-sm">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl shadow-sm text-gray-400">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <p>Belum ada riwayat pemrosesan data.</p>
                        </div>
                    @endif
                </div>
            </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
