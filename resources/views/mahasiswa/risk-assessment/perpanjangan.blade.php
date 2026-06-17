@extends('layouts.app')
@section('title', 'Perpanjangan RA')
@section('page-title', 'Ajukan Perpanjangan Risk Assessment')

@push('styles')
<style>
    .form-section {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    .form-section h3 {
        color: #333;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #667eea;
    }
    .info-box {
        background: #f3f4f6;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    .info-box-warning {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }
    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .char-counter {
        text-align: right;
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        {{-- Info Risk Assessment --}}
        <div class="form-section">
            <h3>📋 Informasi Risk Assessment</h3>
            
            <div class="info-box">
                <p><strong>Judul:</strong> {{ $riskAssessment->topik_judul }}</p>
                <p><strong>Mahasiswa:</strong> {{ $riskAssessment->nama }} ({{ $riskAssessment->nim }})</p>
                <p><strong>Laboratorium:</strong> {{ $riskAssessment->daftarLab->Nama_Laboratorium }}</p>
                <p><strong>Batas Waktu Saat Ini:</strong> 
                    <span style="color: #ef4444; font-weight: 600;">
                        {{ $riskAssessment->getBatasWaktuPeminjamanFormatted() }}
                    </span>
                    ({{ $riskAssessment->getSisaWaktuPeminjaman() }})
                </p>
                @if($riskAssessment->jumlah_perpanjangan > 0)
                <p><strong>Jumlah Perpanjangan Sebelumnya:</strong> {{ $riskAssessment->jumlah_perpanjangan }} kali</p>
                @endif
            </div>

            <div class="info-box info-box-warning">
                <strong>⚠️ Perhatian:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    <li>Pengajuan perpanjangan akan direview oleh Kaprodi</li>
                    <li>Berikan alasan yang jelas dan lengkap (minimal 50 karakter)</li>
                    <li>Durasi perpanjangan yang dapat diminta: 1-12 bulan</li>
                    <li>Kaprodi berhak menyetujui, menolak, atau mengubah durasi yang diminta</li>
                </ul>
            </div>
        </div>

        {{-- Form Perpanjangan --}}
        <form action="{{ route('mahasiswa.risk-assessment.perpanjangan.store', $riskAssessment->id) }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>📝 Form Pengajuan Perpanjangan</h3>

                <div class="form-group">
                    <label for="durasi_perpanjangan_diminta" class="form-label">
                        Durasi Perpanjangan yang Diminta <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="durasi_perpanjangan_diminta" id="durasi_perpanjangan_diminta" class="form-control" required>
                        <option value="">-- Pilih Durasi --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('durasi_perpanjangan_diminta') == $i ? 'selected' : '' }}>
                                {{ $i }} Bulan
                            </option>
                        @endfor
                    </select>
                    @error('durasi_perpanjangan_diminta')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">
                        Pilih durasi sesuai kebutuhan penelitian/praktikum Anda
                    </p>
                </div>

                <div class="form-group">
                    <label for="alasan_perpanjangan" class="form-label">
                        Alasan Perpanjangan <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="alasan_perpanjangan" id="alasan_perpanjangan" class="form-control"
                        placeholder="Jelaskan alasan Anda memerlukan perpanjangan waktu peminjaman laboratorium..."
                        required minlength="50" maxlength="1000" rows="6"
                    >{{ old('alasan_perpanjangan') }}</textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 1000 karakter (minimal 50)
                    </div>
                    @error('alasan_perpanjangan')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">📤 Ajukan Perpanjangan</button>
                    <a href="{{ route('mahasiswa.risk-assessment.show', $riskAssessment->id) }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const textarea = document.getElementById('alasan_perpanjangan');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const length = textarea.value.length;
        charCount.textContent = length;
        charCount.style.color = length < 50 ? '#ef4444' : (length > 900 ? '#f59e0b' : '#10b981');
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount();
</script>
@endpush