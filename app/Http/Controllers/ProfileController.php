<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        // Untuk demo tanpa login, kita pakai data dummy
        $user = (object) [
            'name'     => 'Administrator',
            'email'    => 'admin@teknik.uad.ac.id',
            'phone'    => '081234567890',
            'photo'    => null, // atau path foto lama
        ];

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'phone'    => 'required|string|max:20',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simulasi update (nanti tinggal ganti dengan Auth::user()->update())
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $path;
            // Kalau ada foto lama: Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('profile.edit')
                         ->with('success', 'Profile berhasil diperbarui!');
    }
}