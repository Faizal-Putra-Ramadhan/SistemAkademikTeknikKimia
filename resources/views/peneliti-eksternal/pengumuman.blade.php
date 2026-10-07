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
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .pengumuman-card {
        background: #fafafa;
        border: 1px solid #eaeaea;
        padding: 1.25rem 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 12px;
    }
    .status-publish {
        background: #eff6ff;
        color: #3b82f6;
    }
    .status-draft {
        background: #fffbeb;
        color: #d97706;
    }
    .pengumuman-title-new h3 {
        color: #1f2937;
        margin-bottom: 12px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .pengumuman-excerpt {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    .pengumuman-footer-new {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .pengumuman-footer-new a {
        color: #3b82f6;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }
    .pengumuman-footer-new a:hover {
        text-decoration: underline;
    }
    .pengumuman-date {
        color: #9ca3af;
        font-size: 13px;
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
        <div>
            <h2 style="font-size: 1.2rem; font-weight: 700; color: #111827;">Daftar Pengumuman</h2>
            <p style="color: #64748b; font-size: 0.9rem;">Kelola pengumuman untuk mahasiswa dan civitas lab.</p>
        </div>
        
    </div>

    @if($pengumuman->count() > 0)
    <div class="pengumuman-grid">
        @foreach($pengumuman as $item)
        <div class="pengumuman-card">
            <div>
                <span class="status-badge status-{{ $item->status }}">
                    {{ ucfirst($item->status) }}
                </span>
            </div>
            <div class="pengumuman-title-new">
                <h3>{{ $item->judul }}</h3>
            </div>
            <div class="pengumuman-excerpt">
                {{ Str::limit(strip_tags(str_replace(['</p>', '<br>', '</h1>', '</h2>', '</h3>', '</li>'], ' ', $item->isi)), 150) }}
            </div>
            <div class="pengumuman-footer-new">
                <a href="javascript:void(0)" onclick="openPengumumanModal('modal-pengumuman-{{ $item->id }}')">Lihat Selengkapnya</a>
                <span class="pengumuman-date">{{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                <span class="pengumuman-date" style="margin-left: 8px;">• {{ $item->author }}</span>
            </div>
            
            <!-- Modal -->
            <div id="modal-pengumuman-{{ $item->id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <div style="background: #fff; width: 90%; max-width: 650px; border-radius: 12px; padding: 30px; max-height: 85vh; overflow-y: auto; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: left; white-space: normal;">
                    <button type="button" onclick="closePengumumanModal('modal-pengumuman-{{ $item->id }}')" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 28px; cursor: pointer; color: #6b7280; line-height: 1;">&times;</button>
                    <h2 style="margin-bottom: 8px; color: #1f2937; font-size: 20px;">{{ $item->judul }}</h2>
                    <div style="font-size: 13px; color: #6b7280; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb;">
                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    <div class="quill-content" style="color: #374151; line-height: 1.6;">
                        {!! $item->isi !!}
                    </div>
                </div>
            </div>
            
            
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <p>Belum ada pengumuman.</p>
        
    </div>
    @endif
@endsection

@push('scripts')
<script>
    function openPengumumanModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePengumumanModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target.id && event.target.id.startsWith('modal-pengumuman-')) {
            closePengumumanModal(event.target.id);
        }
    }
</script>
@endpush
