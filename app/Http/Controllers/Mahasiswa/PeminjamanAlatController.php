<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanAlat;
use App\Models\AlatLab;
use App\Models\AktivitasMahasiswa;
use Illuminate\Http\Request;

class PeminjamanAlatController extends Controller
{
    // Tampilkan form peminjaman
    public function create($lab_id)
    {
        $lab = \App\Models\DaftarLab::with('alatLabs')->findOrFail($lab_id);
        return view('mahasiswa.pinjam-alat', compact('lab'));
    }

    // Proses submit peminjaman
    public function store(Request $request, $lab_id)
    {
        $request->validate([
            'alat_lab_id'     => 'required|exists:alat_labs,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        $alat = AlatLab::findOrFail($request->alat_lab_id);

        // Cek stok
        if ($alat->jumlah_tersedia <= 0) {
            return back()->with('error', 'Maaf, "' . $alat->nama_alat . '" sedang tidak tersedia!');
        }

        // Kurangi stok
        $alat->decrement('jumlah_tersedia');

        // Simpan peminjaman
        PeminjamanAlat::create([
            'user_nama'       => $request->user_nama ?? 'Mahasiswa',
            'alat_lab_id'     => $request->alat_lab_id,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'menunggu',
        ]);

        // Catat ke aktivitas mahasiswa
        AktivitasMahasiswa::create([
    'user_nama'       => $request->user_nama ?? 'Mahasiswa',
    'daftar_lab_id'   => $lab_id,
    'jenis_aktivitas' => 'Peminjaman Alat',
    'keterangan'      => 'Mengajukan peminjaman: ' . $alat->nama_alat,
    'waktu'           => now(), // PAKAI KOLOM 'waktu' YANG SUDAH ADA
]);

        return redirect()->route('mahasiswa.alat', $lab_id)
            ->with('success', 'Peminjaman alat berhasil diajukan! Menunggu persetujuan admin.');
    }
}