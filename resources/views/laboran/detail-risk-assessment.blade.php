<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-full">
        <x-laboran.navbar :labs="$labs" :user="$user"></x-laboran.navbar>
        <x-laboran.header>Detail RA</x-laboran.header>

        <main>
            <div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('laboran.risk-assessment', $riskAssessment->daftar_lab_id) }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Risk Assessment
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header with Status -->
        <div class="border-b pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">RISK ASSESSMENT FORM</h1>
                    <p class="text-sm text-gray-600 mt-1">Formulir Penakaran Resiko untuk Kerja Laboratorium</p>
                    <p class="text-sm text-gray-700 mt-2">
                        <strong>Laboratorium:</strong> {{ $riskAssessment->daftarLab->Nama_Laboratorium }}<br>
                        <strong>Dibuat:</strong> {{ $riskAssessment->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                <div>
                    @php
                        $statusConfig = [
                            'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                            'menunggu_dosen' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Dosen'],
                            'menunggu_safety_officer' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Menunggu Safety Officer'],
                            'menunggu_kepala_lab' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Menunggu Kepala Lab'],
                            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                            'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                        ];
                        $config = $statusConfig[$riskAssessment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $riskAssessment->status];
                    @endphp
                    <span class="px-4 py-2 text-sm font-bold rounded-lg {{ $config['bg'] }} {{ $config['text'] }}">
                        {{ $config['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Data Mahasiswa -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">1</span>
                Data Mahasiswa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <p class="text-gray-900">{{ $riskAssessment->nama }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <p class="text-gray-900">{{ $riskAssessment->nim }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Kontak</label>
                    <p class="text-gray-900">{{ $riskAssessment->no_kontak ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Kontak</label>
                    <p class="text-gray-900">{{ $riskAssessment->alamat_kontak ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Jenis RA -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">2</span>
                Jenis Risk Assessment
            </h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-900 font-medium">{{ $riskAssessment->jenis_ra }}</p>
                @if($riskAssessment->topik_judul)
                    <p class="text-sm text-gray-600 mt-2">
                        <strong>Topik/Judul:</strong> {{ $riskAssessment->topik_judul }}
                    </p>
                @endif
                @if($riskAssessment->dosen_pembimbing_nama)
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Dosen Pembimbing:</strong> {{ $riskAssessment->dosen_pembimbing_nama }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Bahan Kimia -->
        @if($riskAssessment->bahanKimias && $riskAssessment->bahanKimias->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">3</span>
                Material atau Bahan Kimia
            </h2>
            
            @foreach($riskAssessment->bahanKimias as $index => $bahan)
                <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 mb-3">Bahan #{{ $index + 1 }}: {{ $bahan->nama_bahan }}</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sifat Bahan:</label>
                        <div class="flex flex-wrap gap-2">
                            @if($bahan->explosive)
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">Explosive</span>
                            @endif
                            @if($bahan->flammable)
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full">Flammable</span>
                            @endif
                            @if($bahan->toxic)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">Toxic</span>
                            @endif
                            @if($bahan->corrosive)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">Corrosive</span>
                            @endif
                            @if($bahan->irritant)
                                <span class="px-3 py-1 bg-pink-100 text-pink-800 text-sm rounded-full">Irritant</span>
                            @endif
                            @if($bahan->oxidizing)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Oxidizing</span>
                            @endif
                        </div>
                        @if($bahan->lain_lain)
                            <p class="text-sm text-gray-600 mt-2"><strong>Lain-lain:</strong> {{ $bahan->lain_lain }}</p>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($riskAssessment->kategoriHazardBahan)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="font-semibold text-gray-800">Kategori Hazard Bahan: 
                        <span class="text-yellow-800">{{ ucfirst(str_replace('_', ' ', $riskAssessment->kategoriHazardBahan->kategori)) }}</span>
                    </p>
                </div>
            @endif
        </div>
        @endif

        <!-- Peralatan & Kondisi Operasi -->
        @if($riskAssessment->peralatanOperasi)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">4</span>
                Peralatan yang Dipergunakan & Kondisi Operasi
            </h2>
            
            <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->peralatanOperasi->tekanan_tinggi)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span>Menggunakan <strong>tekanan tinggi</strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->peralatanOperasi->suhu_tinggi)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span>Menggunakan <strong>suhu tinggi</strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->peralatanOperasi->nyala_api)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span>Menggunakan <strong>nyala api</strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->peralatanOperasi->peralatan_berputar)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span>Menggunakan <strong>peralatan yang berputar</strong></span>
                </div>

                @if($riskAssessment->peralatanOperasi->temperatur_maksimum || $riskAssessment->peralatanOperasi->tekanan_maksimum)
                    <div class="mt-4 pt-4 border-t">
                        @if($riskAssessment->peralatanOperasi->temperatur_maksimum)
                            <p class="text-sm"><strong>Temperatur Maksimum:</strong> {{ $riskAssessment->peralatanOperasi->temperatur_maksimum }}°C</p>
                        @endif
                        @if($riskAssessment->peralatanOperasi->tekanan_maksimum)
                            <p class="text-sm mt-1"><strong>Tekanan Maksimum:</strong> {{ $riskAssessment->peralatanOperasi->tekanan_maksimum }} atm</p>
                        @endif
                    </div>
                @endif

                @if($riskAssessment->peralatanOperasi->kategori_hazard)
                    <div class="mt-4 pt-4 border-t">
                        <p class="font-semibold">Kategori Hazard Peralatan: 
                            <span class="text-orange-700">{{ ucfirst(str_replace('_', ' ', $riskAssessment->peralatanOperasi->kategori_hazard)) }}</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Pelaku Kerja -->
        @if($riskAssessment->pelakuKerja)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">5</span>
                Pelaku Kerja Laboratorium
            </h2>
            
            <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->menyadari_faktor_manusia)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Menyadari faktor manusia dalam kecelakaan kerja</span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->memahami_bahaya_diri)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Memahami bahaya terhadap diri sendiri</span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->memahami_bahaya_orang_lain)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Memahami bahaya terhadap orang lain</span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->memahami_bahaya_lingkungan)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Memahami bahaya terhadap lingkungan</span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->memahami_bahaya_peralatan)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Memahami bahaya dari peralatan</span>
                </div>
                <div class="flex items-center">
                    <span class="w-6 h-6 mr-3">
                        @if($riskAssessment->pelakuKerja->paham_tindakan_kecelakaan)
                            <svg class="text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                    </span>
                    <span class="text-sm">Paham tindakan saat kecelakaan</span>
                </div>

                @if($riskAssessment->pelakuKerja->penilaian_keterampilan)
                    <div class="mt-4 pt-4 border-t">
                        <p class="font-semibold">Penilaian Keterampilan Diri: 
                            <span class="text-purple-700">{{ ucfirst(str_replace('_', ' ', $riskAssessment->pelakuKerja->penilaian_keterampilan)) }}</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Status Persetujuan -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Status Persetujuan</h2>
            
            <div class="space-y-4">
                <!-- Dosen Pembimbing -->
                <div class="border rounded-lg p-4 {{ $riskAssessment->persetujuan_dosen ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Dosen Pembimbing</h3>
                            <p class="text-sm text-gray-600">{{ $riskAssessment->dosen_pembimbing_nama ?? 'Belum ditentukan' }}</p>
                            @if($riskAssessment->kategori_resiko_dosen)
                                <p class="text-sm text-gray-600 mt-1">Kategori Resiko: <strong>{{ ucfirst($riskAssessment->kategori_resiko_dosen) }}</strong></p>
                            @endif
                            @if($riskAssessment->catatan_dosen)
                                <p class="text-sm text-gray-600 mt-1">Catatan: {{ $riskAssessment->catatan_dosen }}</p>
                            @endif
                        </div>
                        <div>
                            @if($riskAssessment->persetujuan_dosen === 1)
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">✓ Disetujui</span>
                                @if($riskAssessment->tanggal_persetujuan_dosen)
                                    <p class="text-xs text-gray-500 mt-1">{{ $riskAssessment->tanggal_persetujuan_dosen->format('d/m/Y H:i') }}</p>
                                @endif
                            @elseif($riskAssessment->persetujuan_dosen === 0)
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">✗ Ditolak</span>
                            @else
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium">⏳ Menunggu</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Safety Officer -->
                <div class="border rounded-lg p-4 {{ $riskAssessment->persetujuan_safety_officer ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Safety Officer</h3>
                            <p class="text-sm text-gray-600">{{ $riskAssessment->safety_officer_nama ?? 'Belum ditentukan' }}</p>
                            @if($riskAssessment->jadwal_wawancara)
                                <p class="text-sm text-gray-600 mt-1">Jadwal Wawancara: <strong>{{ $riskAssessment->jadwal_wawancara->format('d/m/Y H:i') }}</strong></p>
                            @endif
                            @if($riskAssessment->catatan_safety_officer)
                                <p class="text-sm text-gray-600 mt-1">Catatan: {{ $riskAssessment->catatan_safety_officer }}</p>
                            @endif
                        </div>
                        <div>
                            @if($riskAssessment->persetujuan_safety_officer === 1)
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">✓ Disetujui</span>
                                @if($riskAssessment->tanggal_persetujuan_safety_officer)
                                    <p class="text-xs text-gray-500 mt-1">{{ $riskAssessment->tanggal_persetujuan_safety_officer->format('d/m/Y H:i') }}</p>
                                @endif
                            @elseif($riskAssessment->persetujuan_safety_officer === 0)
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">✗ Ditolak</span>
                            @else
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium">⏳ Menunggu</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kepala Laboratorium -->
                <div class="border rounded-lg p-4 {{ $riskAssessment->persetujuan_kepala_lab ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Kepala Laboratorium</h3>
                            <p class="text-sm text-gray-600">{{ $riskAssessment->daftarLab->Kepala_Labolatorium ?? 'Belum ditentukan' }}</p>
                            @if($riskAssessment->catatan_kepala_lab)
                                <p class="text-sm text-gray-600 mt-1">Catatan: {{ $riskAssessment->catatan_kepala_lab }}</p>
                            @endif
                        </div>
                        <div>
                            @if($riskAssessment->persetujuan_kepala_lab === 1)
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">✓ Disetujui</span>
                                @if($riskAssessment->tanggal_persetujuan_kepala_lab)
                                    <p class="text-xs text-gray-500 mt-1">{{ $riskAssessment->tanggal_persetujuan_kepala_lab->format('d/m/Y H:i') }}</p>
                                @endif
                            @elseif($riskAssessment->persetujuan_kepala_lab === 0)
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">✗ Ditolak</span>
                            @else
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium">⏳ Menunggu</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info untuk Laboran -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800 font-medium">Informasi untuk Laboran</p>
                    <p class="text-sm text-blue-700 mt-1">Anda hanya dapat melihat Risk Assessment ini. Untuk persetujuan dilakukan oleh Dosen Pembimbing, Safety Officer, dan Kepala Laboratorium.</p>
                </div>
            </div>
        </div>
    </div>
</div>
        </main>
    </div>
</body>
</html>