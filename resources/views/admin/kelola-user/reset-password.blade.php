@extends('layouts.app')

@section('title', 'Reset Password')
@section('page-title', 'Reset Password User')

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
    .user-info-card { display: flex; align-items: center; gap: 12px; padding: 16px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; margin-bottom: 20px; }
    .user-info-avatar { width: 48px; height: 48px; border-radius: 50%; background: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 18px; flex-shrink: 0; }
    .user-info-detail { font-size: 13.5px; color: #374151; line-height: 1.6; }
    .user-info-detail strong { color: #1f2937; }
    .req-list { margin: 8px 0 0 18px; font-size: 13px; color: #374151; }
    .req-list li { margin-bottom: 4px; }
</style>
@endpush

@section('content')
    <!-- User Info -->
    <div class="user-info-card">
        <div class="user-info-avatar">{{ substr($user->Nama, 0, 1) }}</div>
        <div class="user-info-detail">
            <strong>{{ $user->Nama }}</strong> &mdash; {{ $user->Role_User }}<br>
            User ID: <strong>{{ $user->UserID }}</strong> &middot; Email: <strong>{{ $user->Email }}</strong>
        </div>
    </div>

    <div class="alert-box warning">
        <strong>Peringatan:</strong> Setelah password direset, user harus menggunakan password baru untuk login. Pastikan untuk memberitahu password baru kepada user yang bersangkutan.
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Reset Password</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kelola-user.reset-password.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru <span class="required">*</span></label>
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

                <div class="alert-box info" style="margin-top: 20px;">
                    <strong>Persyaratan Password:</strong>
                    <ul class="req-list">
                        <li>Minimal 6 karakter</li>
                        <li>Password dan konfirmasi password harus sama</li>
                        <li>Disarankan menggunakan kombinasi huruf, angka, dan simbol</li>
                    </ul>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-outline btn-sm">Batal</a>
                    <button type="submit" class="btn btn-warning btn-sm">Reset Password</button>
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
</script>
@endpush