<?php

namespace App\Http\Controllers;

use App\Enums\LabType;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use App\Models\StockGroup;
use Illuminate\Http\Request;

class DaftarLabController extends Controller
{
    // app/Http/Controllers/DaftarLabController.php
    public function index()
    {
        $daftar_labs = DaftarLab::paginate(10);

        return view('daftar-lab.index', compact('daftar_labs'));
    }

    public function create()
    {
        $kepalaLabList = DaftarUser::where(function ($query) {
            $query->where('Role_User', 'Kepala Laboratorium')
                ->orWhereHas('roles', fn ($q) => $q->where('name', 'Kepala Laboratorium'));
        })->get()->unique('id');

        $labTypes = LabType::values();

        return view('daftar-lab.create', compact('kepalaLabList', 'labTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Laboratorium' => 'required|string|max:255|unique:daftar_labs,Nama_Laboratorium',
            'floor' => 'required|string|max:50',
            'lab_type' => 'required|in:'.implode(',', LabType::values()),
            'Kepala_Labolatorium' => 'required|string|max:255',
            'Admin_Laboratorium' => 'required|string|max:255',
            'email_lab' => 'nullable|email|unique:daftar_labs,email_lab',
        ]);

        $data = $request->only(['Nama_Laboratorium', 'floor', 'lab_type', 'Kepala_Labolatorium', 'Admin_Laboratorium']);
        $data['email_lab'] = $request->filled('email_lab') ? $request->email_lab : null;
        $data['stock_group_id'] = StockGroup::firstOrCreate([
            'floor' => $data['floor'],
            'lab_type' => $data['lab_type'],
        ])->id;
        $lab = DaftarLab::create($data);

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name' => auth()->user()->Nama ?? 'System',
            'action' => 'Menambah Laboratorium',
            'description' => $lab->Nama_Laboratorium,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.daftar-lab.index')->with('success', 'Laboratorium berhasil ditambahkan!');
    }

    public function edit(DaftarLab $lab)
    {
        $kepalaLabList = DaftarUser::where(function ($query) {
            $query->where('Role_User', 'Kepala Laboratorium')
                ->orWhereHas('roles', fn ($q) => $q->where('name', 'Kepala Laboratorium'));
        })->get()->unique('id');

        $labTypes = LabType::values();

        return view('daftar-lab.edit', compact('lab', 'kepalaLabList', 'labTypes'));
    }

    public function update(Request $request, DaftarLab $lab)
    {
        $request->validate([
            'Nama_Laboratorium' => 'required|string|max:255|unique:daftar_labs,Nama_Laboratorium,'.$lab->id,
            'floor' => 'required|string|max:50',
            'lab_type' => 'required|in:'.implode(',', LabType::values()),
            'Kepala_Labolatorium' => 'required|string|max:255',
            'Admin_Laboratorium' => 'required|string|max:255',
            'email_lab' => 'nullable|email|unique:daftar_labs,email_lab,'.$lab->id,
        ]);

        $data = $request->only(['Nama_Laboratorium', 'floor', 'lab_type', 'Kepala_Labolatorium', 'Admin_Laboratorium']);
        $data['email_lab'] = $request->filled('email_lab') ? $request->email_lab : null;
        $data['stock_group_id'] = StockGroup::firstOrCreate([
            'floor' => $data['floor'],
            'lab_type' => $data['lab_type'],
        ])->id;
        $lab->update($data);

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name' => auth()->user()->Nama ?? 'System',
            'action' => 'Mengubah Laboratorium',
            'description' => $lab->Nama_Laboratorium,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.daftar-lab.index')->with('success', 'Laboratorium berhasil diperbarui!');
    }

    public function destroy(DaftarLab $lab)
    {
        $namaLab = $lab->Nama_Laboratorium; // simpan dulu sebelum dihapus

        // Hapus semua laboran yang terdaftar di laboratorium ini (beserta akun daftar_users-nya)
        $laborans = DaftarLaboranLaboratorium::where('Laboratorium', $namaLab)->get();
        foreach ($laborans as $laboran) {
            $userId = $laboran->UserID;
            $laboran->delete();
            DaftarUser::where('UserID', $userId)->delete();
        }

        $lab->delete();

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name' => auth()->user()->Nama ?? 'System',
            'action' => 'Menghapus Laboratorium',
            'description' => $namaLab.(count($laborans) > 0 ? ' (beserta '.count($laborans).' laboran)' : ''),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Laboratorium berhasil dihapus!'.(count($laborans) > 0 ? ' '.count($laborans).' laboran terkait juga dihapus.' : ''));
    }
}
