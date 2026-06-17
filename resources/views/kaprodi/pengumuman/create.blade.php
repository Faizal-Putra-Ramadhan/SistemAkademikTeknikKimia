@extends('layouts.app')
@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.card { border: none; border-radius: 12px; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.1); }
        .btn-primary { background-color: #4f46e5; border-color: #4f46e5; }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center mb-6 border-bottom pb-4">
                <a href="{{ route('kaprodi.pengumuman.index') }}" class="btn btn-light rounded-circle mr-3 shadow-sm">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mb-0">
                    Buat Pengumuman Baru
                </h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm ring-1 ring-black ring-opacity-5">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-gray-800 font-bold"><i class="fas fa-edit text-indigo-600 mr-2"></i> Form Pengumuman</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('kaprodi.pengumuman.store') }}" method="POST">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-gray-700">
                                        Judul Pengumuman <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="judul" 
                                           class="form-control rounded-lg py-4 @error('judul') is-invalid @enderror" 
                                           value="{{ old('judul') }}"
                                           placeholder="Contoh: Jadwal Ujian Akhir Semester Ganjil"
                                           required>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-gray-700">
                                        Isi Pengumuman <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="isi" 
                                              class="form-control rounded-lg @error('isi') is-invalid @enderror" 
                                              rows="12"
                                              placeholder="Tuliskan detail pengumuman di sini..."
                                              required>{{ old('isi') }}</textarea>
                                    @error('isi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle mr-1"></i> Gunakan bahasa yang formal dan mudah dimengerti.
                                    </small>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-gray-700">
                                        Status Publikasi <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-control rounded-lg @error('status') is-invalid @enderror" required>
                                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>
                                            📢 Publish (Langsung tampilkan ke publik)
                                        </option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                            📝 Draft (Simpan untuk diedit nanti)
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('kaprodi.pengumuman.index') }}" class="btn btn-light text-gray-600 px-4 rounded-lg">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-lg shadow-sm font-weight-bold">
                                        <i class="fas fa-save mr-1"></i> Simpan Pengumuman
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4 ring-1 ring-black ring-opacity-5">
                        <div class="card-header bg-info text-white py-3">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-lightbulb mr-2"></i> Tips Menulis</h6>
                        </div>
                        <div class="card-body bg-white text-gray-600">
                            <ul class="pl-3 mb-0 small leading-loose">
                                <li>Gunakan judul yang spesifik.</li>
                                <li>Sertakan kontak person jika diperlukan.</li>
                                <li>Periksa kembali ejaan sebelum di-publish.</li>
                                <li>Gunakan paragraf singkat agar mudah dibaca di perangkat mobile.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 ring-1 ring-black ring-opacity-5">
                        <div class="card-header bg-gray-800 text-white py-3">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Meta Data</h6>
                        </div>
                        <div class="card-body bg-white text-sm">
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-gray-500">Pembuat:</span>
                                <span class="font-weight-bold text-gray-800">{{ $user->Nama }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-gray-500">Waktu:</span>
                                <span class="font-weight-bold text-gray-800">{{ now()->format('d M Y') }}</span>
                            </div>
                            <hr>
                            <p class="small text-gray-500 mb-0 italic">
                                * Pengumuman yang di-publish akan muncul di halaman utama mahasiswa dan dosen.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
