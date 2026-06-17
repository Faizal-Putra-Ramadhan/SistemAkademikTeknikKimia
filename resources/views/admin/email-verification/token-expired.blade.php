@extends('layouts.app')

@section('title', 'Token Expired')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .result-icon { text-align: center; margin-bottom: 16px; }
    .result-icon svg { width: 56px; height: 56px; }
    .result-title { text-align: center; font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table td { padding: 8px 12px; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .info-table td:first-child { font-weight: 600; width: 160px; color: #6b7280; }
    .center-actions { text-align: center; margin-top: 20px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header" style="background: #fef3c7; border-bottom-color: #fde68a;">
            <h3 style="color: #92400e;">Token Verifikasi Kadaluarsa</h3>
        </div>
        <div class="card-body">
            <div class="result-icon">
                <svg fill="none" stroke="#d97706" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="result-title">{{ $message }}</p>

            <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 12px;">Informasi Pendaftaran</h4>
                <table class="info-table">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $pending->nama }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $pending->email }}</td>
                    </tr>
                    <tr>
                        <td>Token Expired</td>
                        <td>{{ $pending->token_expires_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div class="alert-box warning">
                <strong>Opsi yang Tersedia:</strong> Silakan meminta admin untuk mengirimkan ulang email verifikasi atau mendaftarkan ulang pengguna.
            </div>

            <div class="center-actions">
                <form action="{{ route('admin.email-verification.resend') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $pending->email }}">
                    <button type="submit" class="btn btn-warning">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>
                <a href="{{ route('admin.tambah-user.index') }}" class="btn btn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Tambah User
                </a>
            </div>
        </div>
    </div>
@endsection
