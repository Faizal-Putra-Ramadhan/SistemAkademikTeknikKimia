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
     <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <div class="min-h-full">
        <x-safety-officer.navbar :labs="$labs" :user="$user"></x-safety-officer.navbar>
        <x-safety-officer.header>Detail</x-safety-officer.header>

        <main>
            <div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('safety-officer.risk-assessment.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Kembali ke Daftar RA
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Status Card -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $riskAssessment->topik_judul }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Risk Assessment #{{ $riskAssessment->id }}</p>
                </div>
                <div>
                    @if($riskAssessment->status === 'menunggu_safety_officer')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Review Anda
                        </span>
                    @elseif($riskAssessment->status === 'menunggu_kepala_lab')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            Menunggu Kepala Lab
                        </span>
                    @elseif($riskAssessment->status === 'disetujui')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Disetujui
                        </span>
                    @elseif($riskAssessment->status === 'ditolak')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="mt-6 border-t pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Status Review</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        <!-- Dosen Review -->
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Disetujui oleh <span class="font-medium text-gray-900">{{ $riskAssessment->dosenPembimbing->Nama }}</span>
                                            </p>
                                            @if($riskAssessment->kategori_resiko_dosen)
                                            <p class="mt-1 text-sm text-gray-500">
                                                Kategori Risiko: 
                                                <span class="px-2 py-1 text-xs font-semibold rounded
                                                    {{ $riskAssessment->kategori_resiko_dosen === 'tinggi' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $riskAssessment->kategori_resiko_dosen === 'sedang' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $riskAssessment->kategori_resiko_dosen === 'rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ ucfirst($riskAssessment->kategori_resiko_dosen) }}
                                                </span>
                                            </p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $riskAssessment->tanggal_persetujuan_dosen ? $riskAssessment->tanggal_persetujuan_dosen->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Safety Officer Review -->
                        <li>
                            <div class="relative pb-8">
                                @if($riskAssessment->persetujuan_safety_officer !== null)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        @if($riskAssessment->persetujuan_safety_officer === true)
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @elseif($riskAssessment->persetujuan_safety_officer === false)
                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-900 font-medium">Safety Officer Review</p>
                                            @if($riskAssessment->persetujuan_safety_officer === null)
                                                <p class="text-sm text-gray-500">Menunggu review</p>
                                            @else
                                                <p class="text-sm text-gray-500">
                                                    {{ $riskAssessment->persetujuan_safety_officer ? 'Disetujui' : 'Ditolak' }} oleh {{ $riskAssessment->safety_officer_nama }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $riskAssessment->tanggal_persetujuan_safety_officer ? $riskAssessment->tanggal_persetujuan_safety_officer->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Kepala Lab Approval -->
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        @if($riskAssessment->persetujuan_kepala_lab === true)
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @elseif($riskAssessment->persetujuan_kepala_lab === false)
                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <p class="text-sm text-gray-900 font-medium">Kepala Laboratorium</p>
                                        <p class="text-sm text-gray-500">
                                            @if($riskAssessment->persetujuan_kepala_lab === null)
                                                Menunggu persetujuan final
                                            @else
                                                {{ $riskAssessment->persetujuan_kepala_lab ? 'Final Approval' : 'Ditolak' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg mb-6 p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Data Mahasiswa</h2>
    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->nama }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">NIM</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->nim }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">No. Kontak</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->no_kontak }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->alamat_kontak }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Laboratorium</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->daftarLab->Nama_Laboratorium }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Jenis Risk Assessment</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->jenis_ra }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Topik/Judul</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->topik_judul }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Dosen Pembimbing</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->dosen_pembimbing_nama }}</dd>
        </div>
    </dl>
</div>

