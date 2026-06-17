@extends('layouts.app')

@section('title', 'Tambah Laboratorium')
@section('page-title', 'Tambah Laboratorium')

@push('styles')
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Tambah Laboratorium Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.daftar-lab.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="Nama_Laboratorium" class="form-label">Nama Laboratorium <span class="required">*</span></label>
                        <input type="text" id="Nama_Laboratorium" name="Nama_Laboratorium" class="form-control" value="{{ old('Nama_Laboratorium') }}" placeholder="Lab Teknik Informatika" required>
                        @error('Nama_Laboratorium') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="floor" class="form-label">Lantai <span class="required">*</span></label>
                        <input type="text" id="floor" name="floor" class="form-control" value="{{ old('floor') }}" placeholder="Lt 2" required>
                        @error('floor') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="lab_type" class="form-label">Jenis Lab <span class="required">*</span></label>
                        <select name="lab_type" id="lab_type" class="form-control" required>
                            <option value="">-- Pilih Jenis Lab --</option>
                            @foreach($labTypes as $labType)
                                <option value="{{ $labType }}" {{ old('lab_type') == $labType ? 'selected' : '' }}>
                                    {{ ucfirst($labType) }}
                                </option>
                            @endforeach
                        </select>
                        @error('lab_type') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="Kepala_Labolatorium" class="form-label">Kepala Laboratorium <span class="required">*</span></label>
                        <select name="Kepala_Labolatorium" id="Kepala_Labolatorium" class="form-control" required>
                            <option value="">-- Pilih Kepala Lab --</option>
                            @foreach($kepalaLabList as $kepalaLab)
                                <option value="{{ $kepalaLab->Nama }}" {{ old('Kepala_Labolatorium') == $kepalaLab->Nama ? 'selected' : '' }}>
                                    {{ $kepalaLab->Nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('Kepala_Labolatorium') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="Admin_Laboratorium" class="form-label">Admin Laboratorium <span class="required">*</span></label>
                        <input type="text" id="Admin_Laboratorium" name="Admin_Laboratorium" class="form-control" value="{{ old('Admin_Laboratorium') }}" required>
                        @error('Admin_Laboratorium') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="email_lab" class="form-label">Email Laboratorium <small style="color:#6b7280;">(opsional)</small></label>
                        <input type="email" id="email_lab" name="email_lab" class="form-control" value="{{ old('email_lab') }}" placeholder="lab@informatika.uad.ac.id">
                        @error('email_lab') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.daftar-lab.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Laboratorium</button>
                </div>
            </form>
        </div>
    </div>
@endsection