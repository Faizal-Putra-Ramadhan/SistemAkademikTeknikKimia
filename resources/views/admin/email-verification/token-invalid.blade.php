@extends('layouts.app')

@section('title', 'Token Invalid')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .result-icon { text-align: center; margin-bottom: 16px; }
    .result-icon svg { width: 56px; height: 56px; }
    .result-title { text-align: center; font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
    .center-actions { text-align: center; margin-top: 20px; }
    .cause-list { margin: 8px 0 0 18px; font-size: 13px; }
    .cause-list li { margin-bottom: 4px; }
    .helper-text { text-align: center; font-size: 13.5px; color: #6b7280; margin-bottom: 20px; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header" style="background: #fee2e2; border-bottom-color: #fecaca;">
            <h3 style="color: #991b1b;">Token Tidak Valid</h3>
        </div>
        <div class="card-body">
            <div class="result-icon">
                <svg fill="none" stroke="#dc2626" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="result-title">{{ $message }}</p>

            <div class="alert-box danger" style="display: block;">
                <strong>Kemungkinan Penyebab:</strong>
                <ul class="cause-list">
                    <li>Token sudah digunakan sebelumnya</li>
                    <li>Token tidak pernah ada</li>
                    <li>Link sudah diperbaharui atau dihapus</li>
                </ul>
            </div>

            <p class="helper-text">
                Silakan hubungi administrator untuk mendaftarkan ulang atau mengirimkan ulang link verifikasi.
            </p>

            <div class="center-actions">
                <a href="{{ route('admin.tambah-user.index') }}" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Tambah User
                </a>
            </div>
        </div>
    </div>
@endsection
