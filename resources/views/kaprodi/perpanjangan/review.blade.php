@extends('layouts.app')
@section('title', 'Review Perpanjangan')
@section('page-title', 'Review Perpanjangan')

@push('styles')
<style>
.detail-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .detail-section h3 {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            color: #374151;
            font-size: 1rem;
        }
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alasan-box {
            background: #f3f4f6;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .radio-group {
            display: flex;
            gap: 2rem;
            margin-top: 0.5rem;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .radio-option input[type="radio"] {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
        }
        .durasi-field {
            display: none;
            margin-top: 1rem;
        }
        .durasi-field.show {
            display: block;
        }
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
</style>
@endpush

@section('content')
{{-- Informasi Risk Assessment --}}
            <div class="detail-section">
                <h3>📋 Informasi Risk Assessment</h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Judul/Topik</span>
                        <span class="detail-value">{{ $riskAssessment->topik_judul }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Mahasiswa</span>
                        <span class="detail-value">{{ $riskAssessment->nama }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIM</span>
                        <span class="detail-value">{{ $riskAssessment->nim }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Laboratorium</span>
                        <span class="detail-value">{{ $riskAssessment->daftarLab->Nama_Laboratorium }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jenis</span>
                        <span class="detail-value">{{ $riskAssessment->jenis_ra }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dosen Pembimbing</span>
                        <span class="detail-value">{{ $riskAssessment->dosen_pembimbing_nama }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Batas Waktu --}}
            <div class="detail-section">
                <h3>⏰ Status Batas Waktu Peminjaman</h3>
                
                <div class="warning-box">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Batas Waktu Saat Ini</span>
                            <span class="detail-value" style="color: #ef4444; font-size: 1.25rem; font-weight: 600;">
                                {{ $riskAssessment->getBatasWaktuPeminjamanFormatted() }}
                            </span>
                            <span style="color: #991b1b; margin-top: 0.25rem;">
                                ({{ $riskAssessment->getSisaWaktuPeminjaman() }})
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Durasi Awal Disetujui</span>
                            <span class="detail-value">{{ $riskAssessment->durasi_batas_peminjaman }} Bulan</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Persetujuan Awal</span>
                            <span class="detail-value">
                                {{ $riskAssessment->tanggal_pengajuan?->format('d M Y') }}
                            </span>
                        </div>
                        @if($riskAssessment->jumlah_perpanjangan > 0)
                        <div class="detail-item">
                            <span class="detail-label">Riwayat Perpanjangan</span>
                            <span class="detail-value">{{ $riskAssessment->jumlah_perpanjangan }} kali sebelumnya</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pengajuan Perpanjangan --}}
            <div class="detail-section">
                <h3>🔄 Pengajuan Perpanjangan</h3>
                
                <div class="detail-grid" style="margin-bottom: 1.5rem;">
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Pengajuan</span>
                        <span class="detail-value">
                            {{ $riskAssessment->tanggal_pengajuan_perpanjangan->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durasi Perpanjangan Diminta</span>
                        <span class="detail-value" style="color: #667eea; font-size: 1.25rem; font-weight: 600;">
                            {{ $riskAssessment->durasi_perpanjangan_diminta }} Bulan
                        </span>
                    </div>
                </div>

                <div class="alasan-box">
                    <strong style="color: #1e40af; font-size: 1.1rem;">📝 Alasan Perpanjangan:</strong>
                    <p style="margin-top: 1rem; color: #374151; line-height: 1.8; font-size: 1rem;">
                        {{ $riskAssessment->alasan_perpanjangan }}
                    </p>
                </div>
            </div>

            {{-- Form Keputusan --}}
            <form action="{{ route('kaprodi.perpanjangan.approve', $riskAssessment->id) }}" method="POST" id="approvalForm">
                @csrf
                
                <div class="detail-section">
                    <h3>✅ Keputusan Perpanjangan</h3>

                    {{-- Keputusan --}}
                    <div class="form-group">
                        <label class="form-label">
                            Keputusan <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input 
                                    type="radio" 
                                    name="persetujuan" 
                                    value="setuju" 
                                    required
                                    onchange="toggleDurasiField(true)"
                                >
                                <span style="font-weight: 500;">✅ Setujui Perpanjangan</span>
                            </label>
                            <label class="radio-option">
                                <input 
                                    type="radio" 
                                    name="persetujuan" 
                                    value="tolak"
                                    onchange="toggleDurasiField(false)"
                                >
                                <span style="font-weight: 500;">❌ Tolak Perpanjangan</span>
                            </label>
                        </div>
                        @error('persetujuan')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Durasi Perpanjangan (only show when approved) --}}
                    <div id="durasiField" class="durasi-field form-group">
                        <label for="durasi_perpanjangan" class="form-label">
                            Durasi Perpanjangan yang Disetujui <span style="color: #ef4444;">*</span>
                        </label>
                        <select 
                            name="durasi_perpanjangan" 
                            id="durasi_perpanjangan" 
                            class="form-select"
                        >
                            <option value="">-- Pilih Durasi --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('durasi_perpanjangan', $riskAssessment->durasi_perpanjangan_diminta) == $i ? 'selected' : '' }}>
                                    {{ $i }} Bulan
                                </option>
                            @endfor
                        </select>
                        @error('durasi_perpanjangan')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">
                            💡 Mahasiswa meminta {{ $riskAssessment->durasi_perpanjangan_diminta }} bulan. Anda dapat menyetujui durasi yang sama atau mengubahnya sesuai pertimbangan.
                        </p>
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group">
                        <label for="catatan" class="form-label">
                            Catatan (opsional)
                        </label>
                        <textarea 
                            name="catatan" 
                            id="catatan" 
                            class="form-textarea"
                            placeholder="Tambahkan catatan jika diperlukan..."
                        >{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="error-message">{{ $message }}</div>
                        @enderror>
                    </div>

                    {{-- Buttons --}}
                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                            📤 Submit Keputusan
                        </button>
                        <a href="{{ route('kaprodi.perpanjangan.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </form>
@endsection

@push('scripts')
<script>
function toggleDurasiField(show) {
        const durasiField = document.getElementById('durasiField');
        const durasiSelect = document.getElementById('durasi_perpanjangan');
        
        if (show) {
            durasiField.classList.add('show');
            durasiSelect.required = true;
        } else {
            durasiField.classList.remove('show');
            durasiSelect.required = false;
        }
    }

    function confirmSubmit() {
        const persetujuan = document.querySelector('input[name="persetujuan"]:checked').value;
        const durasi = document.getElementById('durasi_perpanjangan').value;
        
        if (persetujuan === 'setuju') {
            if (!durasi) {
                alert('Harap pilih durasi perpanjangan yang disetujui!');
                return false;
            }
            return confirm(`Anda akan menyetujui perpanjangan selama ${durasi} bulan. Lanjutkan?`);
        } else {
            return confirm('Anda akan menolak pengajuan perpanjangan ini. Lanjutkan?');
        }
    }
</script>
@endpush
