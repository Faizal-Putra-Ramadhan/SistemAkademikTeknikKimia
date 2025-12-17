<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\DaftarLab;
use Illuminate\Http\Request;

class PeminjamanRuanganController extends Controller
{
    public function create($id)
    {
        $lab = DaftarLab::findOrFail($id);
        return view('mahasiswa.pinjam-ruangan', compact('lab'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'keperluan'    => 'required|string|max:500',
        ]);

        // Cek bentrokan jadwal
        $bentrok = PeminjamanRuangan::where('daftar_lab_id', $id)
            ->where('tanggal', $request->tanggal)
            ->where('status', '!=', 'ditolak')
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$request->jam_mulai])
                  ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$request->jam_selesai]);
            })->exists();

        if ($bentrok) {
            return back()->with('error', 'Maaf, ruangan sudah dibooking pada jam tersebut!');
        }

        PeminjamanRuangan::create([
            'user_nama'      => $request->user_nama ?? 'Rudi Hartono',
            'daftar_lab_id'  => $id,
            'tanggal'        => $request->tanggal,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
            'keperluan'      => $request->keperluan,
            'status'         => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengajuan peminjaman ruangan berhasil! Menunggu persetujuan admin.');
    }
}