@extends('layouts.app')

@section('title', 'Ubah Profil')
@section('page-title', 'Ubah Profil')

@push('styles')
<style>
    .profile-container { max-width: 720px; margin: 0 auto; }
    .profile-photo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding: 30px 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    .photo-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
    }
    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e5e7eb;
        background: #f3f4f6;
    }
    .photo-initials {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-primary), #4338ca);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        border: 4px solid #e5e7eb;
    }
    .photo-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: var(--color-primary);
        color: white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        border: none;
    }
    .photo-upload-btn:hover { background: var(--color-primary-hover); }
    .photo-upload-btn svg { flex-shrink: 0; }
    .photo-hint { font-size: 12px; color: #9ca3af; }
    .photo-filename { font-size: 12px; color: #374151; margin-top: 4px; display: none; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-grid .full-width { grid-column: 1 / -1; }
    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <div class="profile-container">
        @if(session('success'))
            <div class="alert-box success" style="margin-bottom: 20px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box danger" style="margin-bottom: 20px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3>Informasi Profil</h3>
                <span class="badge badge-info">{{ $user->Role_User }}</span>
            </div>
            <div class="card-body" style="padding: 0;">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Photo Section -->
                    <div class="profile-photo-section">
                        @if($user->foto && file_exists(public_path('uploads/profile/' . $user->foto)))
                            <img src="{{ asset('uploads/profile/' . $user->foto) }}" alt="Foto Profil" class="photo-preview" id="photoPreview">
                        @else
                            <div class="photo-initials" id="photoInitials">
                                {{ strtoupper(substr($user->Nama ?? '?', 0, 2)) }}
                            </div>
                            <img src="" alt="Foto Profil" class="photo-preview" id="photoPreview" style="display: none;">
                        @endif

                        <div style="text-align: center;">
                            <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/png,image/jpg" style="display: none;">
                            <label for="fotoInput" class="photo-upload-btn">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Upload Foto
                            </label>
                            <div class="photo-hint">Format: JPG, JPEG, PNG (Maks: 2MB)</div>
                            <div class="photo-filename" id="photoFilename"></div>
                            @error('foto') <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div style="padding: 24px;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="Nama" value="{{ old('Nama', $user->Nama) }}" class="form-control" required>
                                @error('Nama') <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">User ID</label>
                                <input type="text" value="{{ $user->UserID }}" class="form-control" disabled style="background: #f9fafb; color: #6b7280;">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                                <input type="email" name="Email" value="{{ old('Email', $user->Email) }}" class="form-control" required>
                                @error('Email') <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor Telepon <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="Phone" value="{{ old('Phone', $user->Phone) }}" class="form-control" required placeholder="081234567890">
                                @error('Phone') <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                            <button type="submit" class="btn btn-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show filename
            const filenameEl = document.getElementById('photoFilename');
            filenameEl.textContent = file.name;
            filenameEl.style.display = 'block';

            // Preview image
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('photoPreview');
                preview.src = ev.target.result;
                preview.style.display = 'block';
                // Hide initials if present
                const initials = document.getElementById('photoInitials');
                if (initials) initials.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush