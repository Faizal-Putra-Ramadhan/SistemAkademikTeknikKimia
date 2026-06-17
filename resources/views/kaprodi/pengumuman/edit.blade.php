@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.card { border: none; border-radius: 12px; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.1); }
        .btn-warning { background-color: #f59e0b; border-color: #f59e0b; color: white; }
        .btn-warning:hover { background-color: #d97706; border-color: #d97706; color: white; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center mb-6 border-bottom pb-4">
                <a href="{{ route('kaprodi.pengumuman.index') }}" class="btn btn-light rounded-circle mr-3 shadow-sm">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mb-0">
                    Edit Pengumuman
                </h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm ring-1 ring-black ring-opacity-5">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-gray-800 font-bold">
                                <i class="fas fa-pen-to-square text-amber-500 mr-2"></i> Edit Form Pengumuman
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('kaprodi.pengumuman.update', $pengumuman->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-gray-700">
                                        Judul Pengumuman <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="judul" 
                                           class="form-control rounded-lg py-4 @error('judul') is-invalid @enderror" 
                                           value="{{ old('judul', $pengumuman->judul) }}"
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
                                              required>{{ old('isi', $pengumuman->isi) }}</textarea>
                                    @error('isi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-gray-700">
                                        Status Publikasi <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-control rounded-lg @error('status') is-invalid @enderror" required>
                                        <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>
                                            📢 Publish (Tampilkan ke publik)
                                        </option>
                                        <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>
                                            📝 Draft (Simpan sebagai arsip/draft)
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('kaprodi.pengumuman.index') }}" class="btn btn-light text-gray-600 px-4 rounded-lg">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-warning px-5 py-2 rounded-lg shadow-sm font-weight-bold">
                                        <i class="fas fa-save mr-1"></i> Perbarui Pengumuman
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4 ring-1 ring-black ring-opacity-5">
                        <div class="card-header bg-info text-white py-3">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Log Pengumuman</h6>
                        </div>
                        <div class="card-body bg-white text-sm">
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-gray-500">Penulis:</span>
                                <span class="font-weight-bold text-gray-800">{{ $pengumuman->author }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-gray-500">Dibuat:</span>
                                <span class="text-gray-800">{{ $pengumuman->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-gray-500">Update terakhir:</span>
                                <span class="text-gray-800">{{ $pengumuman->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <hr>
                            <div class="text-center mt-3">
                                <span class="badge badge-pill px-3 py-2 badge-{{ $pengumuman->status == 'publish' ? 'success' : 'secondary' }}">
                                    Status: {{ ucfirst($pengumuman->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 ring-1 ring-red-100 mb-4">
                        <div class="card-header bg-red-50 py-3 border-bottom border-red-100">
                            <h6 class="mb-0 text-red-700 font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone</h6>
                        </div>
                        <div class="card-body bg-white">
                            <p class="text-xs text-gray-500 mb-3">
                                Menghapus pengumuman ini akan menghilangkannya secara permanen dari dashboard mahasiswa dan dosen.
                            </p>
                            <form action="{{ route('kaprodi.pengumuman.destroy', $pengumuman->id) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Hapus pengumuman ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-block rounded-lg py-2">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
