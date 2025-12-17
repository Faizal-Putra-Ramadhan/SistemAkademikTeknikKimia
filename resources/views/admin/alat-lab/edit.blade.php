<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat Lab - Admin</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Edit Alat / Aset Lab</div>
                </div>

                <div style="background:white;padding:30px;border-radius:8px;">
                    <form action="{{ route('admin.alat-lab.update', $alat) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom:20px;">
                            <label>Laboratorium</label>
                            <select name="daftar_lab_id" required class="form-control">
                                <option value="">-- Pilih Lab --</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}" {{ old('daftar_lab_id', $alat->daftar_lab_id) == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->Nama_Laboratorium }}
                                    </option>
                                @endforeach
                            </select>
                            @error('daftar_lab_id') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Nama Alat</label>
                            <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required class="form-control">
                            @error('nama_alat') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                            @error('deskripsi') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Jumlah Tersedia</label>
                            <input type="number" name="jumlah_tersedia" value="{{ old('jumlah_tersedia', $alat->jumlah_tersedia) }}" min="0" required class="form-control">
                            @error('jumlah_tersedia') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Foto Saat Ini</label><br>
                            @if($alat->foto)
                                <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto Alat" style="width:200px;height:200px;object-fit:cover;border-radius:8px;margin-top:10px;">
                                <p><small>Ganti foto (kosongkan jika tidak ingin mengganti)</small></p>
                            @else
                                <p><em>Tidak ada foto</em></p>
                            @endif
                        </div>

                        <div style="margin-bottom:25px;">
                            <label>Ganti Foto (opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="form-control">
                            <small>Format: JPG/PNG, max 2MB</small>
                            @error('foto') <small style="color:#dc3545;">{{ $message }}</small> @enderror
                        </div>

                        <div style="display:flex;gap:15px;">
                            <button type="submit" style="background:#007bff;color:white;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;font-weight:600;">
                                Update Alat
                            </button>
                            <a href="{{ route('admin.alat-lab.index') }}" style="background:#6c757d;color:white;padding:12px 30px;border-radius:4px;text-decoration:none;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>