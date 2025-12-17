{{-- resources/views/laboran/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-2">Dashboard Laboran</h1>
            <p class="text-blue-100">Selamat datang, {{ Auth::user()->Nama }}</p>
            <p class="text-sm text-blue-200">{{ $laboran->Laboratorium }} • {{ Auth::user()->Email }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto p-6">
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Peminjaman Ruangan Menunggu</p>
                        <p class="text-3xl font-bold mt-1">{{ $peminjamanRuanganMenunggu }}</p>
                    </div>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Peminjaman Alat Menunggu</p>
                        <p class="text-3xl font-bold mt-1">{{ $peminjamanAlatMenunggu }}</p>
                    </div>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pengajuan Penelitian Menunggu</p>
                        <p class="text-3xl font-bold mt-1">{{ $pengajuanPenelitianMenunggu }}</p>
                    </div>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pengumuman Aktif</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalPengumuman }}</p>
                    </div>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('ruangan')" id="tab-ruangan" class="tab-button border-b-2 border-blue-500 text-blue-600 py-4 px-6 font-semibold">
                        Peminjaman Ruangan
                    </button>
                    <button onclick="showTab('alat')" id="tab-alat" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-6 font-semibold">
                        Peminjaman Alat
                    </button>
                    <button onclick="showTab('penelitian')" id="tab-penelitian" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-6 font-semibold">
                        Pengajuan Penelitian
                    </button>
                    <button onclick="showTab('pengumuman')" id="tab-pengumuman" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-6 font-semibold">
                        Pengumuman
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content Tabs -->
        
        <!-- Tab Peminjaman Ruangan -->
        <div id="content-ruangan" class="tab-content">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">Daftar Peminjaman Ruangan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($peminjamanRuangan as $ruangan)
                            <tr>
                                <td class="px-6 py-4">{{ $ruangan->user_nama }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($ruangan->tanggal)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $ruangan->jam_mulai }} - {{ $ruangan->jam_selesai }}</td>
                                <td class="px-6 py-4">{{ Str::limit($ruangan->keperluan, 50) }}</td>
                                <td class="px-6 py-4">
                                    @if($ruangan->status == 'menunggu')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @elseif($ruangan->status == 'disetujui')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Disetujui</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($ruangan->status == 'menunggu')
                                    <div class="flex gap-2">
                                        <form action="{{ route('laboran.ruangan.setujui', $ruangan->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('laboran.ruangan.tolak', $ruangan->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data peminjaman ruangan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Peminjaman Alat -->
        <div id="content-alat" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">Daftar Peminjaman Alat</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($peminjamanAlat as $alat)
                            <tr>
                                <td class="px-6 py-4">{{ $alat->user_nama }}</td>
                                <td class="px-6 py-4">{{ $alat->alatLab->nama_alat ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($alat->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $alat->tanggal_kembali ? \Carbon\Carbon::parse($alat->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($alat->status == 'menunggu')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @elseif($alat->status == 'disetujui')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Disetujui</span>
                                    @elseif($alat->status == 'dikembalikan')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Dikembalikan</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($alat->status == 'menunggu')
                                    <div class="flex gap-2">
                                        <form action="{{ route('laboran.alat.setujui', $alat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('laboran.alat.tolak', $alat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @elseif($alat->status == 'disetujui')
                                    <form action="{{ route('laboran.alat.kembalikan', $alat->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Tandai Dikembalikan
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data peminjaman alat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Pengajuan Penelitian -->
        <div id="content-penelitian" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">Daftar Pengajuan Penelitian</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Penelitian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen Pembimbing</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengajuanPenelitian as $penelitian)
                            <tr>
                                <td class="px-6 py-4">{{ $penelitian->user_nama }}</td>
                                <td class="px-6 py-4">{{ Str::limit($penelitian->judul_penelitian, 50) }}</td>
                                <td class="px-6 py-4">{{ $penelitian->dosen_pembimbing }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($penelitian->status == 'menunggu')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @elseif($penelitian->status == 'disetujui')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Disetujui</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($penelitian->status == 'menunggu')
                                    <div class="flex gap-2">
                                        <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Detail
                                        </button>
                                        <form action="{{ route('laboran.penelitian.setujui', $penelitian->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('laboran.penelitian.tolak', $penelitian->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Detail
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengajuan penelitian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Pengumuman -->
        <div id="content-pengumuman" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-xl font-bold">Daftar Pengumuman</h2>
                    <a href="{{ route('laboran.pengumuman.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pengumuman
                    </a>
                </div>
                <div class="p-6">
                    @forelse($pengumuman as $item)
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold">{{ $item->judul }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->author }} • {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div class="flex gap-2">
                                @if($item->status == 'publish')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">{{ Str::limit($item->isi, 200) }}</p>
                        <div class="flex gap-2">
                            <a href="{{ route('laboran.pengumuman.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </a>
                            <form action="{{ route('laboran.pengumuman.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada pengumuman</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected button
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-blue-500', 'text-blue-600');
}

function showDetailPenelitian(id) {
    // Implementasi detail penelitian dengan modal atau redirect
    window.location.href = '/laboran/penelitian/' + id;
}
</script>
@endsection