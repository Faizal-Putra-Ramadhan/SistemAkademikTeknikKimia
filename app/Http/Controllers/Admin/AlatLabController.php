<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatLab;
use App\Models\DaftarLab;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatLabController extends Controller
{
    public function index()
    {
        $alats = AlatLab::with('daftarLab')->paginate(15);
        return view('admin.alat-lab.index', compact('alats'));
    }

    public function create()
    {
        $labs = DaftarLab::all();
        return view('admin.alat-lab.create', compact('labs'));
    }

    // STORE
public function store(Request $request)
{
    $request->validate([
        'daftar_lab_id'     => 'required|exists:daftar_labs,id',
        'nama_alat'         => 'required|string|max:255',
        'deskripsi'         => 'nullable|string',
        'jumlah_tersedia'   => 'required|integer|min:0',
        'foto'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('alat-lab', 'public');
    }

    $alat = AlatLab::create($data);

    // FIX: Load relasi dulu sebelum dipakai!
    $alat->load('daftarLab');

    ActivityLog::create([
        'user_name'   => 'Administrator',
        'action'      => 'Menambah Alat Lab',
        'description' => $alat->nama_alat . ' - ' . ($alat->daftarLab?->Nama_Laboratorium ?? 'Lab Tidak Ditemukan'),
        'ip_address'  => request()->ip(),
    ]);

    return redirect()->route('admin.alat-lab.index')->with('success', 'Alat lab berhasil ditambahkan!');
}

public function edit(AlatLab $alat)
{
    $labs = DaftarLab::all(); // WAJIB ADA INI!
    return view('admin.alat-lab.edit', compact('alat', 'labs'));
}

    public function destroy(AlatLab $alat)
    {
        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }

        $nama = $alat->nama_alat;
        $lab  = $alat->daftarLab->Nama_Laboratorium;

        $alat->delete();

        ActivityLog::create([
            'user_name'   => 'Administrator',
            'action'      => 'Menghapus Alat Lab',
            'description' => "$nama - $lab",
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', 'Alat lab berhasil dihapus!');
    }

    // UPDATE
public function update(Request $request, AlatLab $alat)
{
    $request->validate([
        'daftar_lab_id'     => 'required|exists:daftar_labs,id',
        'nama_alat'         => 'required|string|max:255',
        'deskripsi'         => 'nullable|string',
        'jumlah_tersedia'   => 'required|integer|min:0',
        'foto'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('foto')) {
        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }
        $data['foto'] = $request->file('foto')->store('alat-lab', 'public');
    }

    $alat->update($data);

    // FIX: Load relasi setelah update
    $alat->load('daftarLab');

    ActivityLog::create([
        'user_name'   => 'Administrator',
        'action'      => 'Mengubah Alat Lab',
        'description' => $alat->nama_alat . ' - ' . ($alat->daftarLab?->Nama_Laboratorium ?? 'Lab Tidak Ditemukan'),
        'ip_address'  => request()->ip(),
    ]);

    return redirect()->route('admin.alat-lab.index')->with('success', 'Alat lab berhasil diperbarui!');
}
}