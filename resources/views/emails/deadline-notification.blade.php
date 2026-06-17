<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Perpanjangan Deadline dari Laboran</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">📧 LIMS - Notifikasi Perpanjangan Deadline</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                <h2 style="color: #333; margin-top: 0;">📌 Notifikasi Penting dari Laboran</h2>
                <p>Halo <strong>{{ $riskAssessment->user->Nama ?? 'Peneliti' }}</strong>,</p>
                
                <p>Laboran telah mengirimkan notifikasi terkait Risk Assessment Anda. Silakan baca informasi berikut:</p>

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
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="font-weight: bold; color: #555;">Laboran:</td>
                                    <td style="color: #333;">{{ Auth::user()->Nama ?? 'Laboran' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Tanggal Notifikasi:</td>
                                    <td style="color: #333;">{{ now()->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Current Deadline -->
                <h3 style="color: #f59e0b; margin-bottom: 15px;">⏰ Deadline Saat Ini</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #fef3c7; margin: 12px 0; border-radius: 6px; border-left: 4px solid #fbbf24;">
                    <tr>
                        <td style="padding: 20px;">
                            <table width="100%" cellpadding="8" cellspacing="0">
                                <tr style="border-bottom: 1px solid #d4a574;">
                                    <td style="color: #92400e; font-weight: bold; width: 50%;">Deadline Awal:</td>
                                    <td style="color: #92400e; font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($riskAssessment->batas_waktu_peminjaman)->format('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #92400e; font-weight: bold;">Status Deadline:</td>
                                    <td style="color: #92400e; font-weight: 600;">
                                        @if (\Carbon\Carbon::parse($riskAssessment->batas_waktu_peminjaman)->isFuture())
                                            ⏳ Masih Berlaku
                                        @else
                                            ❌ Telah Berakhir
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Notification Message -->
                <h3 style="color: #667eea; margin-bottom: 15px;">💬 Pesan dari Laboran</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; margin: 12px 0; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <tr>
                        <td style="padding: 20px;">
                            <p style="margin: 0; color: #1f2937; line-height: 1.8; font-size: 15px;">
                                ℹ️ Laboran telah mengirimkan notifikasi terkait perpanjangan atau perubahan deadline untuk Risk Assessment Anda.
                            </p>
                            <p style="margin: 15px 0 0 0; color: #1f2937; line-height: 1.8; font-size: 15px;">
                                Silakan cek sistem LIMS atau hubungi Laboran langsung untuk informasi lebih detail.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Action Items -->
                <h3 style="color: #667eea; margin-bottom: 15px; margin-top: 30px;">✅ Langkah Selanjutnya</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; margin: 0; border-radius: 8px;">
                    <tr>
                        <td style="padding: 20px;">
                            <ol style="margin: 0; padding-left: 20px; color: #333;">
                                <li style="margin-bottom: 12px;">Buka sistem LIMS dan periksa detail notifikasi dari Laboran</li>
                                <li style="margin-bottom: 12px;">Baca informasi deadline yang telah diperbarui jika ada</li>
                                <li style="margin-bottom: 12px;">Hubungi Laboran jika memerlukan klarifikasi lebih lanjut</li>
                                <li>Pastikan semua pihak pembimbing tetap mengikuti jadwal yang diperbarui</li>
                            </ol>
                        </td>
                    </tr>
                </table>

                <!-- Call to Action -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="{{ route('mahasiswa.risk-assessment.show', $riskAssessment->id) }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                📋 Lihat Risk Assessment Saya
                            </a>
                        </td>
                    </tr>
                </table>

                <!-- Important Info -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #e0e7ff; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                    <tr>
                        <td style="padding: 15px;">
                            <h4 style="margin: 0 0 8px 0; color: #3730a3;">ℹ️ Penting</h4>
                            <p style="margin: 0; color: #3730a3; font-size: 14px;">
                                Segera lakukan tindakan yang diperlukan. Jika ada kendala atau pertanyaan, jangan ragu untuk menghubungi Laboran atau Kepala Lab.
                            </p>
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
