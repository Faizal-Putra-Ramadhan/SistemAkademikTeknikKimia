@extends('layouts.app')

@section('title', 'Daftar Lab')
@section('page-title', 'Daftar Laboratorium')

@push('styles')
<style>
    .labs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .lab-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .lab-card:hover {
        transform: translateY(-5px);
    }
    .lab-card h3 {
        color: #667eea;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    .lab-info {
        color: #666;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .lab-info strong {
        color: #333;
    }
    .lab-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }
    .lab-actions .btn-lab {
        padding: 0.75rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        font-size: 0.9rem;
        transition: opacity 0.3s;
        font-weight: 600;
        display: block;
    }
    .lab-actions .btn-lab:hover {
        opacity: 0.8;
    }
    .btn-lab-primary {
        background: #667eea;
        color: white;
    }
    .btn-lab-success {
        background: #28a745;
        color: white;
    }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>🔬 Pilih Laboratorium</h3>
            <a href="{{ route('dosen.dashboard') }}" style="padding: 0.5rem 1rem; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: 600;">← Kembali ke Dashboard</a>
        </div>
        <div class="card-body">
            <p style="color: #666; margin-bottom: 1.5rem;">Pilih laboratorium untuk melakukan peminjaman ruangan atau alat.</p>

            <div class="labs-grid">
                @forelse($labs as $lab)
                <div class="lab-card">
                    <h3>{{ $lab->Nama_Laboratorium }}</h3>
                    <div class="lab-info">
                        <strong>Kepala Lab:</strong> {{ $lab->Kepala_Labolatorium }}
                    </div>
                    <div class="lab-info">
                        <strong>Admin:</strong> {{ $lab->Admin_Laboratorium }}
                    </div>
                    <div class="lab-info">
                        <strong>Safety Officer:</strong> {{ $lab->Safety_Officer }}
                    </div>
                    <div class="lab-info">
                        <strong>Email:</strong> {{ $lab->email_lab }}
                    </div>
                    
                    <div class="lab-actions">
                        <a href="{{ route('dosen.pinjam-ruangan', $lab->id) }}" class="btn-lab btn-lab-primary">
                            📅 Pinjam Ruangan
                        </a>
                        <a href="{{ route('dosen.pinjam-alat', $lab->id) }}" class="btn-lab btn-lab-success">
                            🔧 Pinjam Alat
                        </a>
                    </div>
                </div>
                @empty
                <p style="color: #666; text-align: center; grid-column: 1 / -1; font-size: 1.2rem;">
                    Belum ada laboratorium tersedia.
                </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection