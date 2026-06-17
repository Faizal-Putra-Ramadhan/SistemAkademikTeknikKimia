<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PengajuanPenelitian; // atau User, tergantung model dosen kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // INI YANG BENAR!

class PengajuanPenelitianController extends Controller
{
    public function create($id)
    {
        $lab = DaftarLab::findOrFail($id);
        $labs = DaftarLab::penelitian()->get();
        $user = Auth::user();

        // Ambil dosen (termasuk user yang punya role Dosen + role lain)
        $dosens = DaftarUser::withDosenRole()
            ->orderBy('Nama')
            ->get();

        $penelitians = PengajuanPenelitian::with('daftarLab')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        return view('mahasiswa.pengajuan-penelitian', compact('lab', 'dosens', 'user', 'labs', 'penelitians'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'judul_penelitian' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'dosen_id' => 'required|exists:daftar_users,id', // atau users, tergantung tabel
        ]);

        $dosen = DaftarUser::withDosenRole()
            ->findOrFail($request->dosen_id);

        PengajuanPenelitian::create([
            'user_id' => Auth::id(),
            'user_nama' => Auth::user()->Nama ?? 'Mahasiswa',
            'daftar_lab_id' => $id,
            'judul_penelitian' => $request->judul_penelitian,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'dosen_pembimbing' => $dosen->Nama,
            'dosen_id' => $request->dosen_id,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.aktivitas', $id)
            ->with('success', "Pengajuan berhasil! Menunggu persetujuan dari {$dosen->Nama}");
    }
}
