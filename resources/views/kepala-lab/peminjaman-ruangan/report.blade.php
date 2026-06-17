@extends('layouts.app')

@section('title', 'Laporan Peminjaman Ruangan')
@section('page-title', 'Laporan Peminjaman Ruangan')

@push('styles')
<style>
    .filter-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .report-table th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
    }
    .report-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-disetujui { background: #ecfdf5; color: #065f46; }
    .status-ditolak { background: #fef2f2; color: #991b1b; }
    .status-menunggu { background: #fff7ed; color: #d97706; }
</style>
@endpush

@section('content')
<div class="filter-card">
    <form action="{{ route('kepala-lab.peminjaman-ruangan.report') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label font-weight-bold">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label font-weight-bold">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label font-weight-bold">Status</label>
            <select name="status" class="form-control">
                <option value="all">Semua Status</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="menunggu_kepala_lab" {{ request('status') == 'menunggu_kepala_lab' ? 'selected' : '' }}>Menunggu</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label font-weight-bold">Laboratorium</label>
            <select name="lab_id" class="form-control">
                <option value="all">Semua Lab</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>{{ $lab->Nama_Laboratorium }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Peminjam</th>
                    <th>Laboratorium</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>
                        <div class="font-weight-600">{{ $item->user_nama }}</div>
                        <small class="text-muted">{{ $item->nim ?? '-' }}</small>
                    </td>
                    <td>{{ $item->daftarLab->Nama_Laboratorium }}</td>
                    <td>{{ Str::limit($item->keperluan, 30) }}</td>
                    <td>
                        <span class="status-badge status-{{ str_replace('_', '-', $item->status) }}">
                            @switch($item->status)
                                @case('disetujui') ✅ Disetujui @break
                                @case('ditolak') ❌ Ditolak @break
                                @case('menunggu_kepala_lab') ⏳ Menunggu @break
                                @case('disetujui_laboran') 📋 Approved Laboran @break
                                @default {{ $item->status }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('kepala-lab.peminjaman-ruangan.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p>Tidak ada data peminjaman yang ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $riwayat->appends(request()->query())->links() }}
</div>
@endsection
