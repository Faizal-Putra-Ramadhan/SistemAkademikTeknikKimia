@extends('layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .pengumuman-grid {
        display: grid;
        gap: 1rem;
    }
    .pengumuman-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        border-left: 4px solid #0d6efd;
    }
    .pengumuman-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .pengumuman-title {
        flex: 1;
    }
    .pengumuman-title h3 {
        color: #333;
        margin-bottom: 0.25rem;
    }
    .pengumuman-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #666;
    }
    .pengumuman-content {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    .pengumuman-actions {
        display: flex;
        gap: 0.5rem;
    }
    .empty-state {
        background: white;
        padding: 3rem;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }
    .empty-state p {
        color: #666;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <a href="{{ route('kepala-lab.dashboard') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <a href="{{ route('kepala-lab.pengumuman.create') }}" class="btn btn-primary">+ Buat Pengumuman</a>
    </div>

    @if($pengumuman->count() > 0)
    <div class="pengumuman-grid">
        @foreach($pengumuman as $item)
        <div class="pengumuman-card">
            <div class="pengumuman-header">
                <div class="pengumuman-title">
                    <h3>{{ $item->judul }}</h3>
                    <div class="pengumuman-meta">
                        <span>👤 {{ $item->author }}</span>
                        <span>📅 {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</span>
                        <span class="badge {{ $item->status == 'publish' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="pengumuman-content">
                {{ Str::limit($item->isi, 200) }}
            </div>
            
            @if($item->author === $user->Nama)
            <div class="pengumuman-actions">
                <a href="{{ route('kepala-lab.pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">
                    ✏️ Edit
                </a>
                <form action="{{ route('kepala-lab.pengumuman.destroy', $item->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <p>Belum ada pengumuman.</p>
        <a href="{{ route('kepala-lab.pengumuman.create') }}" class="btn btn-primary">Buat Pengumuman Pertama</a>
    </div>
    @endif
@endsection
