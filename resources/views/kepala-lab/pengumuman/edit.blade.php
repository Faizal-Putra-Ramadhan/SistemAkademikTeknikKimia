@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
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
                <h2 style="margin-bottom: 0.5rem;">✏️ Edit Pengumuman</h2>
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
                        <textarea class="form-control" id="isi" name="isi" required style="min-height: 200px; resize: vertical;">{{ old('isi', $pengumuman->isi) }}</textarea>
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
@endsection
