@extends('layouts.app')

@section('title', 'Aktivitas Administrator')
@section('page-title', 'Aktivitas Administrator')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header-info p { font-size: 13.5px; color: #6b7280; margin-top: 2px; }
    .filter-card { margin-bottom: 20px; }
    .filter-form { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    .filter-form .form-group { margin-bottom: 0; }
    .filter-form .form-group label { margin-bottom: 4px; }
    .action-text { font-weight: 600; }
    .action-login { color: #059669; }
    .action-logout { color: #6b7280; }
    .action-create { color: #2563eb; }
    .action-update { color: #d97706; }
    .action-delete { color: #dc2626; }
    .action-default { color: var(--color-primary); }
    .ip-text { font-family: monospace; font-size: 12px; color: #6b7280; background: #f3f4f6; padding: 2px 8px; border-radius: 4px; }
    .time-text { white-space: nowrap; }
    .empty-state { text-align: center; padding: 48px 20px; color: #6b7280; }
    .empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin-bottom: 16px; }
    .empty-state p { font-size: 14px; }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <p>Pantau semua aktivitas pengguna dalam sistem</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card filter-card">
        <div class="card-header">
            <h3>Filter Aktivitas</h3>
            @if(request()->hasAny(['dari','sampai']))
                <a href="{{ route('admin.aktivitas-administrator') }}" class="btn btn-outline btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset Filter
                </a>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>Log Aktivitas</h3>
            <span class="badge badge-info">{{ $logs->total() }} aktivitas</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 150px;">Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Detail</th>
                            <th style="width: 130px;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $i => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $i }}</td>
                            <td>
                                <span class="time-text">{{ $log->created_at->format('d M Y') }}</span>
                                <br>
                                <small style="color: #6b7280;">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 11px; flex-shrink: 0;">
                                        {{ strtoupper(substr($log->user_name ?? '?', 0, 2)) }}
                                    </div>
                                    <span style="font-weight: 500;">{{ $log->user_name }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $actionLower = strtolower($log->action);
                                    $actionClass = 'action-default';
                                    if(str_contains($actionLower, 'login')) $actionClass = 'action-login';
                                    elseif(str_contains($actionLower, 'logout')) $actionClass = 'action-logout';
                                    elseif(str_contains($actionLower, 'create') || str_contains($actionLower, 'tambah') || str_contains($actionLower, 'buat')) $actionClass = 'action-create';
                                    elseif(str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'ubah')) $actionClass = 'action-update';
                                    elseif(str_contains($actionLower, 'delete') || str_contains($actionLower, 'hapus')) $actionClass = 'action-delete';
                                @endphp
                                <span class="action-text {{ $actionClass }}">{{ $log->action }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td><code class="ip-text">{{ $log->ip_address }}</code></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p>Belum ada aktivitas yang tercatat</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
@endsection