@extends('layouts.app')

@section('title', 'Sudah Diverifikasi')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .result-icon { text-align: center; margin-bottom: 16px; }
    .result-icon svg { width: 56px; height: 56px; }
    .result-title { text-align: center; font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
    .helper-text { text-align: center; font-size: 13.5px; color: #6b7280; margin-bottom: 20px; }
    .center-actions { text-align: center; margin-top: 20px; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header" style="background: #dbeafe; border-bottom-color: #bfdbfe;">
            <h3 style="color: #1e40af;">Email Sudah Diverifikasi</h3>
        </div>
        <div class="card-body">
            <div class="result-icon">
                <svg fill="none" stroke="#2563eb" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="result-title">{{ $message }}</p>

            <div class="alert-box info">
                <strong>Status:</strong> Email ini telah diverifikasi sebelumnya dan user sudah ditambahkan ke sistem.
            </div>

            <p class="helper-text">
                User sudah dapat mengakses sistem dengan User ID dan password yang terdaftar.
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
