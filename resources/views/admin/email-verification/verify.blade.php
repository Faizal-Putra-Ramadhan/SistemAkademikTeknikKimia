@extends('layouts.app')

@section('title', 'Verifikasi Email')
@section('page-title', 'Verifikasi Email')

@push('styles')
<style>
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table td { padding: 8px 12px; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .info-table td:first-child { font-weight: 600; width: 180px; color: #6b7280; }
    .confirm-btn { width: 100%; padding: 12px; font-size: 15px; }
    .helper-text { text-align: center; font-size: 13px; color: #6b7280; margin-top: 12px; }
</style>
@endpush

@section('content')
    <div class="card" style="max-width: 700px;">
        <div class="card-header">
            <h3>Verifikasi Email</h3>
        </div>
        <div class="card-body">
            <div class="alert-box info" style="margin-bottom: 20px;">
                <strong>Verifikasi Email Terhadap Pengguna</strong> &mdash;
                Email dari <strong>{{ $pending->email }}</strong> untuk pengguna <strong>{{ $pending->nama }}</strong> perlu diverifikasi.
            </div>

            <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 12px;">Informasi Pengguna</h4>
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
                        <td>Nomor Telepon</td>
                        <td>{{ $pending->phone }}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td><span class="badge badge-info">{{ $pending->role }}</span></td>
                    </tr>
                    <tr>
                        <td>Token Berlaku Hingga</td>
                        <td>{{ $pending->token_expires_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div class="alert-box success" style="margin-bottom: 20px;">
                Email ini belum diverifikasi. Klik tombol di bawah untuk mengkonfirmasi verifikasi.
            </div>

            <form action="{{ route('admin.email-verification.confirm', $token) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success confirm-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Konfirmasi Verifikasi Email
                </button>
            </form>

            <p class="helper-text">Setelah dikonfirmasi, user akan langsung ditambahkan ke sistem dan dapat login.</p>
        </div>
    </div>
@endsection
