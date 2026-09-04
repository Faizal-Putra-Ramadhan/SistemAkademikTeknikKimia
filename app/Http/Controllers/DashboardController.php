<?php

namespace App\Http\Controllers;

use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        
        $daftar_users = DaftarUser::all();
        $daftar_laborans = DaftarLaboranLaboratorium::all();
        $daftar_labs = DaftarLab::all();
        
        
        $countAdmin = $daftar_users->filter(function($user) {
            return strtolower($user->Role_User ?? '') === 'admin';
        })->count();
        
        $countDosen = $daftar_users->filter(function($user) {
            return strtolower($user->Role_User ?? '') === 'dosen';
        })->count();
        
        $countMahasiswa = $daftar_users->filter(function($user) {
            return strtolower($user->Role_User ?? '') === 'mahasiswa';
        })->count();
        
        
        return view('welcome', compact(
            'daftar_users',
            'daftar_laborans',
            'daftar_labs',
            'countAdmin',
            'countDosen',
            'countMahasiswa'
        ));
    }
}