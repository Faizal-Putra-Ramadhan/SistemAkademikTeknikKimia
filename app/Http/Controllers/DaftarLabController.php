<?php

namespace App\Http\Controllers;

use App\Models\DaftarLab;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

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
    return view('daftar-lab.create');
}

public function store(Request $request)
    {
        $request->validate([
            'Nama_Laboratorium'   => 'required|string|max:255|unique:daftar_labs,Nama_Laboratorium',
            'Kepala_Labolatorium' => 'required|string|max:255',
            'Admin_Laboratorium'  => 'required|string|max:255',
            'Safety_Officer'      => 'required|string|max:255',
            'email_lab'           => 'required|email|unique:daftar_labs,email_lab',
        ]);

        $lab = DaftarLab::create($request->all());

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name'    => 'Kevin Morgan', // nanti diganti auth()->user()->name
            'action'       => 'Menambah Laboratorium',
            'description'  => $lab->Nama_Laboratorium,
            'ip_address'   => request()->ip(),
        ]);

        return redirect()->route('daftar-lab.index')->with('success', 'Laboratorium berhasil ditambahkan!');
    }

public function edit(DaftarLab $lab)
{
    return view('daftar-lab.edit', compact('lab'));
}

public function update(Request $request, DaftarLab $lab)
    {
        $request->validate([
            'Nama_Laboratorium'   => 'required|string|max:255|unique:daftar_labs,Nama_Laboratorium,' . $lab->id,
            'Kepala_Labolatorium' => 'required|string|max:255',
            'Admin_Laboratorium'  => 'required|string|max:255',
            'Safety_Officer'      => 'required|string|max:255',
            'email_lab'           => 'required|email|unique:daftar_labs,email_lab,' . $lab->id,
        ]);

        $lab->update($request->all());

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name'    => 'Kevin Morgan',
            'action'       => 'Mengubah Laboratorium',
            'description'  => $lab->Nama_Laboratorium,
            'ip_address'   => request()->ip(),
        ]);

        return redirect()->route('daftar-lab.index')->with('success', 'Laboratorium berhasil diperbarui!');
    }

public function destroy(DaftarLab $lab)
    {
        $namaLab = $lab->Nama_Laboratorium; // simpan dulu sebelum dihapus

        $lab->delete();

        // LOG OTOMATIS
        ActivityLog::create([
            'user_name'    => 'Kevin Morgan',
            'action'       => 'Menghapus Laboratorium',
            'description'  => $namaLab,
            'ip_address'   => request()->ip(),
        ]);

        return back()->with('success', 'Laboratorium berhasil dihapus!');
    }
}