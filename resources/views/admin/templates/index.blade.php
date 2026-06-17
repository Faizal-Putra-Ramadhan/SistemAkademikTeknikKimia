@extends('layouts.app')

@section('title', 'Kelola Template Surat')
@section('page-title', 'Kelola Template Surat')

@push('styles')
<style>
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
        margin-top: 20px;
    }
    .template-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        transition: transform 0.2s;
    }
    .template-card:hover {
        transform: translateY(-2px);
    }
    .template-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: #2563eb;
    }
    .template-info h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    .template-info p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .status-badge.exists { background: #dcfce7; color: #166534; }
    .status-badge.missing { background: #fee2e2; color: #991b1b; }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 32px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        display: block;
        margin-top: 8px;
    }
    .upload-zone:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .upload-zone svg {
        width: 32px;
        height: 32px;
        color: #94a3b8;
        margin: 0 auto 12px;
        display: block;
    }
    .upload-zone span {
        font-size: 14px;
        color: #475569;
        font-weight: 600;
    }
    .file-input { display: none; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="alert-box info mb-4">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div class="ml-3">
            <h4 class="font-bold">Informasi Template</h4>
            <p>Klik kotak di bawah untuk memilih file <strong>.docx</strong> baru. Setelah file dipilih, template akan <strong>otomatis diperbarui</strong>.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-box success mb-4">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="ml-2">{{ session('success') }}</span>
        </div>
    @endif

    <div class="template-grid">
        @foreach($templates as $template)
        <div class="template-card">
            <div class="template-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="template-info">
                <h3>{{ $template['name'] }}</h3>
                <p>Nama File: <code>{{ $template['filename'] }}</code></p>
                
                <div class="status-badge {{ $template['exists'] ? 'exists' : 'missing' }}">
                    @if($template['exists'])
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Aktif di Sistem
                    @else
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        File Tidak Ditemukan
                    @endif
                </div>

                <form action="{{ route($template['route']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="upload-zone" for="file-{{ $loop->index }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span>Pilih File .docx Baru</span>
                        <input type="file" name="template" id="file-{{ $loop->index }}" class="file-input" accept=".docx" onchange="this.form.submit()">
                    </label>
                    @error('template')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
