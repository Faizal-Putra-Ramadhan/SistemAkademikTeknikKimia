<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AlatLab;
use App\Models\DaftarLab;
use App\Models\StockGroup;
use Illuminate\Http\Request;

class AlatLabController extends Controller
{
    public function index()
    {
        $alats = AlatLab::with(['stockGroup', 'daftarLab'])->paginate(15);

        return view('admin.alat-lab.index', compact('alats'));
    }

    public function create()
    {
        // Ambil data unik floor dan lab_type untuk dropdown
        $floors = DaftarLab::select('floor')->distinct()->pluck('floor');
        $labTypes = ['penelitian', 'pendidikan'];
        $daftarLabs = DaftarLab::orderBy('Nama_Laboratorium')->get();

        return view('admin.alat-lab.create', compact('floors', 'labTypes', 'daftarLabs'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'scope' => 'required|in:all_labs,this_lab',
            'floor' => 'required_if:scope,all_labs|nullable|string',
            'lab_type' => 'required_if:scope,all_labs|nullable|in:penelitian,pendidikan',
            'daftar_lab_id' => 'required_if:scope,this_lab|nullable|exists:daftar_labs,id',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah_tersedia' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->scope === 'this_lab') {
            $lab = DaftarLab::findOrFail($request->daftar_lab_id);
            $stockGroup = StockGroup::firstOrCreate([
                'floor' => $lab->floor,
                'lab_type' => $lab->lab_type,
            ]);
            $daftar_lab_id = $lab->id;
        }
        else {
            $stockGroup = StockGroup::firstOrCreate([
                'floor' => $request->floor,
                'lab_type' => $request->lab_type,
            ]);
            $daftar_lab_id = null;
        }

        $data = $request->only(['nama_alat', 'deskripsi', 'jumlah_tersedia']);
        $data['stock_group_id'] = $stockGroup->id;
        $data['daftar_lab_id'] = $daftar_lab_id;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);
            $data['foto'] = $filename;
        }

        $alat = AlatLab::create($data);

        ActivityLog::create([
            'user_name' => 'Administrator',
            'action' => 'Menambah Alat Lab',
            'description' => $alat->nama_alat . ($daftar_lab_id ? ' - Lab: ' . $lab->Nama_Laboratorium : " - Lantai: {$stockGroup->floor}, Jenis: {$stockGroup->lab_type}"),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.alat-lab.index')->with('success', 'Alat lab berhasil ditambahkan!');
    }

    public function edit(AlatLab $alat)
    {
        $floors = DaftarLab::select('floor')->distinct()->pluck('floor');
        $labTypes = ['penelitian', 'pendidikan'];
        $daftarLabs = DaftarLab::orderBy('Nama_Laboratorium')->get();

        return view('admin.alat-lab.edit', compact('alat', 'floors', 'labTypes', 'daftarLabs'));
    }

    public function destroy(AlatLab $alat)
    {
        if ($alat->foto && file_exists(public_path('uploads/' . $alat->foto))) {
            unlink(public_path('uploads/' . $alat->foto));
        }

        $nama = $alat->nama_alat;
        $info = $alat->stockGroup ? "Lantai: {$alat->stockGroup->floor}, Jenis: {$alat->stockGroup->lab_type}" : ($alat->daftarLab->Nama_Laboratorium ?? 'N/A');

        $alat->delete();

        ActivityLog::create([
            'user_name' => 'Administrator',
            'action' => 'Menghapus Alat Lab',
            'description' => "$nama - $info",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Alat lab berhasil dihapus!');
    }

    // UPDATE
    public function update(Request $request, AlatLab $alat)
    {
        $request->validate([
            'scope' => 'required|in:all_labs,this_lab',
            'floor' => 'required_if:scope,all_labs|nullable|string',
            'lab_type' => 'required_if:scope,all_labs|nullable|in:penelitian,pendidikan',
            'daftar_lab_id' => 'required_if:scope,this_lab|nullable|exists:daftar_labs,id',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah_tersedia' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->scope === 'this_lab') {
            $lab = DaftarLab::findOrFail($request->daftar_lab_id);
            $stockGroup = StockGroup::firstOrCreate([
                'floor' => $lab->floor,
                'lab_type' => $lab->lab_type,
            ]);
            $daftar_lab_id = $lab->id;
        }
        else {
            $stockGroup = StockGroup::firstOrCreate([
                'floor' => $request->floor,
                'lab_type' => $request->lab_type,
            ]);
            $daftar_lab_id = null;
        }

        $data = $request->except(['floor', 'lab_type', 'scope', 'daftar_lab_id']);
        $data['stock_group_id'] = $stockGroup->id;
        $data['daftar_lab_id'] = $daftar_lab_id;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);
            $data['foto'] = $filename;

            // Hapus foto lama jika ada
            if ($alat->foto && file_exists(public_path('uploads/' . $alat->foto))) {
                unlink(public_path('uploads/' . $alat->foto));
            }
        }

        $alat->update($data);

        ActivityLog::create([
            'user_name' => 'Administrator',
            'action' => 'Mengubah Alat Lab',
            'description' => $alat->nama_alat . ($daftar_lab_id ? ' - Lab: ' . $lab->Nama_Laboratorium : " - Lantai: {$stockGroup->floor}, Jenis: {$stockGroup->lab_type}"),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.alat-lab.index')->with('success', 'Alat lab berhasil diperbarui!');
    }
}
