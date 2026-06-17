<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman Ruangan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">🏢 LIMS - Peminjaman Ruangan</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                @if($type === 'pengajuan_ke_laboran')
                    <h2 style="color: #333; margin-top: 0;">Pengajuan Peminjaman Ruangan Baru</h2>
                    <p>Halo <strong>Laboran</strong>,</p>
                    <p>Ada pengajuan peminjaman ruangan yang perlu Anda review:</p>

                @elseif($type === 'hasil_laboran')
                    @if($peminjamanRuangan->persetujuan_laboran)
                        <h2 style="color: #333; margin-top: 0;">Peminjaman Ruangan Disetujui Laboran</h2>
                        <p>Halo <strong>{{ $peminjamanRuangan->user_nama }}</strong>,</p>
                        <p>Peminjaman ruangan Anda telah <strong style="color: #28a745;">DISETUJUI</strong> oleh Laboran.</p>
                        
                        <div style="background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 15px 0; border-radius: 4px; color: #0c5460;">
                            <strong>ℹ️ Informasi:</strong> Peminjaman Anda akan diteruskan ke Kepala Lab untuk persetujuan final.
                        </div>
                    @else
                        <h2 style="color: #333; margin-top: 0;">Peminjaman Ruangan Ditolak</h2>
                        <p>Halo <strong>{{ $peminjamanRuangan->user_nama }}</strong>,</p>
                        <p>Mohon maaf, peminjaman ruangan Anda <strong style="color: #dc3545;">DITOLAK</strong> oleh Laboran.</p>
                    @endif

                @elseif($type === 'pengajuan_ke_kepala_lab')
                    <h2 style="color: #333; margin-top: 0;">Perlu Persetujuan Kepala Lab</h2>
                    <p>Halo <strong>Kepala Lab</strong>,</p>
                    <p>Peminjaman ruangan telah disetujui oleh Laboran dan memerlukan persetujuan Anda:</p>

                @elseif($type === 'notifikasi_kaprodi')
                    <h2 style="color: #333; margin-top: 0;">Notifikasi Peminjaman Ruangan</h2>
                    <p>Halo <strong>Kaprodi</strong>,</p>
                    <p>Berikut notifikasi peminjaman ruangan yang telah disetujui oleh Laboran dan diteruskan ke Kepala Lab:</p>

                @elseif($type === 'hasil_kaprodi')
                    <h2 style="color: #333; margin-top: 0;">Status Final Peminjaman Ruangan</h2>
                    <p>Halo <strong>{{ $peminjamanRuangan->user_nama }}</strong>,</p>
                    
                    @if($peminjamanRuangan->persetujuan_kaprodi)
                        <p>Selamat! Peminjaman ruangan Anda telah <strong style="color: #28a745;">DISETUJUI</strong> oleh Kaprodi.</p>
                        <div style="background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 15px 0; border-radius: 4px; color: #0c5460;">
                            <strong>✅ Peminjaman Dikonfirmasi:</strong> Anda dapat menggunakan ruangan sesuai jadwal yang telah ditentukan.
                        </div>
                    @else
                        <p>Mohon maaf, peminjaman ruangan Anda <strong style="color: #dc3545;">DITOLAK</strong> oleh Kaprodi.</p>
                    @endif
                @endif

                <!-- Detail Box -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: white; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                    <tr>
                        <td style="padding: 20px;">
                            <h3 style="margin-top: 0; color: #667eea;">📋 Detail Peminjaman</h3>
                            
                            <table width="100%" cellpadding="8" cellspacing="0">
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555; width: 40%;">Peminjam:</td>
                                    <td style="color: #333;">{{ $peminjamanRuangan->user_nama }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Ruangan:</td>
                                    <td style="color: #333;"><strong>{{ $peminjamanRuangan->daftarLab->Nama_Laboratorium }}</strong></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Tanggal:</td>
                                    <td style="color: #333;">
                                        {{ \Carbon\Carbon::parse($peminjamanRuangan->tanggal)->format('d M Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($peminjamanRuangan->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Waktu:</td>
                                    <td style="color: #333;">
                                        {{ \Carbon\Carbon::parse($peminjamanRuangan->jam_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($peminjamanRuangan->jam_selesai)->format('H:i') }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Keperluan:</td>
                                    <td style="color: #333;">{{ $peminjamanRuangan->keperluan }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Status:</td>
                                    <td>
                                        @if($peminjamanRuangan->status === 'menunggu')
                                            <span style="background: #ffc107; color: #856404; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">Menunggu Laboran</span>
                                        @elseif($peminjamanRuangan->status === 'disetujui_laboran' || $peminjamanRuangan->status === 'menunggu_kepala_lab')
                                            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">Menunggu Kepala Lab</span>
                                        @elseif($peminjamanRuangan->status === 'disetujui')
                                            <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">Disetujui</span>
                                        @else
                                            <span style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Diajukan:</td>
                                    <td style="color: #333;">{{ $peminjamanRuangan->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                @if($catatan)
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 4px;">
                    <strong>💬 Catatan:</strong>
                    <p style="margin: 10px 0 0 0;">{{ $catatan }}</p>
                </div>
                @endif

                @if($type === 'pengajuan_ke_laboran')
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/laboran/peminjaman-ruangan/' . $peminjamanRuangan->daftar_lab_id) }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Review Peminjaman
                        </a>
                    </p>

                @elseif($type === 'pengajuan_ke_kepala_lab')
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/kepala-lab/peminjaman-ruangan') }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Review Peminjaman
                        </a>
                    </p>

                @elseif($type === 'notifikasi_kaprodi')
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/kaprodi/dashboard') }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Lihat Dashboard
                        </a>
                    </p>

                @else
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/mahasiswa/pinjam-ruangan/' . $peminjamanRuangan->daftar_lab_id) }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Lihat Detail
                        </a>
                    </p>
                @endif

                <p style="font-size: 14px; color: #666; text-align: center; margin-top: 15px;">
                    Silakan login ke sistem untuk informasi lebih lengkap.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="text-align: center; padding: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                <p style="margin: 5px 0;">Email ini dikirim otomatis oleh sistem LIMS.<br>Jangan balas email ini.</p>
                <p style="margin: 10px 0 0 0;">
                    <strong>Laboratory Information Management System</strong><br>
                    Universitas Ahmad Dahlan
                </p>
            </td>
        </tr>
    </table>
</body>
</html>