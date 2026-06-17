<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Bebas Lab Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7fafc; color: #2d3748; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 24px;">
            <h2 style="margin: 0; color: #3b82f6;">📋 Notifikasi Bebas Lab</h2>
        </div>
        
        <p>Yth. Kepala Laboratorium,</p>
        
        <p>
            Pengajuan Bebas Lab dari mahasiswa <strong>{{ $bebasLabRequest->user_nama }}</strong> 
            telah disetujui oleh semua laboran.
        </p>
        
        <div style="background-color: #f0fdf4; border-left: 4px solid #059669; padding: 16px; margin: 20px 0; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #059669;">Detail Mahasiswa</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; width: 40%;"><strong>Nama:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->user_nama }}</td>
                </tr>
                @if($bebasLabRequest->user)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>NIM:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->user->NIM ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Email:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->user->Email ?? '-' }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($bebasLabRequest->riskAssessment)
        <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; margin: 20px 0; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #3b82f6;">Detail Penelitian</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; width: 40%;"><strong>Judul Penelitian:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->riskAssessment->topik_judul ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Laboratorium:</strong></td>
                    <td style="padding: 8px 0;">{{ $bebasLabRequest->riskAssessment->daftarLab->Nama_Laboratorium ?? '-' }}</td>
                </tr>
            </table>
        </div>
        @endif
        
        <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin: 20px 0; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #f59e0b;">Status Persetujuan</h3>
            <p style="margin: 0;">
                <strong>Status:</strong> <span style="color: #059669;">✓ Disetujui oleh semua laboran</span>
            </p>
            <p style="margin: 8px 0 0 0;">
                <strong>Tanggal Persetujuan:</strong> {{ $bebasLabRequest->kepala_lab_approved_at ? $bebasLabRequest->kepala_lab_approved_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
            </p>
            <p style="margin: 8px 0 0 0;">
                <strong>Masa Berlaku:</strong> 
                {{ $bebasLabRequest->tanggal_berlaku_dari ? $bebasLabRequest->tanggal_berlaku_dari->format('d/m/Y') : '-' }} 
                s/d 
                {{ $bebasLabRequest->tanggal_berlaku_sampai ? $bebasLabRequest->tanggal_berlaku_sampai->format('d/m/Y') : '-' }}
            </p>
        </div>
        
        @if($bebasLabRequest->approvals->count() > 0)
        <div style="margin: 20px 0;">
            <h3 style="color: #6b7280;">Daftar Persetujuan Laboran:</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 12px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #d1d5db;">Laboratorium</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #d1d5db;">Laboran</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #d1d5db;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bebasLabRequest->approvals as $approval)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $approval->lab->Nama_Laboratorium }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $approval->laboran_nama }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <p style="margin-top: 24px; color: #6b7280;">
            Email notifikasi ini dikirimkan untuk memberitahu bahwa mahasiswa telah menyelesaikan 
            proses persetujuan Bebas Lab dari semua laboran terkait.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 32px 0;">
        
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 0;">
            Email ini dikirim secara otomatis oleh sistem LIMS. Jangan balas email ini.
        </p>
    </div>
</body>
</html>
