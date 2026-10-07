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
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    textarea.form-control { min-height: 250px; resize: vertical; font-family: inherit; line-height: 1.6; }
</style>
@endpush

@section('content')
    <!-- Alerts -->
    

    <div class="card">
        <div class="card-header">
            <h3>Edit Pengumuman</h3>
            <span class="badge {{ $pengumuman->status == 'publish' ? 'badge-success' : 'badge-warning' }}">
                {{ $pengumuman->status == 'publish' ? 'Publish' : 'Draft' }}
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="judul" class="form-label">Judul Pengumuman <span class="required">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control"
                           value="{{ old('judul', $pengumuman->judul) }}" placeholder="Masukkan judul pengumuman" required>
                    @error('judul') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="isi" class="form-label">Isi Pengumuman <span class="required">*</span></label>
                    <input type="hidden" name="isi" id="isi" value="{{ old('isi', $pengumuman->isi) }}">
                    <div id="editor-container" class="quill-editor-container">{!! old('isi', $pengumuman->isi) !!}</div>
                    @error('isi') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="max-width: 300px;">
                    <label for="status" class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control">
                        <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                        <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <p class="form-hint">Pilih "Publish" agar pengumuman langsung tampil</p>
                    @error('status') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
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