@extends('layouts.mahasiswa')
@section('title', 'Daftar Alat Laboratorium')

@section('content')
<div class="container" style="padding: 20px;">
    <!-- Kita ambil data lab dari route/controller, bukan find manual lagi -->
    <!-- $lab sudah dikirim dari route dengan relasi alatLabs -->

    <div style="text-align:center; margin-bottom:40px;">
        <h1 style="color:white; font-size:42px; margin-bottom:10px;">
            {{ $lab->Nama_Laboratorium }}
        </h1>
        <p style="color:#ddd; font-size:18px;">
            Kepala Lab: {{ $lab->Kepala_Labolatorium }} | 
            Safety Officer: {{ $lab->Safety_Officer ?? 'Tidak ada data' }}
        </p>
    </div>

    <!-- Pencarian -->
    <div class="card" style="margin-bottom:30px;">
        <input type="text" id="searchInput" onkeyup="searchAlat()" placeholder="Cari nama alat..."
               style="width:100%; padding:18px; font-size:18px; border:none; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
    </div>

    <!-- Grid Alat -->
    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:25px;">
        @forelse($lab->alatLabs as $alat)
        <div class="menu-card alat-card" style="padding:20px; border-radius:18px; overflow:hidden; transition:0.3s;">
            @if($alat->foto)
                <img src="{{ asset('storage/' . $alat->foto) }}" 
                     alt="{{ $alat->nama_alat }}"
                     style="width:100%; height:220px; object-fit:cover; border-radius:12px; margin-bottom:15px;">
            @else
                <div style="width:100%; height:220px; background:#f0f0f0; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#999; font-size:18px; margin-bottom:15px; border:2px dashed #ccc;">
                    Tidak ada foto
                </div>
            @endif

            <h3 style="margin:10px 0; font-size:20px; color:#333;">{{ $alat->nama_alat }}</h3>

            <div style="margin:10px 0; font-size:15px; color:#555; line-height:1.6;">
                {!! nl2br(e(Str::limit($alat->deskripsi ?? 'Tidak ada deskripsi', 120))) !!}
            </div>

            <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    @if($alat->jumlah_tersedia > 5)
                        <span style="color:#198754; font-weight:bold;">Tersedia</span>
                    @elseif($alat->jumlah_tersedia > 0)
                        <span style="color:#ffc107; font-weight:bold;">Terbatas</span>
                    @else
                        <span style="color:#dc3545; font-weight:bold;">Habis</span>
                    @endif
                    <br>
                    <strong style="font-size:28px; color:#333;">{{ $alat->jumlah_tersedia }}</strong>
                    <small style="color:#666;"> unit</small>
                </div>

                <a href="{{ route('mahasiswa.pinjam-alat', $lab->id) }}#alat-{{ $alat->id }}"
                   style="background:#0d6efd; color:white; padding:14px 24px; border-radius:50px; text-decoration:none; font-weight:600; box-shadow:0 4px 15px rgba(13,110,253,0.4);">
                    Pinjam
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; text-align:center; padding:80px; background:rgba(255,255,255,0.1); border-radius:20px;">
            <h2 style="color:white;">Belum ada alat terdaftar</h2>
            <p style="color:#ddd; margin-top:10px;">Laboratorium ini masih kosong. Hubungi admin untuk menambahkan alat.</p>
        </div>
        @endforelse
    </div>

    <div style="text-align:center; margin:50px 0;">
        <a href="{{ route('mahasiswa.dashboard') }}"
           style="color:white; font-size:18px; text-decoration:underline; opacity:0.9;">
            ← Kembali ke Pilih Laboratorium
        </a>
    </div>
</div>

<script>
function searchAlat() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".alat-card");
    
    cards.forEach(card => {
        let text = card.textContent.toLowerCase();
        card.style.display = text.includes(input) ? "" : "none";
    });
}
</script>
@endsection