<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium; // Tambahkan ini
use App\Models\DaftarUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Support\Facades\Validator;

class TambahLaboranController extends Controller
{
    /**
     * Display a listing of laborans.
     */
    public function index()
    {
        $daftar_laborans = DaftarLaboranLaboratorium::with('laboratoriums')->orderBy('created_at', 'desc')->paginate(10);

        return view('tambah-laboran.index', compact('daftar_laborans'));
    }

    /**
     * Show the form for creating a new laboran.
     */
    public function create()
    {
        $daftar_labs = DaftarLab::orderBy('Nama_Laboratorium', 'asc')->get();
        $potentialParents = DaftarUser::where('is_primary', true)
            ->orWhereNull('parent_user_id')
            ->orderBy('Nama')
            ->get();

        return view('tambah-laboran.create', compact('daftar_labs', 'potentialParents'));
    }

    /**
     * Store a newly created laboran in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'laboratorium_ids' => 'required|array|min:1',
            'laboratorium_ids.*' => 'exists:daftar_labs,id',
            'Nama_Laboran' => 'required|string|max:255',
            'Phone' => 'required|string|max:20',
            'Email' => 'required|email|max:255|unique:daftar_laboran_laboratoriums,Email|unique:daftar_users,Email',
            'Role_User' => 'required|string|max:255',
            'Password' => 'required|string|min:6',
            'link_to_parent' => 'nullable|exists:daftar_users,id',
        ], [
            'laboratorium_ids.required' => 'Minimal pilih satu laboratorium',
            'laboratorium_ids.array' => 'Format laboratorium tidak valid',
            'laboratorium_ids.min' => 'Minimal pilih satu laboratorium',
            'laboratorium_ids.*.exists' => 'Laboratorium yang dipilih tidak valid',
            'Nama_Laboran.required' => 'Nama laboran wajib diisi',
            'Phone.required' => 'Nomor telepon wajib diisi',
            'Email.required' => 'Email wajib diisi',
            'Email.email' => 'Format email tidak valid',
            'Email.unique' => 'Email sudah terdaftar',
            'Role_User.required' => 'Role user wajib diisi',
            'Password.required' => 'Password wajib diisi',
            'Password.min' => 'Password minimal 6 karakter',
            'link_to_parent.exists' => 'Akun parent tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $userId = $this->generateUserId($request->Role_User);

            // Ambil nama laboratorium pertama untuk backward compatibility (kolom Laboratorium)
            $firstLab = DaftarLab::find($request->laboratorium_ids[0]);
            $firstLabName = $firstLab ? $firstLab->Nama_Laboratorium : '';

            // 1. Simpan ke tabel daftar_laboran_laboratoriums
            $laboran = DaftarLaboranLaboratorium::create([
                'Laboratorium' => $firstLabName, // Backward compatibility
                'Nama_Laboran' => $request->Nama_Laboran,
                'UserID' => $userId,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
                'Role_User' => $request->Role_User,
            ]);

            // 2. Simpan relasi many-to-many ke tabel pivot
            $laboran->laboratoriums()->sync($request->laboratorium_ids);

            // 3. Simpan ke tabel daftar_users (dengan opsi Account Linking)
            $isPrimary = ! $request->filled('link_to_parent');
            $parentUserId = $request->link_to_parent;
            DaftarUser::create([
                'Nama' => $request->Nama_Laboran,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
                'UserID' => $userId,
                'Password' => Hash::make($request->Password),
                'Role_User' => $request->Role_User,
                'foto' => null,
                'is_primary' => $isPrimary,
                'parent_user_id' => $parentUserId,
            ]);

            // LOG TAMBAH LABORAN
            $labNames = DaftarLab::whereIn('id', $request->laboratorium_ids)->pluck('Nama_Laboratorium')->implode(', ');
            ActivityLog::create([
                'user_name' => 'Administrator', // nanti: auth()->user()->name
                'action' => 'Menambah Laboran',
                'description' => "{$laboran->Nama_Laboran} ({$laboran->Role_User}) - {$labNames} - UserID: {$userId}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('admin.tambah-laboran.index')
                ->with('success', "Data laboran berhasil ditambahkan! User ID: {$userId}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan laboran: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified laboran.
     */
    public function edit($id)
    {
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->findOrFail($id);
        $user = DaftarUser::where('UserID', $laboran->UserID)->first();
        $currentLinkToParent = $user ? $user->parent_user_id : null;
        $daftar_labs = DaftarLab::orderBy('Nama_Laboratorium', 'asc')->get();
        $selectedLabIds = $laboran->laboratoriums->pluck('id')->toArray();
        $potentialParents = DaftarUser::where('is_primary', true)
            ->orWhereNull('parent_user_id')
            ->where('UserID', '!=', $laboran->UserID)
            ->orderBy('Nama')
            ->get();

        return view('tambah-laboran.edit', compact('laboran', 'daftar_labs', 'potentialParents', 'currentLinkToParent', 'selectedLabIds'));
    }

