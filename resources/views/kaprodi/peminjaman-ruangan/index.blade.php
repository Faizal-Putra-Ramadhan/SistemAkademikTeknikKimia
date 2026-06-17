@extends('layouts.app')
@section('title', 'Peminjaman Ruangan')
@section('page-title', 'Peminjaman Ruangan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background-color: #f8f9fa; }
        .tab-button.active { 
            border-bottom: 3px solid #007bff; 
            color: #007bff !important; 
            font-weight: bold;
        }
        .card-peminjaman {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .card-peminjaman:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-label { font-size: 0.8rem; color: #6c757d; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: #343a40; }
</style>
@endpush

@section('content')
<header class="mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 font-weight-bold text-dark">📋 Monitoring Peminjaman Ruangan</h1>
                    <p class="text-muted">Pantau dan tinjau permintaan peminjaman laboratorium (View Only)</p>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded shadow-sm mb-4">
            <nav class="nav nav-justified border-bottom">
                <button onclick="switchTab('menunggu')" id="tab-menunggu" 
                    class="nav-item nav-link py-3 tab-button active text-muted">
                    <i class="fas fa-hourglass-half mr-2"></i> Sedang Diproses ({{ $peminjamanRuangans->total() }})
                </button>
                <button onclick="switchTab('riwayat')" id="tab-riwayat"
                    class="nav-item nav-link py-3 tab-button text-muted">
                    <i class="fas fa-history mr-2"></i> Riwayat
                </button>
            </nav>
        </div>

        <div id="content-menunggu" class="tab-content">
            <div class="row">
                @forelse($peminjamanRuangans as $peminjaman)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card card-peminjaman h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge badge-{{ $peminjaman->getStatusColor() }} status-badge text-white">
                                        {{ $peminjaman->getStatusLabel() }}
                                    </span>
                                    <small class="text-muted">ID #{{ $peminjaman->id }}</small>
                                </div>
                                
                                <h5 class="card-title font-weight-bold mb-1 text-primary">
                                    {{ $peminjaman->daftarLab->Nama_Laboratorium }}
                                </h5>
                                <p class="text-dark small mb-3"><i class="fas fa-user mr-1 text-muted"></i> {{ $peminjaman->user_nama }}</p>
                                
                                <hr class="my-3">
                                
                                <div class="row">
                                    <div class="col-6">
                                        <p class="info-label">Tanggal</p>
                                        <p class="info-value small">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="info-label">Waktu</p>
                                        <p class="info-value small">{{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</p>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <p class="info-label">Keperluan</p>
                                    <p class="small text-muted italic border-left pl-2">"{{ Str::limit($peminjaman->keperluan, 80) }}"</p>
                                </div>

                                @if($peminjaman->catatan_laboran)
                                    <div class="mt-3 p-2 bg-light rounded border">
                                        <p class="info-label mb-0" style="font-size: 0.7rem;"><i class="fas fa-info-circle mr-1"></i> Catatan Laboran:</p>
                                        <p class="small mb-0 italic">{{ Str::limit($peminjaman->catatan_laboran, 50) }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0 pb-4 px-4">
                                <a href="{{ route('kaprodi.peminjaman-ruangan.show', $peminjaman->id) }}" 
                                   class="btn btn-outline-primary btn-block font-weight-bold">
                                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded shadow-sm">
                            <img src="https://illustrations.popsy.co/gray/empty-folder.svg" alt="empty" style="width: 150px;" class="mb-3">
                            <p class="text-muted">Tidak ada permohonan yang sedang aktif saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $peminjamanRuangans->links() }}
            </div>
        </div>

        <div id="content-riwayat" class="tab-content d-none">
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Lab</th>
                            <th>Pemohon</th>
                            <th>Jadwal</th>
                            <th>Status Kaprodi</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $peminjaman)
                            <tr>
                                <td class="align-middle font-weight-bold">{{ $peminjaman->daftarLab->Nama_Laboratorium }}</td>
                                <td class="align-middle">{{ $peminjaman->user_nama }}</td>
                                <td class="align-middle small">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}<br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge status-badge badge-{{ $peminjaman->getStatusColor() }} text-white">
                                        @if(in_array($peminjaman->status, ['disetujui', 'disetujui_final']))
                                            ✅ Disetujui
                                        @elseif($peminjaman->status === 'ditolak')
                                            ❌ Ditolak
                                        @elseif($peminjaman->status === 'dikembalikan')
                                            🔄 Selesai
                                        @else
                                            {{ $peminjaman->getStatusLabel() }}
                                        @endif
                                    </span>
                                </td>
                                <td class="align-middle text-right">
                                    <a href="{{ route('kaprodi.peminjaman-ruangan.show', $peminjaman->id) }}" class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat persetujuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $riwayat->links() }}
            </div>
        </div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
            // Sembunyikan semua konten tab
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('d-none'));
            
            // Hapus kelas aktif dari semua tombol
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Tampilkan konten yang dipilih
            document.getElementById('content-' + tab).classList.remove('d-none');
            
            // Aktifkan tombol yang diklik
            document.getElementById('tab-' + tab).classList.add('active');
        }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
