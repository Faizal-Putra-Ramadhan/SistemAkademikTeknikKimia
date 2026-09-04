@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@push('styles')
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
                    <textarea name="isi" id="isi" class="form-control" placeholder="Tulis isi pengumuman di sini..." required>{{ old('isi', $pengumuman->isi) }}</textarea>
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
@endsection