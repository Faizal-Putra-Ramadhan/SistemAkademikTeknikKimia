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
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .subtitle {
        color: #666;
        margin-bottom: 2rem;
    }
    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn-group .btn {
        flex: 1;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <div class="form-container">
        <div class="card">
            <div class="card-body">
                <h2 style="margin-bottom: 0.5rem;"> Edit Pengumuman</h2>
                <p class="subtitle">Update pengumuman yang sudah dibuat</p>

                @if($errors->any())
                <div class="alert-box danger" style="margin-bottom: 1rem;">
                    <ul style="margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('kepala-lab.pengumuman.update', $pengumuman->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label" for="judul">Judul Pengumuman *</label>
                        <input type="text" class="form-control" id="judul" name="judul" required value="{{ old('judul', $pengumuman->judul) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="isi">Isi Pengumuman *</label>
                        <input type="hidden" name="isi" id="isi" value="{{ old('isi', $pengumuman->isi) }}">
                    <div id="editor-container" class="quill-editor-container">{!! old('isi', $pengumuman->isi) !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                            <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('kepala-lab.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Pengumuman</button>
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
