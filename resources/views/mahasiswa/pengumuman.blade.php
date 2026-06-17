@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@push('styles')
<style>
    .pengumuman-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }
    .pengumuman-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .pengumuman-header h2 {
        color: #333;
        margin: 0;
    }
    .pengumuman-date {
        color: #999;
        font-size: 0.85rem;
    }
    .pengumuman-author {
        color: #667eea;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .pengumuman-content {
        color: #666;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    .empty-state {
        background: white;
        padding: 3rem;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .empty-state p {
        color: #666;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
    @if($pengumuman->count() > 0)
        @foreach($pengumuman as $item)
        <div class="pengumuman-card">
            <div class="pengumuman-header">
                <h2>{{ $item->judul }}</h2>
                <div class="pengumuman-date">
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                </div>
            </div>
            <div class="pengumuman-author">
                {{ $item->author }}
            </div>
            <div class="pengumuman-content">
                {{ $item->isi }}
            </div>
        </div>
        @endforeach
    @else
    <div class="empty-state">
        <p>Belum ada pengumuman.</p>
    </div>
    @endif
@endsection
