<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            color: #667eea;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .back-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        .profile-photo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-info h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .profile-info p {
            color: #666;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .info-box {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }
        .info-box p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>👤 Profil Saya</h1>
    </div>

    <div class="container">
        <a href="{{ route('dosen.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <div class="profile-header">
                <div class="profile-photo">
                    @if($user->foto)
                        <img src="{{ asset('uploads/profile/' . $user->foto) }}" alt="Foto Profil">
                    @else
                        👨‍🏫
                    @endif
                </div>
                <div class="profile-info">
                    <h2>{{ $user->Nama }}</h2>
                    <p>{{ $user->Role_User }}</p>
                    <p style="color: #999; font-size: 0.9rem;">User ID: {{ $user->UserID }}</p>
                </div>
            </div>

            <div class="info-box">
                <p><strong>ℹ️ Informasi:</strong></p>
                <p>Update informasi profil Anda. Pastikan data yang dimasukkan valid.</p>
            </div>

            <form action="{{ route('dosen.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="Nama">Nama Lengkap *</label>
                    <input type="text" id="Nama" name="Nama" required value="{{ old('Nama', $user->Nama) }}">
                    @error('Nama')
                        <div style="color: red; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Email">Email *</label>
                    <input type="email" id="Email" name="Email" required value="{{ old('Email', $user->Email) }}">
                    @error('Email')
                        <div style="color: red; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Phone">No. Telepon *</label>
                    <input type="text" id="Phone" name="Phone" required value="{{ old('Phone', $user->Phone) }}">
                    @error('Phone')
                        <div style="color: red; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto">Foto Profil</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                    <div style="color: #666; font-size: 0.85rem; margin-top: 0.25rem;">
                        Format: JPG, JPEG, PNG (Max: 2MB)
                    </div>
                    @error('foto')
                        <div style="color: red; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>