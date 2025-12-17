<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pengumuman Baru</title>
    @vite('resources/css/style.css') @vite('resources/js/index.js')
    <style>
        .form-container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        textarea { width: 100%; min-height: 200px; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="form-container">
                <h2>Buat Pengumuman Baru</h2>
                <form action="{{ route('pengumuman.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom:20px;">
                        <label>Judul Pengumuman</label>
                        <input type="text" name="judul" required class="form-control" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                    <div style="margin-bottom:20px;">
                        <label>Isi Pengumuman</label>
                        <textarea name="isi" required></textarea>
                    </div>
                    <div style="margin-bottom:20px;">
                        <label>Status</label>
                        <select name="status" style="padding:12px;border:1px solid #ddd;border-radius:4px;">
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:15px;">
                        <button type="submit" style="background:#007bff;color:white;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;">Simpan</button>
                        <a href="{{ route('pengumuman.index') }}" style="background:#6c757d;color:white;padding:12px 30px;border-radius:4px;text-decoration:none;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>