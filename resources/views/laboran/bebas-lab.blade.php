@extends('layouts.app')

@section('title', 'Bebas Lab')
@section('page-title', 'Bebas Lab')

@section('content')
    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-bold">Daftar Pengajuan Bebas Lab</h2>
            <p class="text-sm text-gray-500">Laboratorium: {{ $lab->Nama_Laboratorium }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Risk Assessment</th>
                        <th>Status Approval</th>
                        <th>Detail Peminjaman</th>
                        <th>Peminjaman Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        @php
                            $approval = $req->approval;
                            $alatList = $equipmentByRequest[$req->id] ?? collect();
                            $ruanganList = $roomByRequest[$req->id] ?? collect();
                            $pendingAlatAtRequest = $req->pending_alat_count_at_request ?? 0;
                            $pendingAlatNow = $req->pending_alat_count_now ?? 0;
                            $pendingRuanganAtRequest = $req->pending_ruangan_count_at_request ?? 0;
                            $pendingRuanganNow = $req->pending_ruangan_count_now ?? 0;
                            $pendingAtRequest = $pendingAlatAtRequest + $pendingRuanganAtRequest;
                            $pendingNow = $pendingAlatNow + $pendingRuanganNow;
                            $isApproved = $approval && $approval->status === 'disetujui';
                        @endphp
                        <tr>
                            <td>{{ $req->user_nama }}</td>
                            <td>
                                <div class="text-sm text-gray-700">
                                    <div class="font-semibold">
                                        {{ $req->riskAssessment?->id_ra ?? '-' }}
                                        @if($req->periode > 1)
                                            <span class="badge badge-warning" style="font-size: 11px; padding: 2px 8px; margin-left: 4px;">(Periode {{ $req->periode }})</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600">{{ $req->riskAssessment?->topik_judul ?? '-' }}</div>
                                </div>
                            </td>
                            <td>
                                @if($req->status === 'dibatalkan')
                                    <span class="badge badge-secondary">Dibatalkan</span>
                                    <div class="text-xs text-gray-500 mt-1">Dibatalkan untuk peminjaman alat</div>
                                @elseif($isApproved)
                                    <span class="badge badge-success">Disetujui</span>
                                    <div class="text-xs text-gray-500 mt-1">{{ $approval->approved_at?->format('d/m/Y H:i') }}</div>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm text-gray-700">
                                    <div class="font-semibold text-gray-800">Peminjaman Alat</div>
                                    @if($alatList->isEmpty())
                                        <div class="text-gray-400">Tidak ada peminjaman alat</div>
                                    @else
                                        <ul class="list-disc list-inside">
                                            @foreach($alatList as $alat)
                                                <li>{{ $alat->alatLab?->nama_alat ?? '-' }} ({{ $alat->status }})</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-700 mt-3">
                                    <div class="font-semibold text-gray-800">Peminjaman Ruangan</div>
                                    @if($ruanganList->isEmpty())
                                        <div class="text-gray-400">Tidak ada peminjaman ruangan</div>
                                    @else
                                        <ul class="list-disc list-inside">
                                            @foreach($ruanganList as $ruangan)
                                                <li>{{ $ruangan->tanggal?->format('d/m/Y') ?? '-' }} {{ $ruangan->jam_mulai }}-{{ $ruangan->jam_selesai }} ({{ $ruangan->status }})</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <span class="text-gray-600">Saat pengajuan:</span>
                                    @if($pendingAtRequest > 0)
                                        <span class="text-red-600 font-semibold">{{ $pendingAtRequest }} aktif</span>
                                    @else
                                        <span class="text-green-600 font-semibold">0</span>
                                    @endif
                                </div>
                                @if($pendingNow !== $pendingAtRequest)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Saat ini: <span class="font-semibold">{{ $pendingNow }} aktif</span>
                                    </div>
                                @endif
                                <div class="text-xs text-gray-500 mt-1">
                                    Alat: {{ $pendingAlatNow }} aktif, Ruangan: {{ $pendingRuanganNow }} aktif
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('laboran.bebas-lab.detail', [$lab->id, $req->id]) }}" class="btn btn-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                    @if($req->is_active && $req->isFullyApproved() && !$req->hasPeminjamanAktif())
                                        <a href="{{ route('laboran.bebas-lab.download', [$lab->id, $req->id]) }}" class="btn btn-success btn-sm" style="background-color: #059669; color: white;">
                                            Download
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500">Belum ada pengajuan bebas lab.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
