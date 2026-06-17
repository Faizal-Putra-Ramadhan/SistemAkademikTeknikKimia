@extends('layouts.app')

@section('title', 'Pengajuan Penelitian')
@section('page-title', 'Pengajuan Penelitian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-bold">Daftar Pengajuan Penelitian</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Penelitian</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanPenelitian as $penelitian)
                    <tr>
                        <td>{{ $penelitian->user_nama }}</td>
                        <td>{{ Str::limit($penelitian->judul_penelitian, 50) }}</td>
                        <td>{{ $penelitian->dosen_pembimbing }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($penelitian->status == 'menunggu')
                                <span class="badge badge-warning">Menunggu</span>
                            @elseif($penelitian->status == 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($penelitian->status == 'menunggu')
                            <div class="flex gap-2">
                                <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="btn btn-info btn-sm">
                                    Detail
                                </button>
                                <form action="{{ route('laboran.penelitian.setujui', $penelitian->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('laboran.penelitian.tolak', $penelitian->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                            @else
                                <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="btn btn-info btn-sm">
                                    Detail
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500">Tidak ada data pengajuan penelitian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showDetailPenelitian(id) {
    window.location.href = '/laboran/penelitian/' + id;
}
</script>
@endpush