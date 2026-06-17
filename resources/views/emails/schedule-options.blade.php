<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Wawancara Risk Assessment</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">📅 LIMS - Jadwal Wawancara Risk Assessment</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                <h2 style="color: #333; margin-top: 0;">Pilih Jadwal Wawancara Anda</h2>
                <p>Halo <strong>{{ $riskAssessment->user->Nama ?? 'Peneliti' }}</strong>,</p>
                
                <p>Safety Officer telah menyediakan beberapa pilihan jadwal wawancara untuk Risk Assessment Anda. Silakan memilih salah satu jadwal yang paling sesuai dengan ketersediaan Anda.</p>

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
                                    <td style="font-weight: bold; color: #555;">Laboratorium:</td>
                                    <td style="color: #333;">{{ $riskAssessment->daftarLab->Nama_Laboratorium ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Safety Officer:</td>
                                    <td style="color: #333;">{{ $riskAssessment->safetyOfficer->Nama ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Schedule Options -->
                <h3 style="color: #667eea; margin-bottom: 15px;">⏰ Opsi Jadwal Wawancara</h3>
                
                @forelse ($riskAssessment->jadwal_wawancara_options ?? [] as $index => $option)
                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; margin: 12px 0; border-radius: 6px; border-left: 4px solid #10b981;">
                        <tr>
                            <td style="padding: 15px;">
                                <div style="font-weight: bold; color: #1f2937; font-size: 15px; margin-bottom: 8px;">
                                    Opsi {{ $index + 1 }}
                                </div>
                                <table width="100%" cellpadding="4" cellspacing="0">
                                    <tr>
                                        <td style="color: #6b7280; width: 30%;">📅 Tanggal:</td>
                                        <td style="color: #1f2937; font-weight: 600;">
                                            {{ \Carbon\Carbon::parse($option['jadwal'])->format('l, d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: #6b7280;">⏰ Jam:</td>
                                        <td style="color: #1f2937; font-weight: 600;">{{ $option['waktu'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #6b7280;">📍 Lokasi:</td>
                                        <td style="color: #1f2937; font-weight: 600;">{{ $option['tempat'] ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                @empty
                    <p style="background: #fef3c7; padding: 15px; border-radius: 6px; color: #92400e;">
                        ⚠️ Belum ada opsi jadwal yang tersedia.
                    </p>
                @endforelse

                @if ($riskAssessment->catatan_safety_officer)
                    <!-- Safety Officer Notes -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #e0e7ff; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                        <tr>
                            <td style="padding: 15px;">
                                <h4 style="margin: 0 0 8px 0; color: #3730a3;">📌 Catatan dari Safety Officer</h4>
                                <p style="margin: 0; color: #3730a3; line-height: 1.6;">{{ $riskAssessment->catatan_safety_officer }}</p>
                            </td>
                        </tr>
                    </table>
                @endif

                <!-- Call to Action -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="{{ route('mahasiswa.risk-assessment.pending-schedules', $riskAssessment->id) }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                📅 Pilih Jadwal Sekarang
                            </a>
                        </td>
                    </tr>
                </table>

                <!-- Deadline Warning -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #fef3c7; margin: 20px 0; border-radius: 8px; border-left: 4px solid #fbbf24;">
                    <tr>
                        <td style="padding: 15px;">
                            <h4 style="margin: 0 0 8px 0; color: #92400e;">⏳ Penting</h4>
                            <p style="margin: 0; color: #92400e;">Silakan memilih jadwal wawancara sesegera mungkin untuk memastikan prosedur Risk Assessment dapat berlanjut sesuai jadwal.</p>
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
