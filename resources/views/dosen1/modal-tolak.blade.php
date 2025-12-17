<!-- Modal Tolak Pengajuan -->
<div id="tolakModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-red-700 mb-6">Tolak Pengajuan</h3>
        <form id="tolakForm" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-3">Alasan Penolakan</label>
                <textarea name="alasan" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-red-500 focus:outline-none" rows="5" placeholder="Tulis alasan penolakan dengan jelas..."></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeTolakModal()" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg">
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTolakModal(id) {
    document.getElementById('tolakForm').action = `/dosen/pengajuan/${id}/tolak`;
    document.getElementById('tolakModal').classList.remove('hidden');
    document.getElementById('tolakModal').classList.add('flex');
}

function closeTolakModal() {
    document.getElementById('tolakModal').classList.add('hidden');
    document.getElementById('tolakModal').classList.remove('flex');
}
</script>