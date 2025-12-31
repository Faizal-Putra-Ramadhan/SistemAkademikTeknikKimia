<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            
            <div class="page-header">
                <h1 class="page-title">Edit User</h1>
                <p class="page-subtitle">Perbarui informasi user dalam sistem</p>
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

            <!-- Info Box -->
            <div class="info-box">
                <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <strong>Informasi:</strong>
                    <p>User ID <strong>{{ $user->UserID }}</strong> tidak dapat diubah. Untuk mengganti password, gunakan menu "Reset Password".</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="section">
                <form action="{{ route('admin.kelola-user.update', $user->id) }}" method="POST" class="registration-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-grid">
                        <!-- User ID (Read Only) -->
                        <div class="form-group">
                            <label for="userid" class="form-label">User ID</label>
                            <input 
                                type="text" 
                                id="userid" 
                                class="form-input" 
                                value="{{ $user->UserID }}"
                                readonly
                                style="background: #f8f9fa; cursor: not-allowed;"
                            >
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                class="form-input @error('nama') error @enderror" 
                                value="{{ old('nama', $user->Nama) }}"
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
                                value="{{ old('email', $user->Email) }}"
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
                                value="{{ old('Phone', $user->Phone) }}"
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
                                <option value="Admin" {{ old('role', $user->Role_User) == 'Admin' ? 'selected' : '' }}>⚙️ Admin</option>
                                <option value="Dosen" {{ old('role', $user->Role_User) == 'Dosen' ? 'selected' : '' }}>👨‍🏫 Dosen</option>
                                <option value="Safety Officer" {{ old('role', $user->Role_User) == 'Safety Officer' ? 'selected' : '' }}>👤 Safety Officer</option>
                                <option value="Kepala Laboratorium" {{ old('role', $user->Role_User) == 'Kepala Laboratorium' ? 'selected' : '' }}>👤 Kepala Laboratorium</option>
                                <option value="Mahasiswa" {{ old('role', $user->Role_User) == 'Mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                                <option value="Laboran" {{ old('role', $user->Role_User) == 'Laboran' ? 'selected' : '' }}>🎓 Laboran</option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Created At (Read Only) -->
                        <div class="form-group">
                            <label for="created_at" class="form-label">Terdaftar Sejak</label>
                            <input 
                                type="text" 
                                id="created_at" 
                                class="form-input" 
                                value="{{ $user->created_at->format('d M Y H:i') }}"
                                readonly
                                style="background: #f8f9fa; cursor: not-allowed;"
                            >
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-secondary">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    
</body>
</html>