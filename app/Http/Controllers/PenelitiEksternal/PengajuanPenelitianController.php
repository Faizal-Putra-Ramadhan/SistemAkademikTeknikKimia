<?php

namespace App\Http\Controllers\PenelitiEksternal;

use App\Http\Controllers\Controller;
use App\Models\DaftarLab;
use App\Models\PengajuanPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPenelitianController extends Controller
{
    /**
     * Show the form for creating a new research proposal
     */
    public function create($id)
    {
        $lab = DaftarLab::findOrFail($id);
        $user = Auth::user();

        return view('peneliti-eksternal.pengajuan-penelitian', compact('lab', 'user'));
    }

    /**
     * Store a newly created research proposal
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'durasi' => 'required|integer|min:1',
            'supervisor' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanPenelitian::create([
                'lab_id' => $id,
                'user_nama' => $user->Nama,
                'user_id' => $user->id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'durasi' => $request->durasi,
                'supervisor' => $request->supervisor,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('peneliti-eksternal.dashboard')
                ->with('success', 'Pengajuan penelitian berhasil diajukan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengajukan penelitian: '.$e->getMessage());
        }
    }
}
