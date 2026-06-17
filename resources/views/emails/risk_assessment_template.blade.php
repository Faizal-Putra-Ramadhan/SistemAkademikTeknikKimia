<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
        .header { background: #004a99; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #ffffff; }
        .footer { background: #f8f9fa; color: #777; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 25px; background: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .info-table td { padding: 8px; border-bottom: 1px solid #f0f0f0; }
        .label { font-weight: bold; color: #555; width: 40%; }
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; background: #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sistem Risk Assessment</h2>
        </div>
        <div class="content">
            <p>Halo,</p>
            
            @if($type == 'ke_dosen')
                <p>Mahasiswa bimbingan Anda telah mengajukan <strong>Risk Assessment</strong> baru dan membutuhkan persetujuan Anda.</p>
            @elseif($type == 'dosen_setuju')
                <p>Selamat! Risk Assessment Anda telah <strong>Disetujui</strong> oleh Dosen Pembimbing dan diteruskan ke Safety Officer.</p>
            @elseif($type == 'ke_so')
                <p>Ada pengajuan Risk Assessment baru yang telah disetujui dosen dan memerlukan review/jadwal wawancara dari Anda.</p>
            @elseif($type == 'jadwal_so')
                <p>Safety Officer telah menentukan jadwal wawancara untuk Risk Assessment Anda:</p>
                <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 5px solid #ffc107;">
                    <strong>Jadwal: {{ $ra->jadwal_wawancara }}</strong>
                </div>
            @elseif($type == 'jadwal_options_so')
                <p>Safety Officer telah menyediakan <strong>beberapa pilihan jadwal wawancara</strong> untuk Risk Assessment Anda. Silakan pilih salah satu opsi yang paling sesuai:</p>
            @elseif($type == 'jadwal_dipilih_mahasiswa')
                <p>Mahasiswa <strong>{{ $ra->user->Nama }}</strong> telah memilih jadwal wawancara untuk Risk Assessment mereka. Berikut adalah jadwal yang telah dipilih:</p>
                <div style="background: #d4edda; padding: 15px; border-radius: 5px; border-left: 5px solid #28a745; margin: 20px 0;">
                    <strong style="color: #155724;">✅ JADWAL TERPILIH</strong>
                    <table style="width: 100%; margin-top: 10px; font-size: 14px;">
                        <tr>
                            <td style="color: #555; width: 30%; padding: 6px;"><strong>📅 Tanggal:</strong></td>
                            <td style="color: #333; font-weight: bold; padding: 6px;">{{ \Carbon\Carbon::parse($ra->jadwal_wawancara)->format('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="color: #555; padding: 6px;"><strong>⏰ Jam:</strong></td>
                            <td style="color: #333; font-weight: bold; padding: 6px;">{{ \Carbon\Carbon::parse($ra->jadwal_wawancara)->format('H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <td style="color: #555; padding: 6px;"><strong>📍 Lokasi:</strong></td>
                            <td style="color: #333; font-weight: bold; padding: 6px;">{{ $ra->tempat_wawancara }}</td>
                        </tr>
                    </table>
                </div>
                <p>Pastikan Anda siap pada jadwal yang telah dipilih. Jika ada masalah atau perlu perubahan jadwal, silakan hubungi mahasiswa tersebut.</p>
            @elseif($type == 'hasil_kalab')
                <p>Status Risk Assessment Anda di Laboratorium telah diperbarui oleh Kepala Lab.</p>
            @elseif($type == 'ke_kaprodi')
                <p>Mahasiswa telah mengajukan Risk Assessment yang sudah divalidasi Lab untuk mendapatkan persetujuan akhir dari Kaprodi.</p>
            @elseif($type == 'perpanjangan_deadline')
                <p>Halo <strong>{{ $ra->user->Nama }}</strong>,</p>
                <p>Ini adalah pengingat untuk <strong>melakukan perpanjangan pengajuan peminjaman alat</strong> terkait Risk Assessment Anda.</p>
                <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 5px solid #ffc107; margin: 15px 0;">
                    <strong>Mohon segera ajukan perpanjangan</strong> sebelum masa peminjaman berakhir agar tidak mengganggu kegiatan Anda di laboratorium.
                </div>
            @endif

            @if($type == 'ajukan_perpanjangan')
    <p>Mahasiswa <strong>{{ $ra->user->Nama }}</strong> telah mengajukan perpanjangan durasi untuk Risk Assessment berikut:</p>
    <div style="background: #eaf4ff; padding: 15px; border-radius: 5px; border-left: 5px solid #007bff;">
        <strong>Durasi Diminta:</strong> {{ $ra->durasi_perpanjangan_diminta }} Bulan<br>
        <strong>Alasan:</strong><br>
        <em>"{{ $ra->alasan_perpanjangan }}"</em>
    </div>
    <p>Mohon segera lakukan peninjauan pada sistem.</p>

@elseif($type == 'batal_perpanjangan')
    <p>Informasi: Mahasiswa <strong>{{ $ra->user->Nama }}</strong> telah <strong>Membatalkan</strong> pengajuan perpanjangan untuk RA #{{ $ra->id }}.</p>

@elseif($type == 'hasil_perpanjangan')
    <p>Halo {{ $ra->user->Nama }}, pengajuan perpanjangan Anda untuk topik <strong>{{ $ra->topik_judul }}</strong> telah diproses.</p>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;">
        <h3 style="color: {{ $ra->persetujuan_perpanjangan_kaprodi ? '#28a745' : '#dc3545' }}">
            {{ $ra->persetujuan_perpanjangan_kaprodi ? 'DISETUJUI' : 'DITOLAK' }}
        </h3>
    </div>
    @if($customMessage)
        <p><strong>Catatan Kaprodi:</strong> {{ $customMessage }}</p>
    @endif
@endif

@if($type == 'ke_kepala_lab')
    <p>Halo <strong>Kepala Laboratorium</strong>,</p>
    <p>Ada pengajuan Risk Assessment baru yang telah disetujui oleh Safety Officer dan saat ini <strong>menunggu validasi akhir</strong> dari Anda sebelum diteruskan ke Kaprodi.</p>
    
    <table style="width: 100%; margin-bottom: 20px;">
        <tr><td><strong>Topik:</strong></td><td>{{ $ra->topik_judul }}</td></tr>
        <tr><td><strong>Mahasiswa:</strong></td><td>{{ $ra->user->Nama }}</td></tr>
        <tr><td><strong>Lab:</strong></td><td>{{ $ra->daftarLab->Nama_Laboratorium }}</td></tr>
    </table>
@endif

@if($type == 'hasil_kaprodi')
                <p>Pengajuan Risk Assessment Anda telah selesai ditinjau oleh <strong>Kaprodi</strong>.</p>
                <p>Status Final: 
                    <span class="badge {{ $ra->persetujuan_kaprodi ? 'success' : 'danger' }}">
                        {{ $ra->persetujuan_kaprodi ? 'DISETUJUI' : 'DITOLAK' }}
                    </span>
                </p>
                @if($ra->persetujuan_kaprodi)
                    <p>Masa berlaku peminjaman alat Anda diatur hingga: <strong>{{ \Carbon\Carbon::parse($ra->batas_waktu_peminjaman)->format('d M Y') }}</strong></p>
                @endif

            @elseif($type == 'hasil_perpanjangan')
                <p>Permohonan <strong>Perpanjangan</strong> Risk Assessment Anda telah diproses.</p>
                <p>Keputusan: 
                    <span class="badge {{ $ra->persetujuan_perpanjangan_kaprodi ? 'success' : 'danger' }}">
                        {{ $ra->persetujuan_perpanjangan_kaprodi ? 'DISETUJUI' : 'DITOLAK' }}
                    </span>
                </p>
            @endif

            @if($customMessage)
                <div style="background: #f9f9f9; padding: 10px; border-left: 4px solid #ccc;">
                    <strong>Catatan:</strong><br>{{ $customMessage }}
                </div>
            @endif

            <!-- Jadwal Wawancara Options untuk type jadwal_options_so -->
            @if($type == 'jadwal_options_so' && $ra->jadwal_wawancara_options)
                <div style="margin: 20px 0;">
                    <h3 style="color: #004a99; margin-bottom: 15px;">📋 Opsi Jadwal Wawancara yang Tersedia:</h3>
                    @foreach($ra->jadwal_wawancara_options as $index => $option)
                        <div style="background: #f0f8ff; padding: 15px; margin-bottom: 12px; border-radius: 5px; border-left: 4px solid #004a99;">
                            <div style="font-weight: bold; color: #004a99; margin-bottom: 8px;">🕐 Opsi {{ $index + 1 }}</div>
                            <table style="width: 100%; font-size: 14px;">
                                <tr>
                                    <td style="color: #666; width: 30%; padding: 4px;">📅 Tanggal:</td>
                                    <td style="color: #333; font-weight: bold; padding: 4px;">{{ \Carbon\Carbon::parse($option['jadwal'])->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666; padding: 4px;">⏰ Jam:</td>
                                    <td style="color: #333; font-weight: bold; padding: 4px;">{{ $option['waktu'] ?? $option['jadwal'] }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666; padding: 4px;">📍 Lokasi:</td>
                                    <td style="color: #333; font-weight: bold; padding: 4px;">{{ $option['tempat'] }}</td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($customMessage)
                <div style="background: #f9f9f9; padding: 10px; border-left: 4px solid #ccc;">
                    <strong>Catatan:</strong><br>{{ $customMessage }}
                </div>
            @endif

            <table class="info-table">
                <tr><td class="label">Judul Topik</td><td>{{ $ra->topik_judul }}</td></tr>
                <tr><td class="label">Mahasiswa</td><td>{{ $ra->user->Nama }}</td></tr>
                <tr><td class="label">Laboratorium</td><td>{{ $ra->daftarLab->Nama_Laboratorium }}</td></tr>
                <tr><td class="label">Status Saat Ini</td><td><span class="status-badge">{{ str_replace('_', ' ', $ra->status) }}</span></td></tr>
            </table>

            @if($customMessage)
                <p style="margin-top:20px;"><strong>Catatan:</strong><br><em>"{{ $customMessage }}"</em></p>
            @endif

            <center>
                <a href="{{ url('/login') }}" class="btn">Lihat Detail di Sistem</a>
            </center>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Manajemen Laboratorium - Universitas Anda.<br>
            Email ini dikirim otomatis oleh sistem, mohon tidak membalas.
        </div>
    </div>
</body>
</html>