<!-- Bahan Kimia -->
<div class="bg-white shadow rounded-lg mb-6 p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Bahan Kimia yang Digunakan</h2>
    <div class="space-y-4">
        @foreach($riskAssessment->bahanKimias as $bahan)
        <div class="border rounded-lg p-4">
            <h3 class="font-medium text-gray-900 mb-2">{{ $bahan->nama_bahan }}</h3>
            <div class="flex flex-wrap gap-2">
                @if($bahan->explosive)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Explosive</span>
                @endif
                @if($bahan->flammable)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">Flammable</span>
                @endif
                @if($bahan->toxic)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">Toxic</span>
                @endif
                @if($bahan->corrosive)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Corrosive</span>
                @endif
                @if($bahan->irritant)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-pink-100 text-pink-800">Irritant</span>
                @endif
                @if($bahan->oxidizing)
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Oxidizing</span>
                @endif
            </div>
            @if($bahan->lain_lain)
            <p class="mt-2 text-sm text-gray-600">Keterangan: {{ $bahan->lain_lain }}</p>
            @endif
        </div>
        @endforeach
    </div>
    
    @if($riskAssessment->kategoriHazardBahan)
    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm font-medium text-gray-700">Kategori Hazard Bahan Keseluruhan:</p>
        <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full
            {{ $riskAssessment->kategoriHazardBahan->kategori === 'sangat_hazardous' ? 'bg-red-100 text-red-800' : '' }}
            {{ $riskAssessment->kategoriHazardBahan->kategori === 'hazardous' ? 'bg-orange-100 text-orange-800' : '' }}
            {{ $riskAssessment->kategoriHazardBahan->kategori === 'moderat' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $riskAssessment->kategoriHazardBahan->kategori === 'tidak_hazardous' ? 'bg-green-100 text-green-800' : '' }}">
            {{ ucfirst(str_replace('_', ' ', $riskAssessment->kategoriHazardBahan->kategori)) }}
        </span>
    </div>
    @endif
</div>

<!-- Peralatan dan Kondisi Operasi -->
@if($riskAssessment->peralatanOperasi)
<div class="bg-white shadow rounded-lg mb-6 p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Peralatan dan Kondisi Operasi</h2>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Tekanan Tinggi</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Suhu Tinggi</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->peralatanOperasi->nyala_api ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Nyala Api</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Peralatan Berputar</label>
        </div>
    </div>
    
    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        @if($riskAssessment->peralatanOperasi->temperatur_maksimum)
        <div>
            <dt class="text-sm font-medium text-gray-500">Temperatur Maksimum</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->peralatanOperasi->temperatur_maksimum }}°C</dd>
        </div>
        @endif
        @if($riskAssessment->peralatanOperasi->tekanan_maksimum)
        <div>
            <dt class="text-sm font-medium text-gray-500">Tekanan Maksimum</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $riskAssessment->peralatanOperasi->tekanan_maksimum }} atm</dd>
        </div>
        @endif
    </dl>
    
    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm font-medium text-gray-700">Kategori Hazard Peralatan:</p>
        <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full
            {{ $riskAssessment->peralatanOperasi->kategori_hazard === 'sangat_hazardous' ? 'bg-red-100 text-red-800' : '' }}
            {{ $riskAssessment->peralatanOperasi->kategori_hazard === 'hazardous' ? 'bg-orange-100 text-orange-800' : '' }}
            {{ $riskAssessment->peralatanOperasi->kategori_hazard === 'moderat' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $riskAssessment->peralatanOperasi->kategori_hazard === 'tidak_hazardous' ? 'bg-green-100 text-green-800' : '' }}">
            {{ ucfirst(str_replace('_', ' ', $riskAssessment->peralatanOperasi->kategori_hazard)) }}
        </span>
    </div>
</div>
@endif

<!-- Pelaku Kerja -->
@if($riskAssessment->pelakuKerja)
<div class="bg-white shadow rounded-lg mb-6 p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Pelaku Kerja Laboratorium</h2>
    <div class="space-y-2 mb-4">
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Menyadari faktor manusia dalam bekerja</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->memahami_bahaya_diri ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Memahami bahaya terhadap diri sendiri</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Memahami bahaya terhadap orang lain</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Memahami bahaya terhadap lingkungan</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Memahami bahaya terhadap peralatan</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" {{ $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? 'checked' : '' }} disabled class="h-4 w-4 text-indigo-600 rounded">
            <label class="ml-2 text-sm text-gray-700">Paham tindakan saat terjadi kecelakaan</label>
        </div>
    </div>
    
    <div class="p-4 bg-gray-50 rounded-lg">
        <p class="text-sm font-medium text-gray-700">Penilaian Keterampilan:</p>
        <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full
            {{ $riskAssessment->pelakuKerja->penilaian_keterampilan === 'ceroboh' ? 'bg-red-100 text-red-800' : '' }}
            {{ $riskAssessment->pelakuKerja->penilaian_keterampilan === 'kurang_terampil' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $riskAssessment->pelakuKerja->penilaian_keterampilan === 'cukup_terampil' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $riskAssessment->pelakuKerja->penilaian_keterampilan === 'sangat_terampil' ? 'bg-green-100 text-green-800' : '' }}">
            {{ ucfirst(str_replace('_', ' ', $riskAssessment->pelakuKerja->penilaian_keterampilan)) }}
        </span>
    </div>
