@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@push('styles')
<style>
* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            color: #667eea;
        }
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .back-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
        }
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
<div class="navbar">
        <h1>📢 Pengumuman</h1>
    </div>

    <div class="container">
        <a href="{{ route('peneliti-eksternal.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

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
                    👤 {{ $item->author }}
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
    </div>
@endsection
