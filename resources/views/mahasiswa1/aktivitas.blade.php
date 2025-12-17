@extends('layouts.mahasiswa')
@section('title', 'Aktivitas Saya - ' . $lab->Nama_Laboratorium)

@section('content')
<div class="container" style="max-width:1200px; margin:40px auto; padding:20px;">
    <h1 style="text-align:center; color:white; font-size:42px; margin-bottom:10px;">
        Aktivitas Saya
    </h1>
    <h2 style="text-align:center; color:#ddd; font-size:26px; margin-bottom:40px;">
        {{ $lab->Nama_Laboratorium }}
    </h2>

    <!-- Tab Navigasi Keren -->
    <div style="text-align:center; margin-bottom:40px;">
        <div style="display:inline-flex; background:white; border-radius:50px; padding:8px; box-shadow:0 8px 30px rgba(0,0,0,0.15);">
            <a href="#alat" class="tab-btn active">Peminjaman Alat</a>
            <a href="#ruangan" class="tab-btn">Ruangan Lab</a>
            <a href="#penelitian" class="tab-btn">Penelitian</a>
        </div>
    </div>

    <div class="card" style="background:white; border-radius:24px; padding:35px; min-height:600px; box-shadow:0 10px 40px rgba(0,0,0,0.1);">

        <!-- TAB PEMINJAMAN ALAT -->
        <div id="alat" class="tab-content active">
            <h3 style="color:#333; font-size:26px; margin-bottom:25px; font-weight:600;">Peminjaman Alat</h3>
            @php
                $peminjamanAlat = \App\Models\PeminjamanAlat::whereHas('alatLab.daftarLab', fn($q) => $q->where('id', $lab->id))
                    ->latest()->get();
            @endphp

            @forelse($peminjamanAlat as $item)
                @php $alat = $item->alatLab; @endphp
                <div style="display:flex; gap:20px; padding:22px; background:#f8f9fa; border-radius:16px; margin-bottom:20px; align-items:center; transition:0.3s;" onmouseover="this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                    @if($alat && $alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" style="width:110px; height:110px; object-fit:cover; border-radius:14px;">
                    @else
                        <div style="width:110px; height:110px; background:#e9ecef; border-radius:14px; display:flex; align-items:center; justify-content:center; color:#999; font-size:14px;">
                            No Photo
                        </div>
                    @endif

                    <div style="flex-grow:1;">
                        <h4 style="margin:0; font-size:20px; color:#333; font-weight:600;">
                            {{ $alat?->nama_alat ?? 'Alat tidak ditemukan' }}
                        </h4>
                        <p style="margin:6px 0; color:#555; font-size:15px;">
                            Pinjam: <strong>{{ $item->tanggal_pinjam }}</strong>
                            @if($item->tanggal_kembali) → Kembali: <strong>{{ $item->tanggal_kembali }}</strong>@endif
                        </p>
                        <p style="margin:6px 0; color:#777; font-size:14px;">
                            Diajukan: {{ $item->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div>
                        @switch($item->status)
                            @case('menunggu')
                                <span style="background:#fff3cd; color:#856404; padding:12px 24px; border-radius:50px; font-weight:600; font-size:15px;">Menunggu</span>
                                @break
                            @case('disetujui')
                                <span style="background:#d4edda; color:#155724; padding:12px 24px; border-radius:50px; font-weight:600; font-size:15px;">Disetujui</span>
                                @break
                            @case('dikembalikan')
                                <span style="background:#d1ecf1; color:#0c5460; padding:12px 24px; border-radius:50px; font-weight:600; font-size:15px;">Dikembalikan</span>
                                @break
                            @case('ditolak')
                                <span style="background:#f8d7da; color:#721c24; padding:12px 24px; border-radius:50px; font-weight:600; font-size:15px;">Ditolak</span>
                                @break
                        @endswitch
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:100px; color:#aaa;">
                    <h3>Belum ada peminjaman alat</h3>
                    <p>Yuk mulai pinjam alat untuk praktikum!</p>
                </div>
            @endforelse
        </div>

        <!-- TAB PEMINJAMAN RUANGAN -->
        <div id="ruangan" class="tab-content" style="display:none;">
            <h3 style="color:#333; font-size:26px; margin-bottom:25px; font-weight:600;">Peminjaman Ruangan</h3>
            @php
                $peminjamanRuangan = $lab->peminjamanRuangans()->latest()->get();
            @endphp

            @forelse($peminjamanRuangan as $item)
                <div style="padding:22px; background:#f8f9fa; border-radius:16px; margin-bottom:20px; transition:0.3s;" onmouseover="this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'" on Hiveout="this.style.boxShadow='none'">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h4 style="margin:0; font-size:20px; color:#333; font-weight:600;">
    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
</h4>
                            <p style="margin:8px 0; font-size:16px; color:#555;">
                                <strong>{{ $item->jam_mulai }}</strong> → <strong>{{ $item->jam_selesai }}</strong>
                            </p>
                            <p style="margin:8px 0; color:#666;">
                                Keperluan: <strong>{{ $item->keperluan }}</strong>
                            </p>
                            <p style="margin:5px 0; color:#888; font-size:14px;">
    Diajukan: {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') : 'Tidak diketahui' }}
</p>
                        </div>
                        <div>
                            @switch($item->status)
                                @case('menunggu')
                                    <span style="background:#fff3cd; color:#856404; padding:12px 28px; border-radius:50px; font-weight:600; font-size:15px;">Menunggu</span>
                                    @break
                                @case('disetujui')
                                    <span style="background:#d4edda; color:#155724; padding:12px 28px; border-radius:50px; font-weight:600; font-size:15px;">Disetujui</span>
                                    @break
                                @case('ditolak')
                                    <span style="background:#f8d7da; color:#721c24; padding:12px 28px; border-radius:50px; font-weight:600; font-size:15px;">Ditolak</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:100px; color:#aaa;">
                    <h3>Belum ada peminjaman ruangan</h3>
                    <p>Silakan ajukan jadwal penggunaan lab!</p>
                </div>
            @endforelse
        </div>

        <!-- TAB PENELITIAN (kosong dulu) -->
        <div id="penelitian" class="tab-content" style="display:none;">
    <h3 style="color:#333; font-size:26px; margin-bottom:25px; font-weight:600;">Pengajuan Penelitian</h3>
    @php $penelitian = $lab->pengajuanPenelitians()->latest()->get(); @endphp

    @forelse($penelitian as $p)
        <div style="padding:25px; background:#f8f9fa; border-radius:16px; margin-bottom:20px; border-left:6px solid #0d6efd;">
            <h4 style="margin:0 0 12px 0; font-size:21px; color:#333; font-weight:600;">
                {{ $p->judul_penelitian }}
            </h4>
            <p style="margin:8px 0; color:#555; line-height:1.6;">
                {{ Str::limit($p->deskripsi, 150) }}
            </p>
            <p style="margin:10px 0; color:#666;">
                Periode: <strong>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</strong> → 
                         <strong>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</strong>
            </p>
            <p style="margin:5px 0; color:#777;">
                Dosen Pembimbing: <strong>{{ $p->dosen_pembimbing }}</strong>
            </p>
            <div style="margin-top:15px; display:inline-block;">
                @if($p->status == 'menunggu')
                    <span style="background:#fff3cd; color:#856404; padding:10px 24px; border-radius:50px; font-weight:600;">Menunggu Persetujuan</span>
                @elseif($p->status == 'disetujui')
                    <span style="background:#d4edda; color:#155724; padding:10px 24px; border-radius:50px; font-weight:600;">Disetujui</span>
                @else
                    <span style="background:#f8d7da; color:#721c24; padding:10px 24px; border-radius:50px; font-weight:600;">Ditolak</span>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:100px; color:#aaa;">
            <h3>Belum ada pengajuan penelitian</h3>
            <p>
                <a href="{{ route('mahasiswa.pengajuan-penelitian', $lab->id) }}" style="color:#0d6efd; font-weight:600; text-decoration:underline;">
                    Ajukan penelitian sekarang →
                </a>
            </p>
        </div>
    @endforelse
</div>
    </div>

    <div style="text-align:center; margin-top:40px;">
        <a href="{{ route('mahasiswa.alat', $lab->id) }}" style="color:white; font-size:18px; text-decoration:underline;">
            Kembali ke Daftar Alat
        </a>
    </div>
</div>

<style>
.tab-btn {
    padding:14px 32px; border-radius:50px; font-weight:600; transition:0.3s; color:#333; text-decoration:none;
}
.tab-btn.active, .tab-btn:hover {
    background:#0d6efd; color:white !important;
}
</style>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        this.classList.add('active');
        document.querySelector(this.getAttribute('href')).style.display = 'block';
    });
});
</script>
@endsection