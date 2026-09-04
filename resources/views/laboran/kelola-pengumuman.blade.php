@extends('layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')

@push('styles')
<style>
    .announce-page {
        max-width: 980px;
        margin: 0 auto;
        padding: 1.5rem 1.25rem 2.5rem;
    }
    .announce-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .announce-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .announce-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .announce-subtitle {
        margin: 0.4rem 0 0;
        color: #64748b;
        font-size: 0.9rem;
    }
    .announce-body {
        padding: 1.5rem;
        display: grid;
        gap: 1rem;
    }
    .announce-item {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.2rem;
        background: #fff;
    }
    .announce-meta {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    .announce-heading {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .announce-info {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.2rem;
    }
    .announce-status {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .announce-status.publish {
        background: #e7f6ef;
        color: #0f5132;
    }
    .announce-status.draft {
        background: #e2e8f0;
        color: #475569;
    }
    .announce-actions {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-top: 0.85rem;
    }
    .announce-empty {
        text-align: center;
        color: #64748b;
        padding: 2rem 1rem;
        border-radius: 14px;
        border: 1px dashed #dbe3ef;
        background: #f8fafc;
    }
</style>
@endpush

@section('content')
    <div class="announce-page">
        
        
        <div class="announce-card">
            <div class="announce-header">
                <div>
                    <h2 class="announce-title">Daftar Pengumuman</h2>
                    <p class="announce-subtitle">Kelola pengumuman untuk mahasiswa dan civitas lab.</p>
                </div>
                <a href="{{ route('laboran.pengumuman.create') }}" class="btn btn-primary">
                    Buat Pengumuman
                </a>
            </div>
            <div class="announce-body">
                @forelse($pengumuman as $item)
                    <div class="announce-item">
                        <div class="announce-meta">
                            <div>
                                <h3 class="announce-heading">{{ $item->judul }}</h3>
                                <div class="announce-info">{{ $item->author }} • {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</div>
                            </div>
                            <span class="announce-status {{ $item->status == 'publish' ? 'publish' : 'draft' }}">
                                {{ $item->status == 'publish' ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                        <p class="text-gray-700">{{ Str::limit($item->isi, 200) }}</p>
                        <div class="announce-actions">
                            <a href="{{ route('laboran.pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('laboran.pengumuman.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="announce-empty">Belum ada pengumuman</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto dismiss success alert after 5 seconds
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.3s ease-out';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 300);
        }, 5000);
    }
</script>
@endpush