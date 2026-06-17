@extends('layouts.app')

@section('title', 'Verifikasi Berhasil')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .result-icon { text-align: center; margin-bottom: 16px; }
    .result-icon svg { width: 56px; height: 56px; }
    .result-title { text-align: center; font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table td { padding: 8px 12px; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .info-table td:first-child { font-weight: 600; width: 160px; color: #6b7280; }
    .center-actions { text-align: center; margin-top: 20px; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header" style="background: #d1fae5; border-bottom-color: #a7f3d0;">
            <h3 style="color: #065f46;">Verifikasi Email Berhasil</h3>
        </div>
        <div class="card-body">
            <div class="result-icon">
                <svg fill="none" stroke="#059669" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="result-title">{{ $message }}</p>

            <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 12px;">Detail User yang Ditambahkan</h4>
                <table class="info-table">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $user->Nama }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->Email }}</td>
                    </tr>
                    <tr>
                        <td>User ID</td>
                        <td><strong style="color: var(--color-primary);">{{ $user->UserID }}</strong></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td><span class="badge badge-info">{{ $user->Role_User }}</span></td>
                    </tr>
                    <tr>
                        <td>Ditambahkan</td>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div class="alert-box info">
                <strong>Informasi:</strong> User sekarang dapat login menggunakan User ID <strong>{{ $user->UserID }}</strong> dan password yang didaftarkan.
            </div>

            <div class="center-actions">
                <a href="{{ route('admin.tambah-user.index') }}" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Tambah User
                </a>
            </div>
        </div>
    </div>
@endsection
