<!-- Modal Ajukan Pengembalian Alat -->
<div id="modalPengembalianAlat" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <h2>📦 Ajukan Pengembalian Alat</h2>
            <button class="modal-close" onclick="closePengembalianAlatModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-alat-info" id="pengembalianAlatInfo">
                <!-- Info alat akan diisi oleh JavaScript -->
            </div>

            <form id="formPengembalianAlat" method="POST" action="">
                @csrf
                
                <div class="form-group">
                    <label for="kondisi_barang">
                        <span style="color: red;">*</span> Kondisi Barang Saat Dikembalikan
                    </label>
                    <select id="kondisi_barang" name="kondisi_barang" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">✅ Baik (Tidak ada kerusakan)</option>
                        <option value="rusak ringan">⚠️ Rusak Ringan (Ada kerusakan kecil)</option>
                        <option value="rusak berat">❌ Rusak Berat (Kerusakan parah)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="keterangan_pengembalian">Keterangan (Opsional)</label>
                    <textarea 
                        id="keterangan_pengembalian" 
                        name="keterangan_pengembalian" 
                        rows="4" 
                        placeholder="Tambahkan catatan jika ada (misalnya: kerusakan yang terjadi, kondisi khusus, dll)"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;"
                    ></textarea>
                </div>

                <div class="alert alert-warning" style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
                    <strong>⚠️ Perhatian:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        <li>Pastikan alat sudah dibersihkan sebelum dikembalikan</li>
                        <li>Laporkan kondisi yang sebenarnya untuk transparansi</li>
                        <li>Pengembalian akan diverifikasi oleh laboran</li>
                    </ul>
                </div>

                <div class="btn-group" style="margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePengembalianAlatModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn" style="background: #dc3545; color: white;">
                        📦 Ajukan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPeminjamanAlatId = null;

function openPengembalianAlatModal(peminjamanId, namaAlat, tanggalPinjam) {
    currentPeminjamanAlatId = peminjamanId;
    
    // Update info alat
    document.getElementById('pengembalianAlatInfo').innerHTML = `
        <h3>${namaAlat}</h3>
        <p><strong>Tanggal Pinjam:</strong> ${tanggalPinjam}</p>
        <p style="color: #666; margin-top: 0.5rem;">
            Silakan isi formulir di bawah untuk mengajukan pengembalian alat ini.
        </p>
    `;
    
    // Update form action
    const form = document.getElementById('formPengembalianAlat');
    form.action = `/mahasiswa/peminjaman-alat/${peminjamanId}/ajukan-pengembalian`;
    
    // Reset form
    form.reset();
    
    // Show modal
    document.getElementById('modalPengembalianAlat').classList.add('show');
}

function closePengembalianAlatModal() {
    document.getElementById('modalPengembalianAlat').classList.remove('show');
    currentPeminjamanAlatId = null;
}

// Close modal when clicking outside
document.getElementById('modalPengembalianAlat').addEventListener('click', function(e) {
    if (e.target === this) {
        closePengembalianAlatModal();
    }
});
</script>

<style>
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
}

.modal-overlay.show {
    display: flex;
}

.alert-warning {
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>