    /**
     * Update the specified laboran in storage.
     */
    public function update(Request $request, $id)
    {
        $laboran = DaftarLaboranLaboratorium::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'laboratorium_ids' => 'required|array|min:1',
            'laboratorium_ids.*' => 'exists:daftar_labs,id',
            'Nama_Laboran' => 'required|string|max:255',
            'Phone' => 'required|string|max:20',
            'Email' => 'required|email|max:255|unique:daftar_laboran_laboratoriums,Email,'.$id.'|unique:daftar_users,Email,'.$laboran->UserID.',UserID',
            'Role_User' => 'required|string|max:255',
            'Password' => 'nullable|string|min:6',
            'link_to_parent' => 'nullable|exists:daftar_users,id',
        ], [
            'laboratorium_ids.required' => 'Minimal pilih satu laboratorium',
            'laboratorium_ids.array' => 'Format laboratorium tidak valid',
            'laboratorium_ids.min' => 'Minimal pilih satu laboratorium',
            'laboratorium_ids.*.exists' => 'Laboratorium yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $userId = $laboran->Role_User !== $request->Role_User
                ? $this->generateUserId($request->Role_User)
                : $laboran->UserID;

            // Ambil nama laboratorium pertama untuk backward compatibility (kolom Laboratorium)
            $firstLab = DaftarLab::find($request->laboratorium_ids[0]);
            $firstLabName = $firstLab ? $firstLab->Nama_Laboratorium : '';

            // 1. Update tabel daftar_laboran_laboratoriums
            $laboran->update([
                'Laboratorium' => $firstLabName, // Backward compatibility
                'Nama_Laboran' => $request->Nama_Laboran,
                'UserID' => $userId,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
                'Role_User' => $request->Role_User,
            ]);

            // 2. Update relasi many-to-many ke tabel pivot
            $laboran->laboratoriums()->sync($request->laboratorium_ids);

            // 3. Update tabel daftar_users (termasuk Account Linking)
            $user = DaftarUser::where('UserID', $laboran->UserID)->first();
            if ($user) {
                $isPrimary = ! $request->filled('link_to_parent');
                $parentUserId = $request->link_to_parent;
                $userData = [
                    'Nama' => $request->Nama_Laboran,
                    'Phone' => $request->Phone,
                    'Email' => $request->Email,
                    'UserID' => $userId,
                    'Role_User' => $request->Role_User,
                    'is_primary' => $isPrimary,
                    'parent_user_id' => $parentUserId,
                ];

                if ($request->filled('Password')) {
                    $userData['Password'] = Hash::make($request->Password);
                }

                $user->update($userData);
            }

            // LOG EDIT LABORAN
            $labNames = DaftarLab::whereIn('id', $request->laboratorium_ids)->pluck('Nama_Laboratorium')->implode(', ');
            ActivityLog::create([
                'user_name' => 'Administrator',
                'action' => 'Mengedit Laboran',
                'description' => "{$laboran->Nama_Laboran} ({$laboran->Role_User}) - {$labNames}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('admin.tambah-laboran.index')
                ->with('success', 'Data laboran berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui laboran: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified laboran from storage.
     */
    public function destroy($id)
    {
        try {
            $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->findOrFail($id);
            $nama = $laboran->Nama_Laboran;
            $labNames = $laboran->laboratoriums->pluck('Nama_Laboratorium')->implode(', ') ?: $laboran->Laboratorium;
            $userId = $laboran->UserID;

            // 1. Hapus relasi many-to-many dari tabel pivot
            $laboran->laboratoriums()->detach();

            // 2. Hapus dari daftar_laboran_laboratoriums
            $laboran->delete();

            // 3. Hapus dari daftar_users
            DaftarUser::where('UserID', $userId)->delete();

            // LOG HAPUS LABORAN
            ActivityLog::create([
                'user_name' => 'Administrator',
                'action' => 'Menghapus Laboran',
                'description' => "{$nama} - {$labNames}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('admin.tambah-laboran.index')
                ->with('success', 'Data laboran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus laboran: '.$e->getMessage());
        }
    }

    /**
     * Generate User ID berdasarkan role
     * Format:
     * - Admin: ADM-XXXXXX
     * - Dosen: DSN-XXXXXX
     * - Tendik: TDK-XXXXXX
     * - Mahasiswa: MHS-XXXXXX
     * - Laboran: LAB-XXXXXX
     */
    private function generateUserId($role)
    {
        $prefix = '';

        switch ($role) {
            case 'Admin':
                $prefix = 'ADM';
                break;
            case 'Dosen':
                $prefix = 'DSN';
                break;
            case 'Tendik':
                $prefix = 'TDK';
                break;
            case 'Mahasiswa':
                $prefix = 'MHS';
                break;
            case 'Laboran':
            case 'Koordinator Laboran':
            case 'Asisten Laboran':
                $prefix = 'LAB';
                break;
            default:
                $prefix = 'USR';
        }

        // Generate unique ID dengan timestamp + random
        $timestamp = now()->format('ymd'); // Format: YYMMDD
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $userId = "{$prefix}-{$timestamp}{$random}";

        // Cek apakah UserID sudah ada di KEDUA tabel
        while (DaftarLaboranLaboratorium::where('UserID', $userId)->exists()
        || DaftarUser::where('UserID', $userId)->exists()) {
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $userId = "{$prefix}-{$timestamp}{$random}";
        }

        return $userId;
    }
}
