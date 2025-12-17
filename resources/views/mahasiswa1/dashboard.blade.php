@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="lab-selector">
        <h1 style="color:white;margin-bottom:20px;">Portal Laboratorium</h1>
        <p style="color:white;margin-bottom:20px;">Pilih laboratorium yang ingin kamu akses</p>
        <form action="{{ route('mahasiswa.lab') }}" method="GET">
            <select name="lab_id" onchange="this.form.submit()" required>
                <option value="">-- Pilih Laboratorium --</option>
                @foreach(\App\Models\DaftarLab::all() as $lab)
                    <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->Nama_Laboratorium }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if(request('lab_id'))
        @php
            $lab = \App\Models\DaftarLab::findOrFail(request('lab_id'));
        @endphp

        <div class="card" style="text-align:center;">
            <h2>Kamu sedang mengakses:</h2>
            <h1 style="color:var(--primary);margin:20px 0;">{{ $lab->Nama_Laboratorium }}</h1>
            <p><strong>Kepala Lab:</strong> {{ $lab->Kepala_Labolatorium }}</p>
        </div>

        <div class="grid">
            <a href="{{ route('mahasiswa.alat', $lab->id) }}" class="menu-card">
                <span>Tools</span>
                <h3>Daftar Alat & Aset</h3>
                <p>Cari dan lihat semua alat laboratorium</p>
            </a>

            <a href="{{ route('mahasiswa.aktivitas', $lab->id) }}" class="menu-card">
                <span>History</span>
                <h3>Aktivitas Saya</h3>
                <p>Riwayat peminjaman & pengajuan</p>
            </a>

            <a href="{{ route('mahasiswa.pinjam-alat', $lab->id) }}" class="menu-card">
                <span>Borrow</span>
                <h3>Pinjam Alat</h3>
                <p>Ajukan peminjaman alat lab</p>
            </a>

            <a href="{{ route('mahasiswa.pinjam-ruangan', $lab->id) }}" class="menu-card">
                <span>Room</span>
                <h3>Pinjam Ruangan</h3>
                <p>Booking jadwal penggunaan lab</p>
            </a>

            <a href="{{ route('mahasiswa.pengajuan-penelitian', $lab->id) }}" class="menu-card">
                <span>Research</span>
                <h3>Pengajuan Penelitian</h3>
                <p>Ajukan proposal penelitian</p>
            </a>
        </div>

    @else
        <div class="no-lab">
            <h2>Pilih laboratorium untuk memulai</h2>
            <p>Silakan pilih salah satu laboratorium di atas</p>
        </div>
    @endif
@endsection