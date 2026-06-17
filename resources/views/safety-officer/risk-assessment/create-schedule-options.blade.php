@extends('layouts.app')
@section('title', 'Buat Jadwal')
@section('page-title', 'Buat Jadwal Wawancara')

@push('styles')
<style>
.nav-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .back-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-link:hover {
            opacity: 0.8;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #6b7280;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #1f2937;
            font-size: 1rem;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }
        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        .schedule-option {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        .schedule-option:hover {
            border-color: #667eea;
            background: #f3f4f6;
        }
        .schedule-option-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .schedule-option-number {
            background: #667eea;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .btn-remove {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.3s;
        }
        .btn-remove:hover {
            background: #dc2626;
        }
        .btn-add {
            background: #10b981;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background 0.3s;
            width: 100%;
        }
        .btn-add:hover {
            background: #059669;
        }
        .btn-submit {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #5568d3;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        .alert-success {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #065f46;
        }
        .alert-error {
            background: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }
        .alert-warning {
            background: #fef3c7;
            border-left-color: #fbbf24;
            color: #92400e;
        }
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 600px) {
            .schedule-grid {
                grid-template-columns: 1fr;
            }
        }
</style>
@endpush

@section('content')
<!-- Navigation -->
    <div class="nav-bar">
        <div class="nav-title">📅 Buat Jadwal Wawancara</div>
        <a href="{{ route('safety-officer.risk-assessment.index') }}" class="back-link">
            ← Kembali
        </a>
    </div>

    <div class="container">
        <!-- Flash Messages -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>✅ Sukses:</strong> {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-error">
                <strong>❌ Error:</strong> {{ $message }}
            </div>
        @endif

        <!-- Risk Assessment Info -->
        <div class="card">
            <div class="section-title">📋 Informasi Risk Assessment</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">📝 Judul</span>
                    <span class="info-value">{{ $riskAssessment->topik_judul ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">👤 Peneliti</span>
                    <span class="info-value">{{ $riskAssessment->user->Nama ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">📧 Email</span>
                    <span class="info-value">{{ $riskAssessment->user->Email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">🏢 Laboratorium</span>
                    <span class="info-value">{{ $riskAssessment->daftarLab->Nama_Laboratorium ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Create Schedule Options Form -->
        <form action="{{ route('safety-officer.risk-assessment.store-schedule-options', $riskAssessment->id) }}" method="POST">
            @csrf

            <div class="card">
                <div class="section-title">📅 Buat Jadwal Wawancara</div>
                
                <div class="alert alert-warning" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1.25rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <strong style="color: #92400e; display: block; margin-bottom: 0.5rem;">📋 INSTRUKSI PENTING:</strong>
                    <p style="color: #b45309; margin: 0.5rem 0 0 0; line-height: 1.6;">
                        ✅ Buat <strong>minimal 2 jadwal hingga maksimal 5 jadwal wawancara</strong> yang berbeda.<br>
                        ✅ Mahasiswa akan memilih salah satu jadwal yang paling sesuai.<br>
                        ✅ Gunakan tombol <strong>"➕ TAMBAH OPSI JADWAL"</strong> di bawah untuk menambah jadwal baru.
                    </p>
                </div>

                <!-- Schedule Options Container -->
                <div id="schedules-container">
                    @php
                        $scheduleOptions = old('schedule_options', []);
                        // Jika kosong, tampilkan 2 default
                        if (empty($scheduleOptions)) {
                            $scheduleOptions = [
                                ['jadwal' => '', 'waktu' => '', 'tempat' => ''],
                                ['jadwal' => '', 'waktu' => '', 'tempat' => '']
                            ];
                        }
                    @endphp
                    
                    @foreach($scheduleOptions as $index => $option)
                        <div class="schedule-option" data-index="{{ $index }}">
                            <div class="schedule-option-header">
                                <span class="schedule-option-number">{{ $index + 1 }}</span>
                                @if ($index > 1)
                                    <button type="button" class="btn-remove" onclick="removeSchedule(this)">
                                        🗑️ Hapus
                                    </button>
                                @endif
                            </div>

                            <div class="schedule-grid">
                                <div class="form-group">
                                    <label class="form-label">📅 Tanggal Wawancara</label>
                                    <input type="date" name="schedule_options[{{ $index }}][jadwal]" 
                                           class="form-input" 
                                           value="{{ old('schedule_options.' . $index . '.jadwal', $option['jadwal'] ?? '') }}"
                                           required>
                                    @error('schedule_options.' . $index . '.jadwal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">⏰ Jam Wawancara</label>
                                    <input type="time" name="schedule_options[{{ $index }}][waktu]" 
                                           class="form-input" 
                                           value="{{ old('schedule_options.' . $index . '.waktu', $option['waktu'] ?? '') }}"
                                           required>
                                    @error('schedule_options.' . $index . '.waktu')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">📍 Lokasi Wawancara</label>
                                <input type="text" name="schedule_options[{{ $index }}][tempat]" 
                                       class="form-input" 
                                       placeholder="Contoh: Ruang Lab, Office Room A, Online via Zoom"
                                       value="{{ old('schedule_options.' . $index . '.tempat', $option['tempat'] ?? '') }}"
                                       required>
                                @error('schedule_options.' . $index . '.tempat')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Schedule Button -->
                <div style="margin: 2rem 0;">
                    <button type="button" class="btn-add" onclick="addSchedule()">
                        ➕ TAMBAH OPSI JADWAL (Klik untuk menambah, min 2, maks 5)
                    </button>
                </div>

                <div class="form-group">
                    <label class="form-label">💬 Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" class="form-textarea" placeholder="Berikan instruksi atau informasi tambahan kepada mahasiswa...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-submit">
                    ✅ Kirim Jadwal Wawancara
                </button>
                <a href="{{ route('safety-officer.risk-assessment.index') }}" 
                   style="display: inline-flex; align-items: center; padding: 0.75rem 2rem; background: #6b7280; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.3s;"
                   onmouseover="this.style.background='#4b5563'" 
                   onmouseout="this.style.background='#6b7280'">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let scheduleCount = document.querySelectorAll('.schedule-option').length;

        function addSchedule() {
            if (scheduleCount >= 5) {
                alert('⚠️ Maksimal 5 jadwal wawancara!\n\nHapus beberapa jadwal menggunakan tombol 🗑️ Hapus jika perlu.');
                return;
            }

            const container = document.getElementById('schedules-container');
            const newOption = document.createElement('div');
            newOption.className = 'schedule-option';
            newOption.dataset.index = scheduleCount;
            newOption.innerHTML = `
                <div class="schedule-option-header">
                    <span class="schedule-option-number">${scheduleCount + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeSchedule(this)">
                        🗑️ Hapus
                    </button>
                </div>
                <div class="schedule-grid">
                    <div class="form-group">
                        <label class="form-label">📅 Tanggal Wawancara</label>
                        <input type="date" name="schedule_options[${scheduleCount}][jadwal]" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">⏰ Jam Wawancara</label>
                        <input type="time" name="schedule_options[${scheduleCount}][waktu]" class="form-input" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">📍 Lokasi Wawancara</label>
                    <input type="text" name="schedule_options[${scheduleCount}][tempat]" class="form-input" placeholder="Contoh: Ruang Lab, Office Room A, Online via Zoom" required>
                </div>
            `;
            container.appendChild(newOption);
            scheduleCount++;

            // Update numbers
            updateScheduleNumbers();
            
            // Smooth scroll to new element
            newOption.scrollIntoView({ behavior: 'smooth' });
        }

        function removeSchedule(button) {
            const currentCount = document.querySelectorAll('.schedule-option').length;
            if (currentCount <= 2) {
                alert('⚠️ Minimal 2 jadwal wawancara harus ada!');
                return;
            }
            button.closest('.schedule-option').remove();
            scheduleCount--;
            updateScheduleNumbers();
        }

        function updateScheduleNumbers() {
            document.querySelectorAll('.schedule-option').forEach((option, index) => {
                option.querySelector('.schedule-option-number').textContent = index + 1;
                option.dataset.index = index;
            });
        }

        // ✅ Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const scheduleCount = document.querySelectorAll('.schedule-option').length;
            
            if (scheduleCount < 2) {
                e.preventDefault();
                alert('⚠️ MINIMAL 2 JADWAL WAWANCARA HARUS DIBUAT!\n\nKlik tombol "➕ TAMBAH OPSI JADWAL" untuk menambah jadwal.');
                return false;
            }
            
            if (scheduleCount > 5) {
                e.preventDefault();
                alert('⚠️ MAKSIMAL 5 JADWAL WAWANCARA!\n\nHapus beberapa jadwal menggunakan tombol 🗑️ Hapus.');
                return false;
            }
        });
</script>
@endpush
