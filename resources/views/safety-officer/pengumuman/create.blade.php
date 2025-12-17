<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
  
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        h1 .judul {
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
            font-weight: 600;
        }
        input, textarea, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: inherit;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
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
            font-weight: 600;
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
        .help-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>

  <div class="min-h-full">

    <x-safety-officer.navbar :labs="$labs" :user="$user" />

    <x-safety-officer.header>Kelola Pengumuman</x-safety-officer.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="container">
                 <h1 class="judul">📢 Buat Pengumuman Baru</h1>
        <p class="subtitle">Buat pengumuman untuk mahasiswa dan pengguna laboratorium</p>

        @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('safety-officer.pengumuman.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="judul">Judul Pengumuman *</label>
                <input type="text" id="judul" name="judul" required placeholder="Masukkan judul pengumuman" value="{{ old('judul') }}">
            </div>

            <div class="form-group">
                <label for="isi">Isi Pengumuman *</label>
                <textarea id="isi" name="isi" required placeholder="Tulis isi pengumuman di sini...">{{ old('isi') }}</textarea>
                <div class="help-text">Tulis pengumuman dengan jelas dan detail</div>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish (Langsung Tampil)</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan sebagai Draft)</option>
                </select>
                <div class="help-text">Pilih "Publish" agar pengumuman langsung terlihat oleh semua pengguna</div>
            </div>

            <div class="btn-group">
                <a href="{{ route('safety-officer.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
            </div>
        </form>
            </div>

        </div>
    </main>

   
  </div>
  

  {{-- Pindahkan ke paling bawah, SETELAH konten --}}
  {{-- Pindahkan semua skrip ke paling bawah, SEBELUM </body> --}}
  
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

</body>
</html>
