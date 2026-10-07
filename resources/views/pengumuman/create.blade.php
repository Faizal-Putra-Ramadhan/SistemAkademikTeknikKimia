@extends('layouts.app')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman Baru')

@push('styles')

<!-- Quill Styles -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor-container { height: 250px; background-color: white; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; }
    .ql-toolbar.ql-snow { background-color: #f8fafc; border-top-left-radius: 8px; border-top-right-radius: 8px; border-color: #d1d5db; }
    .ql-container.ql-snow { border-color: #d1d5db; }
</style>

<style>
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    textarea.form-control { min-height: 220px; resize: vertical; font-family: inherit; line-height: 1.6; }
</style>
@endpush

@section('content')
    <!-- Info Box -->
    <div class="alert-box info">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <span><strong>Info:</strong> Pengumuman dengan status <strong>Publish</strong> akan langsung terlihat oleh semua pengguna. Pilih <strong>Draft</strong> untuk menyimpan tanpa mempublikasikan.</span>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Formulir Pengumuman Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="judul" class="form-label">Judul Pengumuman <span class="required">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control"
                           value="{{ old('judul') }}" placeholder="Masukkan judul pengumuman" required>
                    @error('judul') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="isi" class="form-label">Isi Pengumuman <span class="required">*</span></label>
                    <input type="hidden" name="isi" id="isi" value="{{ old('isi') }}">
                    <div id="editor-container" class="quill-editor-container">{!! old('isi') !!}</div>
                    @error('isi') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="max-width: 300px;">
                    <label for="status" class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control">
                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <p class="form-hint">Pilih "Publish" agar pengumuman langsung tampil</p>
                    @error('status') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Pengumuman</button>
                </div>
            </form>
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