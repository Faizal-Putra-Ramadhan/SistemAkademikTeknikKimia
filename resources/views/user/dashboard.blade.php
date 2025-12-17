<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa - Teknik Kimia UAD</title>
    @vite('resources/css/style.css')
    <style>
        .lab-selector { margin: 20px 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .tab-menu { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .tab-menu a { padding: 10px 20px; background: #e9ecef; border-radius: 6px; text-decoration: none; color: #333; }
        .tab-menu a.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">

            <h2>Selamat Datang, <strong>Mahasiswa Teknik Kimia</strong></h2>

            <!-- PILIH LABORATORIUM -->
            <div class="card lab-selector">
                <label><strong>Pilih Laboratorium:</strong></label>
                <select onchange="window.location.href='?lab='+this.value" style="padding:10px; font-size:16px; margin-top:10px; width:100%; max-width:500px;">
                    <option value="">-- Pilih Lab --</option>
                    @foreach(\App\Models\DaftarLab::all() as $lab)
                        <option value="{{ $lab->id }}" {{ request('lab') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->Nama_Laboratorium }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('lab'))
                @php $selectedLab = \App\Models\DaftarLab::find(request('lab')); @endphp
                <h3>Laboratorium: <strong>{{ $selectedLab->Nama_Laboratorium }}</strong></h3>

                <!-- TAB MENU -->
                <div class="tab-menu">
                    <a href="?lab={{ request('lab') }}#daftar-alat" class="active">Daftar Alat</a>
                    <a href="?lab={{ request('lab') }}#aktivitas">Aktivitas Saya</a>
                    <a href="?lab={{ request('lab') }}#pinjam-alat">Pinjam Alat</a>
                    <a href="?lab={{ request('lab') }}#pinjam-ruangan">Pinjam Ruangan</a>
                    <a href="?lab={{ request('lab') }}#pengajuan">Pengajuan Penelitian</a>
                </div>

                <!-- 1. DAFTAR ALAT -->
                <div id="daftar-alat" class="card">
                    <h3>Daftar Alat Tersedia</h3>
                    <input type="text" placeholder="Cari alat..." onkeyup="filterTable(this, 'alat-table')" style="padding:10px; width:100%; max-width:400px; margin-bottom:10px;">
                    <table id="alat-table">
                        <thead><tr><th>No</th><th>Nama Alat</th><th>Deskripsi</th><th>Tersedia</th></tr></thead>
                        <tbody>
                            @foreach($selectedLab->alatLabs as $i => $alat)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $alat->nama_alat }}</td>
                                <td>{{ Str::limit($alat->deskripsi, 100) }}</td>
                                <td><span style="color:green;font-weight:bold;">{{ $alat->jumlah_tersedia }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 2. AKTIVITAS SAYA -->
                <div id="aktivitas" class="card">
                    <h3>Riwayat Aktivitas Saya</h3>
                    <table>
                        <thead><tr><th>Waktu</th><th>Aktivitas</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            @foreach($selectedLab->aktivitasMahasiswas()->latest()->take(10)->get() as $a)
                            <tr>
                                <td>{{ $a->waktu->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $a->jenis_aktivitas }}</strong></td>
                                <td>{{ $a->keterangan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Form peminjaman & pengajuan akan aku buatkan selanjutnya kalau kamu bilang "LANJUT WOI" -->

            @else
                <div class="card" style="text-align:center;padding:50px;color:#666;">
                    Silakan pilih laboratorium terlebih dahulu untuk melihat detail alat dan aktivitas.
                </div>
            @endif

        </div>
    </div>

    <script>
        function filterTable(input, tableId) {
            let filter = input.value.toLowerCase();
            let rows = document.querySelectorAll('#' + tableId + ' tbody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
</body>
</html>