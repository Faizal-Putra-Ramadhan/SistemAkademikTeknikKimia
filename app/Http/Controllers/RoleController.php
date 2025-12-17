<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarUser;
use App\Models\DaftarLaboranLaboratorium;

class RoleController extends Controller
{
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required|string',
            'table' => 'required|string'
        ]);

        try {
            if ($request->table === 'user') {
                $user = DaftarUser::findOrFail($request->user_id);
                $user->Role_User = $request->role;
                $user->save();
            } elseif ($request->table === 'laboran') {
                $laboran = DaftarLaboranLaboratorium::findOrFail($request->user_id);
                $laboran->Role_User = $request->role;
                $laboran->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}