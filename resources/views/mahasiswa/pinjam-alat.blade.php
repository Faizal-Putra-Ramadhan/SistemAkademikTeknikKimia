<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
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

        /* Lab Selection Styles */
        .lab-selection {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        /* Alat Grid Styles */
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
        .alat-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .alat-card.disabled:hover {
            transform: none;
            border-color: #e0e0e0;
            box-shadow: none;
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

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.show {
            display: flex;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-content {
            background: white;
            border-radius: 15px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { 
                transform: translateY(50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .modal-body {
            padding: 2rem;
        }
        .modal-alat-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #28a745;
        }
        .modal-alat-info h3 {
            color: #28a745;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .modal-alat-info p {
            color: #666;
            margin: 0.25rem 0;
        }

        /* Hidden by default */
        #alat-container {
            display: none;
        }
        #alat-container.show {
            display: block;
        }

        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .empty-state p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Info Box */
        .info-box {
            background: #fff3cd;
            padding: 1rem;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin-top: 1rem;
        }
        .info-box strong {
            color: #856404;
        }
        .info-box p {
            margin-top: 0.5rem;
            color: #856404;
            font-size: 0.9rem;
        }

        .page-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .grid-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
        @media (max-width: 1024px) { .grid-layout { grid-template-columns: 1fr; } }

        /* Lab Selection */
        .lab-selection { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .lab-selection h2 { font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }

        /* Alat Grid */
        .alat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
        .alat-card { border: 2px solid #e0e0e0; border-radius: 15px; overflow: hidden; background: white; cursor: pointer; transition: all 0.3s; }
        .alat-card:hover { border-color: #28a745; box-shadow: 0 8px 25px rgba(40,167,69,0.25); transform: translateY(-5px); }
        .alat-card.disabled { opacity: 0.6; cursor: not-allowed; }
        .alat-card.disabled:hover { transform: none; border-color: #e0e0e0; box-shadow: none; }
        .alat-image { width: 100%; height: 200px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white; }
        .alat-image img { width: 100%; height: 100%; object-fit: cover; }
        .alat-body { padding: 1.5rem; }
        .alat-body h3 { margin-bottom: 0.75rem; font-weight: bold; }
        .stock-badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600; margin-top: 0.5rem; }

        /* History Card – Sama persis dengan pinjam-ruangan */
        .history-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); height: fit-content; position: sticky; top: 2rem; }
        .history-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0; }
        .history-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
        .history-header h3 { font-size: 1.25rem; font-weight: bold; color: #333; margin: 0; }
        .history-list { max-height: 700px; overflow-y: auto; }
        .history-item { padding: 1.25rem; background: #f8f9fa; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #ddd; transition: all 0.3s; }
        .history-item:hover { transform: translateX(5px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .history-item.status-menunggu { border-left-color: #ffc107; background: #fff9e6; }
        .history-item.status-disetujui { border-left-color: #28a745; background: #e8f5e9; }
        .history-item.status-ditolak { border-left-color: #dc3545; background: #ffebee; }
        .history-alat-name { font-weight: bold; color: #333; margin-bottom: 0.5rem; }
        .history-meta { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; }
        .history-purpose { background: white; padding: 0.75rem; border-radius: 8px; margin-top: 0.5rem; font-size: 0.85rem; color: #555; font-style: italic; }
        .history-status { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-top: 0.5rem; }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-disetujui { background: #d4edda; color: #155724; }
        .status-ditolak { background: #f8d7da; color: #721c24; }
        .empty-history { text-align: center; padding: 3rem 1rem; color: #999; }
        .empty-history-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }

        /* Modal – tetap sama */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.show { display: flex; }
        .modal-content { background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: none; opacity: 1; } }
        .modal-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 2rem; }

        #alat-container { display: none; }
        #alat-container.show { display: block; }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <div class="min-h-full">
        <x-mahasiswa.navbar :labs="$labs" :user="$user"></x-mahasiswa.navbar>

        <x-mahasiswa.header>Peminjaman Alat</x-mahasiswa.header>
        
        <main>
            <div class="page-container">
                <div class="grid-layout">
                    <!-- Kiri: Pilih Lab & Daftar Alat -->
                    <div>
                        <div class="lab-selection">
                            <h2>🔬 Pilih Laboratorium</h2>
                            <p>Pilih laboratorium untuk melihat daftar alat yang tersedia</p>

                            <div class="form-group mt-6">
                                <label for="lab_select">Laboratorium *</label>
                                <select id="lab_select" onchange="selectLabDropdown()" required>
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach($labs as $labItem)
                                        <option value="{{ $labItem->id }}">{{ $labItem->Nama_Laboratorium }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="alat-container">
                            <div style="background:white;padding:2rem;border-radius:20px;box-shadow:0 10px 40px rgba(0,0,0,0.08);">
                                <h3 style="font-size:1.25rem;font-weight:bold;color:#333;margin-bottom:1rem;">
                                    Pilih Alat yang Akan Dipinjam
                                </h3>
                                <p style="color:#666;margin-bottom:1.5rem;">
                                    Laboratorium: <strong id="selected-lab-name">-</strong>
                                </p>

                                <div class="alat-grid" id="alat-list">
                                    <!-- Alat di-load via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Riwayat Peminjaman Alat -->
                    <div>
                        <div class="history-card">
                            <div class="history-header">
                                <div class="history-icon">🔧</div>
                                <h3>Riwayat Peminjaman Alat</h3>
                            </div>

                            <div class="history-list">
                                @forelse($peminjaman_alats ?? [] as $peminjaman)
                                    <div class="history-item status-{{ $peminjaman->status }}">
                                        <div class="history-alat-name">
                                            {{ $peminjaman->alatLab->nama_alat ?? 'Alat Tidak Diketahui' }}
                                        </div>
                                        <div class="history-meta">
                                            <span>🏷️ Lab: {{ $peminjaman->daftarLab->Nama_Laboratorium ?? '-' }}</span>
                                            <span>📅 Pinjam: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</span>
                                            <span>📅 Kembali: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : 'Belum dikembalikan' }}</span>
                                        </div>
                                        <span class="history-status status-{{ $peminjaman->status }}">
                                            @if($peminjaman->status === 'menunggu')
                                                ⏳ Menunggu Persetujuan
                                            @elseif($peminjaman->status === 'disetujui')
                                                ✅ Disetujui
                                            @elseif($peminjaman->status === 'dikembalikan')
                                                ✅ Dikembalikan
                                            @else
                                                ❌ Ditolak
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div class="empty-history">
                                        <div class="empty-history-icon">🔧</div>
                                        <p>Belum ada riwayat peminjaman alat</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Peminjaman -->
    <div id="modal-peminjaman" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2>📋 Form Peminjaman Alat</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mahasiswa.pinjam-alat.store', $lab->id) }}" method="POST" id="form-peminjaman">
                    @csrf
                    
                    <input type="hidden" id="modal_lab_id" name="daftar_lab_id">
                    <input type="hidden" id="modal_alat_id" name="alat_lab_id">
                    
                    <!-- Info Alat yang Dipilih -->
                    <div class="modal-alat-info">
                        <h3 id="modal-alat-name">-</h3>
                        <p><strong>Laboratorium:</strong> <span id="modal-lab-name">-</span></p>
                        <p><strong>Stok Tersedia:</strong> <span id="modal-alat-stock">-</span> unit</p>
                    </div>

                    <!-- Form Detail Peminjaman -->
                    <div class="form-group">
                        <label for="modal_tanggal_pinjam">Tanggal Pinjam *</label>
                        <input type="date" id="modal_tanggal_pinjam" name="tanggal_pinjam" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="modal_tanggal_kembali">Tanggal Kembali *</label>
                        <input type="date" id="modal_tanggal_kembali" name="tanggal_kembali" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="info-box">
                        <strong>📝 Catatan Penting:</strong>
                        <p>• Pastikan Anda mengembalikan alat tepat waktu dan dalam kondisi baik</p>
                        <p>• Kerusakan atau keterlambatan akan dikenakan sanksi sesuai peraturan</p>
                        <p>• Hubungi admin lab jika ada kendala</p>
                    </div>

                    <div class="btn-group">
                        <button type="button" onclick="closeModal()" class="btn btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Data alat per lab
        const labAlatsData = @json($labs->mapWithKeys(function($lab) {
            return [$lab->id => $lab->alatLabs];
        }));

        let selectedLabId = null;
        let selectedLabName = null;

        function selectLabDropdown() {
            const labSelect = document.getElementById('lab_select');
            const labId = labSelect.value;

            if (!labId) {
                document.getElementById('alat-container').classList.remove('show');
                return;
            }

            const labName = labSelect.options[labSelect.selectedIndex].text;
            selectedLabId = labId;
            selectedLabName = labName;

            document.getElementById('selected-lab-name').textContent = labName;
            loadAlatForLab(labId);
            document.getElementById('alat-container').classList.add('show');
            
            document.getElementById('alat-container').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function loadAlatForLab(labId) {
            const alatList = document.getElementById('alat-list');
            const alats = labAlatsData[labId] || [];

            if (alats.length === 0) {
                alatList.innerHTML = `
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <p>❌ Tidak ada alat tersedia di laboratorium ini</p>
                    </div>
                `;
                return;
            }

            alatList.innerHTML = alats.map(alat => {
                let stockBadge = '';
                let isDisabled = alat.jumlah_tersedia <= 0;
                
                if (alat.jumlah_tersedia > 5) {
                    stockBadge = `<span class="stock-badge stock-available">✓ Tersedia: ${alat.jumlah_tersedia} unit</span>`;
                } else if (alat.jumlah_tersedia > 0) {
                    stockBadge = `<span class="stock-badge stock-low">⚠ Stok Terbatas: ${alat.jumlah_tersedia} unit</span>`;
                } else {
                    stockBadge = `<span class="stock-badge stock-empty">✗ Stok Habis</span>`;
                }

                const imageContent = alat.foto 
                    ? `<img src="/storage/${alat.foto}" alt="${alat.nama_alat}">`
                    : '🔧';

                const description = alat.deskripsi 
                    ? (alat.deskripsi.length > 80 ? alat.deskripsi.substring(0, 80) + '...' : alat.deskripsi)
                    : 'Tidak ada deskripsi';

                const disabledClass = isDisabled ? 'disabled' : '';
                const onclickAttr = isDisabled ? '' : `onclick="openModal(${alat.id}, '${alat.nama_alat}', ${alat.jumlah_tersedia})"`;

                return `
                    <div class="alat-card ${disabledClass}" ${onclickAttr} data-alat-id="${alat.id}">
                        <div class="alat-image">
                            ${imageContent}
                        </div>
                        <div class="alat-body">
                            <h3>${alat.nama_alat}</h3>
                            <p>${description}</p>
                            ${stockBadge}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openModal(alatId, alatNama, stok) {
            if (stok <= 0) {
                alert('❌ Maaf, alat ini tidak tersedia (stok habis)');
                return;
            }

            // Set data ke modal
            document.getElementById('modal_lab_id').value = selectedLabId;
            document.getElementById('modal_alat_id').value = alatId;
            document.getElementById('modal-alat-name').textContent = alatNama;
            document.getElementById('modal-lab-name').textContent = selectedLabName;
            document.getElementById('modal-alat-stock').textContent = stok;

            // Reset form dates
            document.getElementById('modal_tanggal_pinjam').value = '';
            document.getElementById('modal_tanggal_kembali').value = '';

            // Show modal
            document.getElementById('modal-peminjaman').classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        function closeModal() {
            document.getElementById('modal-peminjaman').classList.remove('show');
            document.body.style.overflow = ''; // Restore scroll
        }

        function closeModalOnOverlay(event) {
            if (event.target.id === 'modal-peminjaman') {
                closeModal();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Validate dates
        document.getElementById('modal_tanggal_pinjam').addEventListener('change', function() {
            const pinjamDate = this.value;
            document.getElementById('modal_tanggal_kembali').min = pinjamDate;
        });
    </script>
</body>
</html>