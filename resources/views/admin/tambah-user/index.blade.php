<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            
            <div class="page-header">
                <h1 class="page-title">Registrasi User Baru</h1>
                <p class="page-subtitle">Daftarkan user baru untuk sistem laboratorium. User ID akan di-generate otomatis.</p>
            </div>

            <!-- Alert Success/Error -->
            @if(session('success'))
            <div class="alert alert-success">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Info Box -->
            <div class="info-box">
                <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <strong>Informasi:</strong>
                    <p>User ID akan di-generate otomatis oleh sistem berdasarkan role yang dipilih.</p>
                    <ul>
                        <li><strong>Admin:</strong> ADM-XXXXXXXXXX</li>
                        <li><strong>Dosen:</strong> DSN-XXXXXXXXXX</li>
                        <li><strong>Tendik:</strong> TDK-XXXXXXXXXX</li>
                        <li><strong>Mahasiswa:</strong> MHS-XXXXXXXXXX</li>
                    </ul>
                </div>
            </div>

            <!-- Form Registrasi -->
            <div class="section">
                <form action="{{ route('admin.tambah-user.store') }}" method="POST" class="registration-form">
                    @csrf
                    
                    <div class="form-grid">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                class="form-input @error('nama') error @enderror" 
                                value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                            @error('nama')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="required">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') error @enderror" 
                                value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                required
                            >
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="Phone" class="form-label">Nomor Telepon <span class="required">*</span></label>
                            <input 
                                type="tel" 
                                id="Phone" 
                                name="Phone" 
                                class="form-input @error('Phone') error @enderror" 
                                value="{{ old('Phone') }}"
                                placeholder="08123456789"
                                required
                            >
                            @error('Phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role" class="form-label">Role User <span class="required">*</span></label>
                            <select 
                                id="role" 
                                name="role" 
                                class="form-input @error('role') error @enderror"
                                required
                            >
                                <option value="">Pilih Role</option>
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>⚙️ Admin</option>
                                <option value="Dosen" {{ old('role') == 'Dosen' ? 'selected' : '' }}>👨‍🏫 Dosen</option>
                                <option value="Safety Officer" {{ old('role') == 'Safety Officer' ? 'selected' : '' }}>👤 Safety Officer</option>
                                <option value="Kepala Laboratorium" {{ old('role') == 'Kepala Laboratorium' ? 'selected' : '' }}>👤 Kepala Laboratorium</option>
                                <option value="Mahasiswa" {{ old('role') == 'Mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                                <option value="Laboran" {{ old('role') == 'Laboran' ? 'selected' : '' }}>🎓 Laboran</option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
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

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                            </svg>
                            Daftarkan User
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