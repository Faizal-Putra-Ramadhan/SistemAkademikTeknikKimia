<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Ruangan - {{ $lab->Nama_Laboratorium }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: opacity 0.3s;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Peminjaman Ruangan</h1>
        <p class="subtitle">{{ $lab->Nama_Laboratorium }}</p>

        @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mahasiswa.pinjam-ruangan.store', $lab->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="tanggal">Tanggal Peminjaman *</label>
                <input type="date" id="tanggal" name="tanggal" required min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}">
            </div>

            <div class="form-group">
                <label for="jam_mulai">Jam Mulai *</label>
                <input type="time" id="jam_mulai" name="jam_mulai" required value="{{ old('jam_mulai') }}">
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai *</label>
                <input type="time" id="jam_selesai" name="jam_selesai" required value="{{ old('jam_selesai') }}">
            </div>

            <div class="form-group">
                <label for="keperluan">Keperluan *</label>
                <textarea id="keperluan" name="keperluan" required placeholder="Jelaskan keperluan peminjaman ruangan...">{{ old('keperluan') }}</textarea>
            </div>

            <div class="btn-group">
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</body>
</html>