<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Deadline Risk Assessment</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: white;">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 30px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px;">⏰ LIMS - Pengingat Deadline Risk Assessment</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa;">
                <h2 style="color: #333; margin-top: 0;">⚠️ Deadline Segera Berakhir!</h2>
                <p>Halo <strong>{{ $riskAssessment->user->Nama ?? 'Peneliti' }}</strong>,</p>
                
                <p>Ini adalah pengingat bahwa <strong>Risk Assessment Anda akan segera mencapai batas waktu</strong>. Mohon segera selesaikan proses review dan persetujuan.</p>

                <!-- Risk Assessment Info Box -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: white; margin: 20px 0; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <tr>
                        <td style="padding: 20px;">
                            <h3 style="margin-top: 0; color: #f59e0b;">📋 Informasi Risk Assessment</h3>
                            
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
                                    <td style="font-weight: bold; color: #555;">Risk Assessment ID:</td>
                                    <td style="color: #333;">{{ $riskAssessment->id }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #555;">Status Saat Ini:</td>
                                    <td style="color: #333;">
                                        @switch($riskAssessment->status)
                                            @case('menunggu_safety_officer')
                                                ⏳ Menunggu Safety Officer
                                                @break
                                            @case('menunggu_kepala_lab')
                                                ⏳ Menunggu Kepala Lab
                                                @break
                                            @case('disetujui')
                                                ✅ Disetujui
                                                @break
                                            @default
                                                {{ $riskAssessment->status }}
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Deadline Warning -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #fef3c7; margin: 20px 0; border-radius: 8px; border-left: 4px solid #fbbf24;">
                    <tr>
                        <td style="padding: 20px;">
                            <h3 style="margin: 0 0 10px 0; color: #92400e; font-size: 16px;">🔴 DEADLINE</h3>
                            <p style="margin: 0; color: #92400e; font-size: 18px; font-weight: bold;">
                                {{ \Carbon\Carbon::parse($riskAssessment->batas_waktu_peminjaman)->format('d F Y') }}
                            </p>
                            <p style="margin: 8px 0 0 0; color: #92400e; font-size: 14px;">
                                Tinggal 7 hari lagi!
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- What's Pending -->
                <h3 style="color: #f59e0b; margin-bottom: 15px;">📝 Status Review</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #f9fafb; margin: 0; border-radius: 8px; padding: 0;">
                    <tr>
                        <td style="padding: 20px;">
                            <table width="100%" cellpadding="12" cellspacing="0">
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="width: 40%; color: #6b7280; font-weight: 600;">Dosen Pembimbing:</td>
                                    <td style="color: {{ $riskAssessment->persetujuan_dosen ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                                        {{ $riskAssessment->persetujuan_dosen ? '✅ Disetujui' : '❌ Belum Disetujui' }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #6b7280; font-weight: 600;">Safety Officer:</td>
                                    <td style="color: {{ $riskAssessment->persetujuan_safety_officer ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                                        {{ $riskAssessment->persetujuan_safety_officer ? '✅ Disetujui' : '❌ Belum Disetujui' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #6b7280; font-weight: 600;">Kepala Lab:</td>
                                    <td style="color: {{ $riskAssessment->persetujuan_kepala_lab ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                                        {{ $riskAssessment->persetujuan_kepala_lab ? '✅ Disetujui' : '❌ Belum Disetujui' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #6b7280; font-weight: 600;">Kaprodi:</td>
                                    <td style="color: {{ $riskAssessment->persetujuan_kaprodi ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                                        {{ $riskAssessment->persetujuan_kaprodi ? '✅ Disetujui' : '❌ Belum Disetujui' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Actions -->
                <h3 style="color: #f59e0b; margin-bottom: 15px; margin-top: 30px;">✅ Tindakan yang Diperlukan</h3>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #fff7ed; margin: 0; border-radius: 8px;">
                    <tr>
                        <td style="padding: 20px;">
                            <ol style="margin: 0; padding-left: 20px; color: #92400e;">
                                <li style="margin-bottom: 12px;">Pastikan semua pihak pembimbing telah memberikan persetujuan</li>
                                <li style="margin-bottom: 12px;">Jika ada catatan atau revisi, segera lakukan perbaikan</li>
                                <li style="margin-bottom: 12px;">Hubungi pihak yang belum menyetujui untuk mempercepat proses</li>
                                <li>Jika ingin perpanjangan deadline, hubungi Kepala Lab atau Kaprodi</li>
                            </ol>
                        </td>
                    </tr>
                </table>

                <!-- Call to Action -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="{{ route('mahasiswa.risk-assessment.show', $riskAssessment->id) }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                📋 Lihat Risk Assessment Saya
                            </a>
                        </td>
                    </tr>
                </table>

                <!-- Extension Option -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #e0e7ff; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
                    <tr>
                        <td style="padding: 15px;">
                            <h4 style="margin: 0 0 8px 0; color: #3730a3;">❓ Butuh Perpanjangan Deadline?</h4>
                            <p style="margin: 0; color: #3730a3;">Jika Anda membutuhkan waktu lebih lama, silakan hubungi Kepala Lab atau Kaprodi untuk meminta perpanjangan deadline.</p>
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
