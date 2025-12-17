@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl p-8 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl font-bold text-indigo-800">DASHBOARD DOSEN PEMBIMBING</h1>
        <a href="{{ route('dosen.profil') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-10 py-5 rounded-2xl font-bold text-xl shadow-2xl transform hover:scale-105 transition duration-300">
            Profil Saya
        </a>
    </div>

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
        <div class="bg-green-100 border-l-8 border-green-600 text-green-800 p-6 rounded-xl mb-8 text-center text-2xl font-bold shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi Pengajuan Baru -->
    @if($pengajuanBaru->count() > 0)
        <div class="bg-gradient-to-r from-yellow-100 to-orange-100 border-l-8 border-orange-500 text-orange-900 p-8 rounded-2xl mb-10 text-center shadow-xl">
            <p class="text-3xl font-bold">ADA {{ $pengajuanBaru->count() }} PENGAJUAN BARU MENUNGGU PERSETUJUAN ANDA!</p>
            <p class="text-xl mt-2">Ayo segera ditinjau, Pak!</p>
        </div>
    @else
        <div class="bg-gradient-to-r from-blue-100 to-cyan-100 border-l-8 border-blue-500 text-blue-900 p-8 rounded-2xl mb-10 text-center shadow-xl">
            <p class="text-2xl font-bold">Tidak ada pengajuan baru. Santai dulu Pak </p>
        </div>
    @endif

    <!-- Daftar Pengajuan Baru -->
    <div class="space-y-8">
        @forelse($pengajuanBaru as $p)
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-2xl border-4 border-indigo-300 shadow-xl hover:shadow-2xl transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold text-indigo-900 mb-3">{{ $p->judul_penelitian }}</h3>
                        <div class="space-y-2 text-lg">
                            <p>Mahasiswa: <strong class="text-indigo-700">{{ $p->user_nama }}</strong></p>
                            <p>Lab: <strong class="text-purple-700">{{ $p->lab?->nama_lab ?? $p->nama_lab ?? 'Tidak diketahui' }}</strong></p>
                            <p class="text-gray-700">Periode: {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <!-- SETUJUI -->
                        <form action="{{ route('dosen.setujui', $p->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-12 py-6 rounded-2xl font-bold text-2xl shadow-2xl transform hover:scale-110 transition duration-300">
                                SETUJUI
                            </button>
                        </form>

                        <!-- TOLAK -->
                        <button onclick="openTolakModal({{ $p->id }})"
                                class="bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-12 py-6 rounded-2xl font-bold text-2xl shadow-2xl transform hover:scale-110 transition duration-300">
                            TOLAK
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-3xl text-gray-500 font-light">Belum ada pengajuan baru.</p>
                <p class="text-xl text-gray-400 mt-4">Semoga hari ini tenang, Pak</p>
            </div>
        @endforelse
    </div>

    <!-- Riwayat -->
    @if($riwayat->count() > 0)
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Riwayat Pengajuan (10 Terakhir)</h2>
            <div class="bg-gray-50 rounded-2xl p-8 shadow-xl">
                <div class="space-y-4">
                    @foreach($riwayat as $r)
                        <div class="flex justify-between items-center py-6 px-8 bg-white rounded-xl shadow-md hover:shadow-lg transition">
                            <div>
                                <p class="text-xl font-semibold text-gray-800">{{ $r->judul_penelitian }}</p>
                                <p class="text-gray-600">oleh {{ $r->user_nama }}</p>
                            </div>
                            <span class="px-8 py-4 rounded-full text-white font-bold text-xl shadow-lg
                                {{ $r->status === 'disetujui' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600' }}">
                                {{ strtoupper($r->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Tolak -->
@include('dosen.modal-tolak')
@endsection