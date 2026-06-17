<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Alat - {{ $lab->Nama_Laboratorium }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
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
        select, input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
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
            background: #28a745;
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
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .alat-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
            background: white;
        }
        .alat-card:hover {
            border-color: #28a745;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            transform: translateY(-5px);
        }
        .alat-card.selected {
            border-color: #28a745;
            border-width: 3px;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }
        .alat-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }
        .alat-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .alat-body {
            padding: 1rem;
        }
        .alat-body h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        .alat-body p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        .stock-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .stock-available {
            background: #d4edda;
            color: #155724;
        }
        .stock-low {
            background: #fff3cd;
            color: #856404;
        }
        .stock-empty {
            background: #f8d7da;
            color: #721c24;
        }
        .select-indicator {
            background: #28a745;
            color: white;
            padding: 0.5rem;
            text-align: center;
            font-weight: bold;
            display: none;
        }
        .alat-card.selected .select-indicator {
            display: block;
        }
        .form-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .form-section h3 {
            color: #333;
            margin-bottom: 1rem;
        }
        #form-container {
            display: none;
        }
        #form-container.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Peminjaman Alat Laboratorium</h1>
        <p class="subtitle">{{ $lab->Nama_Laboratorium }}</p>

        @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('peneliti-eksternal.pinjam-alat.store', $lab->id) }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>📦 Pilih Alat yang Akan Dipinjam</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Klik pada card alat untuk memilih</p>
                
                <div class="alat-grid">
                    @foreach($lab->alatLabs as $alat)
                    <div class="alat-card" onclick="selectAlat({{ $alat->id }}, '{{ $alat->nama_alat }}', {{ $alat->jumlah_tersedia }})" data-alat-id="{{ $alat->id }}">
                        <div class="select-indicator">✓ Dipilih</div>
                        <div class="alat-image">
                            @if($alat->foto)
                                <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}">
                            @else
                                🔧
                            @endif
                        </div>
                        <div class="alat-body">
                            <h3>{{ $alat->nama_alat }}</h3>
                            <p>{{ Str::limit($alat->deskripsi ?? 'Tidak ada deskripsi', 80) }}</p>
                            
                            @if($alat->jumlah_tersedia > 5)
                                <span class="stock-badge stock-available">✓ Tersedia: {{ $alat->jumlah_tersedia }} unit</span>
                            @elseif($alat->jumlah_tersedia > 0)
                                <span class="stock-badge stock-low">⚠ Stok Terbatas: {{ $alat->jumlah_tersedia }} unit</span>
                            @else
                                <span class="stock-badge stock-empty">✗ Stok Habis</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div id="form-container">
                <input type="hidden" id="alat_lab_id" name="alat_lab_id" value="{{ old('alat_lab_id') }}">
                
                <div class="form-section">
                    <h3>📅 Detail Peminjaman</h3>
                    <p style="color: #666; margin-bottom: 1rem;">Alat yang dipilih: <strong id="selected-alat-name">-</strong></p>
                    
                    <div class="form-group">
                        <label for="tanggal_pinjam">Tanggal Pinjam *</label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required min="{{ date('Y-m-d') }}" value="{{ old('tanggal_pinjam') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_kembali">Tanggal Kembali *</label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali" required min="{{ date('Y-m-d') }}" value="{{ old('tanggal_kembali') }}">
                    </div>

                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                        <strong>📝 Catatan:</strong>
                        <p style="margin-top: 0.5rem; color: #666;">Pastikan Anda mengembalikan alat tepat waktu dan dalam kondisi baik.</p>
                    </div>
                </div>

                <div class="btn-group">
                    <a href="{{ route('peneliti-eksternal.dashboard') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let selectedAlatId = null;

        function selectAlat(alatId, alatNama, stok) {
            if (stok <= 0) {
                alert('Maaf, alat ini tidak tersedia (stok habis)');
                return;
            }

            // Remove previous selection
            document.querySelectorAll('.alat-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selection to clicked card
            const selectedCard = document.querySelector(`[data-alat-id="${alatId}"]`);
            selectedCard.classList.add('selected');

            // Set hidden input value
            document.getElementById('alat_lab_id').value = alatId;
            document.getElementById('selected-alat-name').textContent = alatNama;

            // Show form
            document.getElementById('form-container').classList.add('show');

            // Scroll to form
            document.getElementById('form-container').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            selectedAlatId = alatId;
        }

        // Restore selection jika ada old value
        window.addEventListener('DOMContentLoaded', function() {
            const oldAlatId = document.getElementById('alat_lab_id').value;
            if (oldAlatId) {
                const selectedCard = document.querySelector(`[data-alat-id="${oldAlatId}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                    document.getElementById('form-container').classList.add('show');
                    
                    // Get alat name from card
                    const alatName = selectedCard.querySelector('h3').textContent;
                    document.getElementById('selected-alat-name').textContent = alatName;
                }
            }
        });
    </script>
</body>
</html>