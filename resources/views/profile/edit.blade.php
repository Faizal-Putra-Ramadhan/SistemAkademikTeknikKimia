<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Profile - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .form-container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; }
        .form-group input:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,.1); }
        .photo-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #e9ecef; }
        .btn-submit { background: #007bff; color: white; padding: 14px 30px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .success-message { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="form-container">
                <h2>Ubah Profile</h2>

                @if(session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div style="text-align:center; margin-bottom: 30px;">
                        @if($user->photo && Storage::disk('public')->exists($user->photo))
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="photo-preview">
                        @else
                            <img src="https://via.placeholder.com/120?text=User" alt="Foto Profil" class="photo-preview">
                        @endif
                        <div style="margin-top: 15px;">
                            <input type="file" name="photo" accept="image/*" style="display:block; margin:0 auto;">
                            @error('photo') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:red">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span style="color:red">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon <span style="color:red">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="081234567890">
                        @error('phone') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                    </div>

                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>