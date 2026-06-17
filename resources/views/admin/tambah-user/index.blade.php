@extends('layouts.app')

@section('title', 'Registrasi User')
@section('page-title', 'Registrasi User Baru')

@push('styles')
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .password-wrapper { position: relative; }
    .password-wrapper .form-control { padding-right: 40px; }
    .toggle-password { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 4px; }
    .toggle-password:hover { color: #374151; }
    .info-list { margin: 6px 0 0 18px; font-size: 13px; color: #374151; }
    .info-list li { margin-bottom: 3px; }
    .link-section { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .link-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
</style>
@endpush

@section('content')
    <!-- Info Box -->
    <div class="alert-box info">
        <strong>Informasi:</strong> User ID akan di-generate otomatis berdasarkan role yang dipilih.
        <ul class="info-list">
            <li><strong>Admin:</strong> ADM-XXXXXXXXXX</li>
            <li><strong>Dosen:</strong> DSN-XXXXXXXXXX</li>
            <li><strong>Tendik:</strong> TDK-XXXXXXXXXX</li>
            <li><strong>Mahasiswa:</strong> MHS-XXXXXXXXXX</li>
            <li><strong>Peneliti Eksternal:</strong> PEX-XXXXXXXXXX</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Registrasi User Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tambah-user.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Phone" class="form-label">Nomor Telepon <span class="required">*</span></label>
                        <input type="tel" id="Phone" name="Phone" class="form-control" value="{{ old('Phone') }}" placeholder="08123456789" required>
                        @error('Phone') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Roles <span class="required">*</span></label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 8px;">
                            @foreach($roles as $role)
                                @php
                                    $oldRoles = old('roles', []);
                                    $isChecked = in_array($role->name, $oldRoles);
                                    $isPrimary = old('primary_role', $oldRoles[0] ?? '') == $role->name;
                                @endphp
                                <label style="display: flex; align-items: center; gap: 8px; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; background: {{ $isChecked ? '#f0f9ff' : '#fff' }};">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $isChecked ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;" onchange="updatePrimaryRoleOptions()">
                                    <span style="flex: 1; font-weight: {{ $isPrimary ? '600' : '400' }};">{{ $role->display_name ?? $role->name }}</span>
                                    @if($isChecked)
                                        <input type="radio" name="primary_role" value="{{ $role->name }}" {{ $isPrimary ? 'checked' : '' }} style="width: 16px; height: 16px; cursor: pointer;" title="Set as primary role">
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        <small style="display: block; margin-top: 8px; color: #6b7280;">Pilih satu atau lebih role. Centang radio button untuk set primary role.</small>
                        @error('roles') <span class="error-msg">{{ $message }}</span> @enderror
                        @error('roles.*') <span class="error-msg">{{ $message }}</span> @enderror
                        @error('primary_role') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Account Linking -->
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
                        <p class="link-hint">Jika di-link, user ini akan bisa switch antar role dengan parent account</p>
                    </div>
                </div> -->

                <div class="form-grid" style="margin-top: 16px;">
                    <div class="form-group" id="nomor-identitas-group">
                        <label for="nomor_identitas" class="form-label"><span id="label-identitas">Nomor Identitas</span> <span class="required">*</span></label>
                        <input type="text" id="nomor_identitas" name="nomor_identitas" class="form-control" value="{{ old('nomor_identitas') }}" placeholder="Masukkan nomor identitas">
                        @error('nomor_identitas') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-outline btn-sm">Reset</button>
                    <button type="submit" class="btn btn-primary btn-sm">Daftarkan User</button>
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

    function updatePrimaryRoleOptions() {
        // Get all checked checkboxes
        const checkedBoxes = document.querySelectorAll('input[name="roles[]"]:checked');
        const labels = document.querySelectorAll('label[for*="role"]');
        
        // Hide/show radio buttons based on checkbox state
        checkedBoxes.forEach((checkbox) => {
            const label = checkbox.closest('label');
            const radio = label.querySelector('input[type="radio"]');
            if (radio) {
                radio.style.display = 'block';
            }
        });
        
        // Hide radio buttons for unchecked roles
        document.querySelectorAll('input[name="roles[]"]').forEach((checkbox) => {
            if (!checkbox.checked) {
                const label = checkbox.closest('label');
                const radio = label.querySelector('input[type="radio"]');
                if (radio) {
                    radio.style.display = 'none';
                    radio.checked = false;
                }
            }
        });
        
        // If no primary role is selected and there are checked roles, select first one
        const checkedRoles = Array.from(checkedBoxes);
        const primaryRadios = document.querySelectorAll('input[name="primary_role"]:checked');
        if (checkedRoles.length > 0 && primaryRadios.length === 0) {
            const firstChecked = checkedRoles[0];
            const firstLabel = firstChecked.closest('label');
            const firstRadio = firstLabel.querySelector('input[type="radio"]');
            if (firstRadio) {
                firstRadio.checked = true;
            }
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updatePrimaryRoleOptions();
        
        // Add event listeners to all checkboxes
        document.querySelectorAll('input[name="roles[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updatePrimaryRoleOptions);
        });
    });
</script>
@endpush