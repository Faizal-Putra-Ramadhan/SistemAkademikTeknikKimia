@extends('layouts.app')

@section('title', 'Pinjam Alat')
@section('page-title', 'Peminjaman Alat')

@push('styles')
<style>
    .page-container { max-width: 1400px; margin: 0 auto; }
    .grid-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
    @media (max-width: 1024px) { .grid-layout { grid-template-columns: 1fr; } }

    /* Alat Grid */
    .alat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
    .alat-card { border: 2px solid #e0e0e0; border-radius: 15px; overflow: hidden; background: white; cursor: pointer; transition: all 0.3s; }
    .alat-card:hover { border-color: #059669; box-shadow: 0 8px 25px rgba(5,150,105,0.25); transform: translateY(-5px); }
    .alat-card.disabled { opacity: 0.6; cursor: not-allowed; }
    .alat-card.disabled:hover { transform: none; border-color: #e0e0e0; box-shadow: none; }
    .alat-image { width: 100%; height: 200px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white; }
    .alat-image img { width: 100%; height: 100%; object-fit: cover; }
    .alat-body { padding: 1.5rem; }
    .alat-body h3 { margin-bottom: 0.75rem; font-weight: bold; }
    .alat-body p { color: #666; font-size: 0.9rem; margin-bottom: 0.75rem; line-height: 1.4; }
    .stock-badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600; margin-top: 0.5rem; }
    .stock-available { background: #d1fae5; color: #065f46; }
    .stock-low { background: #fef3c7; color: #92400e; }
    .stock-empty { background: #fee2e2; color: #991b1b; }

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.show { display: flex; }
    .modal-content { background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: none; opacity: 1; } }
    .modal-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h2 { margin: 0; font-size: 1.5rem; font-weight: bold; }
    .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; }
    .modal-close:hover { background: rgba(255,255,255,0.3); }
    .modal-body { padding: 2rem; }
    .modal-alat-info { background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; border-left: 4px solid #059669; }
    .modal-alat-info h3 { color: #059669; font-size: 1.25rem; margin-bottom: 0.5rem; }
    .modal-alat-info p { color: #666; margin: 0.25rem 0; }
    .btn-group { display: flex; gap: 1rem; margin-top: 2rem; }

    /* Info Box */
    .info-box { background: #fef3c7; padding: 1rem; border-radius: 5px; border-left: 4px solid #d97706; margin-top: 1rem; }
    .info-box strong { color: #92400e; }
    .info-box p { margin-top: 0.5rem; color: #92400e; font-size: 0.9rem; }

    /* History */
    .history-card { height: fit-content; position: sticky; top: 2rem; }
    .history-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0; }
    .history-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
    .history-header h3 { font-size: 1.25rem; font-weight: bold; color: #333; margin: 0; }
    .history-list { max-height: 700px; overflow-y: auto; }
    .history-item { padding: 1.25rem; background: #f8f9fa; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #ddd; transition: all 0.3s; }
    .history-item:hover { transform: translateX(5px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .history-item.status-menunggu { border-left-color: #d97706; background: #fffbeb; }
    .history-item.status-disetujui { border-left-color: #059669; background: #ecfdf5; }
    .history-item.status-ditolak { border-left-color: #dc2626; background: #fef2f2; }
    .history-alat-name { font-weight: bold; color: #333; margin-bottom: 0.5rem; }
    .history-meta { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; }
    .history-status { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-top: 0.5rem; }
    .status-menunggu { background: #fef3c7; color: #92400e; }
    .status-disetujui { background: #d1fae5; color: #065f46; }
    .status-ditolak { background: #fee2e2; color: #991b1b; }
    .empty-history { text-align: center; padding: 3rem 1rem; color: #999; }
    .empty-history-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
    .empty-state { text-align: center; padding: 3rem; color: #666; }

    #alat-container { display: none; }
    #alat-container.show { display: block; }
</style>
@endpush

@section('content')
    <div class="page-container">
        <div class="grid-layout">
            {{-- Left: Lab Selection & Alat List --}}
            <div>
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3>🔬 Pilih Laboratorium</h3>
                    </div>
                    <div class="card-body">
                        <p style="color: #666; margin-bottom: 1.5rem;">Pilih laboratorium untuk melihat daftar alat yang tersedia</p>
                        <div class="form-group">
                            <label class="form-label" for="lab_select">Laboratorium *</label>
                            <select class="form-control" id="lab_select" onchange="selectLabDropdown()" required>
                                <option value="">-- Pilih Laboratorium --</option>
                                @foreach($labs as $labItem)
                                    <option value="{{ $labItem->id }}">{{ $labItem->Nama_Laboratorium }} -  {{ $labItem->floor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="alat-container">
                    <div class="card">
                        <div class="card-header">
                            <h3>🔧 Pilih Alat yang Akan Dipinjam</h3>
                        </div>
                        <div class="card-body">
                            <p style="color: #666; margin-bottom: 1.5rem;">
                                Laboratorium: <strong id="selected-lab-name">-</strong>
                            </p>
                            <div class="alat-grid" id="alat-list">
                                {{-- Alat loaded via JS --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: History --}}
            <div>
                <div class="card history-card">
                    <div class="card-body">
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
                                        <span>📦 Jumlah: {{ $peminjaman->jumlah ?? 1 }} unit</span>
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
    </div>

    {{-- Modal Peminjaman --}}
    <div id="modal-peminjaman" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2>📋 Form Peminjaman Alat</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                @if($lab)
                    <form action="" method="POST" id="form-peminjaman">
                        @csrf

                        <input type="hidden" id="modal_lab_id" name="daftar_lab_id">
                        <input type="hidden" id="modal_alat_id" name="alat_lab_id">

                        <div class="modal-alat-info">
                            <h3 id="modal-alat-name">-</h3>
                            <p><strong>Laboratorium:</strong> <span id="modal-lab-name">-</span></p>
                            <p><strong>Stok Tersedia:</strong> <span id="modal-alat-stock">-</span> unit</p>
                        </div>

                        <!-- Risk Assessment selection removed for dosen peminjaman per request -->

                        <div class="form-group">
                            <label class="form-label" for="modal_jumlah">Jumlah Alat *</label>
                            <input type="number" class="form-control" id="modal_jumlah" name="jumlah" required min="1" value="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="modal_tanggal_pinjam">Tanggal Pinjam *</label>
                            <input type="date" class="form-control" id="modal_tanggal_pinjam" name="tanggal_pinjam" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="modal_tanggal_kembali">Tanggal Kembali *</label>
                            <input type="date" class="form-control" id="modal_tanggal_kembali" name="tanggal_kembali" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="info-box">
                            <strong>📝 Catatan Penting:</strong>
                            <p>• Pastikan Anda mengembalikan alat tepat waktu dan dalam kondisi baik</p>
                            <p>• Kerusakan atau keterlambatan akan dikenakan sanksi sesuai peraturan</p>
                            <p>• Hubungi admin lab jika ada kendala</p>
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
        form.action = "{{ route('dosen.pinjam-alat.store', ':id') }}".replace(':id', selectedLabId);

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

    document.getElementById('modal_tanggal_pinjam').addEventListener('change', function() {
        const pinjamDate = this.value;
        document.getElementById('modal_tanggal_kembali').min = pinjamDate;
    });
</script>
@endpush