@extends('layouts.app')

@section('title', 'Detail Lab - Daftar Alat')
@section('page-title', 'Detail Lab - Daftar Alat')

@push('styles')
<style>
    .back-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: #6c757d;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        margin-bottom: 1rem;
    }
    .lab-header {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .lab-header h2 {
        color: #333;
        margin-bottom: 1rem;
    }
    .alat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .alat-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .alat-card:hover {
        transform: translateY(-5px);
    }
    .alat-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
    }
    .alat-body {
        padding: 1.5rem;
    }
    .alat-body h3 {
        color: #333;
        margin-bottom: 0.5rem;
    }
    .alat-body p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .stock {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: bold;
        margin-top: 0.5rem;
    }
    .stock-available {
        background: #d4edda;
        color: #155724;
    }
    .stock-low {
        background: #fff3cd;
        color: #856404;
    }
    .stock-empty {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 10px;
    }
    .empty-state p {
        color: #666;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
        <a href="{{ route('peneliti-eksternal.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

        <div class="lab-header">
            <h2>Daftar Alat di {{ $lab->Nama_Laboratorium }}</h2>
            <p style="color: #666;">Berikut adalah daftar alat yang tersedia di laboratorium ini.</p>
        </div>

        @if($lab->alatLabs->count() > 0)
        <div class="alat-grid">
            @foreach($lab->alatLabs as $alat)
            <div class="alat-card">
                <div class="alat-image">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        🔧
                    @endif
                </div>
                <div class="alat-body">
                    <h3>{{ $alat->nama_alat }}</h3>
                    <p>{{ $alat->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    
                    @if($alat->jumlah_tersedia > 5)
                        <span class="stock stock-available">✓ Tersedia: {{ $alat->jumlah_tersedia }} unit</span>
                    @elseif($alat->jumlah_tersedia > 0)
                        <span class="stock stock-low">⚠ Stok Terbatas: {{ $alat->jumlah_tersedia }} unit</span>
                    @else
                        <span class="stock stock-empty">✗ Stok Habis</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>Belum ada alat yang terdaftar di laboratorium ini.</p>
        </div>
        @endif
@endsection