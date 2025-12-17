<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Penelitian</title>

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

        /* Form Card Styles */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .form-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        .form-header-text h2 {
            font-size: 1.75rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .form-header-text p {
            color: #666;
            margin: 0.25rem 0 0 0;
            font-size: 0.95rem;
        }

        /* Lab Selection */
        .lab-selection-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            color: white;
        }
        .lab-selection-card h3 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .lab-selection-card select {
            width: 100%;
            padding: 0.875rem;
            border-radius: 10px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.95);
            font-size: 1rem;
            color: #333;
            transition: all 0.3s;
        }
        .lab-selection-card select:focus {
            border-color: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.75rem;
        }
        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.75rem;
        }
        .form-label .required {
            color: #e74c3c;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: inherit;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Info Box */
        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 1.25rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .info-box-title {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .info-box-content {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .info-box-content ul {
            margin: 0.5rem 0 0 1.25rem;
            padding: 0;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .history-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .history-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            font-size: 0.85rem;
            color: #666;
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

        /* Hidden state */
        #form-section {
            display: none;
        }
        #form-section.show {
            display: block;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <div class="min-h-full">
        <x-mahasiswa.navbar :labs="$labs" :user="$user"></x-mahasiswa.navbar>
        <x-mahasiswa.header>Pengajuan Penelitian</x-mahasiswa.header>

        <div class="page-container">
            <div class="grid-layout">
                <!-- Form Section -->
                <div>
                    <div class="form-card">
                        <div class="form-header">
                            <div class="form-icon">🔬</div>
                            <div class="form-header-text">
                                <h2>Ajukan Penelitian Baru</h2>
                                <p>Lengkapi formulir di bawah untuk mengajukan penelitian</p>
                            </div>
                        </div>

                        <!-- Lab Selection -->
                        <div class="lab-selection-card">
                            <h3>📍 Pilih Laboratorium</h3>
                            <select id="lab_select" onchange="selectLab()" required>
                                <option value="">-- Pilih Laboratorium untuk Penelitian --</option>
                                @foreach($labs as $labItem)
                                    <option value="{{ $labItem->id }}">
                                        {{ $labItem->Nama_Laboratorium }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Form (hidden initially) -->
                        <div id="form-section">
                            <form action="{{ route('mahasiswa.pengajuan-penelitian.store', $lab->id) }}" method="POST">
                                @csrf
                                
                                <input type="hidden" id="daftar_lab_id" name="daftar_lab_id">

                                <!-- Info Box -->
                                <div class="info-box">
                                    <div class="info-box-title">
                                        <span>ℹ️</span>
                                        <span>Informasi Penting</span>
                                    </div>
                                    <div class="info-box-content">
                                        <ul>
                                            <li>Pengajuan akan ditinjau oleh kepala laboratorium</li>
                                            <li>Pastikan judul dan deskripsi penelitian jelas dan detail</li>
                                            <li>Dosen pembimbing wajib dipilih</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Judul Penelitian -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <span>📝</span>
                                        <span>Judul Penelitian <span class="required">*</span></span>
                                    </label>
                                    <input type="text" 
                                           name="judul_penelitian" 
                                           class="form-input" 
                                           placeholder="Contoh: Sintesis Nanopartikel Perak dari Ekstrak Daun Jambu Biji"
                                           required>
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <span>📄</span>
                                        <span>Deskripsi Penelitian <span class="required">*</span></span>
                                    </label>
                                    <textarea name="deskripsi" 
                                              class="form-textarea" 
                                              placeholder="Jelaskan tujuan, metode, dan manfaat penelitian secara detail..."
                                              required></textarea>
                                </div>

                                <!-- Tanggal -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <span>📅</span>
                                            <span>Tanggal Mulai <span class="required">*</span></span>
                                        </label>
                                        <input type="date" 
                                               id="tanggal_mulai"
                                               name="tanggal_mulai" 
                                               class="form-input" 
                                               min="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            <span>📅</span>
                                            <span>Tanggal Selesai <span class="required">*</span></span>
                                        </label>
                                        <input type="date" 
                                               id="tanggal_selesai"
                                               name="tanggal_selesai" 
                                               class="form-input"
                                               required>
                                    </div>
                                </div>

                                <!-- Dosen Pembimbing -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <span>👨‍🏫</span>
                                        <span>Dosen Pembimbing <span class="required">*</span></span>
                                    </label>
                                    <select name="dosen_pembimbing" class="form-select" required>
                                        <option value="">-- Pilih Dosen Pembimbing --</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->Nama }}">
                                                {{ $dosen->Nama }}
                                                @if($dosen->UserID)
                                                    ({{ $dosen->UserID }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small style="color: #999; font-size: 0.85rem; display: block; margin-top: 0.5rem;">
                                        Total dosen tersedia: <strong>{{ $dosens->count() }}</strong> orang
                                    </small>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn-submit">
                                    🚀 Ajukan Penelitian
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- History Section -->
                <div>
                    <div class="history-card">
                        <div class="history-header">
                            <div class="history-icon">📋</div>
                            <h3>Riwayat Pengajuan</h3>
                        </div>

                        <div class="history-list">
                            @forelse($penelitians ?? [] as $penelitian)
                                <div class="history-item status-{{ $penelitian->status }}">
                                    <div class="history-title">{{ $penelitian->judul_penelitian }}</div>
                                    <div class="history-meta">
                                        <span>🏢 {{ $penelitian->daftarLab->Nama_Laboratorium ?? 'N/A' }}</span>
                                        <span>👨‍🏫 {{ $penelitian->dosen_pembimbing }}</span>
                                        <span>📅 {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d M Y') }}</span>
                                    </div>
                                    <span class="history-status status-{{ $penelitian->status }}">
                                        @if($penelitian->status === 'menunggu')
                                            ⏳ Menunggu Persetujuan
                                        @elseif($penelitian->status === 'disetujui')
                                            ✅ Disetujui
                                        @else
                                            ❌ Ditolak
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="empty-history">
                                    <div class="empty-history-icon">📭</div>
                                    <p>Belum ada pengajuan penelitian</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectLab() {
            const labSelect = document.getElementById('lab_select');
            const labId = labSelect.value;
            const formSection = document.getElementById('form-section');

            if (!labId) {
                formSection.classList.remove('show');
                return;
            }

            // Set lab ID to hidden input
            document.getElementById('daftar_lab_id').value = labId;

            // Show form
            formSection.classList.add('show');

            // Smooth scroll to form
            formSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Validate dates
        document.getElementById('tanggal_mulai').addEventListener('change', function() {
            const tanggalMulai = this.value;
            const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
            
            // Set minimum end date to start date
            tanggalSelesaiInput.min = tanggalMulai;
            
            // Reset end date if it's before start date
            if (tanggalSelesaiInput.value && tanggalSelesaiInput.value < tanggalMulai) {
                tanggalSelesaiInput.value = '';
            }
        });

        document.getElementById('tanggal_selesai').addEventListener('change', function() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = this.value;
            
            if (tanggalMulai && tanggalSelesai < tanggalMulai) {
                alert('⚠️ Tanggal selesai harus lebih besar dari tanggal mulai!');
                this.value = '';
            }
        });
    </script>
</body>
</html>