<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Alat Lab</title>
    @vite('resources/css/style.css')
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Tambah Alat / Aset Lab</div>
                </div>

                <div style="background:white;padding:30px;border-radius:8px;">
                    <form action="{{ route('admin.alat-lab.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom:20px;">
                            <label>Laboratorium</label>
                            <select name="daftar_lab_id" required class="form-control">
                                <option value="">-- Pilih Lab --</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->Nama_Laboratorium }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Nama Alat</label>
                            <input type="text" name="nama_alat" required class="form-control">
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control"></textarea>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Jumlah Tersedia</label>
                            <input type="number" name="jumlah_tersedia" value="1" min="0" required class="form-control">
                        </div>

                        <div style="margin-bottom:20px;">
                            <label>Foto Alat (opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="form-control">
                            <small>Format: JPG/PNG, max 2MB</small>
                        </div>

                        <div style="display:flex;gap:15px;">
                            <button type="submit" style="background:#007bff;color:white;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;">
                                Simpan Alat
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