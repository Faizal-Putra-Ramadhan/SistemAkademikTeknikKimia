@extends('layouts.app')

@section('title', 'Peminjaman Ruangan')
@section('page-title', 'Peminjaman Ruangan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Peminjaman Ruangan</h3>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamanRuangan as $ruangan)
                <tr>
                    <td>{{ $ruangan->user_nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($ruangan->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $ruangan->jam_mulai }} - {{ $ruangan->jam_selesai }}</td>
                    <td>{{ Str::limit($ruangan->keperluan, 50) }}</td>
                    <td>
                        @if($ruangan->status == 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($ruangan->status == 'menunggu_kepala_lab')
                            <span class="badge badge-info">Menunggu Kepala Lab</span>
                        @elseif($ruangan->status == 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($ruangan->status == 'dikembalikan')
                            <span class="badge badge-secondary">Dikembalikan</span>
                        @elseif($ruangan->status == 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-secondary">{{ $ruangan->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($ruangan->status == 'menunggu')
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn btn-success btn-sm" onclick="document.getElementById('modal-setujui-{{ $ruangan->id }}').style.display='flex'">Setujui</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('modal-tolak-{{ $ruangan->id }}').style.display='flex'">Tolak</button>
                        </div>

                        {{-- Modal Setujui --}}
                        <div id="modal-setujui-{{ $ruangan->id }}" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
                            <div style="background:#fff; border-radius:10px; padding:1.5rem; width:90%; max-width:450px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                                <h4 style="margin:0 0 0.5rem; color:#333;">Setujui Peminjaman</h4>
                                <p style="font-size:13px; color:#666; margin-bottom:1rem;">Peminjaman oleh <strong>{{ $ruangan->user_nama }}</strong></p>
                                <form action="{{ route('laboran.ruangan.setujui', $ruangan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="margin-bottom:1rem;">
                                        <label style="display:block; font-weight:600; margin-bottom:0.25rem; font-size:13px; color:#333;">Catatan (opsional)</label>
                                        <textarea name="catatan" rows="3" style="width:100%; padding:0.5rem; border:1px solid #ddd; border-radius:5px; font-size:13px; font-family:inherit; resize:vertical;" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('[id^=modal-setujui]').style.display='none'">Batal</button>
                                        <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Tolak --}}
                        <div id="modal-tolak-{{ $ruangan->id }}" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
                            <div style="background:#fff; border-radius:10px; padding:1.5rem; width:90%; max-width:450px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                                <h4 style="margin:0 0 0.5rem; color:#333;">Tolak Peminjaman</h4>
                                <p style="font-size:13px; color:#666; margin-bottom:1rem;">Peminjaman oleh <strong>{{ $ruangan->user_nama }}</strong></p>
                                <form action="{{ route('laboran.ruangan.tolak', $ruangan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="margin-bottom:1rem;">
                                        <label style="display:block; font-weight:600; margin-bottom:0.25rem; font-size:13px; color:#333;">Alasan Penolakan (opsional)</label>
                                        <textarea name="catatan" rows="3" style="width:100%; padding:0.5rem; border:1px solid #ddd; border-radius:5px; font-size:13px; font-family:inherit; resize:vertical;" placeholder="Tuliskan alasan penolakan..."></textarea>
                                    </div>
                                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('[id^=modal-tolak]').style.display='none'">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @elseif($ruangan->status == 'disetujui')
                        <form action="{{ route('laboran.ruangan.kembalikan', $ruangan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary btn-sm">Dikembalikan</button>
                        </form>
                        @else
                            <span style="color: #9ca3af; font-size: 13px;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280;">Tidak ada data peminjaman ruangan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection