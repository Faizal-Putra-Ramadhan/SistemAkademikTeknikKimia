@extends('layouts.app')

@section('title', 'Kelola Alat')
@section('page-title', 'Alat Lab: ' . $lab->Nama_Laboratorium)

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .modal-backdrop {
        background: rgba(15, 23, 42, 0.6);
    }
    .modal-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
        max-width: 520px;
        width: 100%;
        overflow: hidden;
    }
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
    }
    .modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .form-grid {
        display: grid;
        gap: 1rem;
    }
    .form-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
    }
    .form-input,
    .form-textarea,
    .form-file {
        width: 100%;
        border: 1px solid #d5dce6;
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
        font-size: 0.95rem;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-textarea {
        resize: vertical;
    }
    .form-input:focus,
    .form-textarea:focus,
    .form-file:focus {
        outline: none;
        border-color: #0f6fff;
        box-shadow: 0 0 0 3px rgba(15, 111, 255, 0.15);
    }
    .form-help {
        font-size: 0.8rem;
        color: #64748b;
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #eef2f7;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div x-data="{ 
    isTambahOpen: false, 
    isEditOpen: false,
    editAlat: { id: '', nama_alat: '', jumlah_total: '', deskripsi: '', daftar_lab_id: '' },
    openEdit(alat) {
        this.editAlat = { ...alat };
        this.editAlat.scope = alat.daftar_lab_id ? 'this_lab' : 'all_labs';
        this.isEditOpen = true;
    }
}">

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="text-xl font-bold">Daftar Alat Laboratorium</h2>
                <div class="flex gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $lab->floor }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $lab->lab_type === 'penelitian' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($lab->lab_type) }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                        Stok Bersama
                    </span>
                </div>
            </div>
            <button @click="isTambahOpen = true" class="btn btn-primary">
                + Tambah Alat
            </button>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Alat</th>
                        <th style="text-align: center;">Stok</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $alat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($alat->foto)
                                <img src="{{ asset('uploads/' . $alat->foto) }}" 
                                     class="size-12 rounded-lg object-cover border" 
                                     alt="{{ $alat->nama_alat }}">
                            @else
                                <div class="size-12 bg-gray-100 rounded-lg flex items-center justify-center text-[10px] text-gray-400 border border-dashed">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="font-medium text-gray-900">{{ $alat->nama_alat }}</div>
                            <div class="text-gray-500 text-xs truncate max-w-xs">{{ $alat->deskripsi ?? 'Tanpa deskripsi' }}</div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge {{ $alat->jumlah_tersedia > 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $alat->jumlah_tersedia }} Unit
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="flex justify-end gap-2">
                                <button @click="openEdit({{ $alat }})" class="btn btn-warning btn-sm">Edit</button>
                                <form action="{{ route('laboran.alat.destroy', [$lab->id, $alat->id]) }}" method="POST" onsubmit="return confirm('Hapus alat?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-gray-500">Belum ada data alat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $alats->links() }}</div>
    </div>

    <!-- Modal Tambah Alat -->
    <div x-show="isTambahOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 modal-backdrop transition-opacity" @click="isTambahOpen = false"></div>
            <div class="relative modal-card">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Alat Baru</h3>
                </div>
                <div class="modal-body">
                    <form action="{{ route('laboran.alat.store', $lab->id) }}" method="POST" enctype="multipart/form-data" class="form-grid">
                        @csrf
                        <div class="form-field">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" required class="form-input" placeholder="Contoh: Mikroskop Binokuler">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Jumlah Total</label>
                            <input type="number" name="jumlah" min="1" required class="form-input" placeholder="Masukkan jumlah unit">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Foto Alat</label>
                            <input type="file" name="foto" class="form-file">
                            <div class="form-help">Format JPG/PNG, max 2MB.</div>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-textarea" placeholder="Catatan singkat tentang alat"></textarea>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Cakupan (Scope)</label>
                            <div class="flex gap-4 mt-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="scope" value="this_lab" checked class="form-radio text-blue-600">
                                    <span class="text-sm text-gray-700">Hanya Lab Ini</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="scope" value="all_labs" class="form-radio text-blue-600">
                                    <span class="text-sm text-gray-700">Semua Lab ({{ $lab->floor }} - {{ ucfirst($lab->lab_type) }})</span>
                                </label>
                            </div>
                            <div class="form-help">"Semua Lab" akan membuat alat ini tersedia di seluruh lab {{ $lab->lab_type }} di lantai {{ $lab->floor }}.</div>
                        </div>
                        <div class="modal-actions">
                            <button type="button" @click="isTambahOpen = false" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Alat -->
    <div x-show="isEditOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 modal-backdrop transition-opacity" @click="isEditOpen = false"></div>
            <div class="relative modal-card">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Data Alat</h3>
                </div>
                <div class="modal-body">
                    <form :action="`{{ url('laboran/lab/'.$lab->id.'/alat') }}/${editAlat.id}`" method="POST" enctype="multipart/form-data" class="form-grid">
                        @csrf @method('PUT')
                        <div class="form-field">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" x-model="editAlat.nama_alat" required class="form-input">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Jumlah Total</label>
                            <input type="number" name="jumlah" x-model="editAlat.jumlah_tersedia" required class="form-input">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Update Foto (Opsional)</label>
                            <input type="file" name="foto" class="form-file">
                            <div class="form-help">Biarkan kosong jika tidak diganti.</div>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editAlat.deskripsi" rows="3" class="form-textarea"></textarea>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Cakupan (Scope)</label>
                            <div class="flex gap-4 mt-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="scope" value="this_lab" x-model="editAlat.scope" class="form-radio text-blue-600">
                                    <span class="text-sm text-gray-700">Hanya Lab Ini</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="scope" value="all_labs" x-model="editAlat.scope" class="form-radio text-blue-600">
                                    <span class="text-sm text-gray-700">Semua Lab ({{ $lab->floor }} - {{ ucfirst($lab->lab_type) }})</span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="button" @click="isEditOpen = false" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection