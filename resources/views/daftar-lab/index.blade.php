@extends('layouts.app')

@section('title', 'Daftar Laboratorium')
@section('page-title', 'Daftar Laboratorium')

@push('styles')
<style>
    .td-actions { display: flex; gap: 6px; align-items: center; }
    .pagination-wrap { margin-top: 16px; display: flex; justify-content: center; }
    .pagination-wrap nav { font-size: 13px; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Daftar Laboratorium</h3>
            <a href="{{ route('admin.daftar-lab.create') }}" class="btn btn-primary btn-sm">+ Tambah Laboratorium</a>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Laboratorium</th>
                            <th>Lantai</th>
                            <th>Jenis Lab</th>
                            <th>Kepala Lab</th>
                            <th>Admin Lab</th>
                            
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_labs as $i => $lab)
                        <tr>
                            <td>{{ $daftar_labs->firstItem() + $i }}</td>
                            <td>{{ $lab->Nama_Laboratorium }}</td>
                            <td>{{ $lab->floor }}</td>
                            <td>
                                <span class="badge {{ $lab->lab_type === 'penelitian' ? 'badge-info' : 'badge-success' }}">
                                    {{ ucfirst($lab->lab_type) }}
                                </span>
                            </td>
                            <td>{{ $lab->Kepala_Labolatorium }}</td>
                            <td>{{ $lab->Admin_Laboratorium }}</td>
                           
                            <td>{{ $lab->email_lab ?? '—' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.daftar-lab.edit', $lab) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.daftar-lab.destroy', $lab) }}" method="POST" onsubmit="return confirm('Yakin hapus laboratorium ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center; padding:24px; color:#6b7280;">Belum ada data laboratorium</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $daftar_labs->links() }}</div>
        </div>
    </div>
@endsection