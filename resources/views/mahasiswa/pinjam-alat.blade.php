@extends('layouts.app')
@section('title', 'Peminjaman Alat')
@section('page-title', 'Peminjaman Alat')

@push('styles')
<style>
/* ... (semua style sebelumnya tetap sama) ... */
        .subtitle { color: #666; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        select, input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        .btn-group { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn { flex: 1; padding: 0.75rem; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; transition: opacity 0.3s; }
        .btn:hover { opacity: 0.8; }
        .btn-primary { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
        .lab-selection { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .alat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .alat-card { border: 2px solid #e0e0e0; border-radius: 10px; overflow: hidden; transition: all 0.3s; cursor: pointer; background: white; }
        .alat-card:hover { border-color: #28a745; box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3); transform: translateY(-5px); }
        .alat-card.disabled { opacity: 0.6; cursor: not-allowed; }
        .alat-card.disabled:hover { transform: none; border-color: #e0e0e0; box-shadow: none; }
        .alat-image { width: 100%; height: 200px; object-fit: cover; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white; }
        .alat-image img { width: 100%; height: 100%; object-fit: cover; }
        .alat-body { padding: 1rem; }
        .alat-body h3 { color: #333; margin-bottom: 0.5rem; font-size: 1.1rem; }
        .alat-body p { color: #666; font-size: 0.9rem; margin-bottom: 0.75rem; line-height: 1.4; }
        .stock-badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: bold; }
        .stock-available { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-empty { background: #f8d7da; color: #721c24; }
        .form-section { background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; }
        .form-section h3 { color: #333; margin-bottom: 1rem; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; animation: fadeIn 0.3s ease; }
        .modal-overlay.show { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { background: white; border-radius: 15px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { padding: 1.5rem; border-bottom: 2px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; }
        .modal-header h2 { margin: 0; font-size: 1.5rem; font-weight: bold; }
        .modal-close { background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 1.5rem; cursor: pointer; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.3s; }
        .modal-close:hover { background: rgba(255, 255, 255, 0.3); }
        .modal-body { padding: 2rem; }
        .modal-alat-info { background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; border-left: 4px solid #28a745; }
        .modal-alat-info h3 { color: #28a745; font-size: 1.25rem; margin-bottom: 0.5rem; }
        .modal-alat-info p { color: #666; margin: 0.25rem 0; }
        #alat-container { display: none; }
        #alat-container.show { display: block; }
        .empty-state { background: white; padding: 3rem; border-radius: 10px; text-align: center; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
        .empty-state p { color: #666; font-size: 1.1rem; }
        .info-box { background: #fff3cd; padding: 1rem; border-radius: 5px; border-left: 4px solid #ffc107; margin-top: 1rem; }
        .info-box strong { color: #856404; }
        .info-box p { margin-top: 0.5rem; color: #856404; font-size: 0.9rem; }
        .page-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .grid-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
        @media (max-width: 1024px) { .grid-layout { grid-template-columns: 1fr; } }
        .lab-selection { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .lab-selection h2 { font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
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

        /* ✅ MODAL BEBAS LAB CANCEL CONFIRMATION */
        .modal-bebaslab { background: white; border-radius: 20px; max-width: 520px; width: 100%; text-align: center; padding: 0; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: slideUp 0.3s ease; }
        .modal-bebaslab-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 2rem; border-radius: 20px 20px 0 0; }
        .modal-bebaslab-icon { font-size: 4rem; margin-bottom: 0.5rem; }
        .modal-bebaslab-title { font-size: 1.5rem; font-weight: bold; margin: 0; }
        .modal-bebaslab-body { padding: 2rem; }
        .modal-bebaslab-body p { color: #555; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; }
        .modal-bebaslab-body .info-highlight { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; text-align: left; margin-bottom: 1.5rem; }
        .modal-bebaslab-body .info-highlight p { margin: 0; color: #92400e; font-size: 0.9rem; }
        .modal-bebaslab-actions { display: flex; gap: 1rem; justify-content: center; }
        .btn-cancel-bebaslab { background: #dc3545; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.3s; }
        .btn-cancel-bebaslab:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220,53,69,0.3); }
        .btn-keep-bebaslab { background: #6c757d; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.3s; }
        .btn-keep-bebaslab:hover { background: #5a6268; }

        /* ✅ MODAL EXPIRED STYLES */
        .modal-expired { background: white; border-radius: 20px; max-width: 500px; width: 100%; text-align: center; padding: 0; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: slideUp 0.3s ease; }
        .modal-expired-header { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 2rem; border-radius: 20px 20px 0 0; }
        .modal-expired-icon { font-size: 5rem; margin-bottom: 1rem; animation: shake 0.5s ease; }
        @keyframes shake { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .modal-expired-title { font-size: 1.75rem; font-weight: bold; margin: 0; }
        .modal-expired-body { padding: 2rem; }
        .modal-expired-body p { color: #555; font-size: 1rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .modal-expired-body strong { color: #dc3545; }
        .btn-danger { background: #dc3545; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; font-weight: bold; transition: all 0.3s; }
        .btn-danger:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220,53,69,0.3); }
</style>
@endpush

@section('content')
@php
$riskAssessmentData = $labs->mapWithKeys(function ($lab) use ($user) {
            $ra = \App\Models\RiskAssessment::where('user_id', $user->id)
                ->where('daftar_lab_id', $lab->id)
                ->where('status', 'disetujui')
                ->first();

            return [$lab->id => $ra ? [
                'exists' => true,
                'masih_berlaku' => $ra->isMasihBerlaku(),
                'batas_waktu' => $ra->getBatasWaktuPeminjamanFormatted(),
            ] : ['exists' => false]];
        });

        $labAlatsData = $labs->mapWithKeys(fn($lab) => [$lab->id => $lab->alatLabs]);
@endphp
<script>
window.RISK_ASSESSMENT_DATA = @json($riskAssessmentData);
    window.LAB_ALATS_DATA = @json($labAlatsData);

    window.DASHBOARD_URL = "{{ route('mahasiswa.dashboard') }}";

    // Data bebas lab aktif per RA ID: {ra_id: bebas_lab_request_id}
    window.BEBAS_LAB_BY_RA = @json($bebasLabByRa);
    window.CSRF_TOKEN = "{{ csrf_token() }}";
</script>
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
                                            <option value="{{ $labItem->id }}">{{ $labItem->Nama_Laboratorium }} -  {{ $labItem->floor }}</option>
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
                                    @forelse($peminjaman ?? [] as $item)
                                        <div class="history-item status-{{ $item->status }}">
                                            <div class="history-alat-name">
                                                {{ $item->alatLab->nama_alat ?? 'Alat Tidak Diketahui' }}
                                            </div>
                                            <div class="history-meta">
                                                <span>🏷️ Lab: {{ $item->daftarLab->Nama_Laboratorium ?? '-' }}</span>
                                                <span>📦 Jumlah: {{ $item->jumlah }} unit</span>
                                                <span>📅 Pinjam: {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</span>
                                                <span>📅 Kembali: {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : 'Belum dikembalikan' }}</span>
                                            </div>
                                            <span class="history-status status-{{ $item->status }}">
                                                @if($item->status === 'menunggu')
                                                    ⏳ Menunggu Persetujuan
                                                @elseif($item->status === 'disetujui')
                                                    ✅ Disetujui
                                                @elseif($item->status === 'dikembalikan')
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
    </div>
    
        <!-- ✅ MODAL BEBAS LAB CANCEL CONFIRMATION -->
    <div id="modal-bebas-lab-cancel" class="modal-overlay">
        <div class="modal-bebaslab">
            <div class="modal-bebaslab-header">
                <div class="modal-bebaslab-icon">⚠️</div>
                <h2 class="modal-bebaslab-title">Bebas Lab Aktif Ditemukan</h2>
            </div>
            <div class="modal-bebaslab-body">
                <p>
                    Risk Assessment yang Anda pilih sudah digunakan untuk pengajuan <strong>Bebas Lab</strong> yang masih aktif.
                </p>
                <div class="info-highlight">
                    <p>Jika Anda melanjutkan peminjaman alat, <strong>Bebas Lab akan dibatalkan</strong> dan Anda perlu mengajukan ulang nanti.</p>
                </div>
                <p>Apakah Anda ingin <strong>membatalkan Bebas Lab</strong> dan melanjutkan peminjaman alat?</p>
                <div class="modal-bebaslab-actions">
                    <button type="button" class="btn-keep-bebaslab" onclick="closeBebasLabModal()">
                        Tidak, Pertahankan Bebas Lab
                    </button>
                    <button type="button" class="btn-cancel-bebaslab" onclick="confirmCancelBebasLab()">
                        Ya, Batalkan Bebas Lab & Pinjam Alat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ MODAL ERROR (untuk menampilkan error dari backend) -->
        <div id="modal-error" class="modal-overlay">
        <div class="modal-expired">
            <div class="modal-expired-header">
                <div class="modal-expired-icon">⚠️</div>
                <h2 class="modal-expired-title" id="error-title">Pengajuan Gagal</h2>
            </div>
            <div class="modal-expired-body">
                <p id="error-message" style="color:#555;font-size:1rem;line-height:1.6;">
                    Terjadi kesalahan. Silakan coba lagi.
                </p>
                <button onclick="closeErrorModal()" class="btn-danger">
                    ✓ Saya Mengerti
                </button>
            </div>
        </div>
    </div>
    
        <!-- ✅ MODAL EXPIRED -->
        <div id="modal-expired" class="modal-overlay">
        <div class="modal-expired">
            <div class="modal-expired-header">
                <div class="modal-expired-icon">⏰</div>
                <h2 class="modal-expired-title">Batas Waktu Pengajuan Berakhir</h2>
            </div>
            <div class="modal-expired-body">
                <p>
                    Maaf, batas waktu untuk <strong>mengajukan peminjaman alat</strong> 
                    sudah berakhir pada tanggal:
                </p>
                <p id="expired-date"
                   style="font-size:1.25rem;font-weight:bold;color:#dc3545;margin:1rem 0;">
                    -
                </p>
                <button onclick="redirectToDashboard()" class="btn-danger">
                    🏠 Kembali ke Dashboard
                </button>
            </div>
        </div>
    </div>
    
    
        <!-- ✅ ERROR MESSAGE DISPLAY (dari session flash) -->
        @if($errors->any() || session('error'))
            
        @endif
    
        <!-- Modal Peminjaman -->
        <div id="modal-peminjaman" class="modal-overlay" onclick="closeModalOnOverlay(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>📋 Form Peminjaman Alat</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mahasiswa.pinjam-alat.store', ['id' => $lab->id ?? 0]) }}" method="POST" id="form-peminjaman">
                        @csrf
                        
                        <input type="hidden" id="modal_lab_id" name="daftar_lab_id">
                        <input type="hidden" id="modal_alat_id" name="alat_lab_id">
                        
                        <!-- Info Alat yang Dipilih -->
                        <div class="modal-alat-info">
                            <h3 id="modal-alat-name">-</h3>
                            <p><strong>Laboratorium:</strong> <span id="modal-lab-name">-</span></p>
                            <p><strong>Stok Tersedia:</strong> <span id="modal-alat-stock">-</span> unit</p>
                        </div>
    
                        <!-- Jumlah Alat -->
                        <div class="form-group">
                            <label for="modal_jumlah">Jumlah Alat yang Dipinjam *</label>
                            <input type="number" id="modal_jumlah" name="jumlah" required min="1" max="1" value="1" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:5px;font-size:1rem;">
                            <small style="color:#666;">Maksimal sesuai stok yang tersedia</small>
                        </div>
    
                        <!-- Form Detail Peminjaman -->
                        @if((!isset($riskAssessments) || $riskAssessments->count() == 0))
                        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                            <strong>⚠️ Tidak Ada Risk Assessment yang Disetujui</strong>
                            <p style="margin: 8px 0 0 0; color: #856404;">
                                Anda belum memiliki Risk Assessment yang disetujui. Silakan buat dan ajukan Risk Assessment terlebih dahulu sebelum meminjam alat.
                            </p>
                        </div>
                        @else
                        <div style="background: #d1ecf1; border-left: 4px solid #0c5460; padding: 12px; margin-bottom: 15px; border-radius: 5px;">
                            <strong>ℹ️ Info:</strong>
                            <p style="margin: 5px 0 0 0; color: #0c5460; font-size: 0.9rem;">
                                Anda dapat meminjam alat dari lab manapun menggunakan Risk Assessment yang sudah disetujui.
                            </p>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="modal_risk_assessment_id">Pilih Risk Assessment *</label>
                            <select id="modal_risk_assessment_id" name="risk_assessment_id" required class="form-control">
                                <option value="">-- Pilih Risk Assessment --</option>
                                @if(isset($riskAssessments) && $riskAssessments->count() > 0)
                                    @foreach($riskAssessments as $ra)
                                        @php
                                            $raBerlaku = $ra->isMasihBerlaku();
                                        @endphp
                                        <option value="{{ $ra->id }}" {{ old('risk_assessment_id') == $ra->id ? 'selected' : '' }} {{ $raBerlaku ? '' : 'disabled' }}>
                                            {{ $ra->id_ra }} - {{ $ra->topik_judul }}
                                            ({{ $raBerlaku ? 'Berlaku sampai: ' . $ra->getBatasWaktuPeminjamanFormatted() : 'Expired: ' . $ra->getBatasWaktuPeminjamanFormatted() }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada Risk Assessment yang disetujui</option>
                                @endif
                            </select>
                            <small class="text-muted">Pilih Risk Assessment yang sesuai dengan penelitian/praktikum Anda</small>
                        </div>
    
                        <div class="form-group">
                            <label for="modal_tanggal_pinjam">Tanggal Pinjam *</label>
                            <input type="date" id="modal_tanggal_pinjam" name="tanggal_pinjam" required min="{{ date('Y-m-d') }}">
                        </div>
    
                        <div class="form-group">
                            <label for="modal_tanggal_kembali">Tanggal Kembali *</label>
                            <input type="date" id="modal_tanggal_kembali" name="tanggal_kembali" required min="{{ date('Y-m-d') }}">
                        </div>
    
                        <div class="info-box">
                            <strong>📌 Catatan Penting:</strong>
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
@endsection

@push('scripts')
@if($errors->any() || session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    showErrorModal(
                        '❌ Pengajuan Gagal',
                        `{{ session('error') ?? $errors->first() }}`
                    );
                }, 300);
            });
</script>
@endif
<script>
const riskAssessmentData = window.RISK_ASSESSMENT_DATA;
        const labAlatsData = window.LAB_ALATS_DATA;
        
        let selectedLabId = null;
        let selectedLabName = null;

        // ✅ Redirect ke dashboard
        function redirectToDashboard() {
            window.location.href = "{{ route('mahasiswa.dashboard') }}";
        }

        // ✅ Show error modal (dari backend validation)
        function showErrorModal(title, message) {
            document.getElementById('error-title').textContent = title;
            document.getElementById('error-message').innerHTML = message;
            document.getElementById('modal-error').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // ✅ Close error modal
        function closeErrorModal() {
            document.getElementById('modal-error').classList.remove('show');
            document.body.style.overflow = '';
            // ✅ JANGAN buka modal form kosong
            // Biarkan user kembali ke halaman normal
            // User bisa pilih alat lagi atau ubah RA di form existing
        }

        function selectLabDropdown() {
    const labSelect = document.getElementById('lab_select');
    const labId = labSelect.value;

    if (!labId) return;

    // ✅ DIUBAH: Hapus cek expired di sini
    // Biarkan mahasiswa pilih alat dan RA dulu
    // Validasi expired akan dilakukan saat submit (di backend)
    
    selectedLabId = labId;
    selectedLabName = labSelect.options[labSelect.selectedIndex].text;
    loadAlatForLab(labId);
    document.getElementById('alat-container').classList.add('show');
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
                    ? `<img src="/uploads/${alat.foto}" alt="${alat.nama_alat}">`
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

            // Reset form fields
            document.getElementById('modal_jumlah').value = 1;
            document.getElementById('modal_jumlah').max = stok;
            document.getElementById('modal_tanggal_pinjam').value = '';
            document.getElementById('modal_tanggal_kembali').value = '';

            const form = document.getElementById('form-peminjaman');
            form.action = "{{ route('mahasiswa.pinjam-alat.store', ':id') }}".replace(':id', selectedLabId);

            // Show modal
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

        // ✅ VALIDASI RA SELECTION - Check apakah RA expired saat dipilih
        document.getElementById('modal_risk_assessment_id').addEventListener('change', function() {
            const raWarning = document.getElementById('ra-deadline-warning');
            const bebasLabWarning = document.getElementById('ra-bebaslab-warning');
            const selectedValue = this.value;

            // Remove existing warnings
            if (raWarning) raWarning.remove();
            if (bebasLabWarning) bebasLabWarning.remove();

            if (!selectedValue) return;

            // Cek apakah RA punya bebas lab aktif
            const bebasLabData = window.BEBAS_LAB_BY_RA;
            if (bebasLabData && bebasLabData[selectedValue]) {
                const warning = document.createElement('div');
                warning.id = 'ra-bebaslab-warning';
                warning.style.cssText = 'background: #fef3c7; border-left: 4px solid #f59e0b; padding: 10px 14px; border-radius: 5px; margin-top: 8px;';
                warning.innerHTML = '<strong style="color: #92400e;">⚠️ Bebas Lab Aktif</strong><p style="margin: 4px 0 0; color: #92400e; font-size: 0.85rem;">Risk Assessment ini memiliki Bebas Lab aktif. Jika Anda meminjam alat, Bebas Lab akan dibatalkan.</p>';
                this.parentNode.appendChild(warning);
            }

            // Cek apakah expired
            const selectedOption = this.options[this.selectedIndex];
            const raText = selectedOption.text;
            if (raText.includes('Berlaku:')) {
                if (raWarning) raWarning.remove();
            }
        });

        // ✅ INTERCEPT FORM SUBMISSION - Check bebas lab sebelum submit
        let pendingBebasLabId = null;
        
        document.getElementById('form-peminjaman').addEventListener('submit', function(e) {
            const selectedRaId = document.getElementById('modal_risk_assessment_id').value;
            const bebasLabData = window.BEBAS_LAB_BY_RA;
            
            // Cek apakah RA punya bebas lab aktif
            if (bebasLabData && bebasLabData[selectedRaId] && !this.dataset.bebasLabConfirmed) {
                e.preventDefault();
                pendingBebasLabId = bebasLabData[selectedRaId]; // value is the bebas_lab_request_id
                
                // Show confirmation modal
                document.getElementById('modal-bebas-lab-cancel').classList.add('show');
                document.body.style.overflow = 'hidden';
                return false;
            }
        });

        function closeBebasLabModal() {
            document.getElementById('modal-bebas-lab-cancel').classList.remove('show');
            document.body.style.overflow = '';
            pendingBebasLabId = null;
        }

        function confirmCancelBebasLab() {
            if (!pendingBebasLabId) return;

            const cancelBtn = document.querySelector('.btn-cancel-bebaslab');
            cancelBtn.textContent = 'Memproses...';
            cancelBtn.disabled = true;

            // Remove the RA from bebas lab data
            const selectedRaId = document.getElementById('modal_risk_assessment_id').value;
            delete window.BEBAS_LAB_BY_RA[selectedRaId];
            
            // Remove warning if shown
            const bebasLabWarning = document.getElementById('ra-bebaslab-warning');
            if (bebasLabWarning) bebasLabWarning.remove();

            // Close modal and submit the form directly
            // Backend (storePeminjamanAlat) will handle bebas lab cancellation
            document.getElementById('modal-bebas-lab-cancel').classList.remove('show');
            document.body.style.overflow = '';
            
            // Mark as confirmed and submit
            document.getElementById('form-peminjaman').dataset.bebasLabConfirmed = 'true';
            document.getElementById('form-peminjaman').submit();
        }
</script>
@endpush
