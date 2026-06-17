@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@push('styles')
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .form-group .required { color: #dc2626; }
    .form-group .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .form-control.readonly { background: #f0f2f5; cursor: not-allowed; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
    <!-- Info Box -->
    <div class="alert-box info">
        <strong>Informasi:</strong> User ID <strong>{{ $user->UserID }}</strong> tidak dapat diubah. Untuk mengganti password, gunakan menu "Reset Password".
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Edit User</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kelola-user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    // Nilai dari data user (fallback untuk old() agar form selalu terisi nilai sebelumnya)
                    $prevNama = old('nama', $user->Nama ?? '');
                    $prevEmail = old('email', $user->Email ?? '');
                    $prevPhone = old('Phone', $user->Phone ?? '');
                    $prevNomorIdentitas = old('nomor_identitas', $user->Nomor_Identitas ?? $user->nomor_identitas ?? '');
                @endphp

                <div class="form-grid">
                    <div class="form-group">
                        <label for="userid" class="form-label">User ID</label>
                        <input type="text" id="userid" class="form-control readonly" value="{{ $user->UserID ?? '' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" value="{{ $prevNama }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $prevEmail }}" placeholder="contoh@email.com" required>
                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Phone" class="form-label">Nomor Telepon <span class="required">*</span></label>
                        <input type="tel" id="Phone" name="Phone" class="form-control" value="{{ $prevPhone }}" placeholder="08123456789" required>
                        @error('Phone') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="nomor_identitas" class="form-label">Nomor Identitas</label>
                        <input type="text" id="nomor_identitas" name="nomor_identitas" class="form-control" value="{{ $prevNomorIdentitas }}" placeholder="NIM / NIY (opsional)">
                        @error('nomor_identitas') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Roles <span class="required">*</span></label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 8px;">
                            @foreach($roles as $role)
                                @php
                                    // Get user roles - check both new roles table and old Role_User
                                    $userRolesFromDb = $user->roles->pluck('name')->toArray();
                                    $userRoles = old('roles', !empty($userRolesFromDb) ? $userRolesFromDb : [$user->Role_User]);
                                    $isChecked = in_array($role->name, $userRoles);
                                    $primaryRoleFromDb = $user->primaryRole()?->name;
                                    $primaryRole = old('primary_role', $primaryRoleFromDb ?? $user->Role_User);
                                    $isPrimary = $primaryRole == $role->name;
                                @endphp
                                <label style="display: flex; align-items: center; gap: 8px; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; background: {{ $isChecked ? '#f0f9ff' : '#fff' }};">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $isChecked ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
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
                    
                    @push('scripts')
                    <script>
                        function updatePrimaryRoleOptions() {
                            // Get all checked checkboxes
                            const checkedBoxes = document.querySelectorAll('input[name="roles[]"]:checked');
                            
                            // Hide/show radio buttons based on checkbox state
                            document.querySelectorAll('input[name="roles[]"]').forEach((checkbox) => {
                                const label = checkbox.closest('label');
                                const radio = label.querySelector('input[type="radio"]');
                                if (radio) {
                                    radio.style.display = checkbox.checked ? 'block' : 'none';
                                    if (!checkbox.checked) {
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

                    <div class="form-group">
                        <label for="created_at" class="form-label">Terdaftar Sejak</label>
                        <input type="text" id="created_at" class="form-control readonly" value="{{ $user->created_at->format('d M Y H:i') }}" readonly>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-outline btn-sm">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection