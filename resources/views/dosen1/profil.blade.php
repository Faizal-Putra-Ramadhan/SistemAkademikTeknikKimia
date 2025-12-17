@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl p-10 max-w-3xl mx-auto">
    <h1 class="text-4xl font-bold text-indigo-800 mb-8 text-center">PROFIL DOSEN</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-8 border-green-600 text-green-800 p-6 rounded-lg mb-8 text-center text-xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="text-center mb-10">
        <img src="{{ Auth::user()->foto ? asset('storage/foto/' . Auth::user()->foto) : asset('img/default.jpg') }}"
             alt="Foto Profil" class="w-48 h-48 rounded-full mx-auto border-8 border-indigo-600 shadow-xl object-cover">
        <h2 class="text-3xl font-bold mt-6">{{ Auth::user()->Nama }}</h2>
        <p class="text-xl text-gray-600">{{ Auth::user()->UserID }} • {{ Auth::user()->Role_User }}</p>
    </div>

    <form action="{{ route('dosen.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">Nama Lengkap</label>
                <input type="text" name="Nama" value="{{ old('Nama', Auth::user()->Nama) }}" required
                       class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">Email</label>
                <input type="email" name="email" value="{{ old('email', Auth::user()->Email) }}" required
                       class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->Phone) }}"
                       placeholder="08123456789" class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">Ganti Foto Profil</label>
                <input type="file" name="foto" accept="image/*"
                       class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">Password Baru (kosongkan jika tidak diganti)</label>
                <input type="password" name="password" class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-3">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-6 py-4 border-2 rounded-xl text-lg">
            </div>
        </div>

        <div class="text-center mt-12">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-16 py-5 rounded-full text-2xl font-bold shadow-2xl transform hover:scale-105 transition">
                SIMPAN PERUBAHAN
            </button>
        </div>
    </form>

    <div class="text-center mt-10">
        <a href="{{ route('dosen.dashboard') }}" class="text-indigo-600 text-xl underline">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection