@extends('layouts.app')

@section('title', 'Kelola Alat Lab')
@section('page-title', 'Kelola Alat Lab')

@push('styles')
<style>
    .alat-img { width: 56px; height: 56px; object-fit: cover; border-radius: 6px; }
    .alat-placeholder { width: 56px; height: 56px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px; }
    .td-actions { display: flex; gap: 6px; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Kelola Alat / Aset Laboratorium</h3>
            <a href="{{ route('admin.alat-lab.create') }}" class="btn btn-primary btn-sm">+ Tambah Alat Baru</a>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Alat</th>
                            <th>Lantai</th>
                            <th>Jenis Lab</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alats as $i => $alat)
                        <tr>
                            <td>{{ $alats->firstItem() + $i }}</td>
                            <td>
                                @if($alat->foto)
                                    <img src="{{ asset('uploads/' . $alat->foto) }}" 
                                     class="size-12 rounded-lg object-cover border" 
                                     alt="{{ $alat->nama_alat }}">
                                @else
                                    <div class="alat-placeholder">No Image</div>
                                @endif
                            </td>
                            <td><strong>{{ $alat->nama_alat }}</strong></td>
                            <td>{{ $alat->stockGroup->floor ?? '-' }}</td>
                            <td>
                                <span class="badge {{ ($alat->stockGroup->lab_type ?? '') === 'penelitian' ? 'badge-primary' : 'badge-info' }}">
                                    {{ ucfirst($alat->stockGroup->lab_type ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $alat->jumlah_tersedia > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $alat->jumlah_tersedia }}
                                </span>
                            </td>
                            <td>{{ Str::limit($alat->deskripsi, 80) }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.alat-lab.edit', $alat) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.alat-lab.destroy', $alat) }}" method="POST" onsubmit="return confirm('Yakin hapus alat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                Belum ada alat laboratorium. <a href="{{ route('admin.alat-lab.create') }}">Tambah sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $alats->links() }}
            </div>
        </div>
    </div>
@endsection