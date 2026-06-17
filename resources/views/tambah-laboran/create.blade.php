@extends('layouts.app')

@section('title', 'Tambah Laboran')
@section('page-title', 'Tambah Laboran Baru')

@push('styles')
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .password-wrapper { position: relative; }
    .password-wrapper .form-control { padding-right: 40px; }
    .toggle-password { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 4px; }
    .toggle-password:hover { color: #374151; }
    .link-section { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .link-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
</style>
@endpush

@section('content')
    <!-- Info Box -->
    <div class="alert-box info">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <span><strong>Info:</strong> User ID akan dibuat otomatis. Role default untuk laboran adalah <strong>Laboran</strong>.</span>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Formulir Tambah Laboran</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tambah-laboran.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Pilih Laboratorium <span class="required">*</span></label>
                        <p class="form-hint" style="margin-bottom: 12px;">Pilih satu atau lebih laboratorium yang akan dikelola oleh laboran ini</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px; max-height: 300px; overflow-y: auto; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background-color: #f9fafb;">
                            @forelse($daftar_labs as $lab)
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px; border-radius: 4px; transition: background-color 0.2s;" 
                                       onmouseover="this.style.backgroundColor='#f3f4f6'" 
                                       onmouseout="this.style.backgroundColor='transparent'">
                                    <input type="checkbox" 
                                           name="laboratorium_ids[]" 
                                           value="{{ $lab->id }}"
                                           {{ in_array($lab->id, old('laboratorium_ids', [])) ? 'checked' : '' }}
                                           style="width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px;">{{ $lab->Nama_Laboratorium }}</span>
                                </label>
                            @empty
                                <p style="color: #6b7280; font-size: 14px;">Tidak ada laboratorium tersedia</p>
                            @endforelse
                        </div>
                        @error('laboratorium_ids') <span class="error-msg">{{ $message }}</span> @enderror
                        @error('laboratorium_ids.*') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Nama_Laboran" class="form-label">Nama Laboran <span class="required">*</span></label>
                        <input type="text" name="Nama_Laboran" id="Nama_Laboran" class="form-control"
                               value="{{ old('Nama_Laboran') }}" placeholder="Contoh: Dr. Ahmad Wijaya" required>
                        @error('Nama_Laboran') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Phone" class="form-label">Nomor Telepon <span class="required">*</span></label>
                        <input type="tel" name="Phone" id="Phone" class="form-control"
                               value="{{ old('Phone') }}" placeholder="Contoh: 081234567890" required>
                        @error('Phone') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Email" class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="Email" id="Email" class="form-control"
                               value="{{ old('Email') }}" placeholder="Contoh: ahmad@email.com" required>
                        @error('Email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Password" class="form-label">Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="Password" id="Password" class="form-control"
                                   placeholder="Minimal 6 karakter" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('Password')">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('Password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="Password_confirmation" id="Password_confirmation" class="form-control"
                                   placeholder="Ulangi password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('Password_confirmation')">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Role_User" class="form-label">Role User <span class="required">*</span></label>
                        <select name="Role_User" id="Role_User" class="form-control" required>
                            <option value="Laboran" {{ old('Role_User', 'Laboran') == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                        </select>
                        <p class="form-hint">Role default untuk laboran adalah Laboran</p>
                        @error('Role_User') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Account Linking (sama seperti di Registrasi User Admin) -->
                <!-- <div class="link-section">
                    <div class="form-group">
                        <label for="link_to_parent" class="form-label">Account Linking (Opsional)</label>
                        <select name="link_to_parent" id="link_to_parent" class="form-control">
                            <option value="">-- Tidak di-link (Buat sebagai Primary Account) --</option>
                            @foreach($potentialParents as $parent)
                                <option value="{{ $parent->id }}" {{ old('link_to_parent') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->Nama }} ({{ $parent->Role_User }}) - {{ $parent->Email }}
                                </option>
                            @endforeach
                        </select>
                        <p class="link-hint">Jika di-link, laboran ini bisa switch antar role dengan akun parent.</p>
                        @error('link_to_parent') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div> -->

                <div class="form-actions">
                    <a href="{{ route('admin.tambah-laboran.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Data Laboran</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // Validasi form: minimal 1 laboratorium harus dipilih
    document.querySelector('form').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('input[name="laboratorium_ids[]"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Minimal pilih satu laboratorium!');
            return false;
        }
    });
</script>
@endpush