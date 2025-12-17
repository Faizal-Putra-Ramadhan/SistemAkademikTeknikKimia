{{-- resources/views/laboran/penelitian-detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Detail Pengajuan Penelitian</h1>
            <a href="{{ route('laboran.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                Kembali
            </a>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if($penelitian->status == 'menunggu')
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 inline-block">
                    Status: Menunggu Persetujuan
                </span>
            @elseif($penelitian->status == 'disetujui')
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 inline-block">
                    Status: Disetujui
                </span>
            @else
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 inline-block">
                    Status: Ditolak
                </span>
            @endif
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b bg-blue-50">
                <h2 class="text-2xl font-bold text-gray-900">{{ $penelitian->judul_penelitian }}</h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Informasi Mahasiswa -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Mahasiswa</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Mahasiswa</p>
                            <p class="text-base font-medium text-gray-900">{{ $penelitian->user_nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dosen Pembimbing</p>
                            <p class="text-base font-medium text-gray-900">{{ $penelitian->dosen_pembimbing }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Informasi Laboratorium -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Laboratorium</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-base font-medium text-gray-900">{{ $penelitian->daftarLab->Nama_Laboratorium ?? 'N/A' }}</p>
                        @if($penelitian->daftarLab)
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Kepala Lab: {{ $penelitian->daftarLab->Kepala_Labolatorium }}</p>
                            <p>Admin Lab: {{ $penelitian->daftarLab->Admin_Laboratorium }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Periode Penelitian -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Periode Penelitian</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Mulai</p>
                            <p class="text-lg font-semibold text-blue-700">
                                {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d F Y') }}
                            </p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Selesai</p>
                            <p class="text-lg font-semibold text-blue-700">
                                {{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        <p>Durasi: {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($penelitian->tanggal_selesai)) }} hari</p>
                    </div>
                </div>

                <hr>

                <!-- Deskripsi Penelitian -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Penelitian</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $penelitian->deskripsi }}</p>
                    </div>
                </div>

                <hr>

                <!-- Informasi Tambahan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Tambahan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Tanggal Pengajuan</p>
                            <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penelitian->created_at)->format('d F Y H:i') }}</p>
                        </div>
                        @if($penelitian->updated_at != $penelitian->created_at)
                        <div>
                            <p class="text-gray-500">Terakhir Diupdate</p>
                            <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penelitian->updated_at)->format('d F Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($penelitian->status == 'menunggu')
                <div class="pt-6 border-t">
                    <div class="flex gap-3">
                        <form action="{{ route('laboran.penelitian.setujui', $penelitian->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menyetujui pengajuan penelitian ini?')"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui Pengajuan
                            </button>
                        </form>
                        
                        <form action="{{ route('laboran.penelitian.tolak', $penelitian->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menolak pengajuan penelitian ini?')"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection