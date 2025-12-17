<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laboratorium - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .form-container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; }
        .form-group input:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,.1); }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-full { grid-column: span 2; }
        .error-message { color: #dc3545; font-size: 13px; margin-top: 5px; }
        .success-message { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn-submit, .btn-cancel { flex: 1; padding: 14px; border-radius: 4px; text-decoration: none; text-align: center; font-size: 16px; }
        .btn-submit { background: #007bff; color: white; border: none; cursor: pointer; }
        .btn-cancel { background: #6c757d; color: white; }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="form-container">
                <h2>Tambah Laboratorium Baru</h2>

                @if(session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                <form action="{{ route('daftar-lab.store') }}" method="POST">
                    @csrf
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Nama Laboratorium <span style="color:red">*</span></label>
                            <input type="text" name="Nama_Laboratorium" value="{{ old('Nama_Laboratorium') }}" required placeholder="Lab Teknik Informatika">
                            @error('Nama_Laboratorium') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Kepala Laboratorium <span style="color:red">*</span></label>
                            <input type="text" name="Kepala_Labolatorium" value="{{ old('Kepala_Labolatorium') }}" required>
                            @error('Kepala_Labolatorium') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Admin Laboratorium <span style="color:red">*</span></label>
                            <input type="text" name="Admin_Laboratorium" value="{{ old('Admin_Laboratorium') }}" required>
                            @error('Admin_Laboratorium') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Safety Officer <span style="color:red">*</span></label>
                            <input type="text" name="Safety_Officer" value="{{ old('Safety_Officer') }}" required>
                            @error('Safety_Officer') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group grid-full">
                            <label>Email Laboratorium <span style="color:red">*</span></label>
                            <input type="email" name="email_lab" value="{{ old('email_lab') }}" required placeholder="lab@informatika.uad.ac.id">
                            @error('email_lab') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn-submit">Simpan Laboratorium</button>
                        <a href="{{ route('daftar-lab.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>