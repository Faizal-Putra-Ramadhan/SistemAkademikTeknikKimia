@extends('layouts.app')

@section('title', 'Daftar Laboran')
@section('page-title', 'Daftar Laboran Laboratorium')

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
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .search-filter-bar { display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-filter-bar .form-control { max-width: 300px; }
    .empty-state { text-align: center; padding: 48px 20px; color: #6b7280; }
    .empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin-bottom: 16px; }
    .empty-state p { font-size: 14px; margin-bottom: 16px; }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-info">
            <p>Kelola data laboran yang bertugas di setiap laboratorium</p>
        </div>
        <a href="{{ route('admin.tambah-laboran.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Laboran
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="stat-info">
                <p>Total Laboran</p>
                <h3>{{ $daftar_laborans->total() }}</h3>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-box success">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box danger">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Laboran</h3>
            <span class="badge badge-info">{{ $daftar_laborans->total() }} data</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Laboratorium</th>
                            <th>Nama Laboran</th>
                            <th>User ID</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_laborans as $index => $laboran)
                            <tr>
                                <td>{{ $daftar_laborans->firstItem() + $index }}</td>
                                <td>
                                    @if($laboran->laboratoriums && $laboran->laboratoriums->count() > 0)
                                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                            @foreach($laboran->laboratoriums as $lab)
                                                <span class="badge badge-info" style="font-size: 11px; padding: 4px 8px;">
                                                    {{ $lab->Nama_Laboratorium }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @elseif($laboran->Laboratorium)
                                        <span style="font-weight: 500;">{{ $laboran->Laboratorium }}</span>
                                        <span class="badge badge-warning" style="font-size: 10px; margin-left: 6px;">Legacy</span>
                                    @else
                                        <span class="badge badge-warning" style="font-size: 11px;">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="font-weight: 500;">{{ $laboran->Nama_Laboran }}</span>
                                    </div>
                                </td>
                                <td><code style="background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $laboran->UserID }}</code></td>
                                <td>{{ $laboran->Phone }}</td>
                                <td>{{ $laboran->Email }}</td>
                                <td><span class="badge badge-primary">{{ $laboran->Role_User }}</span></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="{{ route('admin.tambah-laboran.edit', $laboran->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.tambah-laboran.destroy', $laboran->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data laboran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                                        <p>Belum ada data laboran yang terdaftar</p>
                                        <a href="{{ route('admin.tambah-laboran.create') }}" class="btn btn-primary">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                            Tambah Laboran Pertama
                                        </a>
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
    @if($daftar_laborans->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $daftar_laborans->links() }}
    </div>
    @endif
@endsection
