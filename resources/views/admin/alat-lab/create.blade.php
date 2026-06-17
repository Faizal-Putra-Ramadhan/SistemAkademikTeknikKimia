@extends('layouts.app')

@section('title', 'Tambah Alat Lab')
@section('page-title', 'Tambah Alat Lab')

@push('styles')
<style>
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Tambah Alat / Aset Lab</h3>
        </div>
        <div class="card-body" x-data="{ scope: '{{ old('scope', 'all_labs') }}' }">
            <form action="{{ route('admin.alat-lab.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="form-label">Cakupan Stok (Scope)</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="scope" value="all_labs" x-model="scope" class="form-radio text-blue-600">
                            <span class="text-sm">Semua Lab (Lantai & Jenis)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="scope" value="this_lab" x-model="scope" class="form-radio text-blue-600">
                            <span class="text-sm">Lab Spesifik</span>
                        </label>
                    </div>
                    @error('scope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Opsi Semua Lab (Lantai & Jenis) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" x-show="scope === 'all_labs'">
                    <div class="form-group">
                        <label class="form-label">Lantai</label>
                        <select name="floor" :required="scope === 'all_labs'" class="form-control">
                            <option value="">-- Pilih Lantai --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor }}" {{ old('floor') == $floor ? 'selected' : '' }}>{{ $floor }}</option>
                            @endforeach
                        </select>
                        @error('floor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Lab</label>
                        <select name="lab_type" :required="scope === 'all_labs'" class="form-control">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type }}" {{ old('lab_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                        @error('lab_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Opsi Lab Spesifik -->
                <div class="form-group mb-4" x-show="scope === 'this_lab'">
                    <label class="form-label">Pilih Laboratorium</label>
                    <select name="daftar_lab_id" :required="scope === 'this_lab'" class="form-control">
                        <option value="">-- Pilih Laboratorium --</option>
                        @foreach($daftarLabs as $lab)
                            <option value="{{ $lab->id }}" {{ old('daftar_lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->Nama_Laboratorium }} ({{ $lab->floor }} - {{ ucfirst($lab->lab_type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('daftar_lab_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="form-hint">Alat ini hanya akan muncul di laboratorium yang dipilih.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required class="form-control">
                    @error('nama_alat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Tersedia</label>
                    <input type="number" name="jumlah_tersedia" value="{{ old('jumlah_tersedia', 1) }}" min="0" required class="form-control">
                    @error('jumlah_tersedia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Alat (opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="form-control">
                    @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="form-hint">Format: JPG/PNG, max 2MB</p>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.alat-lab.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Alat</button>
                </div>
            </form>
        </div>
    </div>
@endsection