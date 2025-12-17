<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Ruangan</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        body {
            background: #f5f7fa;
        }
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }
        @media (max-width: 1024px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Lab Selection Styles */
        .lab-selection {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .lab-selection h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .lab-selection p {
            color: #666;
            margin-bottom: 1.5rem;
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
        select, input, textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
        }
        select:focus, input:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            flex: 1;
            padding: 0.875rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
        }
        .btn-primary:hover {
            box-shadow: 0 12px 30px rgba(0, 123, 255, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        /* Lab Grid Styles */
        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .lab-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
            background: white;
        }
        .lab-card:hover {
            border-color: #007bff;
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.2);
            transform: translateY(-5px);
        }
        .lab-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }
        .lab-body {
            padding: 1.5rem;
        }
        .lab-body h3 {
            color: #333;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .lab-info {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .lab-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            background: #e7f3ff;
            color: #007bff;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 600;
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
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 20px 20px 0 0;
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
        .modal-lab-info {
            background: #f0f8ff;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #007bff;
        }
        .modal-lab-info h3 {
            color: #007bff;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .modal-lab-info p {
            color: #666;
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }
        .time-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Info Box */
        .info-box {
            background: #fff8e1;
            padding: 1.25rem;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
            margin-top: 1rem;
        }
        .info-box strong {
            color: #f57c00;
            display: block;
            margin-bottom: 0.5rem;
        }
        .info-box p {
            margin: 0.25rem 0;
            color: #f57c00;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* History Card Styles */
        .history-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        .history-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .history-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .history-header h3 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .history-list {
            max-height: 600px;
            overflow-y: auto;
        }
        .history-item {
            padding: 1.25rem;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid #ddd;
            transition: all 0.3s;
        }
        .history-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .history-item.status-menunggu {
            border-left-color: #ffc107;
            background: #fff9e6;
        }
        .history-item.status-disetujui {
            border-left-color: #28a745;
            background: #e8f5e9;
        }
        .history-item.status-ditolak {
            border-left-color: #dc3545;
            background: #ffebee;
        }
        .history-lab-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .history-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .history-purpose {
            background: white;
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #555;
            font-style: italic;
        }
        .history-status {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 0.5rem;
        }
        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }
        .status-disetujui {
            background: #d4edda;
            color: #155724;
        }
        .status-ditolak {
            background: #f8d7da;
            color: #721c24;
        }
        .empty-history {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }
        .empty-history-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        #lab-container {
            display: none;
        }
        #lab-container.show {
            display: block;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <div class="min-h-full">
        <x-dosen.navbar :labs="$labs" :user="$user"></x-dosen.navbar>
        <x-dosen.header>Peminjaman Ruangan</x-dosen.header>
        
        <main>
            <div class="page-container">
                <div class="grid-layout">
                    <!-- Left Section -->
                    <div>
                        <!-- Lab Selection Section -->
                        <div class="lab-selection">
                            <h2>🏢 Pilih Laboratorium</h2>
                            <p>Pilih laboratorium yang ingin Anda gunakan</p>

                            <div class="form-group">
                                <label for="lab_select">Laboratorium *</label>
                                <select id="lab_select" onchange="selectLabDropdown()" required>
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach($labs as $labItem)
                                        <option value="{{ $labItem->id }}" 
                                                data-kepala="{{ $labItem->Kepala_Labolatorium }}"
                                                data-email="{{ $labItem->email_lab }}">
                                            {{ $labItem->Nama_Laboratorium }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Lab Cards Section (Hidden initially) -->
                        <div id="lab-container">
                            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                                <h3 style="font-size: 1.25rem; font-weight: bold; color: #333; margin-bottom: 1rem;">
                                    📋 Informasi Laboratorium
                                </h3>
                                <p style="color: #666; margin-bottom: 1.5rem;">
                                    Klik card untuk mengajukan peminjaman ruangan
                                </p>
                                
                                <div class="lab-grid">
                                    @foreach($labs as $labItem)
                                    <div class="lab-card" 
                                         onclick="openModal({{ $labItem->id }}, '{{ $labItem->Nama_Laboratorium }}', '{{ $labItem->Kepala_Labolatorium }}', '{{ $labItem->email_lab }}')" 
                                         data-lab-id="{{ $labItem->id }}"
                                         style="display: none;">
                                        <div class="lab-image">
                                            🧪
                                        </div>
                                        <div class="lab-body">
                                            <h3>{{ $labItem->Nama_Laboratorium }}</h3>
                                            <div class="lab-info">
                                                <span>👨‍🔬</span>
                                                <span>{{ $labItem->Kepala_Labolatorium }}</span>
                                            </div>
                                            <div class="lab-info">
                                                <span>📧</span>
                                                <span>{{ $labItem->email_lab }}</span>
                                            </div>
                                            <span class="lab-badge">📅 Reservasi Tersedia</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section - History -->
                    <div>
                        <div class="history-card">
                            <div class="history-header">
                                <div class="history-icon">🕒</div>
                                <h3>Riwayat Peminjaman</h3>
                            </div>

                            <div class="history-list">
                                @forelse($peminjaman_ruangans  as $peminjaman)
                                    <div class="history-item status-{{ $peminjaman->status }}">
                                        <div class="history-lab-name">{{ $peminjaman->daftarLab->Nama_Laboratorium ?? 'N/A' }}</div>
                                        <div class="history-meta">
                                            <span>📅 {{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</span>
                                            <span>🕐 {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</span>
                                        </div>
                                        <div class="history-purpose">
                                            "{{ Str::limit($peminjaman->keperluan, 80) }}"
                                        </div>
                                        <span class="history-status status-{{ $peminjaman->status }}">
                                            @if($peminjaman->status === 'menunggu')
                                                ⏳ Menunggu Persetujuan
                                            @elseif($peminjaman->status === 'disetujui')
                                                ✅ Disetujui
                                            @else
                                                ❌ Ditolak
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div class="empty-history">
                                        <div class="empty-history-icon">🗓️</div>
                                        <p>Belum ada riwayat peminjaman</p>
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
                <h2>📋 Form Peminjaman Ruangan</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dosen.pinjam-ruangan.store', $lab->id) }}" method="POST" id="form-peminjaman">
                    @csrf
                    
                    <input type="hidden" id="modal_lab_id" name="daftar_lab_id">
                    
                    <!-- Info Lab yang Dipilih -->
                    <div class="modal-lab-info">
                        <h3 id="modal-lab-name">-</h3>
                        <p><strong>Kepala Lab:</strong> <span id="modal-lab-kepala">-</span></p>
                        <p><strong>Email:</strong> <span id="modal-lab-email">-</span></p>
                    </div>

                    <!-- Form Detail Peminjaman -->
                    <div class="form-group">
                        <label for="modal_tanggal">📅 Tanggal Peminjaman *</label>
                        <input type="date" id="modal_tanggal" name="tanggal" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="time-group">
                        <div class="form-group">
                            <label for="modal_jam_mulai">🕐 Jam Mulai *</label>
                            <input type="time" id="modal_jam_mulai" name="jam_mulai" required>
                        </div>

                        <div class="form-group">
                            <label for="modal_jam_selesai">🕐 Jam Selesai *</label>
                            <input type="time" id="modal_jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="modal_keperluan">📝 Keperluan / Tujuan Penggunaan *</label>
                        <textarea id="modal_keperluan" name="keperluan" required placeholder="Jelaskan keperluan penggunaan ruangan laboratorium..."></textarea>
                    </div>

                    <div class="info-box">
                        <strong>📝 Catatan Penting:</strong>
                        <p>• Peminjaman ruangan harus diajukan minimal 1 hari sebelum penggunaan</p>
                        <p>• Pastikan ruangan dikembalikan dalam kondisi bersih dan rapi</p>
                        <p>• Jaga fasilitas laboratorium dengan baik</p>
                        <p>• Hubungi kepala lab jika ada kendala atau perubahan jadwal</p>
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
        let selectedLabId = null;

        function selectLabDropdown() {
            const labSelect = document.getElementById('lab_select');
            const labId = labSelect.value;

            if (!labId) {
                document.getElementById('lab-container').classList.remove('show');
                document.querySelectorAll('.lab-card').forEach(card => {
                    card.style.display = 'none';
                });
                return;
            }

            selectedLabId = labId;

            document.querySelectorAll('.lab-card').forEach(card => {
                card.style.display = 'none';
            });

            const selectedCard = document.querySelector(`[data-lab-id="${labId}"]`);
            if (selectedCard) {
                selectedCard.style.display = 'block';
            }

            document.getElementById('lab-container').classList.add('show');
            
            document.getElementById('lab-container').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function openModal(labId, labNama, labKepala, labEmail) {
            document.getElementById('modal_lab_id').value = labId;
            document.getElementById('modal-lab-name').textContent = labNama;
            document.getElementById('modal-lab-kepala').textContent = labKepala;
            document.getElementById('modal-lab-email').textContent = labEmail;

            // Reset form
            document.getElementById('modal_tanggal').value = '';
            document.getElementById('modal_jam_mulai').value = '';
            document.getElementById('modal_jam_selesai').value = '';
            document.getElementById('modal_keperluan').value = '';

            document.getElementById('modal-peminjaman').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modal-peminjaman').classList.remove('show');
            document.body.style.overflow = '';
        }

        function closeModalOnOverlay(event) {
            if (event.target.id === 'modal-peminjaman') {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Validate time inputs
        document.getElementById('modal_jam_mulai').addEventListener('change', function() {
            const jamMulai = this.value;
            const jamSelesaiInput = document.getElementById('modal_jam_selesai');
            
            jamSelesaiInput.min = jamMulai;
            
            if (jamSelesaiInput.value && jamSelesaiInput.value <= jamMulai) {
                jamSelesaiInput.value = '';
            }
        });

        document.getElementById('modal_jam_selesai').addEventListener('change', function() {
            const jamMulai = document.getElementById('modal_jam_mulai').value;
            const jamSelesai = this.value;
            
            if (jamMulai && jamSelesai <= jamMulai) {
                alert('⚠️ Jam selesai harus lebih besar dari jam mulai!');
                this.value = '';
            }
        });
    </script>
</body>
</html>