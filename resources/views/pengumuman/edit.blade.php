<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengumuman - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .form-container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        textarea { 
            width: 100%; 
            min-height: 250px; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-family: inherit; 
            font-size: 15px;
        }
        input, select, textarea { 
            margin-top: 8px; 
        }
        label { 
            font-weight: 600; 
            color: #333; 
        }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="form-container">
                <h2>Edit Pengumuman</h2>

                @if(session('success'))
                    <div style="background:#d4edda;color:#155724;padding:12px;border-radius:4px;margin-bottom:20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('pengumuman.update', $pengumuman) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- INI YANG WAJIB! -->

                    <div style="margin-bottom:20px;">
                        <label>Judul Pengumuman</label>
                        <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required 
                               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;">
                        @error('judul')
                            <small style="color:#dc3545;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="margin-bottom:20px;">
                        <label>Isi Pengumuman</label>
                        <textarea name="isi" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                        @error('isi')
                            <small style="color:#dc3545;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="margin-bottom:25px;">
                        <label>Status</label>
                        <select name="status" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;">
                            <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                            <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')
                            <small style="color:#dc3545;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="display:flex;gap:15px;">
                        <button type="submit" style="background:#007bff;color:white;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;font-weight:600;">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('pengumuman.index') }}" 
                           style="background:#6c757d;color:white;padding:12px 30px;border-radius:4px;text-decoration:none;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>