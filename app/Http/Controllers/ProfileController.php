<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Email' => 'required|email|unique:daftar_users,Email,'.$user->id,
            'Phone' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'Nama' => $request->Nama,
                'Email' => $request->Email,
                'Phone' => $request->Phone,
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $data['foto'] = $filename;

                // Hapus foto lama jika ada
                if ($user->foto && file_exists(public_path('uploads/profile/'.$user->foto))) {
                    unlink(public_path('uploads/profile/'.$user->foto));
                }
            }

            $user->update($data);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Update Profil',
                'description' => 'Memperbarui informasi profil',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('admin.profile.edit')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.profile.edit')
                ->with('error', 'Gagal mengupdate profil: '.$e->getMessage());
        }
    }
}
