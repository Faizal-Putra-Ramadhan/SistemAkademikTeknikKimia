@extends('layouts.app')

@section('title', 'Edit Alat Lab')
@section('page-title', 'Edit Alat Lab')

@push('styles')
<style>
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .current-photo { width: 160px; height: 160px; object-fit: cover; border-radius: 8px; margin-top: 8px; border: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Alat / Aset Lab</h3>
        </div>
        <div class="card-body" x-data="{ scope: '{{ $alat->daftar_lab_id ? 'this_lab' : 'all_labs' }}' }">
            <form action="{{ route('admin.alat-lab.update', $alat) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                </div>

                <!-- Opsi Semua Lab (Lantai & Jenis) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" x-show="scope === 'all_labs'">
                    <div class="form-group">
                        <label class="form-label">Lantai</label>
                        <select name="floor" :required="scope === 'all_labs'" class="form-control">
                            <option value="">-- Pilih Lantai --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor }}" {{ (old('floor', $alat->stockGroup->floor ?? '') == $floor) ? 'selected' : '' }}>
                                    {{ $floor }}
                                </option>
                            @endforeach
                        </select>
                        @error('floor') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Lab</label>
                        <select name="lab_type" :required="scope === 'all_labs'" class="form-control">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type }}" {{ (old('lab_type', $alat->stockGroup->lab_type ?? '') == $type) ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('lab_type') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Opsi Lab Spesifik -->
                <div class="form-group mb-4" x-show="scope === 'this_lab'">
                    <label class="form-label">Pilih Laboratorium</label>
                    <select name="daftar_lab_id" :required="scope === 'this_lab'" class="form-control">
                        <option value="">-- Pilih Laboratorium --</option>
                        @foreach($daftarLabs as $lab)
                            <option value="{{ $lab->id }}" {{ (old('daftar_lab_id', $alat->daftar_lab_id) == $lab->id) ? 'selected' : '' }}>
                                {{ $lab->Nama_Laboratorium }} ({{ $lab->floor }} - {{ ucfirst($lab->lab_type) }})
                            </option>
                        @endforeach
                    </select>
                    <p class="form-hint">Alat ini hanya akan muncul di laboratorium yang dipilih.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required class="form-control">
                    @error('nama_alat') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                    @error('deskripsi') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Tersedia</label>
                    <input type="number" name="jumlah_tersedia" value="{{ old('jumlah_tersedia', $alat->jumlah_tersedia) }}" min="0" required class="form-control">
                    @error('jumlah_tersedia') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Saat Ini</label>
                    @if($alat->foto)
                        <div>
                            <img src="{{ asset('uploads/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}" class="current-photo">
                        </div>
                        <p class="form-hint">Ganti foto (kosongkan jika tidak ingin mengganti)</p>
                    @else
                        <p class="form-hint" style="font-style: italic;">Tidak ada foto</p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="form-control">
                    <p class="form-hint">Format: JPG/PNG, max 2MB</p>
                    @error('foto') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.alat-lab.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Update Alat</button>
                </div>
            </form>
        </div>
    </div>
@endsection