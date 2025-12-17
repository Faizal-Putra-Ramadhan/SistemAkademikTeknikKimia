<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laboran - Teknik UAD</title>
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
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .info-message {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
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
                <h2>Tambah Laboran Baru</h2>
                
                <div class="info-message">
                    ℹ️ <strong>Info:</strong> User ID akan dibuat otomatis berdasarkan role yang dipilih.
                </div>

                <form action="{{ route('tambah-laboran.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="Laboratorium">Nama Laboratorium <span class="required">*</span></label>
                        <select name="Laboratorium" id="Laboratorium">
    <option value="">-- Pilih Laboratorium --</option>
    @foreach($daftar_labs as $lab)
        <option value="{{ $lab->Nama_Laboratorium }}"
            {{ old('Laboratorium') == $lab->Nama_Laboratorium ? 'selected' : '' }}>
            {{ $lab->Nama_Laboratorium }}
        </option>
    @endforeach
</select>

@error('Laboratorium')
    <div class="error-message">{{ $message }}</div>
@enderror

                    </div>

                    <div class="form-group">
                        <label for="Nama_Laboran">Nama Laboran <span class="required">*</span></label>
                        <input type="text" 
                               name="Nama_Laboran" 
                               id="Nama_Laboran" 
                               value="{{ old('Nama_Laboran') }}"
                               placeholder="Contoh: Dr. Ahmad Wijaya">
                        @error('Nama_Laboran')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Phone">Nomor Telepon <span class="required">*</span></label>
                        <input type="text" 
                               name="Phone" 
                               id="Phone" 
                               value="{{ old('Phone') }}"
                               placeholder="Contoh: 081234567890">
                        @error('Phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Email">Email <span class="required">*</span></label>
                        <input type="email" 
                               name="Email" 
                               id="Email" 
                               value="{{ old('Email') }}"
                               placeholder="Contoh: ahmad@email.com">
                        @error('Email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Di form create -->
<div class="form-group">
    <label>Password</label>
    <input type="password" name="Password" class="form-control" required>
</div>

<!-- Di form edit (optional) -->
<div class="form-group">
    <label>Password (Kosongkan jika tidak ingin mengubah)</label>
    <input type="password" name="Password" class="form-control">
</div>

                    <div class="form-group">
                        <label for="Role_User">Role User <span class="required">*</span></label>
                        <select name="Role_User" id="Role_User">
                            <option value="">-- Pilih Role --</option>
                            <option value="Admin" {{ old('Role_User') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Tendik" {{ old('Role_User') == 'Tendik' ? 'selected' : '' }}>Tendik</option>
                            <option value="Dosen" {{ old('Role_User') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Mahasiswa" {{ old('Role_User') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="Laboran" {{ old('Role_User') == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                            <option value="Koordinator Laboran" {{ old('Role_User') == 'Koordinator Laboran' ? 'selected' : '' }}>Koordinator Laboran</option>
                            <option value="Asisten Laboran" {{ old('Role_User') == 'Asisten Laboran' ? 'selected' : '' }}>Asisten Laboran</option>
                        </select>
                        @error('Role_User')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn-submit">Simpan Data</button>
                        <a href="{{ route('tambah-laboran.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>