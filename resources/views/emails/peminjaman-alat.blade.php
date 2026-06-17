<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman Alat</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">🔬 LIMS - Peminjaman Alat</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                @if($type === 'pengajuan_ke_laboran')
                    <h2 style="color: #333; margin-top: 0;">Pengajuan Peminjaman Alat Baru</h2>
                    <p>Halo <strong>Laboran</strong>,</p>
                    <p>Ada pengajuan peminjaman alat yang perlu Anda review:</p>

                @elseif($type === 'hasil_laboran')
                    <h2 style="color: #333; margin-top: 0;">Status Peminjaman Alat Anda</h2>
                    <p>Halo <strong>{{ $peminjamanAlat->user_nama }}</strong>,</p>
                    
                    @if($peminjamanAlat->status === 'disetujui')
                        <p>Kabar baik! Peminjaman alat Anda telah <strong style="color: #28a745;">DISETUJUI</strong> oleh Laboran.</p>
                    @else
                        <p>Mohon maaf, peminjaman alat Anda <strong style="color: #dc3545;">DITOLAK</strong> oleh Laboran.</p>
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
                                    <td style="color: #333;">{{ $peminjamanAlat->user_nama }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Alat:</td>
                                    <td style="color: #333;"><strong>{{ $peminjamanAlat->alatLab->nama_alat }}</strong></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Laboratorium:</td>
                                    <td style="color: #333;">{{ $peminjamanAlat->alatLab->daftarLab->Nama_Laboratorium }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Tanggal Pinjam:</td>
                                    <td style="color: #333;">{{ \Carbon\Carbon::parse($peminjamanAlat->tanggal_pinjam)->format('d F Y') }}</td>
                                </tr>
                                @if($peminjamanAlat->tanggal_kembali)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Tanggal Kembali:</td>
                                    <td style="color: #333;">{{ \Carbon\Carbon::parse($peminjamanAlat->tanggal_kembali)->format('d F Y') }}</td>
                                </tr>
                                @endif
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Status:</td>
                                    <td>
                                        @if($peminjamanAlat->status === 'menunggu')
                                            <span style="background: #ffc107; color: #856404; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase;">Menunggu</span>
                                        @elseif($peminjamanAlat->status === 'disetujui')
                                            <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase;">Disetujui</span>
                                        @else
                                            <span style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase;">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Diajukan:</td>
                                    <td style="color: #333;">{{ $peminjamanAlat->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                @if($catatan)
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 4px;">
                    <strong>💬 Catatan dari Laboran:</strong>
                    <p style="margin: 10px 0 0 0;">{{ $catatan }}</p>
                </div>
                @endif

                @if($type === 'pengajuan_ke_laboran')
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/laboran/peminjaman-alat/' . $peminjamanAlat->alatLab->daftar_lab_id) }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Review Peminjaman
                        </a>
                    </p>
                    <p style="font-size: 14px; color: #666; text-align: center;">
                        Silakan login ke sistem untuk menyetujui atau menolak peminjaman ini.
                    </p>

                @elseif($type === 'hasil_laboran')
                    <p style="margin-top: 20px; text-align: center;">
                        <a href="{{ url('/mahasiswa/aktivitas/' . $peminjamanAlat->alatLab->daftar_lab_id) }}" style="display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                            Lihat Detail
                        </a>
                    </p>
                @endif
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