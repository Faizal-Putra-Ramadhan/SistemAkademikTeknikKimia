<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMahasiswa;
use App\Models\PeminjamanAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengembalianAlatController extends Controller
{
    /**
     * Tampilkan daftar pengajuan pengembalian alat
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil peminjaman yang sudah mengajukan pengembalian tapi belum disetujui
        $labNames = $user->laborans()->pluck('Laboratorium');
        if ($labNames->isEmpty()) {
            abort(403, 'Anda tidak memiliki akses laboratorium.');
        }

        $pengajuan_pengembalian = PeminjamanAlat::with(['alatLab.daftarLab'])
            ->where('pengajuan_pengembalian', true)
            ->whereNull('pengembalian_disetujui')
            ->whereHas('alatLab.daftarLab', function ($q) use ($labNames) {
                $q->whereIn('Nama_Laboratorium', $labNames);
            })
            ->orderBy('tanggal_pengajuan_pengembalian', 'desc')
            ->get();

        // Ambil riwayat pengembalian yang sudah diproses
        $riwayat_pengembalian = PeminjamanAlat::with(['alatLab.daftarLab'])
            ->where('pengajuan_pengembalian', true)
            ->whereNotNull('pengembalian_disetujui')
            ->whereHas('alatLab.daftarLab', function ($q) use ($labNames) {
                $q->whereIn('Nama_Laboratorium', $labNames);
            })
            ->orderBy('tanggal_persetujuan_pengembalian', 'desc')
            ->paginate(20);

        return view('laboran.pengembalian-alat.index', compact(
            'user',
            'pengajuan_pengembalian',
            'riwayat_pengembalian'
        ));
    }

    /**
     * Tampilkan detail pengajuan pengembalian
     */
    public function show($id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanAlat::with(['alatLab.daftarLab'])
            ->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->alatLab?->daftarLab?->Nama_Laboratorium);

        return view('laboran.pengembalian-alat.detail', compact('user', 'peminjaman'));
    }

    /**
     * Setujui pengembalian alat
     */
    public function setujui(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanAlat::with('alatLab')->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->alatLab?->daftarLab?->Nama_Laboratorium);

        // Validasi: sudah mengajukan pengembalian
        if (! $peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Mahasiswa belum mengajukan pengembalian untuk alat ini.');
        }

        // Validasi: belum pernah disetujui/ditolak
        if ($peminjaman->pengembalian_disetujui !== null) {
            return back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_laboran' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Update status pengembalian
            $peminjaman->update([
                'pengembalian_disetujui' => true,
                'tanggal_persetujuan_pengembalian' => now(),
                'laboran_nama' => $user->Nama,
                'catatan_laboran' => $request->catatan_laboran,
                'status' => 'dikembalikan', // Update status peminjaman
            ]);

            // Kembalikan stok alat sebanyak jumlah yang dipinjam
            $peminjaman->alatLab->increment('jumlah_tersedia', $peminjaman->jumlah);

            // Catat aktivitas mahasiswa
            AktivitasMahasiswa::create([
                'user_nama' => $peminjaman->user_nama,
                'daftar_lab_id' => $peminjaman->alatLab->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Alat Disetujui',
                'keterangan' => 'Pengembalian alat "'.$peminjaman->alatLab->nama_alat
                    .'" telah disetujui oleh '.$user->Nama
                    .' (Kondisi: '.ucfirst($peminjaman->kondisi_barang).')',
                'waktu' => now(),
            ]);

            DB::commit();

            Log::info('Pengembalian alat disetujui', [
                'peminjaman_id' => $peminjaman->id,
                'alat' => $peminjaman->alatLab->nama_alat,
                'mahasiswa' => $peminjaman->user_nama,
                'laboran' => $user->Nama,
            ]);

            return redirect()->route('laboran.pengembalian-alat.index')
                ->with('success', 'Pengembalian alat berhasil disetujui! Stok alat telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menyetujui pengembalian alat', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
            ]);

            return back()->with('error', 'Gagal menyetujui pengembalian: '.$e->getMessage());
        }
    }

    /**
     * Tolak pengembalian alat
     */
    public function tolak(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanAlat::with('alatLab')->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->alatLab?->daftarLab?->Nama_Laboratorium);

        // Validasi: sudah mengajukan pengembalian
        if (! $peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Mahasiswa belum mengajukan pengembalian untuk alat ini.');
        }

        // Validasi: belum pernah disetujui/ditolak
        if ($peminjaman->pengembalian_disetujui !== null) {
            return back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_laboran' => 'required|string|max:500',
        ], [
            'catatan_laboran.required' => 'Alasan penolakan harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Update status pengembalian
            $peminjaman->update([
                'pengembalian_disetujui' => false,
                'tanggal_persetujuan_pengembalian' => now(),
                'laboran_nama' => $user->Nama,
                'catatan_laboran' => $request->catatan_laboran,
                'pengajuan_pengembalian' => false, // Reset agar bisa mengajukan lagi
            ]);

            // Catat aktivitas mahasiswa
            AktivitasMahasiswa::create([
                'user_nama' => $peminjaman->user_nama,
                'daftar_lab_id' => $peminjaman->alatLab->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Alat Ditolak',
                'keterangan' => 'Pengembalian alat "'.$peminjaman->alatLab->nama_alat
                    .'" ditolak oleh '.$user->Nama
                    .'. Alasan: '.$request->catatan_laboran,
                'waktu' => now(),
            ]);

            DB::commit();

            Log::info('Pengembalian alat ditolak', [
                'peminjaman_id' => $peminjaman->id,
                'alat' => $peminjaman->alatLab->nama_alat,
                'mahasiswa' => $peminjaman->user_nama,
                'laboran' => $user->Nama,
                'alasan' => $request->catatan_laboran,
            ]);

            return redirect()->route('laboran.pengembalian-alat.index')
                ->with('success', 'Pengembalian alat ditolak. Mahasiswa dapat mengajukan kembali.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menolak pengembalian alat', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
            ]);

            return back()->with('error', 'Gagal menolak pengembalian: '.$e->getMessage());
        }
    }

    private function authorizeLaboranLab($user, ?string $labName): void
    {
        if (! $labName) {
            abort(404, 'Laboratorium tidak ditemukan.');
        }

        $hasAccess = $user->laborans()
            ->where('Laboratorium', $labName)
            ->exists();

        if (! $hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke laboratorium ini.');
        }
    }
}
