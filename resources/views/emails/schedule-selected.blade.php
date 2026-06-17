<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Wawancara Dipilih - Notifikasi ke Safety Officer</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">📅 LIMS - Jadwal Wawancara Dipilih</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                <h2 style="color: #333; margin-top: 0;">Jadwal Wawancara Telah Dipilih</h2>
                <p>Halo <strong>Safety Officer</strong>,</p>
                
                <p>Peneliti telah memilih jadwal wawancara untuk Risk Assessment. Berikut adalah detail jadwal yang dipilih:</p>

                <!-- Risk Assessment Info Box -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: white; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                    <tr>
                        <td style="padding: 20px;">
                            <h3 style="margin-top: 0; color: #667eea;">📋 Informasi Risk Assessment</h3>
                            
                            <table width="100%" cellpadding="8" cellspacing="0">
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555; width: 40%;">Judul:</td>
                                    <td style="color: #333;">{{ $riskAssessment->topik_judul ?? 'N/A' }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Peneliti:</td>
                                    <td style="color: #333;">{{ $riskAssessment->user->Nama ?? 'N/A' }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Email Peneliti:</td>
                                    <td style="color: #333;">{{ $riskAssessment->user->Email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Laboratorium:</td>
                                    <td style="color: #333;">{{ $riskAssessment->daftarLab->Nama_Laboratorium ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Selected Schedule -->
                <h3 style="color: #10b981; margin-bottom: 15px;">✅ Jadwal Wawancara Terpilih</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #ecfdf5; margin: 12px 0; border-radius: 6px; border-left: 4px solid #10b981;">
                    <tr>
                        <td style="padding: 20px;">
                            <table width="100%" cellpadding="8" cellspacing="0">
                                <tr style="border-bottom: 1px solid #d1e9de;">
                                    <td style="color: #047857; font-weight: bold; width: 30%;">📅 Tanggal:</td>
                                    <td style="color: #065f46; font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara_dipilih['jadwal'])->format('l, d F Y') }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #d1e9de;">
                                    <td style="color: #047857; font-weight: bold;">⏰ Jam:</td>
                                    <td style="color: #065f46; font-weight: 600;">{{ $riskAssessment->jadwal_wawancara_dipilih['waktu'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #047857; font-weight: bold;">📍 Lokasi:</td>
                                    <td style="color: #065f46; font-weight: 600;">{{ $riskAssessment->jadwal_wawancara_dipilih['tempat'] ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Confirmation Info -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #e0e7ff; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                    <tr>
                        <td style="padding: 15px;">
                            <h4 style="margin: 0 0 8px 0; color: #3730a3;">✅ Status</h4>
                            <p style="margin: 0; color: #3730a3;">
                                Jadwal wawancara telah dikonfirmasi oleh peneliti pada {{ $riskAssessment->jadwal_wawancara_dipilih_at->format('d F Y H:i') }}
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Action Items -->
                <h3 style="color: #667eea; margin-bottom: 15px;">📝 Langkah Selanjutnya</h3>
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; margin: 0; border-radius: 8px; padding: 0;">
                    <tr>
                        <td style="padding: 20px;">
                            <ol style="margin: 0; padding-left: 20px; color: #333;">
                                <li style="margin-bottom: 12px;">Siapkan materi dan dokumentasi untuk wawancara</li>
                                <li style="margin-bottom: 12px;">Hubungi peneliti untuk konfirmasi final jika diperlukan</li>
                                <li style="margin-bottom: 12px;">Dokumentasikan hasil wawancara dalam sistem</li>
                                <li>Lanjutkan proses Review Risk Assessment</li>
                            </ol>
                        </td>
                    </tr>
                </table>

                <!-- Call to Action -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="{{ route('safety-officer.risk-assessment.show', $riskAssessment->id) }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                📋 Lihat Detail Risk Assessment
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb;">
                <p style="margin: 5px 0;">LIMS - Sistem Manajemen Laboratorium</p>
                <p style="margin: 5px 0;">Universitas / Institusi Pendidikan</p>
                <p style="margin: 5px 0;">Email otomatis, mohon jangan balas email ini</p>
            </td>
        </tr>
    </table>
</body>
</html>
