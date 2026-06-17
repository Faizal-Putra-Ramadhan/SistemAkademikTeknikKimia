@extends('layouts.app')

@section('title', 'Error Verifikasi')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .result-icon { text-align: center; margin-bottom: 16px; }
    .result-icon svg { width: 56px; height: 56px; }
    .result-title { text-align: center; font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
    .center-actions { text-align: center; margin-top: 20px; }
    .error-detail { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b; }
    .error-detail pre { margin: 8px 0 0; white-space: pre-wrap; word-break: break-all; font-size: 12px; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header" style="background: #fee2e2; border-bottom-color: #fecaca;">
            <h3 style="color: #991b1b;">Terjadi Kesalahan</h3>
        </div>
        <div class="card-body">
            <div class="result-icon">
                <svg fill="none" stroke="#dc2626" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="result-title">{{ $message }}</p>

            @if(isset($error) && env('APP_DEBUG'))
            <div class="error-detail">
                <strong>Error Detail:</strong>
                <pre>{{ $error }}</pre>
            </div>
            @endif

            <div class="alert-box warning">
                <strong>Tindakan Selanjutnya:</strong> Silakan hubungi administrator untuk bantuan lebih lanjut.
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
