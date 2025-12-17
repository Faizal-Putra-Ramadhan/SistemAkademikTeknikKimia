<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laboran - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
        }
        .form-group input:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .info-message {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn-submit {
            flex: 1;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-cancel {
            flex: 1;
            background-color: #6c757d;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="form-container">
                <h2>Edit Data Laboran</h2>
                
                <div class="info-message">
                    ⚠️ <strong>Perhatian:</strong> Jika Anda mengubah role, User ID akan dibuat ulang secara otomatis.
                </div>

                <form action="{{ route('tambah-laboran.update', $laboran->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="UserID">User ID (Otomatis)</label>
                        <input type="text" 
                               id="UserID" 
                               value="{{ $laboran->UserID }}"
                               disabled
                               style="background-color: #e9ecef;">
                        <small style="color: #6c757d;">User ID dibuat otomatis dan akan diperbarui jika role berubah</small>
                    </div>

                    <div class="form-group">
                        <label for="Laboratorium">Nama Laboratorium <span class="required">*</span></label>
                        <input type="text" 
                               name="Laboratorium" 
                               id="Laboratorium" 
                               value="{{ old('Laboratorium', $laboran->Laboratorium) }}">
                        @error('Laboratorium')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Nama_Laboran">Nama Laboran <span class="required">*</span></label>
                        <input type="text" 
                               name="Nama_Laboran" 
                               id="Nama_Laboran" 
                               value="{{ old('Nama_Laboran', $laboran->Nama_Laboran) }}">
                        @error('Nama_Laboran')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Phone">Nomor Telepon <span class="required">*</span></label>
                        <input type="text" 
                               name="Phone" 
                               id="Phone" 
                               value="{{ old('Phone', $laboran->Phone) }}">
                        @error('Phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Email">Email <span class="required">*</span></label>
                        <input type="email" 
                               name="Email" 
                               id="Email" 
                               value="{{ old('Email', $laboran->Email) }}">
                        @error('Email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Role_User">Role User <span class="required">*</span></label>
                        <select name="Role_User" id="Role_User">
                            <option value="">-- Pilih Role --</option>
                            <option value="Admin" {{ old('Role_User', $laboran->Role_User) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Tendik" {{ old('Role_User', $laboran->Role_User) == 'Tendik' ? 'selected' : '' }}>Tendik</option>
                            <option value="Dosen" {{ old('Role_User', $laboran->Role_User) == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Mahasiswa" {{ old('Role_User', $laboran->Role_User) == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="Laboran" {{ old('Role_User', $laboran->Role_User) == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                            <option value="Koordinator Laboran" {{ old('Role_User', $laboran->Role_User) == 'Koordinator Laboran' ? 'selected' : '' }}>Koordinator Laboran</option>
                            <option value="Asisten Laboran" {{ old('Role_User', $laboran->Role_User) == 'Asisten Laboran' ? 'selected' : '' }}>Asisten Laboran</option>
                        </select>
                        @error('Role_User')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn-submit">Update Data</button>
                        <a href="{{ route('tambah-laboran.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>