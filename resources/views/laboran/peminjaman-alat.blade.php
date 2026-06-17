@extends('layouts.app')

@section('title', 'Peminjaman Alat')
@section('page-title', 'Peminjaman Alat')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Peminjaman Alat</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>ID Risk Assessment</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamanAlat as $alat)
                <tr>
                    <td>{{ $alat->user_nama }}</td>
                    <td>
                        @if($alat->riskAssessment)
                            <span class="badge badge-info">
                                {{ $alat->riskAssessment->id_ra ?? 'N/A' }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td>{{ $alat->alatLab->nama_alat ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-info">{{ $alat->jumlah ?? 1 }} unit</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($alat->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $alat->tanggal_kembali ? \Carbon\Carbon::parse($alat->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($alat->status == 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($alat->status == 'disetujui')
                            <span class="badge badge-info">Disetujui</span>
                        @elseif($alat->status == 'dikembalikan')
                            <span class="badge badge-success">Dikembalikan</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($alat->status == 'menunggu')
                        <div class="space-y-2">
                            <!-- Approve Form with Duration -->
                            <form action="{{ route('laboran.alat.setujui', $alat->id) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Durasi Peminjaman</label>
                                    <div class="flex gap-2 items-center">
                                        <input 
                                            type="number" 
                                            name="durasi_hari" 
                                            min="1" 
                                            max="365" 
                                            placeholder="Masukkan durasi (hari)"
                                            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        />
                                        <span class="text-xs text-gray-500 font-medium">hari</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika menggunakan tanggal yang diminta</p>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm w-full">
                                    ✓ Setujui Peminjaman
                                </button>
                            </form>
                            
                            <!-- Reject Form -->
                            <form action="{{ route('laboran.alat.tolak', $alat->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm w-full">
                                    ✕ Tolak
                                </button>
                            </form>
                        </div>
                        @elseif($alat->status == 'disetujui')
                        <form action="{{ route('laboran.alat.kembalikan', $alat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-info btn-sm w-full">
                                ✓ Tandai Dikembalikan
                            </button>
                        </form>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500">Tidak ada data peminjaman alat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection