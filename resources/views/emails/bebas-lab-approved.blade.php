<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bebas Lab Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7fafc; color: #2d3748; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 24px;">
            <h2 style="margin: 0; color: #059669;">✓ Bebas Lab Disetujui</h2>
        </div>
        
        <p>Halo <strong>{{ $bebasLabRequest->user_nama }}</strong>,</p>
        
        <p>Selamat! Pengajuan Bebas Lab Anda telah <strong>disetujui oleh semua laboran</strong>.</p>
        
        <div style="background-color: #f0fdf4; border-left: 4px solid #059669; padding: 16px; margin: 20px 0; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #059669;">Detail Bebas Lab</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Nama Mahasiswa:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->user_nama }}</td>
                </tr>
                @if($bebasLabRequest->user)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>NIM:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->user->NIM ?? '-' }}</td>
                </tr>
                @endif
                @if($bebasLabRequest->riskAssessment)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Judul Penelitian:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->riskAssessment->topik_judul ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Laboratorium:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->riskAssessment->daftarLab->Nama_Laboratorium ?? '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Masa Berlaku:</strong></td>
                    <td style="padding: 8px 0;">
                        {{ $bebasLabRequest->tanggal_berlaku_dari ? $bebasLabRequest->tanggal_berlaku_dari->format('d/m/Y') : '-' }} 
                        s/d 
                        {{ $bebasLabRequest->tanggal_berlaku_sampai ? $bebasLabRequest->tanggal_berlaku_sampai->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            </table>
        </div>
        
        <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; margin: 20px 0; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #3b82f6;">Persetujuan Laboran</h3>
            @if($bebasLabRequest->approvals->count() > 0)
            <ul style="margin: 8px 0; padding-left: 20px;">
                @foreach($bebasLabRequest->approvals as $approval)
                <li style="margin: 8px 0;">
                    <strong>{{ $approval->lab->Nama_Laboratorium }}</strong><br>
                    <span style="color: #6b7280; font-size: 14px;">
                        Laboran: {{ $approval->laboran_nama }}<br>
                        Disetujui pada: {{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        
        <p style="margin-top: 24px;">
            Anda dapat mengunduh surat Bebas Lab melalui dashboard mahasiswa.
        </p>
        
        <div style="text-align: center; margin-top: 32px;">
            <a href="{{ url('/mahasiswa/bebas-lab') }}" 
               style="display: inline-block; background-color: #059669; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Lihat Dashboard
            </a>
        </div>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 32px 0;">
        
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 0;">
            Email ini dikirim secara otomatis oleh sistem LIMS. Jangan balas email ini.
        </p>
    </div>
</body>
</html>
