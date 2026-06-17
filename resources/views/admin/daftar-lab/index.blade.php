@extends('layouts.app')

@section('title', 'Daftar Laboratorium')
@section('page-title', 'Daftar Laboratorium')

@push('styles')
<style>
    .toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
    .toolbar-left { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .filter-tab { padding: 6px 14px; border-radius: 6px; border: 1px solid #d1d5db; background: #fff; cursor: pointer; font-size: 13px; color: #374151; transition: all 0.2s; }
    .filter-tab:hover { background: #f3f4f6; }
    .filter-tab.active { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
    .action-link { font-size: 13px; text-decoration: none; padding: 4px 10px; border-radius: 4px; transition: all 0.15s; }
    .action-link.edit { color: var(--color-primary); }
    .action-link.edit:hover { background: #eff6ff; }
    .action-link.delete-btn { color: #dc2626; background: none; border: none; cursor: pointer; font-size: 13px; padding: 4px 10px; border-radius: 4px; }
    .action-link.delete-btn:hover { background: #fef2f2; }
    .td-actions { display: flex; gap: 4px; align-items: center; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Daftar Laboratorium dan Departemen</h3>
            <a href="{{ route('admin.daftar-lab.create') }}" class="btn btn-primary btn-sm">+ Tambah Laboratorium</a>
        </div>
        <div class="card-body">
            <!-- Toolbar -->
            <div class="toolbar">
                <div class="toolbar-left">
                    <button class="filter-tab {{ !request('status') || request('status') == '1' ? 'active' : '' }}"
                            onclick="window.location.href='{{ route('admin.daftar-lab.index') }}'">
                        Lab Aktif
                    </button>
                    <button class="filter-tab {{ request('status') == '0' ? 'active' : '' }}"
                            onclick="window.location.href='{{ route('admin.daftar-lab.index', ['status' => 0]) }}'">
                        Lab Tidak Aktif
                    </button>
                </div>
                <input type="text" name="search" class="form-control" style="max-width: 260px;"
                       placeholder="Pencarian..."
                       value="{{ request('search') }}"
                       hx-get="{{ route('admin.daftar-lab.index') }}"
                       hx-trigger="keyup changed delay:500ms"
                       hx-target="#lab-table" hx-include="[name='status']">
            </div>

            <div id="lab-table">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Laboratorium</th>
                                <th>Kepala Laboratorium</th>
                                <th>Admin Laboratorium</th>
                               
                                <th>e-mail Laboratorium</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labs as $i => $lab)
                            <tr>
                                <td>{{ $labs->firstItem() + $i }}</td>
                                <td>{{ $lab->Nama_Laboratorium }}</td>
                                <td>{{ $lab->Kepala_Labolatorium }}</td>
                                <td>{{ $lab->Admin_Laboratorium }}</td>
                               
                                <td>{{ $lab->email_lab ?? '—' }}</td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('admin.daftar-lab.edit', $lab) }}" class="action-link edit">Edit</a>
                                        <form action="{{ route('admin.daftar-lab.destroy', $lab) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin hapus laboratorium ini?')" class="action-link delete-btn">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">Belum ada data laboratorium</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $labs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection