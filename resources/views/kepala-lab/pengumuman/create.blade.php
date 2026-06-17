@extends('layouts.app')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
    }
    .subtitle {
        color: #666;
        margin-bottom: 2rem;
    }
    .help-text {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.25rem;
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
                <h2 style="margin-bottom: 0.5rem;">📢 Buat Pengumuman Baru</h2>
                <p class="subtitle">Buat pengumuman untuk mahasiswa dan pengguna laboratorium</p>

                @if($errors->any())
                <div class="alert-box danger" style="margin-bottom: 1rem;">
                    <ul style="margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('kepala-lab.pengumuman.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="judul">Judul Pengumuman *</label>
                        <input type="text" class="form-control" id="judul" name="judul" required placeholder="Masukkan judul pengumuman" value="{{ old('judul') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="isi">Isi Pengumuman *</label>
                        <textarea class="form-control" id="isi" name="isi" required placeholder="Tulis isi pengumuman di sini..." style="min-height: 200px; resize: vertical;">{{ old('isi') }}</textarea>
                        <div class="help-text">Tulis pengumuman dengan jelas dan detail</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish (Langsung Tampil)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan sebagai Draft)</option>
                        </select>
                        <div class="help-text">Pilih "Publish" agar pengumuman langsung terlihat oleh semua pengguna</div>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('kepala-lab.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
