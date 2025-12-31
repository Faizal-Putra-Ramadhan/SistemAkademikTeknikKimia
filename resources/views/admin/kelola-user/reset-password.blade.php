<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            
            <div class="page-header">
                <h1 class="page-title">Reset Password User</h1>
                <p class="page-subtitle">Atur ulang password untuk user</p>
            </div>

            <!-- Alert Messages -->
            @if(session('error'))
            <div class="alert alert-error">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- User Info Box -->
            <div class="info-box" style="background: #e3f2fd; border-color: #2196f3;">
                <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20" style="color: #2196f3;">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
                <div>
                    <strong>Reset Password untuk:</strong>
                    <p style="margin: 8px 0 0 0;">
                        <strong>{{ $user->Nama }}</strong> ({{ $user->Role_User }})<br>
                        User ID: <strong>{{ $user->UserID }}</strong><br>
                        Email: <strong>{{ $user->Email }}</strong>
                    </p>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="alert alert-warning" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20" style="color: #ffc107;">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <strong>Peringatan:</strong> Setelah password direset, user harus menggunakan password baru untuk login. Pastikan untuk memberitahu password baru kepada user yang bersangkutan.
            </div>

            <!-- Reset Password Form -->
            <div class="section">
                <form action="{{ route('admin.kelola-user.reset-password.update', $user->id) }}" method="POST" class="registration-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-grid">
                        <!-- Password Baru -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password Baru <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input @error('password') error @enderror" 
                                    placeholder="Minimal 6 karakter"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    👁️
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input" 
                                    placeholder="Ulangi password"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                    👁️
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="info-box" style="margin-top: 24px;">
                        <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong>Persyaratan Password:</strong>
                            <ul style="margin: 8px 0 0 20px;">
                                <li>Minimal 6 karakter</li>
                                <li>Password dan konfirmasi password harus sama</li>
                                <li>Disarankan menggunakan kombinasi huruf, angka, dan simbol</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-secondary">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" style="background: #ff9800;">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
    
</body>
</html>