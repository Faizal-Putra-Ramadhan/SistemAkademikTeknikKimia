<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Bebas Lab</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7fafc; color: #2d3748; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px;">
        @if(!empty($isResubmit) && $isResubmit)
            <h2 style="margin-top: 0; color: #d97706;">Pengajuan Ulang Bebas Lab (Periode {{ $request->periode }})</h2>
            <p>Halo Laboran,</p>
            <p>
                Mahasiswa <strong>{{ $request->user_nama }}</strong> telah mengajukan ulang permohonan Bebas Lab 
                <strong>(Periode {{ $request->periode }})</strong> karena bebas lab sebelumnya telah dibatalkan untuk keperluan peminjaman alat.
            </p>
        @else
            <h2 style="margin-top: 0;">Pengajuan Bebas Lab</h2>
            <p>Halo Laboran,</p>
            <p>
                Mahasiswa <strong>{{ $request->user_nama }}</strong> telah mengajukan permohonan Bebas Lab.
            </p>
        @endif
        <p>
            Silakan buka menu <strong>Bebas Lab</strong> di dashboard laboran untuk melakukan persetujuan.
        </p>
        <p style="margin-bottom: 0;">Terima kasih.</p>
    </div>
</body>
</html>
