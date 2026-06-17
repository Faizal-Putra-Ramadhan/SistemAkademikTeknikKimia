@extends('layouts.app')
@section('title', 'Laporan Risk Assessment')
@section('page-title', 'Laporan Risk Assessment')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Optimasi Print */
        @media print {
            .no-print, .btn, .filter-section, nav, header {
                display: none !important;
            }
            .container, .max-w-6xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            table {
                width: 100% !important;
                font-size: 10pt !important;
            }
        }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Daftar Risk Assessment
                </h1>
                <div class="no-print">
                    <button class="btn btn-success shadow-sm rounded-lg" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Cetak Laporan
                    </button>
                </div>
            </div>

            <div class="border-b border-gray-200 mb-8 no-print">
                <nav class="-mb-px flex space-x-8">
                    <a href="#" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <i class="fas fa-clock mr-2"></i> Menunggu Persetujuan
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <i class="fas fa-history mr-2"></i> Riwayat
                    </a>
                </nav>
            </div>

            <div class="card shadow-sm border-0 rounded-lg mb-6 no-print filter-section">
                <div class="card-header bg-white py-3">
                    <span class="font-weight-bold text-gray-700"><i class="fas fa-filter mr-2"></i> Filter Laporan</span>
                </div>
                <div class="card-body bg-white">
                    <form method="GET" action="{{ route('kaprodi.risk-assessment.report') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-gray-600">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control rounded-md" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-gray-600">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control rounded-md" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-gray-600">Status</label>
                                <select name="status" class="form-control rounded-md">
                                    <option value="all">Semua Status</option>
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="menunggu_kaprodi" {{ request('status') == 'menunggu_kaprodi' ? 'selected' : '' }}>Menunggu Kaprodi</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-gray-600">Laboratorium</label>
                                <select name="lab_id" class="form-control rounded-md">
                                    <option value="all">Semua Lab</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                            {{ $lab->Nama_Laboratorium }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <a href="{{ route('kaprodi.risk-assessment.report') }}" class="btn btn-light border px-4 mr-2">Reset</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-2">
                    <div class="card bg-white shadow-sm border-0 border-left-success rounded-lg">
                        <div class="card-body p-3">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $riskAssessments->where('status', 'disetujui')->count() }}</div>
                        </div>
                    </div>
                </div>
                </div>

            <div class="bg-white shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg border border-gray-200 overflow-hidden">
                @if($riskAssessments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa (NIM)</th>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lab</th>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Risiko</th>
                                    <th class="border-0 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($riskAssessments as $index => $ra)
                                    <tr>
                                        <td class="align-middle text-sm">{{ $riskAssessments->firstItem() + $index }}</td>
                                        <td class="align-middle text-sm text-gray-600">{{ $ra->created_at->format('d/m/Y') }}</td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-gray-900 text-sm">{{ $ra->nama }}</div>
                                            <small class="text-gray-500">{{ $ra->nim }}</small>
                                        </td>
                                        <td class="align-middle text-sm text-gray-600">{{ $ra->daftarLab->Nama_Laboratorium ?? '-' }}</td>
                                        <td class="align-middle">
                                            <span class="badge badge-pill badge-{{ $ra->kategori_resiko_dosen == 'tinggi' ? 'danger' : ($ra->kategori_resiko_dosen == 'sedang' ? 'warning' : 'info') }}">
                                                {{ ucfirst($ra->kategori_resiko_dosen ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-{{ $ra->getStatusBadgeClass() }} py-2 px-3">
                                                {{ $ra->getStatusLabel() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24">
                        <div class="bg-gray-100 p-6 rounded-2xl mb-4 text-gray-400">
                            <i class="fas fa-inbox fa-3x"></i>
                        </div>
                        <h3 class="text-gray-500 font-medium text-lg">Tidak ada data untuk ditampilkan</h3>
                        <p class="text-gray-400 text-sm">Coba ubah filter atau pilih tab lain</p>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-center mt-6 no-print">
                {{ $riskAssessments->appends(request()->query())->links() }}
            </div>
@endsection
