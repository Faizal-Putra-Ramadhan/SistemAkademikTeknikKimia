<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Reset Password</h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 14px;">LAB TEKIM UAD</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px;">
                                Halo <strong>{{ $name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 20px 0; color: #666; font-size: 14px; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda di LAB TEKIM UAD. 
                                Klik tombol di bawah ini untuk membuat password baru:
                            </p>
                            
                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 20px 0; color: #666; font-size: 14px; line-height: 1.6;">
                                Atau copy dan paste URL berikut ke browser Anda:
                            </p>
                            
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; word-wrap: break-word;">
                                <a href="{{ $resetUrl }}" style="color: #667eea; text-decoration: none; font-size: 13px;">{{ $resetUrl }}</a>
                            </div>
                            
                            <!-- Warning Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
                                <tr>
                                    <td style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px;">
                                        <p style="margin: 0; color: #856404; font-size: 13px; line-height: 1.6;">
                                            <strong>⚠️ Perhatian:</strong><br>
                                            • Link ini hanya berlaku selama <strong>1 jam</strong><br>
                                            • Jika Anda tidak meminta reset password, abaikan email ini<br>
                                            • Jangan bagikan link ini kepada siapapun
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; color: #999; font-size: 13px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="margin: 0 0 10px 0; color: #666; font-size: 13px;">
                                © {{ date('Y') }} LAB TEKIM UAD. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                Universitas Ahmad Dahlan<br>
                                Program Studi Teknik Kimia
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>