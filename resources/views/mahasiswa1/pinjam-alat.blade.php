@extends('layouts.mahasiswa')
@section('title', 'Pinjam Alat - ' . $lab->Nama_Laboratorium)

@section('content')
<div class="container" style="max-width:1000px; margin:40px auto; padding:20px;">
    <h1 style="text-align:center; color:white; font-size:42px; margin-bottom:10px;">
        Pinjam Alat Laboratorium
    </h1>
    <h2 style="text-align:center; color:#ddd; font-size:26px; margin-bottom:40px;">
        {{ $lab->Nama_Laboratorium }}
    </h2>

    <!-- Notifikasi -->
    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:18px; border-radius:16px; text-align:center; font-weight:600; margin-bottom:30px; font-size:18px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da; color:#721c24; padding:18px; border-radius:16px; text-align:center; font-weight:600; margin-bottom:30px; font-size:18px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="background:white; border-radius:24px; padding:40px; box-shadow:0 10px 40px rgba(0,0,0,0.1);">
        <form action="{{ route('mahasiswa.pinjam-alat.store', $lab->id) }}" method="POST">
            @csrf
            <input type="hidden" name="user_nama" value="Rudi Hartono">

            <!-- Grid: Foto + Form -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:start;">
                
                <!-- KOLOM KIRI: Daftar Alat dengan Foto -->
                <div>
                    <h3 style="color:#333; font-size:22px; margin-bottom:20px; font-weight:600;">
                        Pilih Alat yang Ingin Dipinjam
                    </h3>
                    <div style="max-height:600px; overflow-y:auto; padding-right:10px;">
                        @forelse($lab->alatLabs as $alat)
                            @if($alat->jumlah_tersedia > 0)
                                <label style="display:block; margin-bottom:20px; border:2px solid {{ old('alat_lab_id') == $alat->id ? '#0d6efd' : '#e0e0e0' }}; border-radius:16px; overflow:hidden; cursor:pointer; transition:0.3s; background:white;"
                                       onclick="pilihAlat({{ $alat->id }})">
                                    <div style="display:flex;">
                                        <div style="width:140px; flex-shrink:0;">
                                            @if($alat->foto)
                                                <img src="{{ asset('storage/' . $alat->foto) }}" 
                                                     style="width:100%; height:140px; object-fit:cover; border-radius:12px 0 0 12px;">
                                            @else
                                                <div style="width:140px; height:140px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#999; font-size:14px; border-radius:12px 0 0 12px;">
                                                    Tidak ada foto
                                                </div>
                                            @endif
                                        </div>
                                        <div style="padding:16px; flex-grow:1;">
                                            <div style="font-weight:600; font-size:18px; color:#333;">
                                                {{ $alat->nama_alat }}
                                            </div>
                                            <div style="color:#555; font-size:14px; margin:8px 0; line-height:1.5;">
                                                {!! Str::limit($alat->deskripsi ?? 'Tidak ada deskripsi', 100) !!}
                                            </div>
                                            <div style="margin-top:10px;">
                                                <span style="background:#d4edda; color:#155724; padding:6px 12px; border-radius:50px; font-size:13px; font-weight:600;">
                                                    Tersedia: {{ $alat->jumlah_tersedia }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="padding:20px; display:flex; align-items:center;">
                                            <input type="radio" name="alat_lab_id" value="{{ $alat->id }}" 
                                                   id="alat-{{ $alat->id }}" required
                                                   style="width:24px; height:24px; cursor:pointer;"
                                                   {{ old('alat_lab_id') == $alat->id ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @empty
                            <div style="text-align:center; padding:60px; color:#999; font-size:18px;">
                                Tidak ada alat yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- KOLOM KANAN: Form Input -->
                <div>
                    <h3 style="color:#333; font-size:22px; margin-bottom:25px; font-weight:600;">
                        Detail Peminjaman
                    </h3>

                    <div style="background:#f8f9fa; padding:20px; border-radius:16px; margin-bottom:25px;">
                        <p style="margin:0; color:#666;">
                            <strong>Nama:</strong> Rudi Hartono<br>
                            <strong>Status:</strong> Mahasiswa Aktif
                        </p>
                    </div>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                            Tanggal Pinjam
                        </label>
                        <input type="date" name="tanggal_pinjam" required value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" style="width:100%; padding:16px; border-radius:12px; font-size:16px; border:1px solid #ddd;">
                    </div>

                    <div style="margin-bottom:30px;">
                        <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                            Tanggal Kembali <small style="color:#666;">(Opsional)</small>
                        </label>
                        <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                               min="{{ date('Y-m-d') }}" style="width:100%; padding:16px; border-radius:12px; font-size:16px; border:1px solid #ddd;">
                    </div>

                    <div style="text-align:center;">
                        <button type="submit" style="background:#0d6efd; color:white; padding:18px 70px; border:none; border-radius:50px; font-size:20px; font-weight:600; cursor:pointer; box-shadow:0 12px 35px rgba(13,110,253,0.4); transition:0.3s;"
                                onmouseover="this.style.transform='translateY(-3px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                            Ajukan Peminjaman
                        </button>
                    </div>
                </div>
            </div>

            <div style="text-align:center; margin-top:40px;">
                <a href="{{ route('mahasiswa.alat', $lab->id) }}" style="color:#0d6efd; font-size:17px; text-decoration:underline;">
                    ← Kembali ke Daftar Alat
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function pilihAlat(id) {
    document.querySelectorAll('input[name="alat_lab_id"]').forEach(radio => {
        radio.checked = (radio.value == id);
    });
}
</script>
@endsection