@extends('layouts.app')
@section('title', 'Jadwal Pending')
@section('page-title', 'Pilih Jadwal Wawancara')

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
            max-width: 1000px;
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
        .schedule-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .schedule-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .schedule-card:hover {
            border-color: #667eea;
            background: #f3f4f6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }
        .schedule-card.selected {
            border-color: #10b981;
            background: #ecfdf5;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .schedule-card input[type="radio"] {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .schedule-info {
            padding-right: 2rem;
        }
        .schedule-date {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .schedule-time {
            font-size: 1rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .schedule-location {
            font-size: 0.95rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        .alert-info {
            background: #e0e7ff;
            border-left-color: #667eea;
            color: #3730a3;
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
        .btn-submit:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .deadline-info {
            background: #fef3c7;
            border-left: 4px solid #fbbf24;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            color: #92400e;
            font-weight: 600;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
</style>
@endpush

@section('content')
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
    
            @forelse($pendingSchedules as $riskAssessment)
            <!-- Risk Assessment Info -->
            <div class="card">
                <div class="section-title">📋 Informasi Risk Assessment</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">📝 Judul</span>
                        <span class="info-value">{{ $riskAssessment->topik_judul ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👤 Dosen Pembimbing</span>
                        <span class="info-value">{{ $riskAssessment->dosenPembimbing->Nama ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🏢 Laboratorium</span>
                        <span class="info-value">{{ $riskAssessment->daftarLab->Nama_Laboratorium ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👮 Safety Officer</span>
                        <span class="info-value">{{ $riskAssessment->safetyOfficer->Nama ?? 'N/A' }}</span>
                    </div>
                </div>
    
                @if ($riskAssessment->catatan_safety_officer)
                    <div class="info-item">
                        <span class="info-label">📌 Catatan dari Safety Officer</span>
                        <span class="info-value">{{ $riskAssessment->catatan_safety_officer }}</span>
                    </div>
                @endif
            </div>
    
            <!-- Schedule Selection Form -->
            @if ($riskAssessment->jadwal_wawancara_options && count($riskAssessment->jadwal_wawancara_options) > 0)
                <div class="card">
                    <div class="section-title">📅 Pilih Jadwal Wawancara</div>
    
                    <div class="deadline-info">
                        ⏰ Silakan pilih salah satu jadwal di bawah ini sebelum batas waktu berakhir
                    </div>
    
                    <form action="{{ route('mahasiswa.risk-assessment.select-schedule', $riskAssessment->id) }}" method="POST">
                        @csrf
    
                        <div class="schedule-list">
                            @foreach ($riskAssessment->jadwal_wawancara_options as $index => $option)
                                <label class="schedule-card" id="schedule-{{ $index }}" 
                                       onclick="selectSchedule({{ $index }})"
                                       style="{{ $riskAssessment->jadwal_wawancara_dipilih && isset($riskAssessment->jadwal_wawancara_dipilih[$index]) ? 'border-color: #10b981; background: #ecfdf5;' : '' }}">
                                    
                                    <input type="radio" name="schedule_index" value="{{ $index }}" 
                                           style="position: absolute; top: 1rem; right: 1rem; width: 20px; height: 20px; cursor: pointer;"
                                           {{ $riskAssessment->jadwal_wawancara && \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('Y-m-d H:i') === \Carbon\Carbon::parse($option['jadwal'])->format('Y-m-d H:i') ? 'checked' : '' }}>
    
                                    <div class="schedule-info">
                                        <div class="schedule-date">
                                            📅 {{ \Carbon\Carbon::parse($option['jadwal'])->format('l, d F Y') }}
                                        </div>
                                        <div class="schedule-time">
                                            🕐 {{ $option['waktu'] ?? \Carbon\Carbon::parse($option['jadwal'])->format('H:i') }}
                                        </div>
                                        <div class="schedule-location">
                                            📍 {{ $option['tempat'] ?? 'N/A' }}
                                        </div>
                                    </div>
    
                                    @if ($riskAssessment->jadwal_wawancara && \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('Y-m-d H:i') === \Carbon\Carbon::parse($option['jadwal'])->format('Y-m-d H:i'))
                                        <div style="position: absolute; bottom: 1rem; right: 1rem; color: #10b981; font-weight: 600;">
                                            ✅ Dipilih
                                        </div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
    
                        @if ($errors->has('schedule_index'))
                            <div class="alert alert-error">
                                {{ $errors->first('schedule_index') }}
                            </div>
                        @endif
    
                        <div class="button-group">
                            <button type="submit" class="btn-submit" id="submit-btn-{{ $riskAssessment->id }}" disabled>
                                ✅ Konfirmasi Jadwal
                            </button>
                            <a href="{{ route('mahasiswa.risk-assessment.index') }}" 
                               style="display: inline-flex; align-items: center; padding: 0.75rem 2rem; background: #6b7280; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.3s;"
                               onmouseover="this.style.background='#4b5563'" 
                               onmouseout="this.style.background='#6b7280'">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
            @else
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <p>Belum ada jadwal wawancara yang tersedia. Mohon hubungi Safety Officer.</p>
                    </div>
                </div>
            @endif
    
            <!-- Already Selected -->
            @if ($riskAssessment->jadwal_wawancara_dipilih_at)
                <div class="card">
                    <div class="section-title">✅ Jadwal Wawancara Terpilih</div>
                    <div class="alert alert-success">
                        <strong>✅ Status: Sudah Dipilih pada {{ $riskAssessment->jadwal_wawancara_dipilih_at->format('d M Y, H:i') }}</strong>
                        <div style="margin-top: 1rem;">
                            📅 {{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('l, d F Y') }}<br>
                            🕐 {{ $riskAssessment->jadwal_wawancara->format('H:i') }} WIB<br>
                            📍 {{ $riskAssessment->tempat_wawancara ?? 'Lokasi tidak tersedia' }}
                        </div>
                    </div>
                </div>
            @endif
            @empty
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <p>Tidak ada jadwal wawancara yang menunggu pilihan Anda.</p>
                    </div>
                </div>
            @endforelse
        </div>
    
        <!-- Pagination -->
        @if($pendingSchedules->count() > 0)
        <div style="padding: 0 2rem; margin-top: 2rem;">
            {{ $pendingSchedules->links() }}
        </div>
        @endif
@endsection

@push('scripts')
<script>
function selectSchedule(index) {
            // Update radio button
            const radio = document.querySelector(`input[name="schedule_index"][value="${index}"]`);
            radio.checked = true;

            // Update card styling
            document.querySelectorAll('.schedule-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.getElementById(`schedule-${index}`).classList.add('selected');

            // Enable submit button - find the form in the current card
            const form = radio.closest('form');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }
        }

        // Check if any schedule is selected on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(form => {
                const checkedRadio = form.querySelector('input[name="schedule_index"]:checked');
                if (checkedRadio) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                    document.getElementById(`schedule-${checkedRadio.value}`).classList.add('selected');
                }
            });
        });
</script>
@endpush
