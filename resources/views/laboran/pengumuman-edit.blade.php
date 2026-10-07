@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@push('styles')

<!-- Quill Styles -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor-container { height: 250px; background-color: white; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; }
    .ql-toolbar.ql-snow { background-color: #f8fafc; border-top-left-radius: 8px; border-top-right-radius: 8px; border-color: #d1d5db; }
    .ql-container.ql-snow { border-color: #d1d5db; }
</style>

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
    }
    .form-grid {
        display: grid;
        gap: 1.2rem;
    }
    .form-field {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
    }
    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        border: 1px solid #d5dce6;
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
        font-size: 0.95rem;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-textarea {
        resize: vertical;
    }
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #0f6fff;
        box-shadow: 0 0 0 3px rgba(15, 111, 255, 0.15);
    }
    .form-error {
        font-size: 0.8rem;
        color: #d92d20;
    }
    .status-note {
        padding: 0.85rem 1rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.85rem;
    }
    .action-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 0.5rem;
        border-top: 1px solid #eef2f7;
    }
</style>
@endpush

@section('content')
    <div class="announce-page">
        <div class="announce-card">
            <div class="announce-header">
                <h2 class="announce-title">Edit Pengumuman</h2>
                <p class="announce-subtitle">Perbarui judul, isi, atau status publikasi pengumuman.</p>
            </div>
            
            <div class="announce-body">
                <form action="{{ route('laboran.pengumuman.update', $pengumuman->id) }}" method="POST" class="form-grid">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-field">
                        <label for="judul" class="form-label">Judul Pengumuman</label>
                        <input type="text" 
                               name="judul" 
                               id="judul" 
                               class="form-input @error('judul') border-red-500 @enderror"
                               placeholder="Masukkan judul pengumuman"
                               value="{{ old('judul', $pengumuman->judul) }}"
                               required>
                        @error('judul')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label for="isi" class="form-label">Isi Pengumuman</label>
                        <input type="hidden" name="isi" id="isi" value="{{ old('isi', $pengumuman->isi) }}">
                    <div id="editor-container" class="quill-editor-container">{!! old('isi', $pengumuman->isi) !!}</div>
                        @error('isi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" 
                                id="status" 
                                class="form-select @error('status') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                        </select>
                        @error('status')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div class="status-note">
                            <strong>Draft:</strong> Pengumuman disimpan tapi tidak ditampilkan ke mahasiswa.<br>
                            <strong>Publish:</strong> Pengumuman akan langsung ditampilkan ke mahasiswa.
                        </div>
                    </div>
                    
                    <div class="action-row">
                        <button type="submit" class="btn btn-primary">Update Pengumuman</button>
                        <a href="javascript:history.back()" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('scripts')

<!-- Quill Scripts -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('editor-container')) {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Tulis isi pengumuman di sini...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            var isiInput = document.querySelector('#isi');
            if (isiInput) {
                var form = isiInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (quill.root.innerText.trim().length === 0 && !quill.root.innerHTML.includes('<img')) {
                            isiInput.value = '';
                        } else {
                            isiInput.value = quill.root.innerHTML;
                        }
                    });
                }
            }
        }
    });
</script>

@endpush
@endsection
