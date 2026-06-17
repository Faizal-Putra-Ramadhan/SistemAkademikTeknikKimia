<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return view('pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
            'status' => 'required|in:draft,publish'
        ]);

        $pengumuman = Pengumuman::create([
            'judul'   => $request->judul,
            'isi'     => $request->isi,
            'status'  => $request->status,
            'author'  => 'Administrator'
        ]);

        ActivityLog::create([
            'user_name'   => 'Administrator',
            'action'      => 'Membuat Pengumuman',
            'description' => $pengumuman->judul,
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
            'status' => 'required|in:draft,publish'
        ]);

        $pengumuman->update($request->only(['judul', 'isi', 'status']));

        ActivityLog::create([
            'user_name'   => 'Administrator',
            'action'      => 'Mengedit Pengumuman',
            'description' => $pengumuman->judul,
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $judul = $pengumuman->judul;
        $pengumuman->delete();

        ActivityLog::create([
            'user_name'   => 'Administrator',
            'action'      => 'Menghapus Pengumuman',
            'description' => $judul,
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', 'Pengumuman berhasil dihapus!');
    }
}