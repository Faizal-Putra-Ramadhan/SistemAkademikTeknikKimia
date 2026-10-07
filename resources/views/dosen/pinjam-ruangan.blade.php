@extends('layouts.app')

@section('title', 'Pinjam Ruangan')
@section('page-title', 'Peminjaman Ruangan')

@push('styles')
<style>
    .page-container { max-width: 1400px; margin: 0 auto; }
    .grid-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
    @media (max-width: 1024px) { .grid-layout { grid-template-columns: 1fr; } }

    /* Lab Grid */
    .lab-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem; }
    .lab-card { border: 1.5px solid #3b82f6; border-radius: 12px; overflow: hidden; transition: all 0.3s; cursor: pointer; background: white; padding: 1.25rem 1.5rem; }
    .lab-card:hover { box-shadow: 0 4px 20px rgba(59,130,246,0.15); transform: translateY(-2px); }
    .lab-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .lab-header-left { display: flex; gap: 0.75rem; align-items: center; }
    .badge-reservasi { background-color: #ecfdf5; color: #059669; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem; }
    .badge-reservasi::before { content: ''; width: 6px; height: 6px; background-color: #10b981; border-radius: 50%; display: inline-block; }
    .badge-tipe { background-color: #eff6ff; color: #2563eb; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
    .badge-lantai { color: #4b5563; font-size: 0.85rem; font-weight: 500; background: #f9fafb; padding: 0.35rem 0.75rem; border-radius: 6px; }
    .badge-lantai strong { color: #111827; }
    .lab-title { color: #111827; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; }
    .lab-subtitle { color: #6b7280; font-size: 0.85rem; margin-bottom: 1.5rem; }
    .lab-attr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
    @media (max-width: 768px) { .lab-attr-grid { grid-template-columns: 1fr; } }
    .lab-attr-item { background: #f9fafb; border-radius: 8px; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 1rem; }
    .lab-attr-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem; }
    .icon-kl { background: #eff6ff; color: #1d4ed8; }
    .icon-al { background: #f5f3ff; color: #6d28d9; }
    .icon-so { background: #fffbeb; color: #b45309; }
    .icon-email { background: #eff6ff; color: #2563eb; }
    .lab-attr-content { flex: 1; }
    .lab-attr-label { font-size: 0.65rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 0.15rem; }
    .lab-attr-value { font-size: 0.85rem; color: #1f2937; font-weight: 500; }
    .lab-footer-flex { display: flex; justify-content: space-between; align-items: center; }
    .lab-proses { color: #6b7280; font-size: 0.85rem; }
    .lab-btn-ajukan { background: #2563eb; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s; cursor: pointer; }
    .lab-btn-ajukan:hover { background: #1d4ed8; }

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; animation: fadeIn 0.3s ease; }
    .modal-overlay.show { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .modal-content { background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-header { padding: 1.5rem 2rem; border-bottom: 2px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: white; border-radius: 20px 20px 0 0; }
    .modal-header h2 { margin: 0; font-size: 1.5rem; font-weight: bold; }
    .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 1.5rem; cursor: pointer; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.3s; }
    .modal-close:hover { background: rgba(255,255,255,0.3); }
    .modal-body { padding: 2rem; }
    .modal-lab-info { background: #f0f8ff; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 4px solid #0d6efd; }
    .modal-lab-info h3 { color: #0d6efd; font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: bold; }
    .modal-lab-info p { color: #666; margin: 0.25rem 0; font-size: 0.9rem; }
    .time-group { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .btn-group { display: flex; gap: 1rem; margin-top: 2rem; }

    /* Info Box */
    .info-box { background: #fef3c7; padding: 1.25rem; border-radius: 10px; border-left: 4px solid #d97706; margin-top: 1rem; }
    .info-box strong { color: #92400e; display: block; margin-bottom: 0.5rem; }
    .info-box p { margin: 0.25rem 0; color: #92400e; font-size: 0.85rem; line-height: 1.6; }

    /* History */
    .history-card { height: fit-content; position: sticky; top: 2rem; }
    .history-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0; }
    .history-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
    .history-header h3 { font-size: 1.25rem; font-weight: bold; color: #333; margin: 0; }
    .history-list { max-height: 600px; overflow-y: auto; }
    .history-item { padding: 1.25rem; background: #f8f9fa; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #ddd; transition: all 0.3s; }
    .history-item:hover { transform: translateX(5px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .history-item.status-menunggu { border-left-color: #f59e0b; background: #fffbeb; }
    .history-item.status-disetujui_laboran, 
    .history-item.status-menunggu_kepala_lab { border-left-color: #06b6d4; background: #ecfeff; }
    .history-item.status-disetujui { border-left-color: #10b981; background: #ecfdf5; }
    .history-item.status-dikembalikan { border-left-color: #6b7280; background: #f3f4f6; }
    .history-item.status-ditolak { border-left-color: #ef4444; background: #fef2f2; }
    .history-lab-name { font-weight: bold; color: #333; margin-bottom: 0.5rem; font-size: 1rem; }
    .history-meta { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; }
    .history-purpose { background: white; padding: 0.75rem; border-radius: 8px; margin-top: 0.5rem; font-size: 0.85rem; color: #555; font-style: italic; }
    .history-status { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-top: 0.5rem; }
    .status-menunggu { background: #fef3c7; color: #92400e; }
    .status-disetujui_laboran,
    .status-menunggu_kepala_lab { background: #cffafe; color: #083344; }
    .status-disetujui { background: #d1fae5; color: #065f46; }
    .status-dikembalikan { background: #e5e7eb; color: #374151; }
    .status-ditolak { background: #fee2e2; color: #991b1b; }
    .empty-history { text-align: center; padding: 3rem 1rem; color: #999; }
    .empty-history-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }

    #lab-container { display: none; }
    #lab-container.show { display: block; }
</style>
@endpush

@section('content')
    <div class="page-container">
        <div class="grid-layout">
            {{-- Left Section --}}
            <div>
                {{-- Lab Selection --}}
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3> Pilih Laboratorium</h3>
                    </div>
                    <div class="card-body">
                        <p style="color: #666; margin-bottom: 1.5rem;">Pilih laboratorium yang ingin Anda gunakan</p>
                        <div class="form-group">
                            <label class="form-label" for="lab_select">Laboratorium *</label>
                            <select class="form-control" id="lab_select" onchange="selectLabDropdown()" required>
                                <option value="">-- Pilih Laboratorium --</option>
                                @foreach($labs as $labItem)
                                    <option value="{{ $labItem->id }}"
                                            data-kepala="{{ $labItem->Kepala_Labolatorium }}"
                                            data-email="{{ $labItem->email_lab }}">
                                        {{ $labItem->Nama_Laboratorium }} -  {{ $labItem->floor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Lab Cards (Hidden initially) --}}
                <div id="lab-container">
                    <div class="card">
                        <div class="card-header">
                            <h3> Informasi Laboratorium</h3>
                        </div>
                        <div class="card-body">
                            <p style="color: #666; margin-bottom: 1.5rem;">Klik card untuk mengajukan peminjaman ruangan</p>
                            <div class="lab-grid">
                                @foreach($labs as $labItem)
                                <div class="lab-card"
                                     onclick="openModal({{ $labItem->id }}, '{{ addslashes($labItem->Nama_Laboratorium) }}', '{{ addslashes($labItem->Kepala_Labolatorium) }}', '{{ addslashes($labItem->email_lab ?? '') }}')"
                                     data-lab-id="{{ $labItem->id }}"
                                     style="display: none;">
                                    <div class="lab-header-flex">
                                        <div class="lab-header-left">
                                            <span class="badge-reservasi">Reservasi Tersedia</span>
                                            <span class="badge-tipe">TIPE: {{ strtoupper($labItem->lab_type ?? 'PENDIDIKAN') }}</span>
                                        </div>
                                        <span class="badge-lantai">Lantai: <strong>{{ $labItem->floor ?? '-' }}</strong></span>
                                    </div>
                                    <h3 class="lab-title">{{ $labItem->Nama_Laboratorium }}</h3>
                                    <p class="lab-subtitle">Daftar atribut sesuai spesifikasi skema laboratorium.</p>
                                    
                                    <div class="lab-attr-grid">
                                        <div class="lab-attr-item">
                                            <div class="lab-attr-icon icon-kl">KL</div>
                                            <div class="lab-attr-content">
                                                <div class="lab-attr-label">KEPALA LABORATORIUM</div>
                                                <div class="lab-attr-value">{{ $labItem->Kepala_Labolatorium ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="lab-attr-item">
                                            <div class="lab-attr-icon icon-al">AL</div>
                                            <div class="lab-attr-content">
                                                <div class="lab-attr-label">ADMIN LABORATORIUM</div>
                                                <div class="lab-attr-value">{{ $labItem->Admin_Laboratorium ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="lab-attr-item">
                                            <div class="lab-attr-icon icon-so">SO</div>
                                            <div class="lab-attr-content">
                                                <div class="lab-attr-label">SAFETY OFFICER</div>
                                                <div class="lab-attr-value">{{ $labItem->Safety_Officer ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="lab-attr-item">
                                            <div class="lab-attr-content">
                                                <div class="lab-attr-label">EMAIL LAB</div>
                                                <div class="lab-attr-value" style="color: #3b82f6;">{{ $labItem->email_lab ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($peminjaman_aktif_per_lab[$labItem->id]) && $peminjaman_aktif_per_lab[$labItem->id]->count() > 0)
                                        <div class="info-box" style="margin-top:0; margin-bottom:1.5rem;">
                                            <strong> Jadwal Terpakai ({{ $peminjaman_aktif_per_lab[$labItem->id]->count() }} booking)</strong>
                                            @foreach($peminjaman_aktif_per_lab[$labItem->id] as $aktif)
                                            <p> {{ $aktif->user_nama }} —  {{ \Carbon\Carbon::parse($aktif->tanggal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($aktif->tanggal_selesai)->format('d M Y') }},  {{ \Carbon\Carbon::parse($aktif->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($aktif->jam_selesai)->format('H:i') }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="lab-footer-flex">
                                        <div class="lab-proses">Proses persetujuan: &plusmn; 1x24 jam kerja</div>
                                        <button type="button" class="lab-btn-ajukan">Ajukan Peminjaman &rarr;</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Section - History --}}
            <div>
                <div class="card history-card">
                    <div class="card-body">
                        <div class="history-header">
                            
                            <h3>Riwayat Peminjaman</h3>
                        </div>

                        <div class="history-list">
                            @forelse($peminjaman_ruangans as $peminjaman)
                                <div class="history-item status-{{ $peminjaman->status }}">
                                    <div class="history-lab-name">{{ $peminjaman->daftarLab->Nama_Laboratorium ?? 'N/A' }}</div>
                                    <div class="history-meta">
                                        <span> {{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai ?? $peminjaman->tanggal)->format('d M Y') }}</span>
                                        <span> {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                    <div class="history-purpose">
                                        "{{ Str::limit($peminjaman->keperluan, 80) }}"
                                    </div>
                                    <span class="history-status status-{{ $peminjaman->status }}">
                                        @if($peminjaman->status === 'menunggu')
                                            ⏳ Menunggu Laboran
                                        @elseif($peminjaman->status === 'disetujui_laboran' || $peminjaman->status === 'menunggu_kepala_lab')
                                             Menunggu Kepala Lab
                                        @elseif($peminjaman->status === 'disetujui')
                                             Disetujui
                                        @elseif($peminjaman->status === 'dikembalikan')
                                             Dikembalikan
                                        @else
                                             Ditolak
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="empty-history">
                                    
                                    <p>Belum ada riwayat peminjaman</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Peminjaman --}}
    <div id="modal-peminjaman" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2> Form Peminjaman Ruangan</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                @if($lab)
                    <form action="{{ route('dosen.pinjam-ruangan.store', $lab->id) }}" method="POST" id="form-peminjaman" data-action-base="{{ url('/dosen/lab') }}">
                        @csrf

                        <input type="hidden" id="modal_lab_id" name="daftar_lab_id">

                        <div class="modal-lab-info">
                            <h3 id="modal-lab-name">-</h3>
                            <p><strong>Kepala Lab:</strong> <span id="modal-lab-kepala">-</span></p>
                            <p><strong>Email:</strong> <span id="modal-lab-email">-</span></p>
                        </div>

                        <div class="date-range-group" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group">
                                <label class="form-label" for="modal_tanggal"> Tanggal Mulai *</label>
                                <input type="date" class="form-control" id="modal_tanggal" name="tanggal" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="modal_tanggal_selesai"> Tanggal Selesai *</label>
                                <input type="date" class="form-control" id="modal_tanggal_selesai" name="tanggal_selesai" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="time-group">
                            <div class="form-group">
                                <label class="form-label" for="modal_jam_mulai"> Jam Mulai *</label>
                                <input type="time" class="form-control" id="modal_jam_mulai" name="jam_mulai" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="modal_jam_selesai"> Jam Selesai *</label>
                                <input type="time" class="form-control" id="modal_jam_selesai" name="jam_selesai" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="modal_keperluan"> Keperluan / Tujuan Penggunaan *</label>
                            <textarea class="form-control" id="modal_keperluan" name="keperluan" required placeholder="Jelaskan keperluan penggunaan ruangan laboratorium..." style="min-height:100px;resize:vertical;"></textarea>
                        </div>

                        <div class="info-box">
                            <strong> Catatan Penting:</strong>
                            <p>• Peminjaman ruangan harus diajukan minimal 1 hari sebelum penggunaan</p>
                            <p>• Pastikan ruangan dikembalikan dalam kondisi bersih dan rapi</p>
                            <p>• Jaga fasilitas laboratorium dengan baik</p>
                            <p>• Hubungi kepala lab jika ada kendala atau perubahan jadwal</p>
                        </div>

                        <div class="btn-group">
                            <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex:1;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary" style="flex:1;">
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin:2rem 0;">
                        Belum ada laboratorium yang tersedia. Silakan hubungi admin untuk menambahkan lab terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
        document.getElementById('modal-lab-email').textContent = labEmail || '—';

        var form = document.getElementById('form-peminjaman');
        if (form && form.dataset.actionBase) {
            form.action = form.dataset.actionBase + '/' + labId + '/pinjam-ruangan';
        }

        document.getElementById('modal_tanggal').value = '';
        document.getElementById('modal_tanggal_selesai').value = '';
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

    const tanggalMulaiInput = document.getElementById('modal_tanggal');
    const tanggalSelesaiInput = document.getElementById('modal_tanggal_selesai');
    const jamMulaiInput = document.getElementById('modal_jam_mulai');
    const jamSelesaiInput = document.getElementById('modal_jam_selesai');

    // Tanggal selesai minimal = tanggal mulai
    if (tanggalMulaiInput && tanggalSelesaiInput) {
        tanggalMulaiInput.addEventListener('change', function() {
            tanggalSelesaiInput.min = this.value;
            if (tanggalSelesaiInput.value && tanggalSelesaiInput.value < this.value) {
                tanggalSelesaiInput.value = this.value;
            }
        });
    }

    const validateJamSelesai = () => {
        if (!jamMulaiInput || !jamSelesaiInput) return;
        if (!jamMulaiInput.value || !jamSelesaiInput.value) return;
        const sameDate = tanggalMulaiInput && tanggalSelesaiInput && tanggalMulaiInput.value === tanggalSelesaiInput.value;
        if (sameDate && jamSelesaiInput.value < jamMulaiInput.value) {
            alert(' Jam selesai tidak boleh lebih kecil dari jam mulai jika tanggal sama!');
            jamSelesaiInput.value = '';
        }
    };

    if (jamMulaiInput) jamMulaiInput.addEventListener('change', validateJamSelesai);
    if (jamSelesaiInput) jamSelesaiInput.addEventListener('change', validateJamSelesai);
</script>
@endpush