<!-- Modal Ajukan Pengembalian Ruangan -->
<div id="modalPengembalianRuangan" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
            <h2>🏢 Ajukan Pengembalian Ruangan</h2>
            <button class="modal-close" onclick="closePengembalianRuanganModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-alat-info" id="pengembalianRuanganInfo" style="border-left-color: #17a2b8;">
                <!-- Info ruangan akan diisi oleh JavaScript -->
            </div>

            <form id="formPengembalianRuangan" method="POST" action="">
                @csrf
                
                <div class="form-group">
                    <label for="kondisi_ruangan">
                        <span style="color: red;">*</span> Kondisi Ruangan Saat Dikembalikan
                    </label>
                    <select id="kondisi_ruangan" name="kondisi_ruangan" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">✅ Baik (Bersih dan rapi)</option>
                        <option value="perlu pembersihan">🧹 Perlu Pembersihan</option>
                        <option value="rusak">❌ Ada Kerusakan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="keterangan_pengembalian_ruangan">Keterangan (Opsional)</label>
                    <textarea 
                        id="keterangan_pengembalian_ruangan" 
                        name="keterangan_pengembalian" 
                        rows="4" 
                        placeholder="Tambahkan catatan jika ada (misalnya: area yang perlu dibersihkan, kerusakan yang terjadi, dll)"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;"
                    ></textarea>
                </div>

                <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
                    <strong>ℹ️ Perhatian:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        <li>Pastikan ruangan dalam kondisi bersih dan rapi</li>
                        <li>Matikan semua peralatan elektronik</li>
                        <li>Laporkan jika ada kerusakan yang terjadi</li>
                        <li>Pengembalian akan diverifikasi oleh laboran</li>
                    </ul>
                </div>

                <div class="btn-group" style="margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePengembalianRuanganModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn" style="background: #17a2b8; color: white;">
                        🏢 Ajukan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPeminjamanRuanganId = null;

function openPengembalianRuanganModal(peminjamanId, namaRuangan, tanggalMulai, tanggalSelesai) {
    currentPeminjamanRuanganId = peminjamanId;
    
    // Update info ruangan
    document.getElementById('pengembalianRuanganInfo').innerHTML = `
        <h3 style="color: #17a2b8;">${namaRuangan}</h3>
        <p><strong>Periode Peminjaman:</strong></p>
        <p>📅 ${tanggalMulai} s/d ${tanggalSelesai}</p>
        <p style="color: #666; margin-top: 0.5rem;">
            Silakan isi formulir di bawah untuk mengajukan pengembalian ruangan ini.
        </p>
    `;
    
    // Update form action
    const form = document.getElementById('formPengembalianRuangan');
    form.action = `/mahasiswa/peminjaman-ruangan/${peminjamanId}/ajukan-pengembalian`;
    
    // Reset form
    form.reset();
    
    // Show modal
    document.getElementById('modalPengembalianRuangan').classList.add('show');
}

function closePengembalianRuanganModal() {
    document.getElementById('modalPengembalianRuangan').classList.remove('show');
    currentPeminjamanRuanganId = null;
}

// Close modal when clicking outside
document.getElementById('modalPengembalianRuangan').addEventListener('click', function(e) {
    if (e.target === this) {
        closePengembalianRuanganModal();
    }
});
</script>

<style>
.alert-info {
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>