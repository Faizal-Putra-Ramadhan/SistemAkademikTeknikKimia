@extends('layouts.mahasiswa')
@section('title', 'Pinjam Ruangan - ' . $lab->Nama_Laboratorium)

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto; padding:20px;">
    <h1 style="text-align:center; color:white; font-size:42px; margin-bottom:10px;">
        Pinjam Ruangan Laboratorium
    </h1>
    <h2 style="text-align:center; color:#ddd; font-size:26px; margin-bottom:40px;">
        {{ $lab->Nama_Laboratorium }}
    </h2>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:18px; border-radius:16px; text-align:center; margin-bottom:30px; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da; color:#721c24; padding:18px; border-radius:16px; text-align:center; margin-bottom:30px; font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="background:white; border-radius:24px; padding:40px; box-shadow:0 10px 40px rgba(0,0,0,0.1);">
        <form action="{{ route('mahasiswa.pinjam-ruangan.store', $lab->id) }}" method="POST">
            @csrf
            <input type="hidden" name="user_nama" value="Rudi Hartono">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:40px;">
                <!-- Kiri: Form -->
                <div>
                    <h3 style="color:#333; font-size:24px; margin-bottom:25px; font-weight:600;">Detail Pengajuan</h3>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                            Tanggal Penggunaan
                        </label>
                        <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}" 
                               style="width:100%; padding:16px; border-radius:12px; font-size:16px; border:1px solid #ddd;">
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:25px;">
                        <div>
                            <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                                Jam Mulai
                            </label>
                            <input type="time" name="jam_mulai" required style="width:100%; padding:16px; border-radius:12px; font-size:16px;">
                        </div>
                        <div>
                            <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                                Jam Selesai
                            </label>
                            <input type="time" name="jam_selesai" required style="width:100%; padding:16px; border-radius:12px; font-size:16px;">
                        </div>
                    </div>

                    <div style="margin-bottom:30px;">
                        <label style="font-weight:600; font-size:18px; color:#333; display:block; margin-bottom:10px;">
                            Keperluan
                        </label>
                        <textarea name="keperluan" rows="5" required placeholder="Contoh: Praktikum Kimia Organik Kelas B..."
                                  style="width:100%; padding:16px; border-radius:12px; font-size:16px; resize:vertical; border:1px solid #ddd;"></textarea>
                    </div>

                    <div style="text-align:center;">
                        <button type="submit" style="background:#0d6efd; color:white; padding:18px 80px; border:none; border-radius:50px; font-size:20px; font-weight:600; cursor:pointer; box-shadow:0 12px 35px rgba(13,110,253,0.4);">
                            Ajukan Peminjaman Ruangan
                        </button>
                    </div>
                </div>

                <!-- Kanan: Kalender -->
                <div>
                    <h3 style="color:#333; font-size:24px; margin-bottom:25px; font-weight:600;">Jadwal Terbooking</h3>
                    <div id="calendar" style="background:#f8f9fa; padding:20px; border-radius:16px;"></div>
                </div>
            </div>
        </form>

        <div style="text-align:center; margin-top:40px;">
            <a href="{{ route('mahasiswa.dashboard') }}" style="color:#0d6efd; font-size:17px; text-decoration:underline;">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<!-- FullCalendar CDN -->
<!-- FullCalendar CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '18:00:00',
        height: 'auto',
        events: [
            @foreach($lab->peminjamanRuangans as $p)
            {
                title: "{{ $p->user_nama }} - {{ ucfirst($p->status) }}",
                start: "{{ $p->tanggal }}T{{ $p->jam_mulai }}",
                end: "{{ $p->tanggal }}T{{ $p->jam_selesai }}",
                color: "{{ $p->status == 'disetujui' ? '#28a745' : ($p->status == 'ditolak' ? '#dc3545' : '#ffc107') }}",
                textColor: 'white',
                borderColor: '#333'
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        eventDidMount: function(info) {
            info.el.style.border = '2px solid #333';
            info.el.style.borderRadius = '8px';
        }
    });
    calendar.render();
});
</script>
@endsection