@extends('layouts.mahasiswa')
@section('title', 'Pengajuan Penelitian - ' . $lab->Nama_Laboratorium)

@section('content')
<div class="container" style="max-width:1000px; margin:40px auto; padding:20px;">
    <h1 style="text-align:center; color:white; font-size:42px; margin-bottom:10px;">
        Pengajuan Penelitian
    </h1>
    <h2 style="text-align:center; color:#ddd; font-size:26px; margin-bottom:40px;">
        {{ $lab->Nama_Laboratorium }}
    </h2>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:18px; border-radius:16px; text-align:center; margin-bottom:30px; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="background:white; border-radius:24px; padding:40px; box-shadow:0 15px 50px rgba(0,0,0,0.12);">
        <form action="{{ route('mahasiswa.pengajuan-penelitian.store', $lab->id) }}" method="POST">
            @csrf
            <input type="hidden" name="user_nama" value="Rudi Hartono">

            <div style="margin-bottom:28px;">
                <label style="font-weight:600; font-size:19px; color:#333; display:block; margin-bottom:12px;">
                    Judul Penelitian
                </label>
                <input type="text" name="judul_penelitian" required placeholder="Contoh: Sintesis Nanopartikel Perak dari Ekstrak Daun Jambu Biji"
                       style="width:100%; padding:18px; border-radius:14px; font-size:16px; border:2px solid #e0e0e0; transition:0.3s;"
                       onfocus="this.style.borderColor='#0d6efd'" onblur="this.style.borderColor='#e0e0e0'">
            </div>

            <div style="margin-bottom:28px;">
                <label style="font-weight:600; font-size:19px; color:#333; display:block; margin-bottom:12px;">
                    Deskripsi Penelitian
                </label>
                <textarea name="deskripsi" rows="6" required placeholder="Jelaskan tujuan, metode, dan manfaat penelitian..."
                          style="width:100%; padding:18px; border-radius:14px; font-size:16px; resize:vertical; border:2px solid #e0e0e0; transition:0.3s;"
                          onfocus="this.style.borderColor='#0d6efd'" onblur="this.style.borderColor='#e0e0e0'"></textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:28px;">
                <div>
                    <label style="font-weight:600; font-size:19px; color:#333; display:block; margin-bottom:12px;">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" required min="{{ date('Y-m-d') }}"
                           style="width:100%; padding:18px; border-radius:14px; font-size:16px; border:2px solid #e0e0e0;">
                </div>
                <div>
                    <label style="font-weight:600; font-size:19px; color:#333; display:block; margin-bottom:12px;">
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" required
                           style="width:100%; padding:18px; border-radius:14px; font-size:16px; border:2px solid #e0e0e0;">
                </div>
            </div>

            <div style="margin-bottom:40px;">
    <label style="font-weight:600; font-size:19px; color:#333; display:block; margin-bottom:12px;">
        Dosen Pembimbing <span style="color:#0d6efd;">(Wajib)</span>
    </label>
    <select name="dosen_id" required
            style="width:100%; padding:18px; border-radius:14px; font-size:16px; border:2px solid #e0e0e0; background:white; transition:0.3s; appearance:none; background-image:url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat:no-repeat; background-position:right 16px center; background-size:12px;"
            onfocus="this.style.borderColor='#0d6efd'" onblur="this.style.borderColor='#e0e0e0'">
        <option value="">-- Pilih Dosen Pembimbing --</option>
        @foreach($dosens as $dosen)
            <option value="{{ $dosen->id }}">
                {{ $dosen->Nama }}
                @if($dosen->UserID ?? $dosen->email)
                    ({{ $dosen->UserID ?? $dosen->email }})
                @endif
            </option>
        @endforeach
    </select>
    <small style="color:#666; font-size:14px; display:block; margin-top:8px;">
        Total dosen tersedia: <strong>{{ $dosens->count() }}</strong> orang
    </small>
</div>

            <div style="text-align:center;">
                <button type="submit" style="background:#0d6efd; color:white; padding:20px 90px; border:none; border-radius:50px; font-size:20px; font-weight:700; cursor:pointer; box-shadow:0 15px 40px rgba(13,110,253,0.4); transition:0.3s;"
                        onmouseover="this.style.transform='translateY(-5px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    Ajukan Penelitian
                </button>
            </div>
        </form>

        <div style="text-align:center; margin-top:40px;">
            <a href="{{ route('mahasiswa.aktivitas', $lab->id) }}" style="color:#0d6efd; font-size:17px; text-decoration:underline;">
                Kembali ke Aktivitas Saya
            </a>
        </div>
    </div>
</div>
@endsection