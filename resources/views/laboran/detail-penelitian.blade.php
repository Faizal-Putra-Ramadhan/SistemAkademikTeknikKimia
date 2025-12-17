<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Penelitian</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-full">
    <div class="min-h-full">

        <x-laboran.navbar :labs="$labs" :user="$user" />

        <x-laboran.header>Detail Pengajuan Penelitian</x-laboran.header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
                <!-- Tombol Kembali -->
                <div class="mb-4">
                    <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </button>
                </div>

                <!-- Card Detail Penelitian -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                        <h2 class="text-2xl font-bold">{{ $penelitian->judul_penelitian }}</h2>
                        <div class="mt-2 flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                {{ $penelitian->status == 'menunggu' ? 'bg-yellow-400 text-yellow-900' : 
                                   ($penelitian->status == 'disetujui' ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900') }}">
                                {{ ucfirst($penelitian->status) }}
                            </span>
                            <span class="text-blue-100">Diajukan: {{ \Carbon\Carbon::parse($penelitian->created_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-6">
                        
                        <!-- Informasi Mahasiswa -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informasi Mahasiswa
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nama Mahasiswa</label>
                                    <p class="text-gray-900 font-medium">{{ $penelitian->user_nama }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Dosen Pembimbing</label>
                                    <p class="text-gray-900 font-medium">{{ $penelitian->dosen_pembimbing }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Penelitian -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Detail Penelitian
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Judul Penelitian</label>
                                    <p class="text-gray-900 font-medium">{{ $penelitian->judul_penelitian }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                                    <p class="text-gray-900 leading-relaxed">{{ $penelitian->deskripsi }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Penelitian -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Periode Penelitian
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Tanggal Mulai</label>
                                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Tanggal Selesai</label>
                                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d F Y') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-500">Durasi Penelitian</label>
                                    <p class="text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($penelitian->tanggal_selesai)) }} hari
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Aksi -->
                        @if($penelitian->status == 'menunggu')
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                            <div class="flex gap-3">
                                <form action="{{ route('laboran.penelitian.setujui', $penelitian->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menyetujui pengajuan penelitian ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui Pengajuan
                                    </button>
                                </form>
                                <form action="{{ route('laboran.penelitian.tolak', $penelitian->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menolak pengajuan penelitian ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tolak Pengajuan
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-2 {{ $penelitian->status == 'disetujui' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($penelitian->status == 'disetujui')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                <span class="text-lg font-medium {{ $penelitian->status == 'disetujui' ? 'text-green-700' : 'text-red-700' }}">
                                    Pengajuan ini telah {{ $penelitian->status == 'disetujui' ? 'disetujui' : 'ditolak' }}
                                </span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>