</div>
@endif

<!-- Catatan Review -->
@if($riskAssessment->catatan_dosen || $riskAssessment->catatan_safety_officer)
<div class="bg-white shadow rounded-lg mb-6 p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Catatan Review</h2>
    
    @if($riskAssessment->catatan_dosen)
    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
        <p class="text-sm font-medium text-blue-900">Catatan Dosen Pembimbing:</p>
        <p class="mt-1 text-sm text-blue-700">{{ $riskAssessment->catatan_dosen }}</p>
    </div>
    @endif
    
    @if($riskAssessment->catatan_safety_officer)
    <div class="p-4 bg-yellow-50 rounded-lg">
        <p class="text-sm font-medium text-yellow-900">Catatan Safety Officer:</p>
        <p class="mt-1 text-sm text-yellow-700">{{ $riskAssessment->catatan_safety_officer }}</p>
    </div>
    @endif
</div>
@endif

<!-- Form Actions untuk Safety Officer -->
@if($riskAssessment->status === 'menunggu_safety_officer')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Tindakan Safety Officer</h2>
    
    <!-- Jadwalkan Wawancara -->
    @if(!$riskAssessment->jadwal_wawancara)
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <h3 class="text-sm font-medium text-blue-900 mb-3">Jadwalkan Wawancara</h3>
        <form action="{{ route('safety-officer.risk-assessment.schedule-interview', $riskAssessment->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal & Waktu Wawancara</label>
                    <input type="datetime-local" name="jadwal_wawancara" required
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Set Jadwal Wawancara
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="mb-6 p-4 bg-green-50 rounded-lg">
        <p class="text-sm font-medium text-green-900">Jadwal Wawancara:</p>
        <p class="mt-1 text-sm text-green-700">{{ $riskAssessment->jadwal_wawancara->format('l, d F Y - H:i') }} WIB</p>
    </div>
    @endif

    <!-- Form Approval/Rejection -->
    <form action="{{ route('safety-officer.risk-assessment.approve', $riskAssessment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan keputusan ini?')">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="radio" name="persetujuan" value="setuju" id="approve" required
                               class="h-4 w-4 text-green-600 focus:ring-green-500">
                        <label for="approve" class="ml-2 text-sm text-gray-700">
                            Setuju - Lanjutkan ke Kepala Laboratorium
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="persetujuan" value="tolak" id="reject" required
                               class="h-4 w-4 text-red-600 focus:ring-red-500">
                        <label for="reject" class="ml-2 text-sm text-gray-700">
                            Tolak - Risk Assessment ditolak
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan Review *</label>
                <textarea name="catatan" rows="4" required
                          placeholder="Berikan catatan mengenai review Anda, termasuk rekomendasi tindakan pencegahan yang harus dilakukan..."
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <p class="mt-1 text-xs text-gray-500">Wajib diisi. Catatan akan diteruskan ke Kepala Lab dan mahasiswa.</p>
            </div>

            <div class="flex space-x-3">
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Submit Review
                </button>
                <button type="button" onclick="showRevisionForm()" 
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Minta Revisi
                </button>
            </div>
        </div>
    </form>

    <!-- Form Request Revision (Hidden by default) -->
    <div id="revisionForm" class="hidden mt-6 p-4 bg-yellow-50 rounded-lg">
        <h3 class="text-sm font-medium text-yellow-900 mb-3">Minta Revisi dari Mahasiswa</h3>
        <form action="{{ route('safety-officer.risk-assessment.request-revision', $riskAssessment->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan Revisi *</label>
                    <textarea name="catatan_revisi" rows="4" required
                              placeholder="Jelaskan poin-poin yang perlu direvisi oleh mahasiswa..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                        Kirim Permintaan Revisi
                    </button>
                    <button type="button" onclick="hideRevisionForm()" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function showRevisionForm() {
    document.getElementById('revisionForm').classList.remove('hidden');
}

function hideRevisionForm() {
    document.getElementById('revisionForm').classList.add('hidden');
}
</script>

    </div>
</div>
        </main>
    </div>
</body>
</html>